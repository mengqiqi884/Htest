<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var mdm\admin\models\searchs\Menu $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="menu-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'mam_id') ?>

    <?= $form->field($model, 'mam_name') ?>

    <?= $form->field($model, 'mam_parentid') ?>

    <?= $form->field($model, 'mam_route') ?>

    <?= $form->field($model, 'mam_data') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('rbac-admin', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('rbac-admin', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
