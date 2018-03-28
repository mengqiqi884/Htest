<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var admin\models\ForumsSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<?=Html::cssFile('@web/css/car/style.css');?>
<?=Html::jsFile('@web/js/wine/selectdate.js');?>

<div class="cforums-search" style="margin:10px 15px">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' =>[
            'class' =>'form-inline'
        ]
    ]); ?>

    <?= $form->field($model, 'l_type1')->dropDownList(['1'=>'标题','2'=>'浏览量'])->label(false); ?>

    <?= $form->field($model, 'l_input')->textInput()->label(false); ?>

    <?=$form->field($model, 'created_time')->textInput(['id'=>'LAY_demorange_s','placeholder'=>'开始日']) ?>

    <?=$form->field($model, 'end_time')->textInput(['id'=>'LAY_demorange_e','placeholder'=>'截止日'])->label('至') ?>

    <?=$form->field($model, 'f_is_top')->dropDownList(['1'=>'置顶','0' =>'非置顶'],['prompt'=>'全部']) ?>

    <?= $form->field($model, 'f_state')->dropDownList(['1'=>'禁用','-1' =>'非禁用'],['prompt'=>'全部']) ?>

    <?= $form->field($model, 'f_fup')->dropDownList(\admin\models\CForumForum::GetForumsName(),['prompt'=>'全部']) ?>

    <div class="form-group" style="margin-top: -10px;">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?=Html::a('重置', ['index'], ['','class' => 'btn btn-default','style'=>'margin-left:10px'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
