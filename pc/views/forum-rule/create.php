<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CForumRule $model
 */

$this->title = '新建规则';
$this->params['breadcrumbs'][] = ['label' => 'Cforum Rules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cforum-rule-create">
   
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
