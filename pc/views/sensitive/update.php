<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CSensitive $model
 */

$this->title = '修改敏感词汇' . ' ' . $model->s_name;
$this->params['breadcrumbs'][] = ['label' => 'Csensitives', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->s_id, 'url' => ['view', 'id' => $model->s_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="csensitive-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
