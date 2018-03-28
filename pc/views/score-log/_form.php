<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

/**
 * @var yii\web\View $this
 * @var admin\models\CScoreLog $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
    .select2-dropdown--below{
        z-index: 99999;
    }
    .help-block,.musted{color: #e51717}
    .col-md-2{width: 18%}
    .col-md-10{width: 82%}
</style>
<div class="cscore-log-form">
    <p>注：带<span class="musted">*</span>号的为必填项</p>
    <?php
    $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_HORIZONTAL,
        'options' => [
            'onsubmit' =>'return checkInput();'
        ]
    ]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'attributes' => [

            'sl_logistics'=>['type'=>Form::INPUT_WIDGET,'label' => '物流公司：*','widgetClass'=>\kartik\widgets\Select2::className(),
                'options'=>[
                    'data'=>\admin\models\CLogistics::GetAllLogistics(),
                    'options'=>['placeholder'=>'--请选择--'],
                    'pluginOptions'=>['allowClear'=>true]
                ],

            ],
        ]
    ]);

    echo $form->field($model,'sl_number')->textInput()->label('物流单号：*');

    echo $form->field($model,'sl_remarks')->textInput();

    ?>
    <div style="text-align: center">
        <?= Html::submitButton('确定', ['class' =>'btn btn-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<script>
    function checkInput(){
        var company=$('select#cscorelog-sl_logistics').val();
        var number=$('input#cscorelog-sl_number').val();

        if(company.length==0) {
            $('.field-cscorelog-sl_logistics .help-block').html('*请选择物流公司');
            return false;
        }

        if(number.length==0) {
            $('.field-cscorelog-sl_number .help-block').html('*请填写物流单号');
            return false;
        }
        return true;
    }
</script>