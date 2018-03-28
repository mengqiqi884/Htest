<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CAgent $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="cagent-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'a_account'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter A Account...', 'maxlength'=>32]],

            'a_pwd'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter A Pwd...', 'maxlength'=>32]],

            'a_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter A Name...', 'maxlength'=>50]],

            'a_areacode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter A Areacode...', 'maxlength'=>200]],

            'a_brand'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter A Brand...', 'maxlength'=>100]],

            'a_state'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter A State...']],

            'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Is Del...']],

            'created_time'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'updated_time'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'a_address'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter A Address...', 'maxlength'=>200]],

            'a_concat'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter A Concat...', 'maxlength'=>100]],

            'a_email'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter A Email...', 'maxlength'=>100]],

            'a_phone'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter A Phone...', 'maxlength'=>11]],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
