<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CPcBanner $model
 */

$this->title = 'Create Cpc Banner';
$this->params['breadcrumbs'][] = ['label' => 'Cpc Banners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cpc-banner-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
