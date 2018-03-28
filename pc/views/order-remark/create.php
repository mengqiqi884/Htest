<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\COrderremarks $model
 */

$this->title = '新增备注';
$this->params['breadcrumbs'][] = ['label' => 'Corderremarks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="corderremarks-create">

    <?= $this->render('_form', [
        'model' => $model,
        'oid' =>$oid
    ]) ?>

</div>
