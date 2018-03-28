<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CMessage $model
 */

$this->title = '新增消息';
$this->params['breadcrumbs'][] = ['label' => 'Cmessages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    ul#ui-id-1{z-index: 9999;}
</style>
<div class="cmessage-create" >

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
