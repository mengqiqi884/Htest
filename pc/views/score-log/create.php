<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CScoreLog $model
 */

$this->title = '添加物流信息';
$this->params['breadcrumbs'][] = ['label' => 'Cscore Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cscore-log-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
