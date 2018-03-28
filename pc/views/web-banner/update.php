<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CPcBanner $model
 */

$this->title = 'Update Cpc Banner: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cpc Banners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cpc-banner-update">
    <?= $this->render('_form', [
        'model' => $model,
        'initialPreview'=>$initialPreview,
    ]) ?>

</div>
