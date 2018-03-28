<?php
namespace admin\controllers;


use admin\models\Admin;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
/**
 * Created by PhpStorm.
 * User: Laria
 * Date: 2017/2/16
 * Time: 14:34
 */
 class ManagerController extends BaseController{

     public function actionIndex(){
         $logo = empty(Yii::$app->user->identity->a_logo) ? '':Yii::$app->user->identity->a_logo;
         return $this->render('logo',[
             'logo'=>$logo,
         ]);
     }

     /**
      * @return array
      * 上传图像
      */
     public function actionUpload(){
         $user_id=Yii::$app->user->identity->getId();
         if(empty($user_id)){
             return $this->showResult(302,'用户登录信息失效');
         }
         $imgtypes=[
             'image/jpg',
             'image/jpeg',
             'image/png',
             'image/pjpeg',
             'image/gif',
             'image/bmp',
             'image/x-png'
         ];
         $max_file_size=2048000;
         $pic_data = Yii::$app->request->post('mypic');
         if(empty($pic_data)){
             return $this->showResult(301,'未获取到图片数据');
         }
         $pos1 = strpos($pic_data,'data:');
         $pos2 = strpos($pic_data,';base64');
         if($pos1===false || $pos2===false){
             return $this->showResult(301,'未获取到图片数据');
         }else{
             $type = substr($pic_data,$pos1+5,$pos2-$pos1-5);
             if(!in_array($type,$imgtypes)){
                 return $this->showResult(301,'图片格式错误');
             }
             $pic_content = substr($pic_data,$pos2+8);
             if(empty($pic_content)){
                 return $this->showResult(301,'未获取到图片数据');
             }
             $pic =base64_decode($pic_content);
             if(empty($pic)){
                 return $this->showResult(301,'未获取到图片数据');
             }
             $basePath= $_SERVER['DOCUMENT_ROOT'].'/'.Yii::$app->params['base_file'];
             $pic_path = $basePath.'/photo/logo/';
             if(!is_dir($pic_path)){
                 @mkdir($pic_path,0777,true);
             }
             $logo_name = 'admin_'.time().$user_id.rand(100,999).'.'.substr($type,6);
             if(file_put_contents($pic_path.$logo_name,$pic)){
                 $size = filesize($pic_path);
                 if($size > $max_file_size){
                     @unlink ($pic_path.$logo_name);
                     return $this->showResult(301,'图片大小不能超过2M');
                 }
                 $admin = Admin::findOne(['a_id'=>$user_id]);
                 if(empty($admin)){
                     return $this->showResult(302,'用户登录信息失效');
                 }else{
                     if(!empty($admin->a_logo)){
                         @unlink ($basePath.'/photo'.$admin->a_logo);
                     }
                     $admin->a_logo = Yii::$app->params['img_path'].'/photo/logo/'.$logo_name;
                     if(!$admin->save()){
                         return $this->showResult(400,'保存失败，请重试');
                     }else{
                         return $this->showResult(200,'修改头像成功',$admin->a_logo);
                     }
                 }
             }else{
                 return $this->showResult(301,'上传失败');
             }
         }
     }


     public function actionLock(){
         $user_id = Yii::$app->user->identity->getId();
         if(empty($user_id)){
             return $this->showResult(302,'用户登录信息失效');
         }
         $wa_id = Yii::$app->request->post('wa_id');
         if(empty($wa_id)){
             return $this->showResult(301,'读取数据发生错误');
         }
         $adminInfo = Admin::findOne(['a_id'=>$wa_id]);
         if(empty($adminInfo)){
             return $this->showResult(301,'未获取到该用户的信息');
         }
         $adminInfo->updated_time = date('Y-m-d H:i:s',time());

         if($adminInfo->save()){
             return $this->showResult(200,'修改成功');
         }else{
             return $this->showResult(400,'修改失败，请重试');
         }
     }

     public function actionDel(){
         $user_id = Yii::$app->user->identity->getId();
         if(empty($user_id)){
             return $this->showResult(302,'用户登录信息失效');
         }
         $wa_id = Yii::$app->request->post('wa_id');
         if(empty($wa_id)){
             return $this->showResult(301,'读取数据发生错误');
         }
         $adminInfo = Admin::findOne(['a_id'=>$wa_id]);
         if(empty($adminInfo)){
             return $this->showResult(301,'未获取到该用户的信息');
         }
         $adminInfo->a_state=0;
         $adminInfo->updated_time = date('Y-m-d H:i:s',time());
         if($adminInfo->save()){
             return $this->showResult(200,'删除成功');
         }else{
             return $this->showResult(400,'删除失败，请重试');
         }
     }

     public function actionRecover(){
         $user_id = Yii::$app->user->identity->getId();
         if(empty($user_id)){
             return $this->showResult(302,'用户登录信息失效');
         }
         $wa_id = Yii::$app->request->post('wa_id');
         if(empty($wa_id)){
             return $this->showResult(301,'读取数据发生错误');
         }
         $adminInfo = Admin::findOne(['admin_id'=>$wa_id]);
         if(empty($adminInfo)){
             return $this->showResult(301,'未获取到该用户的信息');
         }
         $adminInfo->a_state=1;
         $adminInfo->updated_time = date('Y-m-d H:i:s',time());
         if($adminInfo->save()){
             return $this->showResult(200,'恢复成功');
         }else{
             return $this->showResult(400,'恢复失败，请重试');
         }
     }

     public function actionSearch(){
         $user = Yii::$app->user->identity;
         if(empty($user)){
             $this->goHome();
         }else{
             $key = Yii::$app->request->get('key');
             $val = Yii::$app->request->get('val');
             $values = Admin::find()->select($key)->where($key." like '%".$val."%' and a_type>=".$user->a_type)->asArray()->all();
             if(empty($values)){
                 $data = [];
             }else{
                 $data = array_unique(array_column($values,$key));
             }
             return $this->showResult(200,'成功',$data);
         }
     }

     protected function findModel($id)
     {
         if (($model = Admin::findOne($id)) !== null) {
             return $model;
         } else {
             throw new NotFoundHttpException('The requested page does not exist.');
         }
     }

     public function actionView(){
         $id=Yii::$app->request->post('wa_id');
         $query=[];
         if(empty($id)){
             $state='500';
             $res='商户管理员id获取失败';
         }else{
             $model=Admin::findOne($id);
             if(!empty($model)){
                 $state='200';
                 $res='查找成功';
                 $query=array(
                     'username'=>$model->a_name,
                     'password'=>$model->a_pwd,
                     'admin_logo'=>$model->a_logo,
                     'admin_role'=>Admin::getadminValue($model->a_id)
                 );
             }else{
                 $state='500';
                 $res='不存在该商户管理员信息';
             }
         }
         $data=[
             'state'=>$state,
             'res'=>$res,
             'data'=>$query,
         ];
         return json_encode($data);
     }
}