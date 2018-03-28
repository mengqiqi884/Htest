<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CForums $model
 */

$this->title = 'Create Cforums';
$this->params['breadcrumbs'][] = ['label' => 'Cforums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cforums-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
