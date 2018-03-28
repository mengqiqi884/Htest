<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use  \yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\bootstrap\Alert;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\ProductsSearch $searchModel
 */
\admin\assets\WarningAsset::register($this);
\admin\assets\TableAsset::register($this);

$this->title = '产品列表';
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

<div class="row">
    <div class="col-sm-12">
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="icon pull-left"><i class="fa fa-gift text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?=Html::encode($this->title)?></h5>
                </div>
                <div class="ibox-content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'options'=>['id'=>'grid'],
                        'export' =>false,
                        'pjax' => true,
                        'pager' => [
                            'options'=>['class'=>'hidden'] //关闭分页
                        ],
                        'summaryOptions'=>['class'=>'hidden'], //关闭总页数
                        'pjaxSettings' => [
                            'options' => [
                                'id' => 'ordering_pjax',
                            ],
                            'neverTimeout' => true,
                        ],
                        'columns' => [
                            'p_sortorder',
                            'p_name',

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                                'template' => '{view} {update} {delete}',
                                'buttons' => [
                                    'view' =>function($url, $model){
                                        return Html::a('<i class="fa fa-eye"> 查看</i>',$url, [
                                            'class' => 'btn btn-outline btn-primary btn-sm ',
//                                            'id' => 'info',
//                                            'data-toggle' => 'modal',
//                                            'data-target' => '#info-modal',
//                                            'data-id' => $model->p_id,
//                                            'data-url' => $url,
                                        ]);
                                    },
                                    'update' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-pencil"> 编辑</i>', 'javascript:void(0)', [
                                            'class' => 'btn btn-outline btn-warning btn-sm update',
                                            'id' => 'info',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#info-modal',
                                            'data-id' => $model->p_id,
                                            'data-url' => $url,
                                            'data-flag' =>'update'
                                        ]);
                                    },
                                    'delete' => function ($url, $model,$key) {
                                        return Html::a('<i class="fa fa-trash"> 删除</i>', 'javascript:void(0);', [
                                            'class' => 'btn btn-outline btn-danger btn-sm delete',
                                            'data-url' => $key,
                                            'onclick' => 'confirm_del(this)'
                                        ]);
                                    }
                                ],
                            ],
                        ],
                        'toolbar' => [
                            ['content'=>
                                Html::a('<i class="fa fa-plus"></i> 新增产品','javascript:void(0);' ,[
                                    'class'=>'btn btn-outline btn-primary btn-space create',
                                    'id' => 'info',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#info-modal',
                                    'data-url' => \yii\helpers\Url::toRoute('products/create'),
                                ])
//                                Html::a('<i class="fa fa-refresh"></i>刷新', ['index'], ['class'=>'btn btn-outline btn-default btn-space', 'title'=>'刷新'])
//                                Html::button('<i class="fa fa-print"></i>打印',['class'=> 'btn btn-outline btn-default btn-space','title'=>'打印','onclick'=> 'print_Preview()'])
                            ],
                        ],
                        'responsive'=>true,
                        'hover'=>true,
                        'condensed'=>true,
                        'floatHeader'=>false,
                        'panel' => [
                            'heading'=>false,
                            'after'=>'<div style="margin-top:8px">{summary}</div>',
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
    //新增金融产品
    Modal::begin([
        'id' => 'info-modal', //与上面的data-target值保持一致
    ]);
    Modal::end();
?>

