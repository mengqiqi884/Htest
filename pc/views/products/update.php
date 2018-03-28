<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CProducts $model
 */

$this->title = '更新金融产品: ' . ' ' . $model->p_name;
$this->params['breadcrumbs'][] = ['label' => 'Cproducts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->p_id, 'url' => ['view', 'id' => $model->p_id]];
$this->params['breadcrumbs'][] = 'Update';
?>



<div class="cproducts-update">

    <?= $this->render('_form', [
        'model' => $model,
        'initialPreview' =>$initialPreview,
        'initialPreviewConfig' =>$initialPreviewConfig
    ]) ?>

</div>
