<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/**
 * @var yii\web\View $this
 * @var admin\models\CarreplaceSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div id="search">
    <?=Html::jsFile('@web/js/plugins/layui/layui.js')?>
    <?=Html::cssFile('@web/css/car/style.css');?>
    <?=Html::jsFile('@web/js/wine/selectdate.js');?>
    <style>
        .field-carreplacesearch-created_time,.field-carreplacesearch-end_time {margin-top: -10px}
    </style>

    <div class="ccarreplace-search" style="margin:10px 15px">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options'=>[
                'class'=>'form-inline',
                // 'onsubmit' =>'return checkInput();'
            ]
        ]); ?>

        <?= $form->field($model, 'user_name')->label('名称') ?>

        <?=$form->field($model, 'created_time')->textInput(['id'=>'LAY_demorange_s','placeholder'=>'开始日']) ?>

        <?=$form->field($model, 'end_time')->textInput(['id'=>'LAY_demorange_e','placeholder'=>'截止日'])->label('至') ?>

        <div class="form-group" style="margin-top: -10px;">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            <?=Html::a('重置', ['index'], ['','class' => 'btn btn-default','style'=>'margin-left:10px'])?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
