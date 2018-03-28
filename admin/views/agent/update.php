<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CAgent $model
 */

$this->title = '编辑4S店: ' . ' ' . $model->a_name;
$this->params['breadcrumbs'][] = ['label' => 'Cagents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->a_id, 'url' => ['view', 'id' => $model->a_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cagent-update">

    <?php
    switch($flag) {
        case 'reset-pwd': //重置密码
              echo  $this->render('_formpwd', [
                  'model' => $model,
              ]);
            break;
        case 'modify-agent': //编辑4S店基本信息
              echo  $this->render('_formagent', [
                  'model' => $model,
              ]);
            break;
    }
   ?>

</div>
