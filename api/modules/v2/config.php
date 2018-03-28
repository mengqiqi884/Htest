<?php
require(__DIR__ . '/db-local.php');
$config = [
    'components' => [
        'db'=>$db,
        'user'=>[
            'class'=>'yii\web\User',
            'identityClass'=>'v2\models\Login',
            'enableSession'=>false,
            'loginUrl'=>null,
        ],
    ],
    'params' => [
        'ryappid'=>'kj7swf8o70r02',
        'ryappsecret'=>'4OqjttQi87y'
    ],
];
return $config;
