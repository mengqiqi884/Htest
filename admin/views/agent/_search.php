<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\form\ActiveForm;

/**
 * @var yii\web\View $this
 * @var admin\models\AgentSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<?=Html::cssFile('@web/css/car/style.css');?>
<?=Html::jsFile('@web/js/wine/selectdate.js');?>
<style>
    .field-LAY_demorange_s div{margin-left:55px;margin-top:-25px}
    .field-LAY_demorange_e div{margin-left: 20px;margin-top:-25px}
</style>

<div class="cagent-search" style="margin: 5px 10px ;">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' =>[
            'class'=>'form-inline'
        ]
    ]);

    ?>

    <?= $form->field($model, 'l_type')->dropDownList(['1'=>'用户名','2'=>'姓名','3'=>'手机号','4'=>'邮箱'])->label(false); ?>

    <?= $form->field($model, 'l_input')->textInput()->label(false); ?>

    <?= $form->field($model, 'a_state')->dropDownList(['1'=>'启用','2'=>'禁用'],['prompt' =>'不限'])->label(false); ?>

    <?=$form->field($model, 'created_time')->textInput(['id'=>'LAY_demorange_s','placeholder'=>'开始日']) ?>

    <?=$form->field($model, 'end_time')->textInput(['id'=>'LAY_demorange_e','placeholder'=>'截止日'])->label('至') ?>

    <?=Form::widget([
        'model' => $model,
        'form' => $form,
        'attributes' => [
            'a_brand'=>['type'=>Form::INPUT_WIDGET,'label'=>false,'widgetClass'=>\kartik\widgets\Select2::className(),
                'options'=>[
                    'data'=>\admin\models\CAgent::GetAllBrand(),
                    'options'=>['placeholder'=>'全部'],
                    'pluginOptions'=>['allowClear'=>true]
                    ],
            ],
        ]
    ])?>


    <div class="form-group" style="margin-top:0px; ">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?=Html::a('重置', ['index'], ['','class' => 'btn btn-default','style'=>'margin-left:10px'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
