<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var admin\models\SensitiveSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="csensitive-search" style="margin:10px 15px">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' =>[
            'class' =>'form-inline'
        ]
    ]); ?>

    <?= $form->field($model, 's_name') ?>

    <div class="form-group" style="margin-top: -10px;">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?=Html::a('重置', ['index'], ['','class' => 'btn btn-default','style'=>'margin-left:10px'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
