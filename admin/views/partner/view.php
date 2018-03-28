<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CPartner $model
 */

$this->title = $model->p_id;
$this->params['breadcrumbs'][] = ['label' => 'Cpartners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cpartner-view">
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
            'p_id',
            'p_logo',
            'p_is_show',
            'p_url:url',
            'is_del',
        ],
        'deleteOptions'=>[
            'url'=>['delete', 'id' => $model->p_id],
            'data'=>[
                'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
                'method'=>'post',
            ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
