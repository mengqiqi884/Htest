<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use mdm\admin\components\MenuHelper;

$this->title = '鲜橙置换';
$url=Yii::$app->params['base_url'].Yii::$app->params['base_file'];
?>

<div>
    <?=$model->p_content?>
</div>