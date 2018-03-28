<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var admin\models\ProductsSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="cproducts-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'p_id') ?>

    <?= $form->field($model, 'p_name') ?>

    <?= $form->field($model, 'p_month12') ?>

    <?= $form->field($model, 'p_month24') ?>

    <?= $form->field($model, 'p_month36') ?>

    <?php // echo $form->field($model, 'p_content') ?>

    <?php // echo $form->field($model, 'p_sortorder') ?>

    <?php // echo $form->field($model, 'is_all') ?>

    <?php // echo $form->field($model, 'created_time') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
