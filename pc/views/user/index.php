<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\export\ExportMenu;
use yii\helpers\Url;
\admin\assets\TableAsset::register($this);
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\UserSearch $searchModel
 */

$this->title = '用户列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    /*用户头像*/
    .user-img{
        border-radius: 3px;
        display: inline-block;
        height: 28px;
        margin-right: 10px;
        vertical-align: middle;
        width: 28px;
    }
</style>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="icon pull-left"><i class="fa fa-group text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?=Html::encode($this->title);?></h5>
                </div>
                <div class="ibox-content">
                    <?=\kartik\grid\GridView::widget([
                        'dataProvider' => $dataProvider,
                        'pager' => [
                            'options'=>['class'=>'hidden'] //关闭分页
                        ],
                        'summaryOptions'=>['class'=>'hidden'], //关闭总页数
                        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                        'options'=>['id'=>'grid'],
                        'export' => false , //不需要导出
                        'toggleData' => false, //不需要“全部显示按钮”
                        'pjax' => true,
                        'pjaxSettings' => [
                            'options' => [
                                'id' => 'userinfo'
                            ],
                            'neverTimeout' => true,
                        ],
                        'columns' => [
                            [
                                'attribute' =>'u_nickname',
                                'width' => '15%',
                                'format' => 'html',
                                'value' => function($data){
                                    $headimg = empty($data->u_headImg) ? Url::to('@web/img/icons/user.png'):$data->u_headImg;
                                    return '<img src="'.$headimg.'" class="user-img">'.$data->u_nickname;
                                }
                            ],
                            [
                                'attribute' => 'u_phone',
                                'headerOptions' => ['width' =>'150']
                            ],
                            [
                                'attribute'=>'u_sex',
                                'value'=>function($model){
                                    return $model->u_sex ==1?'男':($model->u_sex==2 ? '女':'性别未知');
                                },
                                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                                'filter' => ['1'=>'男','2'=>'女'],
                                'filterWidgetOptions' => [
                                    'options' => ['placeholder' => ''],
                                    'pluginOptions' => ['allowClear' => true],
                                ],
                            ],
                            [
                                'attribute' => 'u_state',
                                'format' => 'html',
                                'headerOptions' => ['width' =>'210'],
                                'class' => 'kartik\grid\EditableColumn',
                                'editableOptions'=>[
                                    'inputType'=>\kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                                    'asPopover' => false,
                                    'data' => ['1'=>'启用','2'=>'禁用'],
                                ],
                                'value' => function($model) {
                                    return $model->u_state == 1 ? '<span class="badge badge-success">启用</span>' : '<span class="badge badge-info">禁用</span>';
                                },
                                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                                'filter' => ['1'=>'启用','2'=>'禁用'],
                                'filterWidgetOptions' => [
                                    'options' => ['placeholder' => ''],
                                    'pluginOptions' => ['allowClear' => true],
                                ],
                            ],

                            [
                                'attribute' => 'created_time',
                                'filter' => false, //不显示搜索框
                            ],

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-folder"></i> 查看', $url, [
                                            'title' => Yii::t('app', '查看'),
                                            'class' => 'btn btn-outline btn-info btn-sm',
                                        ]);
                                    }
                                ],
                            ],
                        ],
                        'hover'=>true,
                        'responsive'=>true,
                        'condensed'=>true,
                        'floatHeader'=>false,
                        'panel' => [
                            'heading'=>false,
                            'before'=>Html::a('<i class="fa fa-sign-out"></i> 导出Excel', ['/export/to-excel','table'=>'user'],[
                                'class'=>'btn btn-outline btn-info btn-sm',
                                'data-pjax'=>0
                            ]) . '&nbsp;&nbsp;' . Html::a('<i class="fa fa-bar-chart-o"></i> 生成图表至excel', ['/export/create-excel-img'], [
                                    'class' => 'btn btn-outline btn-warning btn-sm',
                                    'data-pjax' => 0
                                ]),
                            'showFooter'=>false
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

