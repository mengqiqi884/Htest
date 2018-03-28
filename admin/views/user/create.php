<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var v1\models\CUser $model
 */

$this->title = 'Create Cuser';
$this->params['breadcrumbs'][] = ['label' => 'Cusers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cuser-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
