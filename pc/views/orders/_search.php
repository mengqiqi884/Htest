<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
/**
 * @var yii\web\View $this
 * @var admin\models\OrdersSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<?=Html::cssFile('@web/css/car/style.css');?>
<?=Html::jsFile('@web/js/wine/selectdate.js');?>
<style>
    .corders-index input#orderssearch-l_input{margin-left:-4px}
    .select2-dropdown--below{z-index: 99999;}
    .corders-index .field-orderssearch-province{width: 150px;height:40px;position: relative;}
    .corders-search .field-orderssearch-province label {padding-top:8px!important;}
    .corders-index .field-orderssearch-province div{margin-left:60px;margin-top:-28px}

    .field-LAY_demorange_s div{margin-left:55px;margin-top:-25px}
    .field-LAY_demorange_e div{margin-left: 20px;margin-top: -25px}
    .corders-search #w1 .row:last-child{float:left;position:relative; top:-40px;left:190px;}
    .corders-search .field-orderssearch-city{width: 120%}
    .corders-search .field-orderssearch-city div{margin-top:-2px;}
</style>
<div class="corders-search" style="margin:10px 15px;height: 50px;width: 115%">

    <?php $form = ActiveForm::begin([
        'action' => [$type=='ordering'?'index-ordering':($type=='ordered'?'index-ordered':($type=='order-dismiss'?'index-order-dismiss':'index'))],
        'method' => 'get',
        'options' =>[
            'class'=>'form-inline'
        ]
    ]); ?>

    <?= $form->field($model, 'l_type')->dropDownList(['1'=>'用户名','2'=>'昵称','3'=>'4s店名称','4'=>'预约序号'])->label(false); ?>

    <?= $form->field($model, 'l_input')->textInput()->label(false); ?>

    <?=$form->field($model, 'created_time')->textInput(['id'=>'LAY_demorange_s','placeholder'=>'开始日']) ?>

    <?=$form->field($model, 'end_time')->textInput(['id'=>'LAY_demorange_e','placeholder'=>'截止日'])->label('至') ?>


    <?php
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'attributes' => [
                'province'=>['type'=>Form::INPUT_WIDGET,'label'=>'上牌城市','widgetClass'=>\kartik\widgets\Select2::className(),
                    'options'=>[
                        'data' =>[],
//                        'data'=>\admin\models\CCity::GetAllProvince(),
                        'options'=>['placeholder'=>!empty($model->province) ? \admin\models\CCity::getSelectedCityName($model->province):'--省--'],
//                        'pluginOptions'=>['allowClear'=>true,'width'=>'90%']
                    ],
                ],

                'city'=>['type'=>Form::INPUT_WIDGET,'label'=>false,
                    'widgetClass'=>\kartik\widgets\Select2::className(),
                    'options'=>[
                        'data' =>[],
                        //'options' =>['placeholder' =>'--市--'],
                       // 'data' =>!empty($model->province) ? [\admin\models\CCity::getSelectedCityName($model->city)]:[],
//                        'data'=>\admin\models\CCity::GetAllProvince(),
                        'options'=>['placeholder'=>!empty($model->province) ? (!empty($model->city)?\admin\models\CCity::getSelectedCityName($model->city):'--市--'):'--市--'],
                        'pluginOptions'=>['width'=>'90%']
                    ],
                ],

            ]
        ]);
    ?>

    <div class="form-group" style="margin-left:12%">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?=Html::a('重置', [$type=='ordering'?'index-ordering':($type=='ordered'?'index-ordered':($type=='order-dismiss'?'index-order-dismiss':'index'))], ['','class' => 'btn btn-default','style'=>'margin-left:10px'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
