<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CPartner $model
 */

$this->title = '新增合作伙伴';
$this->params['breadcrumbs'][] = ['label' => 'Cpartners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cpartner-create">

    <?= $this->render('_form', [
        'model' => $model,
        'initialPreview' =>'',
        'initialPreview2' =>''
    ]) ?>

</div>
