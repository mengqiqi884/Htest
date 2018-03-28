<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CScoreList $model
 */

$this->title = 'Update Cscore List: ' . ' ' . $model->sl_id;
$this->params['breadcrumbs'][] = ['label' => 'Cscore Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sl_id, 'url' => ['view', 'id' => $model->sl_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cscore-list-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
