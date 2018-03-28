<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CBanner $model
 */

$this->title = '修改广告图片: ' . ' ' . \admin\models\BannerSearch::getLocationName($model->b_location);
$this->params['breadcrumbs'][] = ['label' => 'Cbanners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->b_id, 'url' => ['view', 'id' => $model->b_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cbanner-update">
    <?= $this->render('_form', [
        'model' => $model,
        'initialPreview' =>$initialPreview,
        'initialPreviewConfig' =>$initialPreviewConfig
    ]) ?>

</div>
