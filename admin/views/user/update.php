<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var v1\models\CUser $model
 */

$this->title = 'Update Cuser: ' . ' ' . $model->u_id;
$this->params['breadcrumbs'][] = ['label' => 'Cusers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->u_id, 'url' => ['view', 'id' => $model->u_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cuser-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
