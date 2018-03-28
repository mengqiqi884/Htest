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
 * @var admin\models\BannerSearch $searchModel
 */

$this->title = 'PC端广告图展示';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cbanner-index">
    <?php
    //弹出操作成功提示
    if( Yii::$app->getSession()->hasFlash('success') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success', //这里是提示框的class
            ],
            'body' => Yii::$app->getSession()->getFlash('success'), //消息体
        ]);
    }
    //弹出操作失败提示
    if( Yii::$app->getSession()->hasFlash('error') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-danger',
            ],
            'body' => Yii::$app->getSession()->getFlash('error'),
        ]);
    }
    ?>
    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'export' =>false,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' =>'序号'
            ],
            [
                'attribute' =>'title',
                'value'=>function($data){
                    return \admin\models\CPcBanner::getLocationName($data->type);
                }
            ],
            [
                'attribute' =>'pic',
                'format' =>'raw',
                'value' =>function($data){
                    return '<img src="'.Yii::$app->params['base_url'].Yii::$app->params['base_file'].$data->pic.'" width="150" class="img-renct" onclick="showImg()">';
                }
            ],

            'url:url',
            [
                'header' =>'操作',
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}&nbsp;&nbsp;{delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('修改','javascript:void(0);', [
                            'title' => Yii::t('app', '修改'),
                            'class' => 'btn btn-success btn-xs update',
                            'id' => 'info',
                            'data-toggle' => 'modal',
                            'data-target' => '#info-modal',
                            'data-id' => $model->id,
                            'data-flag' =>'update',
                            'data-url' => $url,
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return  Html::a('删除',$url, [
                            'class' => 'btn btn-danger btn-xs',
                            'data' => ['confirm' => '您确定删除该广告图片吗？']
                        ]);
                    }

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        // 'floatHeader'=>true,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 新添广告图', ['javascript:void(0);'], [
                'class' => 'btn btn-success create',
                'id' => 'info',
                'data-toggle' => 'modal',
                'data-target' => '#info-modal',
                'data-url' => Url::toRoute('create'),
                'data-flag' =>'create'
            ]),
            //  'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]);
    Pjax::end();
    ?>

    <?php
    //新增banner
    Modal::begin([
        'id' => 'info-modal', //与上面的data-target值保持一致
    ]);
    Modal::end();
    ?>
</div>
<script>
    /*新建广告图*/
    $('.create').click(function(){
        showPage($(this));
    });
    /*编辑广告图*/
    $('.update').click(function(){
        showPage($(this));
    });

    function showPage(obj){
        $('#banner-form').remove();
        $.get(obj.attr("data-url"),{},
            function(data){
                $(".modal-body").html(data);
            }
        )
    }

    function showImg(){
        layer.photos({
            photos: '.cbanner-index table' //直接从页面中获取图片，那么需要指向图片的父容器
            ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机
        });
    }
</script>