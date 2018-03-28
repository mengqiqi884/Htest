<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CScoreList $model
 */

$this->title = 'Create Cscore List';
$this->params['breadcrumbs'][] = ['label' => 'Cscore Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cscore-list-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
