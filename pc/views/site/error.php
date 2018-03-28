<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
$newString = strstr($name, '#');
$length = strlen('#');
$arr = explode(')',substr($newString, $length));
?>
<div class="middle-box text-center animated fadeInDown">
    <h1><?=$arr[0]?></h1>
    <h3 class="font-bold"><?=nl2br(Html::encode($message))?></h3>

    <div class="error-desc">
        <?=$arr[0]=='404' ?
            '抱歉，页面好像去火星了~' :($arr[0]=='405' ? ' 服务器好像出错了...':'')
            .'<br/>'.'您可以返回主页看看<br/>'.'<a href="' . \yii\helpers\Url::toRoute(['site/index']).'" class="btn btn-primary m-t">主页</a>'
        ?>
    </div>
</div>