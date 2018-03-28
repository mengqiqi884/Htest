<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var admin\models\BannerSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="cbanner-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'b_id') ?>

    <?= $form->field($model, 'b_location') ?>

    <?= $form->field($model, 'b_img') ?>

    <?= $form->field($model, 'b_url') ?>

    <?= $form->field($model, 'b_title') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'b_sortorder') ?>

    <?php // echo $form->field($model, 'created_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
