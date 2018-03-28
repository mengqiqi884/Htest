<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\bootstrap\Alert;

\admin\assets\WarningAsset::register($this);
\admin\assets\ImgAsset::register($this);
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\BannerSearch $searchModel
 */

$this->title = '广告图展示';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .table-bordered, .table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
        border: none;
        border-bottom: 1px solid #ddd;
    }
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


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="icon pull-left"><i class="fa fa-picture-o text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?=Html::encode($this->title)?></h5>
                </div>
                <div class="ibox-content">
                    <?php
                        echo Html::a('<i class="fa fa-plus"></i> 新添广告图', Url::toRoute(['banner/create']), [
                            'class' => 'btn btn-outline btn-primary create',
                        ]);
                        echo GridView::widget([
                            'dataProvider' => $dataProvider,
                            'export' =>false,  //不导出
                            'pager' => [
                                'options'=>['class'=>'hidden'] //关闭分页
                            ],
                            'summaryOptions'=>['class'=>'hidden'], //关闭总页数
                            'columns' => [
                                [
                                    'class' => 'yii\grid\SerialColumn',
                                    'header' =>'序号'
                                ],
                                [
                                    'attribute' =>'b_location',
                                    'value'=>function($data){
                                        return \admin\models\BannerSearch::getLocationName($data->b_location);
                                    }
                                ],
                                [
                                    'attribute' =>'b_img',
                                    'format' =>'raw',
                                    'value' =>function($data){
                                        $imgurl = Yii::$app->params['base_url'].Yii::$app->params['base_file'].$data->b_img;
                                        return
                                            '<a class="fancybox" href="'.$imgurl.'" title="' . (empty($data->b_title)?'banner':$data->b_title) . '">' .
                                            Html::img(
                                                $imgurl,
                                            ['class'=>'img-renct1','width'=>'150','height'=>'70'])
                                            . '</a>';
                                    }
                                ],

                                'b_url:url',
                                [
                                    'header' =>'操作',
                                    'class' => 'yii\grid\ActionColumn',
                                    'template'=>'{update}&nbsp;&nbsp;{delete}',
                                    'buttons' => [
                                        'update' => function ($url, $model) {
                                            return Html::a('<i class="fa fa-pencil-square-o"></i> 编辑',$url, [
                                                'class' => 'btn btn-outline btn-info',
                                            ]);
                                        },
                                        'delete' => function ($url, $model,$key) {  //confirm_del(controller,id)第一个参数是控制器名
                                            return  Html::a('<i class="fa fa-trash-o"></i> 删除','javascript:void(0);', [
                                                'class' => 'btn btn-outline btn-danger delete',
                                                'data-url' => $url,
                                                'onclick' => 'confirm_del(this)'
                                            ]);
                                        }

                                    ],
                                ],
                            ],
                            'responsive'=>true,
                            'hover'=>true,
                            'condensed'=>true,
                            'floatHeader'=>false,
                        ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
