<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\COrders $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="corders-form" style="margin:0 auto;text-align: center">

    <?php
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'o_state'=>[
                'type'=> Form::INPUT_DROPDOWN_LIST,
                'items'=>[1=>'预约完成', 2=>'预约取消'],
                'options'=>['style'=>'width:30%'],
                'label'=>false
            ],

            'o_remark'=>[
                'type'=> Form::INPUT_TEXTAREA,
                'options'=>['placeholder'=>'请填写备注内容', 'maxlength'=>200,'rows'=>6],
                'label'=>false
            ],

            'o_multi'=>[
                'type'=> Form::INPUT_HIDDEN,
                'options'=>['value'=>$multiids],
                'label'=>false
            ]

        ]
    ]);

    ?>
    <div style="text-align: center">
        <?php echo Html::submitButton(Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
