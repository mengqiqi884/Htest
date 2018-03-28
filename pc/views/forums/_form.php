<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CForums $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="cforums-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'f_fup'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter F Fup...']],

            'f_user_nickname'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter F User Nickname...', 'maxlength'=>150]],

            'f_title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter F Title...', 'maxlength'=>200]],

            'f_user_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter F User ID...']],

            'f_views'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter F Views...']],

            'f_replies'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter F Replies...']],

            'f_is_top'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter F Is Top...']],

            'f_is_first_top'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter F Is First Top...']],

            'f_state'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter F State...']],

            'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Is Del...']],

            'f_content1'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter F Content1...','rows'=> 6]],

            'f_content2'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter F Content2...','rows'=> 6]],

            'created_time'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'updated_time'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'f_content_pic1'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter F Content Pic1...', 'maxlength'=>200]],

            'f_content_pic2'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter F Content Pic2...', 'maxlength'=>200]],

            'f_car_describle'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter F Car Describle...', 'maxlength'=>200]],

            'f_car_cycle'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter F Car Cycle...', 'maxlength'=>100]],

            'f_car_miles'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter F Car Miles...', 'maxlength'=>100]],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
