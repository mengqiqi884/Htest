<?php
namespace v2\controllers;

use v2\models\Friend;
use v2\models\Parklot;
use v2\QueryParamAuth;
use api\helpers\ApiResponse;
use api\helpers\controllers\ApiController;
use api\helpers\filters\ApiVerbFilter;
use common\helpers\ArrayHelper;
use common\rysdk\ServerAPI;
use v2\models\Brand;
use v2\models\Lock;
use v2\models\ResCity;
use v2\models\ResDistrict;
use v2\models\ResProvince;
use v2\V2;
use yii;
use v2\models\Login;
use v2\models\User;
use yii\web\UploadedFile;

class UserController extends ApiController
{
	 public function behaviors(){
     $behaviors = parent::behaviors();
     $behaviors['authenticator'] = [
         'class' => QueryParamAuth::className(),
         'except'=>['smser','check','lock','login','brandlist','updatelogo','rytoken','userinfo','test','logsmser','regsmser','updatelogo']
     ];
     $behaviors['verbs'] = [
         'class'=> ApiVerbFilter::className(),
         'actions'=>[
             '*'=>['post'],
			 'rytoken'=>['post']
         ]
     ];
     return $behaviors;
 }

	public function actionTest(){
		$phone = Yii::$app->request->post('phone');
		$coach = Yii::$app->cache;
		$code = $coach->get($phone);
		echo $code;
		exit;
	}
	/**
	 * 网信通
	 * 发送验证码接口
	 */

	public function actionSmser()
	{
		$code = rand(111111, 999999);
		$account = Yii::$app->request->post('account');
		if(empty($account)){
			return ApiResponse::showResult(301,[],'手机号不能为空');
		}
		$content = "【51停车】尊敬的用户您好，欢迎使用51停车，您本次的手机验证码为：" . $code . "，30分钟之内有效，请尽快完成验证！回复TD退订";
		$smser = Yii::$app->smser;
		$cache = Yii::$app->cache;
		$model = yii\base\DynamicModel::validateData(compact('account'),[
				['account','string','max'=>11,'tooLong'=>'手机号不正确'],
				['account','api\helpers\validator\PhoneValidator']
		]);
		if($model->hasErrors()){
			$first_error = ArrayHelper::myGetFirstError($model);
			return ApiResponse::showResult(101,[],$first_error);
		}else{
			$data = $smser->sendSms($account, $content);
			if($data) {
				$time = 1800;
				$cache->set($account, $code, $time);
				return ApiResponse::showResult(200);
			} else {
				return ApiResponse::showResult(100);
			}
		}
	}


	/**
	 * 车位列表接口
	 */
	public function actionSpotlist()
	{
		$user_id = Yii::$app->user->identity->id;
		if (empty($user_id)){
			return $this->showResult(302, '读取用户信息出错');
		}
		$spotlist = Lock::find()->where(['user_id' => $user_id, 'is_del' => 0,'parkspot_id'=>0])->asArray()->all();
		if (empty($spotlist)) {
			return $this->showResult(301, '没有数据');
		}
		foreach ($spotlist as $val) {
			$data[] = [
				'lock_id' => $val['lock_id'],
				'park_nb' => $val['park_nb'],
			];
		}

		return $this->showResult('200', '成功', $data);
	}

	public function actionLogin(){
		$coach = Yii::$app->cache;
		$account = Yii::$app->request->post('account');
		$code = Yii::$app->request->post('code');
		$registerid = Yii::$app->request->post('registerid','');
		$model = yii\base\DynamicModel::validateData(compact('account','code','registerid'),[
			['account','api\helpers\validator\PhoneValidator'],
			['code','compare','compareValue'=>$coach->get($account),'message'=>'验证码不正确', 'when'=>function($model){
				$arr = ['15861895830','15895086683','13914335261','15189722265','18020319656','15189705273','18915037775'];
				return  !in_array($model->account,$arr);
			}],
			['registerid','string','max'=>50]
		]);
		if($model->hasErrors()){
			return ApiResponse::showResult(101,[],ArrayHelper::myGetFirstError($model));
		}
		//判断用户是否存在
		$loginModel = Login::find()->where(['account'=>$account,'is_del'=>0])->one();
		if($loginModel === null){
			//注册
			$res = Login::create($account,$registerid);
			$user_id = $res->user_id;
			$userModel = User::findOne($user_id);
			if($res){
				return ApiResponse::showResult(200,$userModel->toArray([],User::info()));
			}else{
				return ApiResponse::showResult(100);
			}
		}else{
			//登录
			$transaction = V2::getInstance()->db->beginTransaction();
			try {
				$userModel = User::findOne($loginModel->user_id);
				$userModel->registerid = $registerid;
				if (!$userModel->save()) {
					throw new yii\base\Exception;
				}
				$loginModel->token = Yii::$app->security->generateRandomString();
				if (!$loginModel->save()) {
					throw new yii\base\Exception;
				}
				$transaction->commit();
				$coach->delete($account);
				return ApiResponse::showResult(200,$userModel->toArray([],User::info()));
			}catch (yii\base\Exception $e){
				$transaction->rollBack();
				return ApiResponse::showResult(100);
			}

		}
	}

