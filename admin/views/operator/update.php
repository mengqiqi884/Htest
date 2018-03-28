<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\Admin $model
 */

//$this->title = 'Update Admin: ' . ' ' . $model->a_id;
//$this->params['breadcrumbs'][] = ['label' => 'Admins', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->a_id, 'url' => ['view', 'id' => $model->a_id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="admin-update">

    <?php
    switch($flag) {
        case 'reset-pwd': //重置运营人员密码
            echo  $this->render('_formpwd', [
                'model' => $model,
            ]);
            break;
        case 'modify-operator': //编辑运营人员基本信息
            echo  $this->render('_formoperator', [
                'model' => $model,
                'routes' => $routes,
            ]);
            break;
        case 'modify-role' : //编辑角色基本信息
            echo  $this->render('_formrole', [
                'model' => $model,
            ]);
            break;
    }
   ?>

</div>
