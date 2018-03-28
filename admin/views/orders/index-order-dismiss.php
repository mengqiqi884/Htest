<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\export\ExportMenu;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\OrdersSearch $searchModel
 */

$this->title = '预约取消';
$this->params['breadcrumbs'][] = $this->title;
\kartik\select2\Select2Asset::register($this);

$get_data =Yii::$app->request->get('OrdersSearch');
$pro=empty($get_data['province']) ? '--省--':\admin\models\CCity::getSelectedCityName($get_data['province']);
?>


<style>
    .btn-space{margin-left:5px!important;}
</style>

<div class="row">
    <div class="col-sm-12">
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="icon pull-left"><i class="fa fa-reorder text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?=Html::encode($this->title)?></h5>
                </div>
                <div class="ibox-content">

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'export' =>false,
                        'pjax' => true,  //pjax is set to always true for this demo
                        'pjaxSettings' => [
                            'options' => [
                                'id' => 'order-dismiss_pjax',
                            ],
                            'neverTimeout' => true,
                        ],
                        'options'=>['id'=>'grid'],
                        'columns' =>[
                            [
                                'header' =>'序号',
                                'class' => 'yii\grid\SerialColumn',
                                'options' =>['width'=>'5%']
                            ],

                            'o_code',
                            [
                                'attribute' =>'o_user_name',
                                'value' =>function($data){
                                    if($data->user){
                                        return $data->user->u_phone;
                                    }else{
                                        return '';
                                    }
                                }
                            ],
                            [
                                'attribute' =>'o_user_nickname',
                                'value' =>function($data){
                                    if($data->user){
                                        return $data->user->u_nickname;
                                    }else{
                                        return '';
                                    }
                                }
                            ],

                            [
                                'attribute' =>'created_time',
                                'value' => function($data){
                                    return $data->created_time ? $data->created_time :'';
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

                            [
                                'attribute' => 'o_usercar',
                                'value' =>function($data){
                                    return \admin\models\CarSearch::getUserCar($data->o_usercar);
                                },
                                'filter' =>  false,
                            ],

                            [
                                'attribute' => 'o_replacecar',
                                'value' =>function($data){
                                    return \admin\models\CarSearch::getUserCar($data->o_replacecar);
                                },
                                'filter' =>  false,
                            ],

                            [
                                'attribute' =>'o_agency_name',
                                'value' =>function($data){
                                    if($data->agent){
                                        return $data->agent->a_name;
                                    }else{
                                        return '';
                                    }
                                }
                            ],

                            [
                                'header' => '操作',
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {create}',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-folder"></i> 查看', $url, [
                                            'class' => 'btn btn-outline btn-xs btn-success',
                                            'data-pjax'=>'0'  //不需要经过pjax直接跳转新页面，
                                        ]);
                                    },
                                    'create' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-exclamation"></i> 备注', 'javascript:void(0);', [
                                            'class' => 'btn btn-outline btn-xs btn-danger write',
                                            'id' => 'info',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#info-modal',
                                            'data-url' => \yii\helpers\Url::toRoute(['order-remark/index?oid=' . $model->o_id], true),
                                        ]);
                                    }
                                ],
                            ],
                        ],
                        'toolbar' => [
                            ['content' =>
                                Html::a('<i class="fa fa-refresh"></i>刷新', ['index-ordering'], ['class'=>'btn btn-outline btn-info btn-space', 'title'=>'刷新']) .' ' .
                                Html::button('<i class="fa fa-print"></i>打印',['class'=> 'btn btn-outline btn-default btn-space','title'=>'打印','onclick'=> 'print_Preview()'])
                            ]
                        ],
                        'responsive'=>true,
                        'hover'=>true,
                        'condensed'=>true,
                       // 'floatHeader'=>true,

                        'panel' => [
                            'heading' => false,
                            'after' =>'<div style="margin-top:8px">{summary}</div>',
                            'showFooter' => false
                        ],
                    ]);
                    //Pjax::end();
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
    function init(num,selected_province)
    {
        /*查看*/
        $('.look').click(function(){
            showPage($(this));
        });
        /*备注*/
        $('.write').click(function(){
            showPage($(this));
        });

        function showPage(obj){
            //$.fn.modal.Constructor.prototype.enforceFocus = function () { }; //防止select2无法输入
            $.get(obj.attr("data-url"),{},
                function(data){
                    $(".modal-body").html(data);
                }
            );
        }
    }

</script>
