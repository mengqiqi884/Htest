<?php
namespace v2\controllers;

use api\helpers\ApiResponse;
use api\helpers\filters\ApiVerbFilter;
use v2\QueryParamAuth;
use api\helpers\controllers\ApiController;
use common\helpers\ArrayHelper;
use v2\models\Friend;
use v2\models\Limit;
use v1\models\Parkboot;
use v2\models\Parkspot;
use v2\models\User;
use yii;

class IndexController extends ApiController
{
	 public function behaviors(){
     $behaviors = parent::behaviors();
     $behaviors['authenticator'] = [
         'class' => QueryParamAuth::className(),
         'except'=>['limit']
     ];
     $behaviors['verbs'] = [
         'class'=> ApiVerbFilter::className(),
         'actions'=>[
             '*'=>['post']
         ]
     ];
     return $behaviors;
 	}

	/**
	 *主页滑动用户接口(系统推荐的附近的发不过车位的人)
     */
	public function actionIndex(){
		//$userinfo = Yii::$app->user->identity->toArray();
		$user_id = Yii::$app->controller->module->user->identity->id;
		$lnt = Yii::$app->request->post('lnt','');
		$lat = Yii::$app->request->post('lat','');
		$model = yii\base\DynamicModel::validateData(compact('lnt','lat'),[
			[['lnt','lat'],'required','message'=>'参数不能为空']
		]);
		if($model->hasErrors()){
			return ApiResponse::showResult(101,[],ArrayHelper::myGetFirstError($model));
		}
		$page = Yii::$app->request->post('page',1);
		$limit = Yii::$app->request->post('limit',5);
		$offset = ($page-1)* $limit;
		$list = User::find()
				->userSelect('user.user_id,user.sex,user.username,user.logo')
				->userJoinParkspot('parkspot.park_id,parkspot.address,parkspot.building')
				->selectParkspotDistance($lat,$lnt)
				->joinDistrict('parkspot.district')
//				->IsRecommend()
				->isApplyPark()
				->orderBy('distance asc')
				->filterDistance()
				->andWhere('user.user_id != :user_id',[':user_id'=>$user_id])
				->limit($limit)
				->offset($offset)
				->asArray()
				->all();
		array_walk_recursive($list,[User::className(),'walkBack']);
		return ApiResponse::showResult(200,$list);
	}

	/**
	 *主页滑动用户接口(系统推荐的附近的人)
     */
	public function actionNear(){
		$user_id = Yii::$app->controller->module->user->identity->id;
		$lnt = Yii::$app->request->post('lnt','');
		$lat = Yii::$app->request->post('lat','');
		$model = yii\base\DynamicModel::validateData(compact('lnt','lat'),[
				[['lnt','lat'],'required','message'=>'参数不能为空']
		]);
		if($model->hasErrors()){
			return ApiResponse::showResult(101,[],ArrayHelper::myGetFirstError($model));
		}
		$page = Yii::$app->request->post('page',1);
		$limit = Yii::$app->request->post('limit',5);
		$offset = ($page-1)* $limit;
		$list = User::find()
				->userSelect('user.sex,user.logo,user.user_id')
				->userJoinParkspot('parkspot.park_id')
				->selectParkspotDistance($lat,$lnt)
//				->IsRecommend()
//				->isApplyPark()
				->orderBy('distance asc')
				->andWhere('user.user_id != :user_id',[':user_id'=>$user_id])
				->filterDistance()
				->limit($limit)
				->offset($offset)
				->asArray()
				->all();
		array_walk_recursive($list,[User::className(),'walkBack']);
		return ApiResponse::showResult(200,$list);
	}

	/**
	 *附近的人列表
	 */
	public function actionNearlist(){
		$user_id = Yii::$app->controller->module->user->identity->id;
		$lnt = Yii::$app->request->post('lnt','');
		$lat = Yii::$app->request->post('lat','');
		$model = yii\base\DynamicModel::validateData(compact('lnt','lat'),[
				[['lnt','lat'],'required','message'=>'参数不能为空']
		]);
		if($model->hasErrors()){
			return ApiResponse::showResult(101,[],ArrayHelper::myGetFirstError($model));
		}
		$page = Yii::$app->request->post('page',1);
		$limit = Yii::$app->request->post('limit',10);
		$sex = Yii::$app->request->post('sex');
		$distance = Yii::$app->request->post('distance');
		$price = Yii::$app->request->post('price');
		$offset = ($page-1)* $limit;
		$list = User::find()
				->userSelect('user.user_id,user.sex,user.username,user.logo')
				->userJoinParkspot('parkspot.park_id,parkspot.address,parkspot.building,parkspot.price')
				//->userJoinCarBrand()
				->joinSaleType()
				->selectParkspotDistance($lat,$lnt)
				//->IsRecommend()
				//->isApplyPark()
				->filterDistance($distance)
				->filterPrice($price)
				->filterSex($sex)
				->andWhere('user.user_id != :user_id',[':user_id'=>$user_id])
				->limit($limit)
				->offset($offset)
				->asArray()
				->all();
		array_walk_recursive($list,[User::className(),'walkBack']);
		return ApiResponse::showResult(200,$list);
	}

	/**
	 *主页限号接口
     */
	public function actionLimit(){
		$today_w = date('w');
		$nums = Limit::find()->where(['limit_w'=>$today_w])->orderBy('limit_id desc')->one();
		if(!empty($nums)){
			return $this->showResult('200','成功',explode(',',$nums->limit_nb));
		}else{
			return $this->showResult('300','不限号');
		}
	}

	/**
	 *首页去哪停接口
     */
	public function actionSearchnear(){
		$lnt = Yii::$app->request->post('lnt','');
		$lat = Yii::$app->request->post('lat','');
		$model = yii\base\DynamicModel::validateData(compact('lnt','lat'),[
				[['lnt','lat'],'required','message'=>'参数不能为空']
		]);
		$page = Yii::$app->request->post('page',1);
		$limit = Yii::$app->request->post('limit',50);
		$offset = ($page-1)*$limit;
		if($model->hasErrors()){
			return ApiResponse::showResult(101,[],ArrayHelper::myGetFirstError($model));
		}
		$list = User::find()
				->userSelect('user.user_id,user.sex,user.username,user.logo')
				->userJoinParkspot('parkspot.park_id,parkspot.building,parkspot.price,parkspot.lat,parkspot.lnt')
				//->userJoinCarBrand()
				->joinSaleType()
				->selectParkspotDistance($lat,$lnt)
				//->filterDistance()
				//->IsRecommend()
				->isApplyPark()
				->countParks()
				->limit($limit)
				->offset($offset)
				->asArray()
				->all();
		array_walk_recursive($list,[User::className(),'walkBack']);
		return ApiResponse::showResult(200,$list);
	}
}