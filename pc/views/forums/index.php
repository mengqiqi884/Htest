<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\editable\Editable;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\ForumsSearch $searchModel
 */

$this->title = '帖子列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="icon pull-left"><i class="fa fa-bookmark-o text-navy"></i>&nbsp;&nbsp;</span>
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
                                'id' => 'forum_pjax',
                            ],
                            'neverTimeout' => true,
                        ],
                        'columns' => [
                            [
                                'header' =>'序号',
                                'mergeHeader' => true,
                                'class' => 'kartik\grid\SerialColumn',

                            ],
                            [
                                'attribute' =>'f_fup',
                                'format' => 'raw',
                                'width' =>'100px',
                                'value' =>function($data){
                                    return \admin\models\CForumForum::GetFupName($data->f_fup);
                                },
                                'filterType'=>GridView::FILTER_SELECT2,
                                'filter' => \admin\models\CForumForum::GetForumsName(),
                                'filterInputOptions'=>['placeholder'=>'请选择'],
                                'group'=>true,  // 分类合并
                            ],
                            [
                                'label' => '封面图',
                                'attribute' =>'f_pic',
                                'format' =>['image',['width' =>'84','height'=>'84']],
                                'value' => function($data){
                                    return $data->f_pic;
                                },
                                'filter' => false,
                            ],
                            [
                                'attribute' => 'f_title',
                                'value' => function($data) {
                                    return rawurldecode($data->f_title);  //Url解码
                                }
                            ],
                            [
                                'attribute' =>  'f_user_nickname',
                                'headerOptions' => ['width' =>'220px'],
                                'value' => function($data) {
                                    return $data->f_user_nickname . '【' . $data->user->u_phone. '】';
                                },
                            ],
                            [
                                'attribute' => 'created_time',
                                'headerOptions' => ['width' =>'300px'],
                                'format' => ['date', 'php:Y-m-d'],
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
                                ],
                                'group'=>true,  // 分类合并
                            ],
                            [
                                'attribute' => 'f_views',
                                'headerOptions' => ['width' =>'80px'],
                            ],
                            [
                                'attribute' =>  'f_replies',
                                'headerOptions' => ['width' =>'80px'],
                                'filter' => false
                            ],
                            [
                                'attribute' => 'is_week_new',
                                'class'=>'kartik\grid\EditableColumn',
                                'editableOptions'=>[
                                    'asPopover' => true,
                                    'inputType'=>\kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                                    'data' => ['0' => '否','1'=>'是'],
                                    'options' => ['class' => 'form-control'],
                                    'displayValueConfig' => [
                                        '0' => '<i class="fa fa-times text-danger"></i>',
                                        '1' => '<i class="fa fa-check text-navy"></i>'
                                    ]
                                ],
                            ],
                            [
                                'attribute' => 'f_is_top',
                                'class' => 'kartik\grid\EditableColumn',
                                'editableOptions'=>[
                                    'asPopover' => true,
                                    'inputType'=>\kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                                    'data' => ['0' => '非置顶','1'=>'置顶'],
                                    'options' => ['class' => 'form-control'],
                                    'displayValueConfig' => [
                                        '0' => '<span class="text-danger"> 不置顶</span>',
                                        '1' => '<span class="text-navy"><i class="fa fa-check-square-o"></i> 置顶</span>'
                                    ]
                                ],
                                'filter' => false
                            ],
                            [
                                'attribute' => 'f_state',
                                'class' => 'kartik\grid\EditableColumn',
                                'editableOptions'=>[
                                    'asPopover' => true,
                                    'inputType'=>\kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                                    'data' => ['-1' => '启用','1'=>'禁用'],
                                    'options' => ['class' => 'form-control'],
                                    'displayValueConfig' => [
                                        '-1' => '<span class="text-navy"><i class="fa  fa-check-square-o"></i> 启用</span>',
                                        '1' => '<span class="text-danger"> 禁用</span>'
                                    ]
                                ],
                                'filter' => false
                            ],
                            [
                                'header' => '操作',
                                'class' => 'kartik\grid\ActionColumn',
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-eye"></i> 查看', $url, [
                                            'class' => 'btn btn-outline btn-default btn-sm',
                                        ]);
                                    }
                                ],
                            ],
                        ],
//                        'toolbar' => [
//                            ['content' => ''],
//                        ],
                        'responsive'=>true,
                        'hover'=>true,
                        'condensed'=>true,
                        'floatHeader'=>false,

                        'panel' => [
                            'heading'=>false,
                            'before'=>Html::a('<i class="fa fa-refresh"></i> 刷新', ['index'], ['class' => 'btn btn-outline pull-right']),
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
//新增消息
Modal::begin([
    'id' => 'view-modal', //与上面的data-target值保持一致
    'header' => '<h4 class="modal-title">编辑：</h4>',
]);
Modal::end();
?>

<script>
    $(function(){
        $(document).ready();
        $(document).on('pjax:complete', function () {
            pjax_loadinit();
        });
        //在pjax加载完成后，重新加载js,能使用Editable进行编辑
        function pjax_loadinit()
        {
            $.getScript("../../assets/dad8b871/js/editable.js");
            $.getScript("../../assets/dad8b871/js/editable-pjax.js");
            $.getScript("../../assets/48d7f4cc/js/bootstrap-popover-x.js");
            $.getScript("../../assets/8715fbb7/jquery.pjax.js");
        }
    });

</script>