<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CBanner $model
 */

$this->title = 'Create Cbanner';
$this->params['breadcrumbs'][] = ['label' => 'Cbanners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cbanner-create">

    <?= $this->render('_form', [
        'model' => $model,
        'initialPreview' =>'',
        'initialPreviewConfig' =>[]
    ]) ?>

</div>
