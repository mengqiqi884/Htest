<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var admin\models\UserSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<?=Html::jsFile('@web/js/plugins/layui/layui.js');?>
<?=Html::cssFile('@web/css/car/style.css');?>
<?=Html::jsFile('@web/js/wine/selectdate.js');?>
<style>
    .select2-dropdown--below{z-index: 99999;}
    .field-LAY_demorange_s label,.field-LAY_demorange_e label{padding-top:6px}
</style>
<div class="cuser-search" style="margin: 5px ;">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' =>[
            'class'=>'form-inline'
        ]
    ]); ?>

    <?= $form->field($model, 'l_type')->dropDownList(['1'=>'手机号','2'=>'昵称'])->label(false); ?>

    <?= $form->field($model, 'l_input')->textInput()->label(false); ?>

    <?=$form->field($model, 'created_time')->textInput(['id'=>'LAY_demorange_s','placeholder'=>'开始日']) ?>

    <?=$form->field($model, 'end_time')->textInput(['id'=>'LAY_demorange_e','placeholder'=>'截止日'])->label('至') ?>

    <?= $form->field($model, 'u_sex')->dropDownList(['1'=>'男','2'=>'女'],['prompt' =>'全部'])->label(false); ?>

    <div class="form-group" style="margin-top:-10px; ">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?=Html::a('重置', ['index'], ['','class' => 'btn btn-default','style'=>'margin-left:10px'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