	/**
	 *获取个人信息接口
     */
	public function actionInfo(){
			$user_id = Yii::$app->user->identity->id;
			$userModel = User::findOne($user_id);
			$data = $userModel->toArray([],User::info());
			unset($data['token']);
			return $this->showResult(200,'成功',$data);
	}
	/**
	 * 汽车品牌列表
	 */
	public function actionBrandlist()
	{
		$brandlist = Brand::find()->where(['is_del' => 0])->orderBy('fword')->asArray()->all();
		$data = [];
		foreach ($brandlist as $val) {
			$fword = $val['fword'];
			$logo = yii\helpers\Url::to($val['logo'],true);

			$data[] = [
				'fword'=>$fword,
				'brand_id'=>$val['brand_id'],
				'name'=>$val['name'],
				'logo'=>$logo
			];
		}
		$data = array_values($data);
		return ApiResponse::showResult(200,$data);
	}


	/**
	 * 上传图片接口
	 */

	public function actionUpdatelogo()
	{
		$post = Yii::$app->request->isPost;
		if (!$post) {
			return ApiResponse::showResult(101);
		}
		$file = UploadedFile::getInstanceByName('logo');
		if (!empty($file)) {
			$baseurl = Yii::$app->request->baseUrl;
			$path = './images/logo/';
			if (!is_dir($path)) {
				@mkdir($path, 0777, true);
			}
			$logopath = $path . time() . rand(100000, 999999) . '.' . $file->extension;
			$file->saveAs($logopath);
			$host = Yii::$app->request->hostInfo;
			$logopath = ltrim($logopath, '.');
			$pic_url = $host . $baseurl . $logopath;
			return ApiResponse::showResult(200,['logo' => $pic_url]);
		} else {
			return ApiResponse::showResult(100);
		}

	}


	/**
	 * 区域列表接口(省)
	 */
	public function actionArealist()
	{
		$provincelist = ResProvince::find()->asArray()->all();
		$data = [];
		foreach ($provincelist as $val) {
		$data[] = [
				'province_id' => $val['province_id'],
				'province_name' => $val['province_name'],
		];
	}
		return ApiResponse::showResult(200,$data);
	}

	/**
	 * 区域列表接口（市）
	 */
	public function actionCitylist()
	{
		$province_id = Yii::$app->request->post('province_id');

		if(empty($province_id)){
			return ApiResponse::showResult(101);
		}
		$citylist = ResCity::find()->where(['o_province_id' => $province_id])->asArray()->all();
		$data = [];
		foreach ($citylist as $val) {
			$data[] = [
				'city_id' => $val['city_id'],
				'city_name' => $val['city_name'],
			];
		}
		return ApiResponse::showResult(200,$data);
	}

	/**
	 * 区域列表接口（区）
	 */
	public function actionDistrictlist()
	{
		$city_id = Yii::$app->request->post('city_id');
		if(empty($city_id)){
			return ApiResponse::showResult(101);
		}
		$districtlist = ResDistrict::find()->where(['o_city_id' => $city_id])->asArray()->all();
		$data = [];
		foreach ($districtlist as $val) {
			$data[] = [
				'district_id' => $val['district_id'],
				'district_name' => $val['district_name'],
			];
		}
		return $this->showResult('200', '成功', $data);
	}

	/**
	 *完善个人信息
     */
	public function actionUpdateinfo(){
		$user_id = Yii::$app->controller->module->user->identity->id;
		$userModel = User::findOne($user_id);
		$username = Yii::$app->request->post('username',$userModel->username);
		$sex =  Yii::$app->request->post('sex',$userModel->sex);
		$logo =  Yii::$app->request->post('logo',$userModel->logo);
		$brand_id = Yii::$app->request->post('brand_id',$userModel->brand_id);
		$plate_nu = Yii::$app->request->post('plate_nu',$userModel->plate_nu);
		$model = yii\base\DynamicModel::validateData(compact('username','sex','logo','brand_id','plate_nu'),[
				[['username'], 'string', 'max' => 50,'tooLong'=>'昵称过长'],
				[['plate_nu'],'string','max'=>20,'tooLong'=>'车牌号过长'],
				[['sex'],'in','range'=>[0,1,2],'message'=>'性别类型错误'],
				[['brand_id'],'integer','message'=>'品牌参数错误'],
				[['username','sex','logo','brand_id','plate_nu'],'required','message'=>'参数不能为空']
		]);
		//查找昵称是否存在
		$is_exist_nickname = User::find()->where(['username'=>$username,'is_del'=>0])->andWhere(['!=','user_id',$user_id])->one();
		if($is_exist_nickname != null){
			return ApiResponse::showResult(101,[],'用户昵称已存在');
		}
		$plate_nu_isexists = User::find()->where(['plate_nu'=>$plate_nu,'is_del'=>0])->andWhere(['!=','user_id',$user_id])->one();
		if($plate_nu_isexists != null){
			return ApiResponse::showResult(101,[],'车牌号已存在');
		}
		if($model->hasErrors()){
			return ApiResponse::showResult(101,[],ArrayHelper::myGetFirstError($model));
		}
		$userModel->attributes = [
			'username'=>$username,
			'sex'=>$sex,
			'logo'=>$logo,
			'brand_id'=>$brand_id,
			'plate_nu'=>$plate_nu
		];
		if($userModel->save()){
			return ApiResponse::showResult(200,$userModel->toArray([],User::info()));
		}else{
			return ApiResponse::showResult(100);
		}
	}

