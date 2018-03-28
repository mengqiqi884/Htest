<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var admin\models\AdminSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<?=Html::cssFile('@web/css/car/style.css');?>
<?=Html::jsFile('@web/js/wine/selectdate.js');?>

<div class="admin-search" style="margin: 5px 10px ">

    <?php $form = ActiveForm::begin([
        'action' => ['operator-index'],
        'method' => 'get',
        'options' => [
            'class' => 'form-inline'
        ]
    ]); ?>

    <?= $form->field($model, 'l_type')->dropDownList(['1'=>'用户名','2'=>'姓名','3'=>'手机号','4'=>'邮箱'])->label(false); ?>

    <?= $form->field($model, 'l_input')->textInput()->label(false); ?>

    <?= $form->field($model, 'a_state')->dropDownList(['1'=>'启用','2'=>'禁用'],['prompt' =>'不限'])->label(false); ?>

    <?=$form->field($model, 'created_time')->textInput(['id'=>'LAY_demorange_s','placeholder'=>'开始日']) ?>

    <?=$form->field($model, 'end_time')->textInput(['id'=>'LAY_demorange_e','placeholder'=>'截止日'])->label('至') ?>

    <div class="form-group" style="margin-top:-10px ">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?=Html::a('重置', ['operator-index'], ['','class' => 'btn btn-default','style'=>'margin-left:10px'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
