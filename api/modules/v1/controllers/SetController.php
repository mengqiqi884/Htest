<?php

namespace v1\controllers;
use api\controllers\ApiController;
use api\ext\auth\QueryParamAuth;
use v1\models\ResCity;
use v1\models\ResDistrict;
use v1\models\ResProvince;
use v1\models\User;
use Yii;
use yii\web\Controller;

class SetController extends ApiController
{
    public function behaviors(){
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'except'=>['arealist','citylist','districtlist','lat-lng','query-insert']
        ];
        $behaviors['verbs'] = [
            'class'=> \yii\filters\VerbFilter::className(),
            'actions'=>[
                '*'=>['post']
            ]
        ];
        return $behaviors;
    }

    /**
     * 检查是否设置密码
     * @return array
     */
    public function actionCheckpwd(){
        $userModel = Yii::$app->user->identity->toArray();
        $paypwd = $userModel['paypwd'];
        if(!empty($paypwd)){
            return $this->showResult('200','已设置');
        }else{
            return $this->showResult('300','尚未设置');
        }
    }


    /**
     * 设置支付密码
     * @return array
     */
    public function actionPwd(){
        $userModel = Yii::$app->user->identity->toArray();
        $user_id = $userModel['user_id'];
        $password = trim(Yii::$app->request->post('pwd',''));
        $compwd = trim(Yii::$app->request->post('compwd',''));
        if(empty($password) || empty($compwd)){
            return $this->showResult('300','密码不能为空');
        }
        if(strlen($password) <6){
            return $this->showResult('303','密码长度不能小于6位');
        }
        if($password != $compwd){
            return $this->showResult('301','两次密码输入不一致');
        }
        $userNewModel = User::findOne($user_id);
        $userNewModel->paypwd =strtolower(MD5($password));
        if($userNewModel->save()){
            return $this->showResult('200','成功');
        }else{
            return $this->showResult('302','失败');
        }
    }

    /**
     *修改支付密码
     */
    public function actionUpdatepwd(){
        $user_id = Yii::$app->user->identity->id;
        $old_pwd = trim(Yii::$app->request->post('oldpwd',''));
        $new_pwd = trim(Yii::$app->request->post('newpwd',''));
        $con_pwd = trim(Yii::$app->request->post('conpwd',''));
        if(strlen($new_pwd) <6){
            return $this->showResult('303','密码长度不能小于6位');
        }
        if($new_pwd != $con_pwd){
            return $this->showResult('301','两次密码输入不一致');
        }
        $old_pwd_md5 = strtolower(MD5($old_pwd));
        $userModel = User::findOne($user_id);
        if($userModel->paypwd != $old_pwd_md5){
            return $this->showResult('300','旧密码错误');
        }
        $userModel->paypwd = strtolower(MD5($new_pwd));
        if($userModel->save()){
            return $this->showResult('200','设置成功');
        }else{
            return $this->showResult('304','失败');
        }
    }


     /**
     *忘记支付密码1
     */
    public function actionFindpwd(){
        $account = Yii::$app->user->identity->toArray();
        $user_id = $account['user_id'];
        $phone = $account['account'];
        $code = Yii::$app->request->post('code','');
        $pwd = Yii::$app->request->post('pwd','');
        $con_pwd = Yii::$app->request->post('conpwd','');
        $old_code = Yii::$app->cache->get($phone);
        if(empty($code) || empty($pwd) || empty($con_pwd)){
            return $this->showResult('301','请填写所有参数');
        }
        if($old_code != $code){
            return $this->showResult('300','验证码错误');
        }
        if(strlen($pwd) < 6){
            return $this->showResult('302','密码长度不能小于6位');
        }
        if($pwd != $con_pwd){
            return $this->showResult('303','两次密码输入不一致');
        }
        $userModel = User::findOne($user_id);
        $userModel->paypwd = strtolower(MD5($pwd));
        if($userModel->save()){
            return $this->showResult('200','成功');
        }else{
            return $this->showResult('304','失败');
        }
    }

    /**
     * 区域列表接口(省)
     */
    public function actionArealist()
    {
        $provincelist = ResProvince::find()->asArray()->all();
        if (empty($provincelist)) {
            return $this->showResult(301, '没有数据');
        }
        foreach ($provincelist as $val) {
            $data[] = array(
                'province_id' => $val['province_id'],
                'province_name' => $val['province_name'],
            );
        }
        return $this->showResult('200', '成功', $data);
    }

    /**
     * 区域列表接口（市）
     */
    public function actionCitylist()
    {
        $province_id = Yii::$app->request->post('province_id');
        if (empty($province_id)) {
            return $this->showResult(302, '读取信息出错');
        }
        $citylist = ResCity::find()->where(['o_province_id' => $province_id])->asArray()->all();
        if (empty($citylist)) {
            return $this->showResult(301, '没有数据');
        }
        foreach ($citylist as $val) {
            $data[] = array(
                'city_id' => $val['city_id'],
                'city_name' => $val['city_name'],
            );
        }
        return $this->showResult(200, '成功', $data);
    }

    /**
     * 区域列表接口（区）
     */
    public function actionDistrictlist()
    {
        $city_id = Yii::$app->request->post('city_id');
        if (empty($city_id)) {
            return $this->showResult(302, '读取信息出错');
        }
        $districtlist = ResDistrict::find()->where(['o_city_id' => $city_id])->asArray()->all();
        if (empty($districtlist)) {
            return $this->showResult(301, '没有数据');
        }
        foreach ($districtlist as $val) {
            $data[] = array(
                'district_id' => $val['district_id'],
                'district_name' => $val['district_name'],
            );
        }
        return $this->showResult('200', '成功', $data);
    }
    /*
     * 根据地理位置获取经纬度
     */
    public function actionLatLng(){
        $address = Yii::$app->request->post('address');
        $url = 'http://api.map.baidu.com/place/v2/suggestion?query='.$address.'&region=全国&output=json&ak=7owiIqz0Fgv9356u7438jPGr&mcode=com.glavesoft.kdxshd';
        $res = $this->urlSenMsg($url);
        header("Content-type:text/html;charset=utf-8");
        echo $res; exit;
    }
    protected function urlSenMsg($uri = ''){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }


    /*
     * 批量生成水军用户
     */
    public function actionQueryInsert()
    {
        $data=[];
        for($i=1;$i<=100;$i++) {
            $phone='18600000';
            if($i <10) {
                $phone=$phone.'00'.$i;
            }else{
                if($i<100) {
                    $phone=$phone.'0'.$i;
                }else{
                    $phone=$phone.$i;
                }
            }

            $data[]=[
                'u_type' =>1,
                'u_phone' =>$phone,
                'u_pwd' =>strtolower(md5('123456')),
                'created_time'=>date('Y-m-d H:i:s'),
                'updated_time' => date('Y-m-d H:i:s')
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            Yii::$app->db->createCommand()->batchInsert('c_user',['u_type','u_phone','u_pwd','created_time','updated_time'],$data)->execute();
            $transaction->commit();
            return $this->showResult(200, '成功',$data);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->showResult(400, '失败');
        }

    }
}