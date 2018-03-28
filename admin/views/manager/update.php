<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model admin\models\AuthItem */

$this->title = '更新管理员';
$user = Yii::$app->user->identity;
?>
<div class="wrapper wrapper-content">
    <div class="ibox-content">
        <div class="row pd-10">
            <h1><?= Html::encode($this->title) ?></h1>
            <div class="auth-item-form col-sm-4">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'a_id')->hiddenInput()->label('')?>

                <?= $form->field($model, 'a_name')->textInput(['readonly'=>true,'disabled'=>true])->label('登陆名<font style=" font-size: 12px;color: #28866a">（不可修改）</font>') ?>
                <?php
                if(($user->a_type<$model->a_type) || $user->a_id == $model->a_id){
                    echo $form->field($model, 'admin_pwd')->passwordInput(['value'=>'******','onchange'=>"$('#confirm_password').show()"])->label('密码');
                    ?>
                    <div id="confirm_password" style="display: none">
                        <?= $form->field($model, 'confirm_password')->passwordInput(['value'=>''])->label('确认密码')?>
                    </div>
                <?php
                }else{
                    echo $form->field($model, 'a_pwd')->passwordInput(['value'=>'******','disabled'=>true,'readonly'=>true])->label('密码<font style=" font-size: 12px;color: #28866a">（不可修改）</font>');
                ?>
                    <div id="confirm_password" style="display: none">
                        <?= $form->field($model, 'confirm_password')->passwordInput(['value'=>''])->label('确认密码')?>
                    </div>
                <?php
                }
                ?>
<!--                --><?php
//                if($user->a_type<$model->a_type){
//                    echo $form->field($model->admingroup, 'item_name' )->dropDownList($item)->label('用户组');
//                }else{
//                    echo $form->field($model->admingroup, 'item_name' )->dropDownList($item,['disabled'=>true])->label('用户组<font style=" font-size: 12px;color: #28866a">（不可修改）</font>');
//                }
//                ?>
                <?php ActiveForm::end(); ?>
                    <button type="submit" id="manager_update" class="btn btn-primary">保存</button>
                    <button type="submit" id="" class="btn btn-default" onclick="javascript:history.go(-1);">返回</button>
            </div>
        </div>
    </div>

</div>
<?=Html::jsFile('@web/js/wine/wine.js?_'.time())?>
<?=Html::jsFile('@web/js/wine/manager.js?_'.time())?>
