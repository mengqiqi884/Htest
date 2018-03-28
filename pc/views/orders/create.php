<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\COrders $model
 */

$this->title = 'Create Corders';
$this->params['breadcrumbs'][] = ['label' => 'Corders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="corders-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
