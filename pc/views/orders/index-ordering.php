<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\export\ExportMenu;
use \yii\bootstrap\Alert;
use yii\helpers\Url;
use yii\jui\AutoComplete;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\OrdersSearch $searchModel
 */

\admin\assets\WarningAsset::register($this);
\admin\assets\PrintAsset::register($this);   //打印

$this->title = '预约中';
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
 .btn-space{margin-left:5px!important;}
</style>

<?php
//弹出操作成功提示
if( Yii::$app->getSession()->hasFlash('success') ) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-success alert-dismissable', //这里是提示框的class
        ],
        'body' => Yii::$app->getSession()->getFlash('success'), //消息体
    ]);
}
//弹出操作失败提示
if( Yii::$app->getSession()->hasFlash('error') ) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-danger alert-dismissable',
        ],
        'body' => Yii::$app->getSession()->getFlash('error'),
    ]);
}
?>

<div class="row">
    <div class="col-sm-12">
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="icon pull-left"><i class="fa fa-reorder text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?=Html::encode($this->title)?></h5>
                </div>
                <div class="ibox-content">
                    <?php
                        //预约中 列表
                        echo GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel, //搜索
                            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                            'toggleData' => false,
                            'pjax' => true,  //pjax is set to always true for this demo
                            'pjaxSettings' => [
                                'options' => [
                                    'id' => 'ordering_pjax',
                                ],
                                'neverTimeout' => true,
                            ],
                            'options' => ['id' => 'printThis'],
                            'layout'=> '{items}<div class="text-right tooltip-demo">{pager}</div>',
                            'pager'=>[
                                //'options'=>['class'=>'hidden']//关闭分页
                                'firstPageLabel'=>"First",
                                'prevPageLabel'=>'Prev',
                                'nextPageLabel'=>'Next',
                                'lastPageLabel'=>'Last',
                            ],
                            'columns' => [
                                [
                                    'class' => 'yii\grid\CheckboxColumn'
                                ],
                                [
                                    'header' => '序号',
                                    'class' => 'yii\grid\SerialColumn',
                                    'options' => ['width' => '50px']
                                ],

                                [
                                    'label' => '预约序号',
                                    'attribute' => 'o_code',
                                ],

                                [
                                    'attribute' => 'o_user_name',
                                    'value' => function ($data) {
                                        if ($data->user) {
                                            return $data->user->u_phone;
                                        } else {
                                            return '';
                                        }
                                    },
//                                    'filterType'=>AutoComplete::className(),
//                                    'filterWidgetOptions' => [
//                                        'clientOptions' => [
//                                            'source' => \admin\models\CUser::GetAllUser('account'),
//                                        ],
//                                    ]
                                ],
                                [
                                    'attribute' => 'o_user_nickname',
                                    'headerOptions' => ['width' =>'100'],
                                    'value' => function ($data) {
                                        if ($data->user) {
                                            return $data->user->u_nickname;
                                        } else {
                                            return '';
                                        }
                                    },
//                                    'filterType'=>AutoComplete::className(),
//                                    'filterWidgetOptions' => [
//                                        'clientOptions' => [
//                                            'source' => \admin\models\CUser::GetAllUser('nickname'),
//                                        ],
//                                    ]
                                ],
                                [
                                    'label' => '申请时间',
                                    'attribute' => 'created_time',
                                    'value' => function ($data) {
                                        return $data->created_time ? $data->created_time : '';
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
                                    'value' => function ($data) {
                                        return \admin\models\CarSearch::getUserCar($data->o_usercar);
                                    },
                                    'filter' => false, //不显示搜索框
                                ],

                                [
                                    'attribute' => 'o_replacecar',
                                    'value' => function ($data) {
                                        return \admin\models\CarSearch::getUserCar($data->o_replacecar);
                                    },
                                    'filter' => false, //不显示搜索框
                                ],

                                [
                                    'attribute' => 'o_agency_name',
                                    'value' => function ($data) {
                                        if ($data->agent) {
                                            return $data->agent->a_name;
                                        } else {
                                            return '';
                                        }
                                    },
//                                    'filterType'=>AutoComplete::className(),
//                                    'filterWidgetOptions' => [
//                                        'clientOptions' => [
//                                            'source' => \admin\models\CAgent::GetAllAgencyName(),
//                                        ],
//                                    ]
                                ],

                                [
                                    'header' => '操作',
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{view} {create} {update}',
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
                                        },
                                        'update' => function ($url, $model) {
                                            return Html::a('<i class="fa fa-pencil-square-o"></i> 处理', 'javascript:void(0);', [
                                                'class' => 'btn btn-outline btn-xs btn-warning update',
                                                'id' => 'info',
                                                'data-toggle' => 'modal',
                                                'data-target' => '#info-modal',
                                                'data-url' => $url,
                                            ]);
                                        }

                                    ],
                                ],
                            ],
                            'toolbar' => [
                                ['content'=>
                                    Html::a('<i class="glyphicon glyphicon-edit"></i>批量处理', 'javascript:void(0);',[
                                        'class'=>'btn btn-outline btn-primary btn-space gridview ',
                                        'title' => ' 批量确定',
                                        'data-url' => \yii\helpers\Url::toRoute('ajax-update-all')
                                    ]) .
                                    Html::a('<i class="fa fa-refresh"></i>刷新', ['index-ordering'], ['class'=>'btn btn-outline btn-info btn-space', 'title'=>'刷新']) .' ' .
                                    Html::button('<i class="fa fa-print"></i>打印',['class'=> 'btn btn-outline btn-default btn-space','title'=>'打印','onclick'=> 'PrintPage()'])
                                ],
                                '{export}',
                            ],
                            'responsive' => true,
                            'hover' => true,
                            'condensed' => true,
                            'panel' => [
                                'heading' => false,
                                'after' =>'<div style="margin-top:8px">{summary}</div>',
                                'showFooter' => false
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

    function init() {

        /*备注*/
        $('.write').click(function () {
            showPage($(this));
        });
        /*更新预约状态*/
        $('.update').click(function () {
            showPage($(this));
        });

        function showPage(obj) {
            //$.fn.modal.Constructor.prototype.enforceFocus = function () { }; //防止select2无法输入
            $.get(obj.attr("data-url"), {},
                function (data) {
                    $(".modal-body").html(data);
                }
            );
        }

        /*批量确定*/
        $(".gridview").click(function () {
            var keys = $("#grid").yiiGridView("getSelectedRows");
            if (keys.length == 0) {
                swal("", "请选择需要处理的预约单", "error");
            } else {
                console.log(keys);

                $(".gridview").attr('data-id', 'info');
                $(".gridview").attr('data-toggle', 'modal');
                $(".gridview").attr('data-target', '#info-modal');

                var url = $(".gridview").attr('data-url');

                $.get(url + '?multi=' + keys, {},
                    function (data) {
                        $(".modal-body").html(data);
                    }
                );

            }
        });
    }
</script>
