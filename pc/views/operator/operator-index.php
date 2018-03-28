<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\AdminSearch $searchModel
 */
\admin\assets\WarningAsset::register($this);

$this->title = '运营人员列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
//弹出操作成功提示
if (Yii::$app->getSession()->hasFlash('success')) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-success alert-dismissable', //这里是提示框的class
        ],
        'body' => Yii::$app->getSession()->getFlash('success'), //消息体
    ]);
}
//弹出操作失败提示
if (Yii::$app->getSession()->hasFlash('error')) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-danger  alert-dismissable',
        ],
        'body' => Yii::$app->getSession()->getFlash('error'),
    ]);
};
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="icon pull-left"><i class="fa fa-fire text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?=Html::encode($this->title)?></h5>
                </div>
                <div class="ibox-content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'export' => false ,
                        'toggleData' => false,
                        'pjax' => true,  //pjax is set to always true for this demo
                        'pjaxSettings' => [
                            'options' => [
                                'id' => 'operator_pjax',
                            ],
                            'neverTimeout' => true,
                        ],
                        'columns' => [
                            [
                                'header' => '序号',
                                'class' => 'yii\grid\SerialColumn'
                            ],
                            [
                                'attribute' => 'a_name',
                                'width' => '10%'
                            ],
                            [
                                'attribute' => 'a_realname',
                                'width' => '10%'
                            ],

                            [
                                'attribute' =>'a_role',
                                'value' =>function($data){
                                    return \admin\models\Admin::getUserRoleName($data->a_role);
                                },
                                'filter' => false
                            ],
                            [
                                'attribute' =>'a_position',
                                'value' =>function($data){
                                    return $data->a_position ? $data->a_position : '';
                                },
                                'filter' => false
                            ],
                            [
                                'attribute' =>'a_phone',
                                'width' => '8%',
                                'value' =>function($data){
                                    return $data->a_phone ? $data->a_phone:'';
                                }
                            ],
                            [
                                'attribute' => 'a_email',
                                'format' => 'email',
                                'width' => '8%'
                            ],

                            [
                                'attribute' => 'created_time',
                                'width' => '25%',
                                'value' =>function($data){
                                    return $data->created_time ? $data->created_time:'';
                                },
                                'filterType' => GridView::FILTER_DATE_RANGE,
                                'filterWidgetOptions' => [
                                    'language' => 'zh-CN',
                                    'value' => '',
                                    'presetDropdown'=>true, //下拉选择“今日/昨日/最近7天/当月/上月 ，在vendor/kartik-v/yii2-date-range/DateRangePicker.php下修改”
                                    'hideInput'=>true,
                                    'convertFormat' => true,
                                    'pluginOptions' => [
                                        'locale' => [
                                            'format' => 'Y-m-d',
                                            'separator' => ' to ',
                                        ],
                                        'opens' => 'left'
                                    ],
                                ],
                            ],
                            [
                                'attribute' => 'a_state',
                                'class'=>'kartik\grid\EditableColumn',
                                'editableOptions'=>[
                                    'asPopover' => true,
                                    'inputType'=>\kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                                    'data' => ['1' => '启用','2'=>'禁用'],
                                    'options' => ['class' => 'form-control'],
                                    'displayValueConfig' => [
                                        '1' => '<label class="text-navy"><i class="fa fa-check"></i> 启用</label>',
                                        '2' => '<label class="text-danger"><i class="fa fa-times"></i> 禁用</label>'
                                    ]
                                ],
                                'filter' => false
                            ],

                            [
                                'header' => '重置密码',
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{reset}',
                                'buttons' => [
                                    'reset' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-lightbulb-o"></i> 重置', 'javascript.void(0);', [
                                            'class' => 'btn btn-outline btn-success btn-sm reset',
                                            'id' => 'info',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#info-modal',
                                            'data-url' => $url
                                        ]);
                                    }

                                ],
                            ],

                            [
                                'header' => '操作',
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update}',
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-edit"></i> 编辑', $url, [
                                            'class' => 'btn btn-outline btn-warning btn-sm',
//                                            'id' => 'info',
//                                            'data-toggle' => 'modal',
//                                            'data-target' => '#info-modal',
//                                            'data-url' => $url
                                        ]);
                                    }
                                ],
                            ],
                        ],
                        'toolbar' => [
                            ['content' => ''],
                        ],
                        'responsive' => true,
                        'hover' => true,
                        'condensed' => true,
                        'floatHeader'=>false,

                        'panel' => [
                            'heading' => false,
                            'type' => '',
                            'before' => false,
                            'after' => "<div class='margin-left:20px;'>{summary}</div>",
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
//弹框
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
        /*重置密码*/
        $('.reset').click(function () {
            showPage($(this));
        });

        /*编辑*/
//        $('.update').click(function () {
//            showPage($(this));
//        });
//
//        function showPage(obj) {
//            $.fn.modal.Constructor.prototype.enforceFocus = function () {
//            }; //防止select2无法输入
//            $.get(obj.attr("data-url"), {},
//                function (data) {
//                    $(".modal-body").html(data);
//                }
//            );
//        }
    }
</script>
