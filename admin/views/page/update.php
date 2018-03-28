<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CPage $model
 */

$this->title = $model->p_remark;
$this->params['breadcrumbs'][] = ['label' => 'Cpages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->p_id, 'url' => ['view', 'id' => $model->p_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cpage-update">
    <?php
        switch($flag) {
            case 'user-agreement':  //用户协议
                echo $this->render('_form', [
                    'model' => $model,
                ]);
                break;
            case 'app-download':  //APP下载
                echo $this->render('_downloadform', [
                    'model' => $model,
                ]);
                break;
            case 'phone':
                echo $this->render('_phoneform', [
                    'model' => $model,
                ]);
                break;
        }
    ?>
</div>
