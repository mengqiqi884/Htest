<?php

$config = [
    'language'=>'zh-CN',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=test_bk',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'hz0u0RTulpp22XOnL99ZGiFpA51P5pc0',
        ],
    ],
];

if (!YII_ENV_TEST) {  //原来代码
//if (!YII_ENV_DEV) {  //Laria修改后，关闭网页的底部debug
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators'=>[
            'kartikgii-crud' => ['class' => 'warrence\kartikgii\crud\Generator'],
        ],
        'allowedIPs' => ['127.0.0.1','::1'] //允许访问的IP地址
    ];
}

return $config;
