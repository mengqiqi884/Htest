<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\Admin $model
 */

$this->title = 'Create Admin';
$this->params['breadcrumbs'][] = ['label' => 'Admins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-create">

    <?php
    switch($flag) {
        case 'reset-pwd': //重置运营人员密码
            echo  $this->render('_formpwd', [
                'model' => $model,
            ]);
            break;
        case 'create-operator': //新增运营人员基本信息
            echo  $this->render('_formoperator', [
                'model' => $model,
                'routes' => $routes,
            ]);
            break;
        case 'create-role' : //新增角色基本信息
            echo  $this->render('_formrole', [
                'model' => $model,
            ]);
            break;
    }
    ?>

</div>
