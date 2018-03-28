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
    'controllerNamespace' => 'admin\controllers',
    'bootstrap' => ['log'],
    'language'=>'zh-CN',
    "modules" => [
        'redactor' => [  //文本编辑器，需要开启php扩展 extension=php_fileinfo;扩展
            'class'=>'yii\redactor\RedactorModule',
            'imageAllowExtensions'=>['jpg','png','gif','jpeg'],
            'uploadDir' => '@photo/goods/detail',
            'uploadUrl' => '@web/../../photo/goods/detail',
        ],
        "admin" => [
            "class" => 'mdm\admin\Module',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
            'downloadAction' => 'gridview/export/download',
        ],
    ],
    "aliases" => [
        "@mdm/admin" => "@vendor/mdmsoft/yii2-admin",
    ],

    'as access' => [
        //ACF肯定要加,加了才会自动验证是否有权限
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'debug/*',
//            '*',
            'site/login',
            'site/error',
            'site/user-page',
            'message/test',
            'test/*'
        ],
    ],


    'components' => [
//        'request' => [
//            'csrfParam' => '_wine-admin',
//        ],
//        'jpush' => [
//            'class' => 'common\jpush\JPush',
//        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            //'cookieValidationKey' => 'nPY8jIj6BgRnL2W7iowq-vJBR_y1VJ-4',
            'enableCookieValidation'=>false,
            'enableCsrfValidation'=>false
        ],
//        'response'=>[
//            'class'=>'yii\web\Response',
//            'format'=>\yii\web\Response::FORMAT_JSON,
//        ],

        'user' => [
            'identityClass' => 'admin\models\Admin',
            'loginUrl'=>['site/login'],
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_wine-admin', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'wine-admin',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        "urlManager" => [
            //用于表明urlManager是否启用URL美化功能，在Yii1.1中称为path格式URL，
            // Yii2.0中改称美化。
            // 默认不启用。但实际使用中，特别是产品环境，一般都会启用。
            "enablePrettyUrl" => true,
            // 是否启用严格解析，如启用严格解析，要求当前请求应至少匹配1个路由规则，
            // 否则认为是无效路由。
            // 这个选项仅在 enablePrettyUrl 启用后才有效。
         //   "enableStrictParsing" => false,
            // 是否在URL中显示入口脚本。是对美化功能的进一步补充。
            "showScriptName" => true,
            // 指定续接在URL后面的一个后缀，如 .html 之类的。仅在 enablePrettyUrl 启用时有效。
//            "suffix" => "",
//            "rules" => [
//                "<controller:\w+>/<id:\d+>"=>"<controller>/view",
//                "<controller:\w+>/<action:\w+>"=>"<controller>/<action>"
//            ],
        ],

        'authManager' => [
            'class' => 'yii\rbac\DbManager', // 使用数据库管理配置文件
            "defaultRoles" => ["guest"],
        ],
        'assetManager'=>[
            'bundles'=>[
                'yii\web\JqueryAsset' => [
                    'js'=>[]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js'=>[]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
            ],

        ],
    ],
    'params' => $params,
];

