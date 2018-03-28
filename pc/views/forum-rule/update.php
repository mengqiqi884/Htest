<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CForumRule $model
 */

$this->title = '编辑积分规则: ' . ' ' . $model->fr_item;
$this->params['breadcrumbs'][] = ['label' => 'Cforum Rules', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fr_id, 'url' => ['view', 'id' => $model->fr_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cforum-rule-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
