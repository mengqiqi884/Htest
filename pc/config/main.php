<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
return [
    'id' => 'wine-manage',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'pc\controllers',
    'defaultRoute'=>'site/index', //默认访问路由
    'bootstrap' => ['log'],
    'language'=>'zh-CN',

    'components' => [
        'session' => [
            'class' => 'yii\web\Session',
            'timeout' =>36000,
            //'name' => 'TestLogin',//(正式环境下不需要此句)
        ],
        'user' => [
            'identityClass' => 'pc\models\CUser',
            'enableAutoLogin' => false,
            'loginUrl'=>['site/login'],
            'identityCookie' => ['name' => '_test-admin', 'httpOnly' => true],
        ],

        'smser'=>[
            'class'=>'zyj\smser\Ytxsms',
            'accountSid'=>'aaf98f894de202f3014de56cba3801c3',
            'accountToken'=>'b0d1931311324e0b8a40fb7e4b3785c5',
            'appId'=>'aaf98f894de79698014df0ba80be0413',
            'template_id'=>'22646'
        ],
        'urlManager'=>[
            'enablePrettyUrl'=>true
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // 使用数据库管理配置文件
            "defaultRoles" => ["guest"],
        ],
        'view'=>[
            'renderers'=>[
                'tpl'=>[
                    'class' => 'yii\smarty\ViewRenderer',
                    'options'=>[
                        'left_delimiter'=>'<{',
                        'right_delimiter'=>'}>'
                    ]
                ]
            ]
        ],
    ],

    'params' => $params,
];

