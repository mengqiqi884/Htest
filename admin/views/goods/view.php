<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CGoods $model
 */

//$this->title = '兑换记录';
$this->params['breadcrumbs'][] = ['label' => 'Cgoods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cgoods-view">
    <?php
    // Pjax::begin();
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'export' =>false,

        'pjax' => true,  //pjax is set to always true for this demo
        'pjaxSettings' => [
            'options' => [
                'id' => 'score-log_pjax',
            ],
            'neverTimeout' => true,
        ],
        'columns' => [
            [
                'header'=>'序号',
                'attribute' => 'sl_id'
            ],
            [
                'attribute' =>'user_name',
                'value' =>function($data){
                    if($data->user){
                        return $data->user->u_nickname . '(' . $data->user->u_phone . ')';
                    }else{
                        return '';
                    }
                }
            ],
            [
                'attribute' =>'sl_state',
                'format' =>'html',
                'value' =>function($data){
                    switch($data->sl_state){
                        case 0: $str='<span style="color:#ed5565">待发货</span>';break;
                        case 1: $str='<span style="color:#803f1e">已发货</span>';break;
                        default: $str='状态异常';break;
                    }
                    return $str;
                }
            ],
            'sl_goodsname',
            'sl_score',
            [
                'attribute' => 'created_time',
                'value' =>function($data){
                    return empty($data->created_time) ? '':$data->created_time;
                }
            ],
            'sl_receivename',
            'sl_receivephone',
            'sl_receiveaddress',
            [
                'attribute' => 'sl_operater',
                'value' =>function($data){
                    return empty($data->sl_operater) ? '':$data->sl_operater;
                }
            ],

        ],
        'toolbar' => [],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'panel' => [
            'heading'=>'<div>兑换记录</div>',
            'before' => false,
            'type'=>'info',
            'after'=>false,
            'showFooter'=>false
        ],
    ]);
    ?>

</div>
