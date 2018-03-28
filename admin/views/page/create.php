<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CPage $model
 */

$this->title = 'Create Cpage';
$this->params['breadcrumbs'][] = ['label' => 'Cpages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cpage-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
