<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\bootstrap\Alert;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\AgentSearch $searchModel
 */

$this->title = '4S店列表';
$this->params['breadcrumbs'][] = $this->title;
?>

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


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="icon pull-left"><i class="fa fa-fire text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?=Html::encode($this->title)?></h5>
                </div>
                <div class="ibox-content">
                    <?php
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'export' => false,
                        'toggleData' => false,
                        'pjax' => true,  //pjax is set to always true for this demo
                        'pjaxSettings' => [
                            'options' => [
                                'id' => 'agent_pjax',
                            ],
                            'neverTimeout' => true,
                        ],
                        'columns' => [
                            [
                                'header' =>'序号',
                                'options' =>['width'=>'3%'],
                                'class' => 'kartik\grid\SerialColumn',
                                'mergeHeader' => true
                            ],

                            'a_account',
                            [
                                'attribute' => 'a_brand',
                                'value' => function($data) {
                                    return $data->car?$data->car->c_title:'';
                                },
                                'filterType'=>GridView::FILTER_SELECT2,
                                'filter' => \admin\models\CAgent::GetAllBrand(),
                                'filterInputOptions'=>['placeholder'=>'请选择',],
                                'filterWidgetOptions' => ['changeOnReset'=>true]
                            ],
                            [
                                'attribute' => 'a_areacode',
                                'value' => function($data) {
                                    $str=\admin\models\CCity::GetCityName($data->a_areacode);
                                    $city_arr=explode('^',$str);
                                    $pro=\admin\models\CCity::GetCityName($city_arr[1]);
                                    $pro_arr=explode('^',$pro);
                                    return $pro_arr[0].' / '.$city_arr[0];
                                },
                                'filter' => false
                            ],
                            'a_name',
                            [
                                'attribute' => 'a_address',
                                'filter' => false
                            ],

                            [
                                'attribute' => 'a_concat',
                                'width' => '5%',
                                'value' => function($data) {
                                    return $data->a_concat?$data->a_concat:'';
                                },
                                'filter' => false
                            ],
                            [
                                'attribute' =>  'a_phone',
                                'width' => '8%',
                                'value' => function($data) {
                                    return $data->a_phone?$data->a_phone:'';
                                }
                            ],
                            [
                                'attribute' =>'a_email',
                                'format' =>'email',
                                'filter' => false
                            ],
                            [
                                'attribute' => 'created_time',
                                'width' =>'18%',
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
                                'width' =>'4%',
                                'class'=>'kartik\grid\EditableColumn',
                                'mergeHeader' => true,  //合并标题栏与搜索栏
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
                                'header' =>'重置密码',
                                'class' => 'kartik\grid\ActionColumn',
                                'mergeHeader' => true, //合并标题栏与搜索栏
                                'template' =>'{reset}',
                                'buttons' => [
                                    'reset' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-undo"></i> 重置','javascript:void(0);', [
                                            'class'=> 'btn btn-success btn-outline btn-sm reset',
                                            'id' =>'info',
                                            'data-toggle' => 'modal',
                                            'data-target' =>'#info-modal',
                                            'data-url' =>$url
                                        ]);
                                    }

                                ],
                            ],

                            [
                                'header' =>'操作',
                                'class' => 'kartik\grid\ActionColumn',
                                'mergeHeader' => true, //合并标题栏与搜索栏
                                'template' =>'{update}',
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-edit"></i> 编辑',$url, [
                                            'class'=> 'btn btn-warning btn-outline btn-sm update',
                                        ]);
                                    }

                                ],
                            ],
                            [
                                'header' =>'发布',
                                'class' => 'kartik\grid\ActionColumn',
                                'mergeHeader' => true, //合并标题栏与搜索栏
                                'template' =>'{create-info}',
                                'buttons' => [
                                    'create-info' => function ($url, $model,$key) {
                                        return Html::a('<i class="fa fa-files-o"></i> 发布', 'javascript:void(0);', [
                                            'class'=> 'btn btn-primary btn-outline btn-sm create',
                                            'id' =>'info',
                                            'data-toggle' => 'modal',
                                            'data-target' =>'#info-modal',
                                            'data-url' =>Url::toRoute('carreplace/create?id='.$key.'&brand='.$model->a_brand)
                                        ]);
                                    }

                                ],
                            ],
                            [   //详细扩展
                                'class' => 'kartik\grid\ExpandRowColumn',
                                'width' =>'6%',
                                'value' => function($data,$key,$index,$column){
                                    return GridView::ROW_COLLAPSED;
                                },
                                'detail' => function($data,$key,$index,$column){

                                    $carsmodel = \admin\models\CCarreplace::find()
                                        ->where(['r_role' => 2, 'r_accept_id' => $data->a_id])
                                        ->all();
                                    return Yii::$app->controller->renderPartial('/agent/view',['model'=> $carsmodel]);
                                },
                                'expandAllTitle' => '展开-已发布的车型',
                                'collapseAllTitle' => '收缩-已发布的车型',
                                'expandIcon'=>'<span class="fa fa-angle-double-down"></span>',
                                'collapseIcon'=>'<span class="fa fa-angle-double-up"></span>',
                                'headerOptions' => ['class'=>'kartik-sheet-style'],
                            ],

                        ],
//                        'toolbar' => [
//                            ['content' => ''],
//                        ],
                        'responsive'=>true,
                        'hover'=>true,
                        'condensed'=>true,
                        'panel' => [
                            'heading'=>false,
//                            'type'=>'',
                            'before'=>Html::a('<i class="fa fa-refresh"></i> 刷新', ['index'], ['class' => 'btn btn-outline btn-default pull-right']),
                            'after' => false,
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
    //发布车型
    Modal::begin([
        'id' => 'info-modal', //与上面的data-target值保持一致
    ]);
    Modal::end();
?>

<script>
    $(function () {
        $(document).ready();
        $(document).on('pjax:complete', function () {
            init();
            pjax_loadinit();
        });
    });

    function init()
    {
        /*查看已发布的车型*/
        $('.view').click(function(){
            showPage($(this));
        });

        /*重置密码*/
        $('.reset').click(function(){
            showPage($(this));
        });
        /*发布*/
        $('.create').click(function(){
            showPage($(this));
        });
        /*编辑*/
        $('.update').click(function(){
            showPage($(this));
        });

        function showPage(obj){
            $(".modal-body").empty();
            $.fn.modal.Constructor.prototype.enforceFocus = function () { }; //防止select2无法输入
            $.get(obj.attr("data-url"),{},
                function(data){
                    $(".modal-body").html(data);
                }
            );
        }
    }

    //在pjax加载完成后，重新加载js,能使用Editable进行编辑
    function pjax_loadinit()
    {
        $.getScript("../../assets/dad8b871/js/editable.js");
        $.getScript("../../assets/dad8b871/js/editable-pjax.js");
        $.getScript("../../assets/48d7f4cc/js/bootstrap-popover-x.js");
        $.getScript("../../assets/8715fbb7/jquery.pjax.js");
    }
</script>
