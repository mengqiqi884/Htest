<?php

/* @var $this \yii\web\View */
/* @var $content string */


use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use admin\assets\BootboxAsset;

\admin\assets\AppAsset::register($this);
//
//BootboxAsset::register($this);
//BootboxAsset::overrideSystemConfirm();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <title>H_test主页</title>
    <meta name="keywords" content="H+">
    <meta name="description" content="">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <link rel="shortcut icon" href="favicon.ico">
    <?php $this->head() ?>
</head>
<body class="signin fixed-sidebar full-height-layout gray-bg">
<?php $this->beginBody() ?>
<?php $this->endBody() ?>
<?= $content ?>
</body>
</html>
<?php $this->endPage() ?>