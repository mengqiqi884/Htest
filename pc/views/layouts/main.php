<?php

/* @var $this \yii\web\View */
/* @var $content string */


use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use admin\assets\BootboxAsset;

\pc\assets\AppAsset::register($this);
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
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="favicon.ico">
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Cabin:400,500,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>

    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php $this->endBody() ?>
<!--加载圈-->
<!--<div id="preloader">-->
<!--    <i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom"></i>-->
<!--</div>-->
<!--头部-->
<?php include 'header.php';?>
<!--内容主体-->
<?= $content ?>
<!--底部-->
<?php include 'footer.php';?>
<!--返回顶部-->
<div class="scroll" style="display: none;">
    <img id="test" width="50" height="auto" style="position:fixed;right:5%;bottom:25%;" src="<?=\yii\helpers\Url::to('@web/images/return_top.png')?>"/>
</div>

<script>
    $(function () {
        showScroll();
        function showScroll() {
            //当下拉距离顶部150时，显示“返回顶部”图标
            $(window).scroll(function () {
                var scrollValue = $(window).scrollTop();
                scrollValue > 150 ? $('div[class=scroll]').fadeIn() : $('div[class=scroll]').fadeOut();
            });
        }
        //鼠标移上去改变光标样式
        $('#test').mouseover(function(){
            $(this).css("cursor","pointer");
        });
        //点击图标，返回顶部
        $('#test').click(function () {
            $("html,body").animate({ scrollTop: 0 }, 200);
        });
    });
</script>

</body>
</html>
<?php $this->endPage() ?>