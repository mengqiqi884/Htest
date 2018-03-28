<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use \kartik\widgets\Select2;
use \admin\models\CLogistics;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var admin\models\CScoreLog $model
 */

//$this->title = empty($model->user)?'':$model->user->u_phone.'--'.$model->sl_goodsname;
$this->params['breadcrumbs'][] = ['label' => 'Cscore Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
    if (empty($model->sl_logistics) && empty($model->sl_number) && empty($model->sl_remarks)) {  //物流信息为空
        $attribute = [
            [
                'attribute' => 'sl_receivename',
                'displayOnly' => true,
            ],
            [
                'attribute' => 'sl_receivephone',
                'displayOnly' => true
            ],
            [
                'attribute' => 'sl_receiveaddress',
                'displayOnly' => true
            ],
            [
                'attribute' => 'sl_logistics',
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => CLogistics::GetAllLogistics(),
                    'options' => ['placeholder' => '--请选择--'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
            ],
            'sl_number',
            'sl_remarks',
        ];
    } else {
        $attribute = [
            [
                'attribute' => 'sl_receivename',
                'displayOnly' => true,
            ],
            [
                'attribute' => 'sl_receivephone',
                'displayOnly' => true
            ],
            [
                'attribute' => 'sl_receiveaddress',
                'displayOnly' => true
            ],
            [
                'attribute' => 'sl_logistics',
                'displayOnly' => true
            ],
            'sl_number',
            'sl_remarks',
        ];
    }
?>

<?= DetailView::widget(
        [
            'model' => $model,
            'condensed' => false,
            'hover' => true,
            'responsive' => false,
            'mode' => Yii::$app->request->get('edit') == 't' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel' => [
                'heading' => '查看物流信息',
                'type' => DetailView::TYPE_INFO,
            ],
            'formOptions' => [
                'action' => Url::toRoute(['score-log/ajax-operator?id='.$model->sl_id])
            ], //选中操作的表单提交
            'attributes' => $attribute,
            'enableEditMode' => true,
            'deleteOptions'=>[ // 删除链接
                'url' => Url::toRoute(['score-log/delete?id='.$model->sl_id]),
//                'style'=>'display:none'
            ],

        ])
?>
