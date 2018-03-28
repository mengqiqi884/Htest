<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CScoreLog $model
 */

$this->title = '更新兑换记录: ' . ' ' . $model->sl_goodsname;
$this->params['breadcrumbs'][] = ['label' => 'Cscore Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sl_id, 'url' => ['view', 'id' => $model->sl_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cscore-log-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
