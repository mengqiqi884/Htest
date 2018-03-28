<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CForums $model
 */

$this->title = 'Update Cforums: ' . ' ' . $model->f_id;
$this->params['breadcrumbs'][] = ['label' => 'Cforums', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->f_id, 'url' => ['view', 'id' => $model->f_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cforums-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
