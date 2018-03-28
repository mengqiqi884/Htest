<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CCarreplace $model
 */

$this->title = 'Create Ccarreplace';
$this->params['breadcrumbs'][] = ['label' => 'Ccarreplaces', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ccarreplace-create">
    <h3 style="text-align: center">发布</h3>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
