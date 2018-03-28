<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CSensitive $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
    .help-block{color: #e51717}
</style>
<div class="csensitive-form">

    <?php $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_HORIZONTAL,
        //验证场景规则
        'enableAjaxValidation' => true,
        'validationUrl'=>\yii\helpers\Url::toRoute(['valid-form','id'=>empty($model['s_id'])?0:$model['s_id']]),
    ]);
    ?>

    <?= $form->field($model,'s_name')->textInput(['placeholder'=>'输入敏感词汇...','maxlength'=>'100'])?>

    <div style="text-align: center">
    <?php
    echo Html::submitButton('确定', ['class' =>'btn btn-primary']);
    ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
、