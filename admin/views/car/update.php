<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var admin\models\CCar $model
 */

$this->title = 'Update Ccar: ' . ' ' . $model->c_code;
$this->params['breadcrumbs'][] = ['label' => 'Ccars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->c_code, 'url' => ['view', 'id' => $model->c_code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ccar-update">
    <?php
    switch($level){
        case 1:  //品牌
            echo $this->render('_formbrand', [
                'model' => $model,
                'level' =>$level,
                'parent' =>$code,
                'initialPreview'=>$initialPreview,
                'initialPreviewConfig'=>$initialPreviewConfig
            ]);
            break;
        case 2: //车系
            echo $this->render('_formcarxi', [
                'model' => $model,
                'level' =>$level,
                'parent' =>$code,
                'initialPreview'=>$initialPreview,
                'initialPreviewConfig'=>$initialPreviewConfig,
                'initialPreview1'=>$initialPreview1,
                'initialPreviewConfig1'=>$initialPreviewConfig1,
                'initialPreview2'=>$initialPreview2,
                'initialPreviewConfig2'=>$initialPreviewConfig2,
            ]);
            break;
        case 3: //车型
            echo $this->render('_formcartype', [
                'model' => $model,
                'level' =>$level,
                'parent' =>$code,
//                'initialPreview'=>$initialPreview,
//                'initialPreviewConfig'=>$initialPreviewConfig
            ]);
            break;
        default:
            echo '页面异常';
            break;
    }
    ?>

</div>
