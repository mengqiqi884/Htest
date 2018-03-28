<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'timeZone'=>'Asia/Chongqing',
    'modules'=>[
        'v1'=>[
            'class'=>'v1\v1',
            'basePath'=>'@api/modules/v1'
        ],
        'v2'=>[
            'class'=>'v2\v2',
            'basePath'=>'@api/modules/v2'
        ],
    ],
    'components' => [
        #短信
        'smser'=>[
            'class'=>'zyj\smser\Ypsms',
            // 'key'=>$params['lsmkey']
            'apikey'=>'cc0b68dbf6ed9e1e7725118bfc5775b1'
        ],
        #地图
        'gaode'=>[
            'class'=>'zyj\map\gaode\Regao',

        ],
        #支付宝支付
        'alipay' => [
            'class'=>'common\pay\alipay\AlipayNotify',
        ],
        'jpush' => [
            'class' => 'common\jpush\JPush',
        ],
        'cache'=> [
            'class' => 'yii\caching\FileCache',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            //'cookieValidationKey' => 'nPY8jIj6BgRnL2W7iowq-vJBR_y1VJ-4',
            'enableCookieValidation'=>false,
            'enableCsrfValidation'=>false
        ],
        'response'=>[
            'class'=>'yii\web\Response',
            'format'=>\yii\web\Response::FORMAT_JSON,
            'on beforeSend' => function($event){   //自定义错误响应格式。
                $response = $event->sender;  //event sender：调用 trigger() 方法的对象
                if ($response->data !== null) { //custom data：附加事件处理器时传入的数据，默认为空，
                    $response->data = [
                        'status' => '-1',
                        'success' => $response->isSuccessful,
                        'data' => $response->data,
                    ];
                }
            }
        ],
        'user'=>[
            'identityClass'=>'v1\models\CUser',
            'enableSession'=>false,
            'loginUrl'=>null,
        ],
        "urlManager" => [
            //用于表明urlManager是否启用URL美化功能，在Yii1.1中称为path格式URL，
            // Yii2.0中改称美化。
            // 默认不启用。但实际使用中，特别是产品环境，一般都会启用。
            "enablePrettyUrl" => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',  //yii\log\FileTarget：保存日志消息到文件中.  yii\log\DbTarget：在数据库表里存储日志消息。 yii\log\EmailTarget：发送日志消息到预先指定的邮箱地址。 yii\log\SyslogTarget：通过调用PHP函数 syslog() 将日志消息保存到系统日志里。
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
