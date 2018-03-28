<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
\admin\assets\LoginAsset::register($this);
$this->title = 'H+';

?>


<script>
    if(window.top!==window.self){window.top.location=window.location}; //// 判断当前的window对象是否是top对象，如果不是，将top对象的网址自动导向被嵌入网页的网址
</script>

<div class="signinpanel">
    <div class="row">
        <div class="col-sm-7">
            <div class="signin-info">
                <div class="logopanel m-b">
                    <h1>[ H+ ]</h1>
                </div>
                <div class="m-b"></div>
                <h4>欢迎使用 <strong>H+ 后台主题UI框架</strong></h4>
                <strong>还没有账号？ <a href="#">立即注册&raquo;</a></strong>
            </div>
        </div>
        <div class="col-sm-5">
            <?php $form = ActiveForm::begin(['id' => 'loginform'])?>
            <h4 class="no-margins">登录：</h4>
            <p class="m-t-md">登录到H+后台主题UI框架</p>
            <?= $form->field($model,'username')->textInput(['placeholder'=>'用户名','class'=>'form-control uname'])->label(false);?>
            <?= $form->field($model,'password')->passwordInput(['placeholder'=>'密码','class'=>'form-control pword m-b'])->label(false);?>
            <?= Html::submitButton('登录',['class'=>'btn btn-success btn-block'])?>

            <?php ActiveForm::end();?>
        </div>
    </div>
    <div class="signup-footer">
        <div class="pull-left">
            &copy; 2015 All Rights Reserved. H+
        </div>
    </div>
</div>




