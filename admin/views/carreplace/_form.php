<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use admin\models\CarSearch;

/**
 * @var yii\web\View $this
 * @var admin\models\CCarreplace $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<?=Html::jsFile('@web/js/wine/wine.js')?>
<style>
    .select2-container--open{z-index: 9999;}
    .help-block{color: #e51717}
</style>
<div class="ccarreplace-form">

    <?php
    $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_HORIZONTAL,
        'options' =>[
            'onSubmit'=>'return checkSelect();'
        ]
    ]);
    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'r_car_id'=>[
                'type'=>Form::INPUT_WIDGET,'widgetClass'=>\kartik\widgets\Select2::className(),
                'options'=>[
                    'data'=>CarSearch::GetSonTree($model->r_brand),
                    'options'=>['placeholder'=>'--请选择--','style'=>'width:30%'],
                    'pluginOptions'=>['allowClear'=>true]
                ],
            ],

            'r_volume_id'=>['type'=>Form::INPUT_WIDGET,'widgetClass'=>\kartik\widgets\DepDrop::className(),
                'options'=>[
                    'type' => \kartik\widgets\DepDrop::TYPE_SELECT2,
                    'data'=>$model->r_car_id===''?[]:CarSearch::GetSonTree($model->r_car_id),
                    'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                    'pluginOptions'=>[
                        'placeholder'=>'--请选择--',
                        'depends'=>['ccarreplace-r_car_id'], //上一select的id值
                        'url' =>\yii\helpers\Url::toRoute(['carreplace/child-car']),
                        'loadingText' => '查找...',
                    ]
                ]
            ],

            'r_brand'=>['type'=> Form::INPUT_HIDDEN,'label'=>false],
        ]

    ]);

    echo '<div style="text-align: center">';
    echo Html::submitButton('发布', ['class' =>'btn btn-success']);
    echo '</div>';
    ActiveForm::end(); ?>

</div>

<script>
    function checkSelect(){
        var car_id=$('select#ccarreplace-r_car_id').find('option:selected').val();
        var volume_id=$('select#ccarreplace-r_volume_id').find('option:selected').val();

        if(!checkValue(car_id)) {
            $('.field-ccarreplace-r_car_id .help-block').html('请选择车系');
            return false;
        }

        if(!checkValue(volume_id)) {
            $('.field-ccarreplace-r_volume_id .help-block').html('请选择车型');
            return false;
        }

        return true;
    }
</script>