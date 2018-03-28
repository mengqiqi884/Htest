<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\ScoreLogSearch $searchModel
 */

$this->title = '兑换记录列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
//弹出操作成功提示
if( Yii::$app->getSession()->hasFlash('success') ) {
    echo \yii\bootstrap\Alert::widget([
        'options' => [
            'class' => 'alert-success alert-dismissable', //这里是提示框的class
        ],
        'body' => Yii::$app->getSession()->getFlash('success'), //消息体
    ]);
}
//弹出操作失败提示
if( Yii::$app->getSession()->hasFlash('error') ) {
    echo \yii\bootstrap\Alert::widget([
        'options' => [
            'class' => 'alert-danger alert-dismissable',
        ],
        'body' => Yii::$app->getSession()->getFlash('error'),
    ]);
}
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="icon pull-left"><i class="fa fa-list-ol text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?=Html::encode($this->title)?></h5>
                </div>
                <div class="ibox-content">
                    <?php
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'export' =>false,
                        'toggleData' => false,
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
                                'attribute' => 'sl_id',
                                'mergeHeader'=>true,
                                'filter' => false
                            ],
                            [
                                'attribute' =>'user_name',
                                'value' =>function($data){
                                    if($data->user){
                                        return $data->user->u_nickname . '【' .$data->user->u_phone . '】';
                                    }else{
                                        return '';
                                    }
                                },
                                'filterType'=>\yii\jui\AutoComplete::className(),
                                'filterWidgetOptions' => [
                                    'clientOptions' => [
                                        'source' => \admin\models\CUser::GetAllUser('nickname'),
                                    ],
                                ]
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
                                },
                                'filterType'=>GridView::FILTER_SELECT2,
                                'filter' => ['1'=>'已发货','0'=>'待发货'],
                                'filterInputOptions'=>['placeholder'=>'请选择'],
                            ],
                            'sl_goodsname',
                            [
                                'attribute' => 'sl_score',
                                'filter' => false,
                                'value' => function($data){
                                    return $data->sl_score . ' 积分';
                                }
                            ],
                            [
                                'attribute' => 'created_time',
                                'value' =>function($data){
                                    return empty($data->created_time) ? '':$data->created_time;
                                },
                                'filterType' => GridView::FILTER_DATE_RANGE,
                                'filterWidgetOptions' => [
                                    'language' => 'zh-CN',
                                    'value' => '',
                                    'convertFormat' => true,
                                    'pluginOptions' => [
                                        'locale' => [
                                            'format' => 'Y-m-d',
                                            'separator' => ' to ',
                                        ],
                                        'opens' => 'left'
                                    ],
                                ]
                            ],
                            [   //详细扩展
                                'class' => 'kartik\grid\ExpandRowColumn',
                                'width' => '80px',
                                'value' => function($data,$key,$index,$column){
                                    return GridView::ROW_COLLAPSED;
                                },
                                'detail' => function($data,$key,$index,$column){
                                    return Yii::$app->controller->renderPartial('/score-log/view',['model'=> $data]);
                                },
                                'detailAnimationDuration' => 100,
                                'expandIcon'=>'<span class="fa fa-angle-double-down"></span>',
                                'collapseIcon'=>'<span class="fa fa-angle-double-up"></span>',
                                'headerOptions' => ['class'=>'kartik-sheet-style'],
                            ],
                        ],
//                        'toolbar' => [
//                            ['content' => Html::a('<i class="fa fa-refresh"></i> 刷新', ['index'], ['class' => 'btn btn-outline btn-default', 'title' => '重置'])
//                            ],
//                        ],
                        'responsive'=>true,
                        'hover'=>true,
                        'condensed'=>true,
                        //'floatHeader'=>true,

                        'panel' => [
                            'heading'=> false,
                            'before' => false,
                            'after'=>'<div style="margin-left:20px;">{summary} </div>',
                            'showFooter'=>false
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
//更新物流
Modal::begin([
    'id' => 'info-modal', //与上面的data-target值保持一致
]);
Modal::end();
?>

<script>
    $(function () {
        $(document).ready(init());
        $(document).on('pjax:complete', function () {
            init();
        });
    });

    function init()
    {
        $('.send').click(function(){
            $.fn.modal.Constructor.prototype.enforceFocus = function () { }; //防止select2无法输入
            $.get($(this).attr("data-url"),{},
                function(data){
                    $(".modal-body").html(data);
                }
            );
        });

        $('.look').click(function(){
            $.get($(this).attr("data-url"),{},
                function(data){
                    $(".modal-body").html(data);
                }
            );
        });
    }
</script>