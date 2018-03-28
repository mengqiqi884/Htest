<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CGoods $model
 */

$this->title = '新增商品';
$this->params['breadcrumbs'][] = ['label' => 'Cgoods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cgoods-create">

    <?= $this->render('_form', [
        'model' => $model,
        'initialPreview'=>$initialPreview,
        'initialPreviewConfig'=>$initialPreviewConfig
    ]) ?>

</div>