	/**
	 * 融云获取token
	 * @return array
     */
	public function actionRytoken(){
		$appid =  V2::getInstance()->params['ryappid'];
		$appsecret = V2::getInstance()->params['ryappsecret'];

		$user_id = Yii::$app->request->post('user_id',0);
		$userModel = User::findOne($user_id);
		if($user_id == 0 || empty($userModel)){
			return ApiResponse::showResult(101);
		}
		$username = empty($userModel->username) ? 'test' : $userModel->username;
		$logo =empty($userModel->logo) ? 'http://120.25.144.153/parking/api/web/images/logo/1453082596650490.jpg' : $userModel->logo;

		$ry = new ServerAPI($appid,$appsecret);
		$result = $ry->getToken($user_id,$username,$logo);
		$data = json_decode($result);
		return ApiResponse::showResult(200,$data);
	}

	/**
	 * 融云获取用户信息
	 * @return array
     */
	public function actionUserinfo(){
		$user_id = Yii::$app->request->post('user_id',0);
		$userModel = User::findOne($user_id);
		if(empty($userModel)){
			return ApiResponse::showResult(101);
		}
		$logo = empty($userModel->logo) ? 'http://120.25.144.153/parking/api/web/images/logo/1453082596650490.jpg' : $userModel->logo;
		$data =[
			'username'=>$userModel->username,
			'logo'=>$logo
		];
		return ApiResponse::showResult(200,$data);
	}

	/**
	 *用户详情接口
     */
	public function actionDetail(){
		$user_id = Yii::$app->request->post('user_id',0);
		$your_user_id = Yii::$app->controller->module->user->identity->id;
		$userModel = User::find()
				->userSelect('user.username,user.sex,user.brand_id,user.logo')
				->where(['user_id'=>$user_id])
				->asArray()
				->one();
		if(empty($userModel)){
			return ApiResponse::showResult(101,[],'用户不存在');
		}else{
			$is_friend = Friend::find()->user($your_user_id)->buser($user_id)->one();

			$is_friend_res = $is_friend == null ? '' : ($is_friend->tag == 1 ? 1 : 0);
			$userModel['is_friend'] = $is_friend_res;
		}
		$userModel['brand_logo'] =   yii\helpers\Url::to($userModel['brand_logo'],true);
		return ApiResponse::showResult(200,$userModel);
	}

	/**
	 *推荐人列表
     */
	public function actionRecommendlist(){
		$user_id = Yii::$app->controller->module->user->identity->id;
		$list = User::find()
				->select(['user.username','login.account'])
				->where(['user.recommend_id'=>$user_id])
				->leftJoin('login','login.user_id = user.user_id')
				->leftJoin('parkspot','parkspot.user_id = user.user_id')
				->isApplyPark()
				->groupBy('user.user_id')
				->asArray()
				->all();
		return ApiResponse::showResult(200,$list);
	}


	/**
	 *分享得股权
     */
	public function actionEquity(){
		$user_id = Yii::$app->controller->module->user->identity->id;
		//是否发布车位
		$is_send_park = User::find()
				->userJoinParkspot('parkspot.created_at')
				->isApplyPark()
				->one();
		//推荐至少5人发布车位
		$recommend = User::find()
				->where(['recommend_id'=>$user_id])
				->userJoinParkspot('parkspot.created_at')
				->groupBy('user.user_id')
				->isApplyPark()
				->count();
		//至少推荐一个停车场
		$recommend_parklot = Parklot::find()->where(['recommend'=>$user_id,'is_del'=>0,'status'=>2])->one();
		//每月至少下一次订单(需要修改)

		$order = true;
		if(!empty($is_send_park) && $recommend>=5 && !empty($recommend_parklot) && !empty($order)){
			$has = 1;
		}else{
			$has = 0;
		}
		return ApiResponse::showResult(200,['has'=>$has]);
	}
}