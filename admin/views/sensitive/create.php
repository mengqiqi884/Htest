<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CSensitive $model
 */

$this->title = '新增敏感词汇';
$this->params['breadcrumbs'][] = ['label' => 'Csensitives', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="csensitive-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
