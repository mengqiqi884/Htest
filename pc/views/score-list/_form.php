<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CScoreList $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="cscore-list-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'sl_user_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Sl User ID...']],

            'sl_rule'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Sl Rule...', 'maxlength'=>200]],

            'sl_score'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Sl Score...']],

            'created_time'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'sl_act'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Sl Act...', 'maxlength'=>10]],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
