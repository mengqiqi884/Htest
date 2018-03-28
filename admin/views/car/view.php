<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CCar $model
 */

$this->title = $model->c_code;
$this->params['breadcrumbs'][] = ['label' => 'Ccars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ccar-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>


    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'c_code',
            'c_title',
            'c_parent',
            'c_logo',
            'c_level',
            'c_type',
            'c_engine',
            'c_volume',
            'c_price',
            'c_sortorder',
        ],
        'deleteOptions'=>[
            'url'=>['delete', 'id' => $model->c_code],
            'data'=>[
                'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
                'method'=>'post',
            ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
