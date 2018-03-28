<?php

namespace v2\controllers;
use api\ext\auth\QueryParamAuth;
use api\helpers\ApiResponse;
use api\helpers\controllers\ApiController;
use api\helpers\filters\ApiVerbFilter;
use v2\models\User;
use Yii;
use yii\web\Controller;

class SetController extends ApiController
{
    public function behaviors(){
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
        'class' => \v2\QueryParamAuth::className(),
            //'except'=>['']
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
     * 检查是否设置密码
     * @return array
     */
    public function actionCheckpwd(){
        $userModel = Yii::$app->controller->module->user->identity->toArray();
        $paypwd = $userModel['paypwd'];
        if(!empty($paypwd)){
            return ApiResponse::showResult(200,[],'已设置');
        }else{
            //return $this->showResult('300','尚未设置');
            return ApiResponse::showResult(101,[],'尚未设置');
        }
    }


    /**
     * 设置支付密码
     * @return array
     */
    public function actionPwd(){
        $userModel = Yii::$app->controller->module->user->identity->toArray();
        $user_id = $userModel['user_id'];
        $password = trim(Yii::$app->request->post('pwd',''));
        $compwd = trim(Yii::$app->request->post('compwd',''));
        if(empty($password) || empty($compwd)){
//            return $this->showResult('300','密码不能为空');
            return ApiResponse::showResult(101,[],'密码不能为空');
        }
        if(strlen($password) <6){
//            return $this->showResult('303','密码长度不能小于6位');
            return ApiResponse::showResult(101,[],'密码长度不能小于6位');
        }
        if($password != $compwd){
            return ApiResponse::showResult(101,[],'两次密码输入不一致');
//            return $this->showResult('301','两次密码输入不一致');
        }
        $userNewModel = User::findOne($user_id);
        $userNewModel->paypwd =strtolower(MD5($password));
        if($userNewModel->save()){
            return ApiResponse::showResult(200);
//            return $this->showResult('200','成功');
        }else{
            return ApiResponse::showResult(100);
//            return $this->showResult('302','失败');
        }
    }

    /**
     *修改支付密码
     */
    public function actionUpdatepwd(){
        $user_id = Yii::$app->controller->module->user->identity->id;
        $old_pwd = trim(Yii::$app->request->post('oldpwd',''));
        $new_pwd = trim(Yii::$app->request->post('newpwd',''));
        $con_pwd = trim(Yii::$app->request->post('conpwd',''));
        $old_pwd_md5 = strtolower(MD5($old_pwd));
        $userModel = User::findOne($user_id);
        if($userModel->paypwd != $old_pwd_md5){
//            return $this->showResult('300','旧密码错误');
            return ApiResponse::showResult(101,[],'旧密码错误');
        }
        if(strlen($new_pwd) <6){
//            return $this->showResult('303','密码长度不能小于6位');
            return ApiResponse::showResult(101,[],'密码长度不能小于6位');
        }
        if($new_pwd != $con_pwd){
//            return $this->showResult('301','两次密码输入不一致');
            return ApiResponse::showResult(101,[],'两次密码输入不一致');
        }

        $userModel->paypwd = strtolower(MD5($new_pwd));
        if($userModel->save()){
//            return $this->showResult('200','设置成功');
            return ApiResponse::showResult(200);
        }else{
            return ApiResponse::showResult(100);
//            return $this->showResult('304','失败');
        }
    }


     /**
     *忘记支付密码1
     */
    public function actionFindpwd(){
        $account = Yii::$app->controller->module->user->identity->toArray();
        $user_id = $account['user_id'];
        $phone = $account['account'];
        $code = Yii::$app->request->post('code','');
        $pwd = Yii::$app->request->post('pwd','');
        $con_pwd = Yii::$app->request->post('conpwd','');
        $old_code = Yii::$app->cache->get($phone);
        if(empty($code) || empty($pwd) || empty($con_pwd)){
            return ApiResponse::showResult(101,[],'请将信息填写完整');
//            return $this->showResult('301','请填写所有参数');
        }
        if($old_code != $code){
//            return $this->showResult('300','验证码错误');
            return ApiResponse::showResult(101,[],'验证码错误');
        }
        if(strlen($pwd) < 6){
//            return $this->showResult('302','密码长度不能小于6位');
            return ApiResponse::showResult(101,[],'密码长度不能小于6位');
        }
        if($pwd != $con_pwd){
//            return $this->showResult('303','两次密码输入不一致');
            return ApiResponse::showResult(101,[],'两次密码输入不一致');
        }
        $userModel = User::findOne($user_id);
        $userModel->paypwd = strtolower(MD5($pwd));
        if($userModel->save()){
//            return $this->showResult('200','成功');
            return ApiResponse::showResult(200);
        }else{
            return ApiResponse::showResult(100);
//            return $this->showResult('304','失败');
        }
    }


}