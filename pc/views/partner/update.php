<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CPartner $model
 */

$this->title = '编辑合作伙伴: ' . ' ' . $model->p_id;
$this->params['breadcrumbs'][] = ['label' => 'Cpartners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->p_id, 'url' => ['view', 'id' => $model->p_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cpartner-update">
    <?= $this->render('_form', [
        'model' => $model,
        'initialPreview' =>$initialPreview,
        'initialPreview2'=>$initialPreview2
    ]) ?>

</div>
