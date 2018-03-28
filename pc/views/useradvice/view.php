<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CUseradvice $model
 */

$this->title = empty($model->user)?'反馈意见':$model->user->u_nickname;
$this->params['breadcrumbs'][] = ['label' => 'Cuseradvices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cuseradvice-view">
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
                'content'
            ],
        'enableEditMode'=>false,
    ]) ?>

</div>
