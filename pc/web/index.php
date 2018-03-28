<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);  //标识应用是否运行在调试模式
defined('YII_ENV') or define('YII_ENV', 'dev');  //默认值为 'prod'，表示应用运行在线上产品环境

//prod：生产环境。常量 YII_ENV_PROD 将被看作 true。如果你没修改过，这就是 YII_ENV 的默认值。
//dev：开发环境。常量 YII_ENV_DEV 将被看作 true。
//test：测试环境。常量 YII_ENV_TEST 将被看作 true。


require(__DIR__ . '/../../vendor/autoload.php');  //注册composer自动加载器
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');  //包含Yii类文件
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);
//创建、配置、运行一个应用
$application = new yii\web\Application($config);
$application->run();