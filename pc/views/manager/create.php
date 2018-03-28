<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model admin\models\AuthItem */

$this->title = '新增管理员';
$user = Yii::$app->user->identity;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox-content">
        <div class="row pd-10">
            <h1><?= Html::encode($this->title) ?></h1>
            <div class="auth-item-form col-sm-4">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'admin_name')->textInput()->label('用户名') ?>
                <?= $form->field($model, 'admin_pwd')->passwordInput()->label('密码') ?>
                <?= $form->field($model, 'confirm_password')->passwordInput()->label('确认密码')?>
                <?= $form->field($model, 'item_name')->dropDownList($item)->label('用户组');?>
                <?php ActiveForm::end(); ?>
                <button type="submit" id="manager_create" class="btn btn-primary">新 增</button>
            </div>
        </div>
    </div>

</div>
<?=Html::jsFile('@web/js/wine/wine.js?_'.time())?>
<?=Html::jsFile('@web/js/wine/manager.js?_'.time())?>
