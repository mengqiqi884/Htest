<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use admin\models\CarSearch;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\CarreplaceSearch $searchModel
 */

$this->title = '汽车列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
//弹出操作成功提示
if (Yii::$app->getSession()->hasFlash('success')) {
    echo \yii\bootstrap\Alert::widget(['options' => ['class' => 'alert-success alert-dismissable', //这里是提示框的class
    ], 'body' => Yii::$app->getSession()->getFlash('success'), //消息体
    ]);
}
//弹出操作失败提示
if (Yii::$app->getSession()->hasFlash('error')) {
    echo \yii\bootstrap\Alert::widget(['options' => ['class' => 'alert-danger alert-dismissable',], 'body' => Yii::$app->getSession()->getFlash('error'),]);
}
?>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="icon pull-left"><i class="fa fa-picture-o text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?= Html::encode($this->title) ?></h5>
                </div>
                <div class="ibox-content">
                    <?php

                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'export' => false,
                        'pjax' => true,  //pjax is set to always true for this demo
                        'pjaxSettings' => [
                            'options' => ['id' => 'carreplace_pjax',],
                            'neverTimeout' => true,
                        ],
                        'striped'=>true,
                        'hover'=>true,
                        'columns' => [
                            [
                                'header' => '序号',
                                'class' => 'kartik\grid\SerialColumn'
                            ],
                            [
                                'label' => '车型',
                                'attribute' => 'r_brand',
                                'headerOptions' => ['width' =>'400'],
                                'value' => function($data){
                                    $brand = CarSearch::getCarTitle($data->r_brand);
                                    $car = CarSearch::getCarTitle($data->r_car_id);
                                    $volume = CarSearch::getCarTitle($data->r_volume_id);

                                    return (empty($brand) ? '' : $brand) . (empty($car) ? '' : '/' . $car) . (empty($volume) ? '' : '/' . $volume);
                                },
                                'filter' => false
                            ],
                            [
                                'label' => '名称',
                                'attribute' => 'r_accept_name',
                                'headerOptions' => ['width' =>'350'],
                                'value' => function($data){
                                    $level = $data->r_role == 1 ? '用户' : '4s店';
                                    $name = '';
                                    if ($data->r_role == 1) { //用户
                                        if ($data->user) {
                                            $name = $data->user->u_nickname . '(' . $data->user->u_phone . ')--' . $level;
                                        }

                                    } elseif ($data->r_role == 2) { //4s店
                                        if ($data->accept_id) {
                                            $arr = explode(',', $data->accept_id);

                                            foreach ($arr as $v) {
                                                if ($v) {
                                                    $info = \admin\models\CAgent::find()->where(['a_id' => $v])->one();
                                                    $name .= (empty($info) ? '' : $info->a_name . ' 、 ' ) . '--' . $level;
                                                }
                                            }
                                        } else {
                                            if ($data->agent) {
                                                $name = $data->agent->a_name . '--' . $level;
                                            }
                                        }
                                    }

                                    return $name;
                                },
                                'filterWidgetOptions'=>[
                                    'pluginOptions'=>['allowClear'=>true],
                                ],
                                'filterInputOptions'=>['placeholder'=>'用户名称/4s店名称','class'=>'form-control'],
                            ],
                            [
                                'attribute' => 'r_state',
                                'format' => 'raw',
                                'value' => function($data){
                                    if ($data->r_role == 1) {   //用户
                                        switch ($data->r_state) {
                                            case 0:
                                                $s = '<span class="text-warning">待审核</span>';
                                                break;
                                            case 1:
                                                $s = '<span class="text-success">置换中</span>';
                                                break;
                                            case 2:
                                                $s = '<span class="text-muted">已删除</span>';
                                                break;
                                            case 3:
                                                $s = '<span class="text-info">已置换</span>';
                                                break;
                                            case 4:
                                                $s = '<span class="text-danger">审核不通过</span>';
                                                break;
                                            default:
                                                $s = '状态有误';
                                                break;
                                        }
                                    } else {    //4s店
                                        $s = '';
                                    }

                                    return $s;
                                },'filter' => false
                            ],
                            [
                                'attribute' => 'created_time',
                                'headerOptions' => ['width' =>'210'],
                                'value' => function($data){
                                    return empty($data->created_time) ? '' : $data->created_time;
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
                                'label' => '车子状态',
                                'attribute' => 'is_forbidden',
                                'format' => 'raw',
                                'headerOptions' => ['width' =>'200'],
                                'class' => 'kartik\grid\EditableColumn',
                                'editableOptions'=>[
                                    'inputType'=>\kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                                    'asPopover' => false,
                                    'data' => ['1'=>'禁用','0'=>'非禁用'],
                                ],
                                'value' => function($model) {
                                    return $model->is_forbidden == 1 ? '<i class="glyphicon glyphicon-remove-circle text-danger">禁 用</i>' : '<i class="glyphicon glyphicon-ok-circle text-info">非禁用</i>';
                                },
                            ],
                            [
                                'header' => '操作',
                                'mergeHeader'=>true,  //合并头部单元格
                                'width'=>'250px',
                                'class' => 'kartik\grid\ActionColumn',
                                'template' => '{view} {check-car-ok} {check-car-refuse}',
                                'buttons' => [
                                    'view' => function($url, $model){
                                        return Html::a('<i class="fa fa-eye"></i> 查看', 'javascript:void(0);', [
                                            'class' => 'btn btn-success btn-outline btn-xs',
                                            'id' => 'view',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#view-modal',
                                            'data-url' => \yii\helpers\Url::toRoute(['view?id=' . $model->r_id]),
                                            'style' => $model->r_role == 2 ? 'display:none' : ''
                                        ]);
                                    },
                                    'check-car-ok' => function($url, $model, $key){
                                        return Html::a('同意审核', ['update', 'id' => $key, 'state' => 'ok'], [
                                            'title' => '车辆审核',
                                            'class' => 'btn btn-warning btn-xs',
                                            'style' => ($model->r_state == 0 && $model->is_forbidden == 0) ? '' : 'display:none'
                                        ]);
                                    },
                                    'check-car-refuse' => function($url, $model, $key){
                                        return Html::a('拒绝审核', ['update', 'id' => $key, 'state' => 'refuse'], [
                                            'title' => '车辆审核',
                                            'class' => 'btn btn-danger btn-xs',
                                            'style' => ($model->r_state == 0 && $model->is_forbidden == 0) ? '' : 'display:none'
                                        ]);
                                    },
                                ],
                            ],
                        ],
                        'toolbar' => [
                            ['content' => Html::a('<i class="fa fa-refresh"></i> 刷新', ['index'], ['class' => 'btn btn-outline btn-default']),
                            ],
                            //                '{toggleData}',
                            //                '{export}',
                        ],
                        'responsive' => true,
                        'hover' => true,
                        'condensed' => true,
                        //  'floatHeader'=>true,
                        'panel' => [
                            'heading' => false,
                            'after' => '<div  style="margin-top:8px">{summary}</div>',
                            'showFooter' => false
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    //新增消息
    Modal::begin(['id' => 'view-modal', //与上面的data-target值保持一致
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
        $("a#view").each(function () {
            $(this).click(function () {
                $.get($(this).attr("data-url"), {},
                    function (data) {
                        $('#view-modal').find('.modal-body').empty();
                        $(".modal-body").html(data);
                    }
                );
            })
        });
        /*修改车辆状态*/
        $('.update-state').click(function () {
            $.get($(this).attr("data-url"), {},
                function (data) {
                    $('#view-modal').find('.modal-body').empty();
                    $(".modal-body").html(data);
                }
            );
        });
    }
</script>