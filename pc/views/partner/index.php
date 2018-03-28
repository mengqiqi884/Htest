<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use  \yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = '合作伙伴';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cpartner-index">
    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'export' =>false,
        'columns' => [
            'p_id',

            [
                'attribute' =>'p_colorlogo',
                'format' =>'raw',
                'value' => function($data){
                    return empty($data->p_colorlogo)?"":"<img src='../../../../".$data->p_colorlogo."' width='auto' height='50' onclick='showImg()'>";
                }
            ],
            'p_url:url',
            [
                'header' =>'是否显示',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update-show}',
                'buttons' => [
                    'update-show' => function ($url, $model,$key) {
                        if($model->p_is_show == 1){
                            return Html::a('<i style="color: #23c6c8">显示<i class="glyphicon glyphicon-ok "></i></i>&nbsp;', ['update-show', 'id' => $key], [
                                'title' =>'显示',
                            ]);
                        }else if($model->p_is_show == 0){
                            return Html::a('<i style="color: #ff4d35">不显示<i class="glyphicon glyphicon-remove "></i></i>&nbsp;',['update-show', 'id' => $key], [
                                'title' => '不显示',
                            ]);
                        }
                    }
                ]
            ],
            [
                'header' =>'操作',
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{update}&nbsp;&nbsp;{delete}',
                'buttons' => [
                'update' => function ($url, $model) {
                       return Html::a('编辑', $url,[
                           'class' => 'btn btn-warning btn-xs update',
                           'id' => 'info',
                           'data-toggle' => 'modal',
                           'data-target' => '#info-modal',
                           'data-url' => $url
                       ]);
                    },
                'delete' => function ($url, $model) {
                        return Html::a('删除', $url,[
                            'class' => 'btn btn-danger btn-xs',
                        ]);
                },

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 新增合作伙伴', 'javascript:void(0);', [
                'class' => 'btn btn-success create',
                'id' => 'info',
                'data-toggle' => 'modal',
                'data-target' => '#info-modal',
                'data-url' => \yii\helpers\Url::toRoute(['create'],true)
            ]),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> 刷新列表', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]);
    Pjax::end();
    ?>
    <?php
    //新增合作伙伴
    Modal::begin([
        'id' => 'info-modal', //与上面的data-target值保持一致
    ]);
    Modal::end();
    ?>
</div>

<script>
    $(function(){
        /*发布*/
        $('.create').click(function(){
            showPage($(this));
        });
        /*编辑*/
        $('.update').click(function(){
            showPage($(this));
        });
        function showPage(obj){
            $('#info-modal').find('.modal-body').empty();
            //$.fn.modal.Constructor.prototype.enforceFocus = function () { }; //防止select2无法输入
            $.get(obj.attr("data-url"),{},
                function(data){
                    $(".modal-body").html(data);
                }
            );
        }
    });
    /*显示图片大图*/
    function showImg(){
        layer.photos({
            photos: '.cpartner-index' //直接从页面中获取图片，那么需要指向图片的父容器
            ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机
        });
    }
</script>
