<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/31
 * Time: 15:31
 */

namespace v1\controllers;


use v1\models\CBanner;
use v1\models\CCarreplace;
use v1\models\CForums;
use v1\models\CMessage;
use v1\models\CUser;

use Yii;
use common\helpers\StringHelper;
use yii\web\UploadedFile;
use zyj\smser\Qczhsms;


class UserController extends ApiController
    {
    const PAGE_SIZE=10;

    protected function validatePassword($pass1,$pass2){
        return $pass1 == $pass2;
    }

    public function actionIndex(){
//        $sms = Yii::$app->xwsmser;
//        var_dump($sms->Sendmsg(17701420033,'111'));
    }

    //用户注册
    public  function actionRegister(){
        $phone = Yii::$app->request->post('phone', '');
        $password = Yii::$app->request->post('password', '');
        $code = Yii::$app->request->post('code', '');
        if(empty($code)){
            return $this->showResult(302,'请填写验证码');
        }
        if (empty($phone) || empty($password)) {
            return $this->showResult(301, "手机号或者密码不能为空");
        }
        //验证注册手机号是否已注册
        $userExists = CUser::findOne(['u_phone'=>$phone]);
        if(!empty($userExists)){
            return $this->showResult(303,'该手机号已注册');
        }
        //判断验证码正确
        $user_code = Yii::$app->cache->get($phone);

        if($user_code === false){
            return $this->showResult(305,'验证码已过期，请重新获取');
        }

        if($user_code != $code){
            return $this->showResult(307,'验证码不正确');
        }

        $login = new CUser();
        $login->u_phone = $phone;
        $login->u_pwd = StringHelper::MyMd5($password);
        $login->u_type = 1;
        $login->u_token = Yii::$app->getSecurity()->generateRandomString();
        $login->created_time = date('Y-m-d H:i:s',time());
        $login->updated_time = date('Y-m-d H:i:s',time());

        $data=[
            'token'=>$login->u_token
        ];
        if($login->save()){
            return $this->showResult(200,'注册成功',$data);
        }else{
            return $this->showResult(400,'注册失败');
        }
    }

        /*
         * 用户登录
          */
      public function actionLogin()
      {
          //获取数据
          $phone = Yii::$app->request->post('phone', '');
          $password = Yii::$app->request->post('password', '');

          //检查参数是否为空
          if (empty($phone) || empty($password)) {
              return $this->showResult(301, "手机号或者密码不能为空");
          }

          //获取用户信息并验证
          $user = CUser::find()->where(['u_phone' => $phone, 'u_state' => 1, 'is_del' => 0])->one();
          if (empty($user)) {
              return $this->showResult(302, "手机号尚未注册或者已被锁定");
          }

          if (!$this->validatePassword(StringHelper::MyMd5($password), $user->u_pwd)) {
              return $this->showResult(303, "密码错误，请重新输入");
          }
          $token = Yii::$app->getSecurity()->generateRandomString();
          $user->u_token = $token;
          $user->updated_time = date('Y-m-d H:i:s', time());
          $data = [
              'token' => $token,
          ];

          if ($user->save()) {
              return $this->showResult(200, '登录成功', $data);
          } else {
              return $this->showResult(400, '登录失败');
          }

      }



    /*
        * 上传图片
        * */
    public function actionUpdatelogo()
    {
        //获取数据
        $post = Yii::$app->request->isPost;
        if(!$post){
            return $this->showResult(301,'未上传图片');
        }

        //解析二进制数据到文件中
        $file = UploadedFile::getInstanceByName('logo');
        //生成文件名和路径
        $fileName = time().rand(111111,999999).'.'.$file->extension;
        $filePath = '../../photo/logo/'.$fileName;
        $pic_url =Yii::$app->params['img_path'].'/logo/'.$fileName;

        //存放
        if($file->saveAs($filePath)){
            return $this->showResult(200,'上传成功',['logo' => $pic_url]);
        }else {
            return $this->showResult(400, '系统异常，上传失败');
        }

        }

    /**
     * 头像编辑添加
     */

    public function actionHeadpic(){
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }

        $userModel = CUser::findOne(['u_id'=>$user_id]);
        if(empty($userModel)){
            return $this->showResult(302, '获取用户信息失败');
        }

        $photo = Yii::$app->request->post('headImg', $userModel->u_headImg);
        $userModel->u_headImg = $photo;
        if ($userModel->save()) {
            return $this->showResult(200, '保存头像成功');
        } else {
            return $this->showResult(400, '保存头像失败');
        }

    }


    /**
     * @return array
     * 填写修改个人信息接口
     */
    public function actionInformation()
    {
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id'=>$user_id]);
        if(empty($userModel)){
            return $this->showResult(302, '获取用户信息失败');
        }
        $nickname = Yii::$app->request->post('nickname', $userModel->u_nickname);
        $sex = Yii::$app->request->post('sex_id', $userModel->u_sex);
        $age = Yii::$app->request->post('age', $userModel->u_age);

//            $photo = Yii::$app->request->post('head_pic', $userModel->head_pic);
        $userModel->u_nickname = $nickname;
        $userModel->u_sex= $sex;
        $userModel->u_age = $age;
        $userModel->updated_time = date('Y-m-d H:i:s',time());
        //参数空检查

        if ($userModel->save()) {
            return $this->showResult(200, '保存个人信息成功');
        } else {
            return $this->showResult(400, '保存个人信息失败');
        }
    }



    /**
     * 个人中心
     */

    public function  actionMycenter(){
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id'=>$user_id]);
        if(empty($userModel)){
            return $this->showResult(302, '获取用户信息失败');
        }

        //查找用户的车辆
        $carcount = CCarreplace::find()
            ->where(['is_del'=>0,'is_forbidden'=>0,'r_role'=>1,'r_accept_id'=>$user_id])
            ->andWhere(['!=','r_state',2])
            ->count();
        //查找用户帖子数
        $forumscount = CForums::find()->where(['is_del'=>0,'f_user_id'=>$user_id])
           // ->andWhere(['f_state'=>'-1'])
            ->count();

//        var_dump($userModel);
//        exit;
        $data='';
        $data[]=[
            'head_pic'=>$userModel->u_headImg,
            'nickname'=>$userModel->u_nickname,
            'sex'=>$userModel->u_sex,
            'age'=>$userModel->u_age,
            'score'=>$userModel->u_score,
            'forums'=>$forumscount,
            'cars'=>$carcount,
        ];

        if(empty($data)){
            return $this->showResult(400, '获取失败');
        }else{
            return $this->showResult(200, '获取成功',$data);
        }

    }

    //判断该用户是否已经添加车辆
    public function actionCarexists(){
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id'=>$user_id]);
        if(empty($userModel)){
            return $this->showResult(302, '获取用户信息失败');
        }

        $exists = CCarreplace::find()->where(['r_accept_id'=>$user_id,'r_state'=>1,'is_del'=>0,'is_forbidden'=>0,'r_role'=>1])->one();

        $is_exists = $exists ==''? 0 : 1;
        $data = [
            'is_exists'=>$is_exists
        ];
        if(empty($data)){
            return $this->showResult(400, '获取失败');
        }else{
            return $this->showResult(200, '获取成功',$data);
        }
    }

    /*
      * 获取验证码
       */
    public function actionSmser(){

        $code = strval(rand(100000, 999999));
        $phone=Yii::$app->request->post('phone','');
        $type=Yii::$app->request->post('type','');//1为注册发送 2为改密发送
        $content = "您的验证码是".$code;
        $session = '鲜橙置换';
        $cache = Yii::$app->cache;
        if($type == 1){
        //查找用户
        $is_exists = CUser::find()->where(['u_phone'=>$phone,'u_type'=>1,'is_del'=>0])->one();
        //判断号码是否已注册
        if(!empty($is_exists)){
            return $this->showResult(303,'该用户已注册或已被锁定，请勿重复注册');
        }
        }else if ($type == 2){
            $is_exists = CUser::find()->where(['u_phone'=>$phone,'u_type'=>1,'is_del'=>0])->one();
            //判断号码是否已注册
            if(empty($is_exists)){
                return $this->showResult(303,'该用户尚未注册，或已被禁用');
            }
            $black = $is_exists->u_state;
            if($black == 2){
                return $this->showResult(305,'账号已被禁用');
            }
        }
        if (empty($phone)) {
            return $this->showResult(301, '手机号不能为空');
        }
        if ($this->validateMobilePhone($phone)) {
            $smser = new Qczhsms();
            $res = $smser->sendSms($phone,$content,$session);
            if ($res) {
                $cache->set($phone, $code, 1800);
                return $this->showResult(200, '发送成功');
            } else {
                return $this->showResult(400, '发送失败，检查网络');
            }
        }else {
            return $this->showResult(302, '手机号格式出错，请重新输入');
        }
    }


    //用户修改找回密码
    public  function actionRetrievepwd(){
        $phone = Yii::$app->request->post('phone', '');
        $password = Yii::$app->request->post('password', '');
        $code = Yii::$app->request->post('code', '');

        if (empty($phone) || empty($password)) {
            return $this->showResult(301, "手机号或者密码不能为空");
        }
        //判断验证码正确
        $user_code = Yii::$app->cache->get($phone);

        if($user_code === false){
            return $this->showResult(305,'验证码已过期，请重新获取');
        }

        if($user_code != $code){
            return $this->showResult(307,'验证码不正确');
        }
        //验证注册手机号是否已注册
        $userExists = CUser::findOne(['u_phone'=>$phone,'u_state'=>1]);
        if(empty($userExists)){
            return $this->showResult(303,'该手机号尚未注册或已被禁用');
        }
        $userExists->u_phone = $phone;
        $userExists->u_pwd = StringHelper::MyMd5($password);
        $userExists->u_token = Yii::$app->getSecurity()->generateRandomString();
        $userExists->updated_time = date('Y-m-d H:i:s',time());

        $data=[
            'token'=>$userExists->u_token
        ];
        if($userExists->save()){
            return $this->showResult(200,'修改成功',$data);
        }else{
            return $this->showResult(400,'修改失败');
        }
    }

    /*
       * 登陆后接口register_id
      * */

    public function actionRegisterid(){
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id'=>$user_id]);
        if(empty($userModel)){
            return $this->showResult(302, '获取用户信息失败');
        }
        $register_id = Yii::$app->request->post('register_id','');
        if(empty($register_id)){
            return $this->showResult(303, '缺少参数');
        }
        $userModel->u_register_id = $register_id;
        if($userModel->save()){
            return $this->showResult(200,'成功');
        }else{

            return $this->showResult(400,'失败');
        }
    }

    //banner
    public function actionBanner(){
        $type = Yii::$app->request->post('type','');//传1是首页 ，传2 是用车报告，3是维修保养
        if(empty($type)){
            return $this->showResult(303, '缺少参数');
        }

        $banner = CBanner::find()->where(['b_location'=>$type])->orderBy('b_sortorder ASC')->all();
        if(empty($banner)){
            return $this->showResult(304, '暂无轮播图');
        }
        $data=[];
        foreach ($banner as $v){
            $data[]=[
                'pic'=>Yii::$app->params['pic_url'].$v->b_img,//图片
                'url'=>$v->b_url,//跳转链接
                'title'=>$v->b_title,//标题
                'content'=>$v->content,//内容
                'time'=>$v->created_time
            ];
        }

        if(!empty($data)){
            return $this->showResult(200,'获取成功',$data);
        }else{
            return $this->showResult(400,'获取失败');
        }
    }

    //消息列表

    public function actionMessagelist(){
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id'=>$user_id]);
        if(empty($userModel)){
            return $this->showResult(302, '获取用户信息失败');
        }
        $type = Yii::$app->request->post('type','');//传1是最新一条 ，传2 列表
        if(empty($type)){
            return $this->showResult(303, '缺少参数');
        }
        $page = Yii::$app->request->post('page',1);
        $string = '0,'.$user_id;
        $data='';

        $message = CMessage::find()->where(['and','m_type = 1','m_user_id in ('.$string.')'])->orderBy('created_time DESC')->all();

        if($type == 2){
            if(empty($message)){
                return $this->showResArr(400, '暂无系统消息');
            }
        foreach($message as $v){
            $data[]=[
                'author'=>$v->m_author,
                'content'=>$v->m_content,
                'url'=>$v->m_url,
                'created_time'=>$v->created_time,
                'is_read'=>$v->m_is_read,
            ];
        }
            if (empty($data)) {
                return $this->showResArr(400, '暂无消息');
            } else {
                $result = CMessage::updateAll(['m_is_read' => 1], 'm_user_id in ('.$string.')');
                return $this->showListArr(200, '获取成功', count($data), array_slice($data, ($page - 1) * static::PAGE_SIZE, static::PAGE_SIZE));
            }
        }elseif ($type == 1){
            if(empty($message)){
                return $this->showResult(400,'暂无消息');
            }
            $data=[
                'author'=>$message[0]->m_author,
                'content'=>$message[0]->m_content,
                'url'=>$message[0]->m_url,
                'created_time'=>$message[0]->created_time,
                'is_read'=>$message[0]->m_is_read,
            ];

            if(!empty($data)){
                return $this->showResult(200,'获取成功',$data);
            }else{
                return $this->showResult(400,'获取失败');
            }
        }
    }
    //退出登录
    public function actionQuit(){
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id'=>$user_id]);
        if(empty($userModel)){
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel->u_token ='';
        $userModel->u_register_id='';
        $userModel->updated_time=date('Y-m-d H:i:s',time());
        if($userModel->save()){
            return $this->showResult(200,'退出成功');
        }else{
            return $this->showResult(400,'退出失败');
        }
    }
}

