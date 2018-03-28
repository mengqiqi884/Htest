<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\COrderremarks $model
 */

$this->title = 'Update Corderremarks: ' . ' ' . $model->or_id;
$this->params['breadcrumbs'][] = ['label' => 'Corderremarks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->or_id, 'url' => ['view', 'id' => $model->or_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="corderremarks-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
