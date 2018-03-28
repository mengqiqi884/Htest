<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=ikuaidian.mysql.rds.aliyuncs.com;dbname=kd2',
            'username' => 'kdapp',
            'password' => 'ltj_kdapp',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
