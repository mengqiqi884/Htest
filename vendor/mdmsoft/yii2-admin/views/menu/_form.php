<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mdm\admin\models\Menu;
use yii\helpers\Json;
use mdm\admin\AutocompleteAsset;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Menu */
/* @var $form yii\widgets\ActiveForm */
AutocompleteAsset::register($this);
$opts = Json::htmlEncode([
        'menus' => Menu::getMenuSource(),
        'routes' => Menu::getSavedRoutes(),
    ]);
$this->registerJs("var _opts = $opts;");
$this->registerJs($this->render('_script.js'));
?>

<div class="menu-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= Html::activeHiddenInput($model, 'mam_parentid', ['id' => 'parent_id']); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'mam_name')->textInput(['maxlength' => 128]) ?>

            <?= $form->field($model, 'parent_name')->textInput(['id' => 'parent_name']) ?>

            <?= $form->field($model, 'mam_route')->textInput(['id' => 'route']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'mam_order')->input('number') ?>

            <?= $form->field($model, 'mam_data')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class="form-group">
        <?=
        Html::submitButton($model->isNewRecord ? Yii::t('rbac-admin', 'Create') : Yii::t('rbac-admin', 'Update'), ['class' => $model->isNewRecord
                    ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-primary'])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>