<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CProducts $model
 */

$this->title = '新建金融产品';
$this->params['breadcrumbs'][] = ['label' => 'Cproducts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cproducts-create">

    <?= $this->render('_form', [
        'model' => $model,
        'initialPreview'=>$initialPreview,
        'initialPreviewConfig'=>$initialPreviewConfig
    ]) ?>

</div>
