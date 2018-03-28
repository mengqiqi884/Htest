<?php

namespace v1\controllers;
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
     //   $res = ArrayHelper::getTimeTable($year, $month);
       echo(date("Y-m-d"));
        exit;
    }


    public function actionTest(){
        $app_key = '578a0b0f36c3076d9a4d3c91';
        $master_secret = 'e36a0eff21f88ae4b498a5b7';

        $client = new JPush($app_key, $master_secret);
        $result = $client->push()
            ->setPlatform(array('ios', 'android'))
            ->addRegistrationId(["101d855909475e71bfb"])
            ->setNotificationAlert('Hi, JPush')
            ->addAndroidNotification("您有新订单，请派送", "新订单", 1, array('repair_id'=>'367','type'=>'requirement'))
            ->addIosNotification("您有新订单，请派送", 'iOS sound', \JPush::DISABLE_BADGE, true, 'iOS category', array('order_id'=>'761','type'=>'new_waiting_order'))
            ->setMessage("msg content", 'msg title', 'type', array("key1"=>"value1", "key2"=>"value2"))
            ->setOptions(100000, 3600, null, false)
            ->send();
        var_dump($result);
        exit;
    }

    /**
     *
     */
    public function actionTest2(){
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