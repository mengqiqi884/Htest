<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CGoods $model
 */

$this->title = '编辑商品: ' . ' ' . $model->g_name;
$this->params['breadcrumbs'][] = ['label' => 'Cgoods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->g_id, 'url' => ['view', 'id' => $model->g_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cgoods-update">

    <?= $this->render('_form', [
        'model' => $model,
        'initialPreview'=>$initialPreview,
        'initialPreviewConfig'=>$initialPreviewConfig
    ]) ?>

</div>
