<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\COrders $model
 */

$this->title = 'Update Corders: ' . ' ' . $model->o_id;
$this->params['breadcrumbs'][] = ['label' => 'Corders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->o_id, 'url' => ['view', 'id' => $model->o_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="corders-update">

    <h3>操作</h3>

    <?= $this->render('_form', [
        'model' => $model,
        'multiids'=>$multiids
    ]) ?>

</div>
