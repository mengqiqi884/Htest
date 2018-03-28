<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CCarreplace $model
 */

$this->title = 'Update Ccarreplace: ' . ' ' . $model->r_id;
$this->params['breadcrumbs'][] = ['label' => 'Ccarreplaces', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->r_id, 'url' => ['view', 'id' => $model->r_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ccarreplace-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
