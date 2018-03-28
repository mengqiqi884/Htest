<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var v1\models\CUser $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="cuser-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'u_type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter U Type...']],

            'u_age'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter U Age...']],

            'u_score'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter U Score...']],

            'u_cars'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter U Cars...']],

            'u_forums'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter U Forums...']],

            'u_state'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter U State...']],

            'is_del'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Is Del...']],

            'u_pwd'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter U Pwd...', 'maxlength'=>32]],

            'created_time'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'updated_time'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],

            'u_phone'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter U Phone...', 'maxlength'=>32]],

            'u_token'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter U Token...', 'maxlength'=>32]],

            'u_register_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter U Register ID...', 'maxlength'=>32]],

            'u_headImg'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter U Head Img...', 'maxlength'=>200]],

            'u_nickname'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter U Nickname...', 'maxlength'=>200]],

            'u_sex'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter U Sex...', 'maxlength'=>2]],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
