<?php

namespace v2\controllers;
use api\models\User;
use common\helpers\ArrayHelper;
use Yii;
use yii\helpers\StringHelper;
use yii\web\Controller;
use api\ext\auth\QueryParamAuth;
use \JPush;
class TextController extends Controller
{

    public function actionCalender()
    {
        $year = Yii::$app->request->post('year', date('Y'));
        $month = Yii::$app->request->post('month', date('n'));
        $res = ArrayHelper::getTimeTable($year, $month);
        var_dump($res);
        exit;
    }


    public function actionTest(){
      //  $regsterUserModel = \v2\models\User::findOne(6);
     $res =    ArrayHelper::pushNotifyByReg(['161a3797c807726728a'],'51停车','你有个车位，用户已付款,请确认',['notice_type'=>'order']);
//       $res =  ArrayHelper::pushNotifyByReg(['1507bfd3f7c596b6519'],'51停车','你有个车位，用户已付款');
        var_dump($res);
        exit;
//        $app_key = '0dc7b4852d740aaa2e6efef4';
//        $master_secret = '8882c9a191cfa4438f95dde6';
//
//        $client = new JPush($app_key, $master_secret);
//        $result = $client->push()
//            ->setPlatform(array('ios', 'android'))
//            ->addRegistrationId(['141fe1da9ea931858a5'])
////            ->addAlias('alias1')
////            ->addTag(array('tag1', 'tag2'))
//            ->setNotificationAlert('Hi, JPush')
//            ->addAndroidNotification('Hi, android notification', 'notification title', 1, array("key1"=>"value1", "key2"=>"value2"))
//            ->addIosNotification("Hi, iOS notification", 'iOS sound', JPush::DISABLE_BADGE, true, 'iOS category', array("key1"=>"value1", "key2"=>"value2"))
//            ->setMessage("msg content", 'msg title', 'type', array("key1"=>"value1", "key2"=>"value2"))
//            ->setOptions(100000, 3600, null, false)
//            ->send();
//        var_dump($result);
//        exit;
    }

    /**
     *
     */
    public function actionTest2(){
        echo md5('51parking');
        exit;
        $reflectionClass = new \ReflectionClass('v1\models\User');
        \Reflection::export($reflectionClass);
        exit;
        var_dump(get_class_methods($this));
        exit;
        var_dump(get_class($this));
        exit;
        print_r(get_declared_classes());
        exit;
        var_dump(class_exists('v1\models\User'));
        exit;
        echo Yii::$app->security->generateRandomString();
        echo uniqid();
        exit;
    }
}