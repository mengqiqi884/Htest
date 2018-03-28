<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = '用户意见反馈';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cuseradvice-index">

    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'export' =>false,
        'columns' => [

            'a_id',
            [
                'attribute' =>'user_id',
                'value' =>function($data){
                    return empty($data->user)?'':$data->user->u_nickname;
                }
            ],
            [
                'attribute' =>'content',
                'value' => function($data) {
                    return mb_substr($data->content,0,20,'utf-8').'...';
                }
            ],
            'created_time',
            [
                'header' =>'操作',
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view}',
                'buttons' => [
                'view' => function ($url, $model) {
                      return Html::a('<span class="glyphicon glyphicon-eye-open"></span>','javascript:void(0);', [
                            'title' => Yii::t('yii', 'View'),
                            'class' =>'btn btn-success btn-xs view',
                            'id' => 'info',
                            'data-toggle' => 'modal',
                            'data-target' => '#info-modal',
                            'data-url' => $url
                      ]);}
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
           // 'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> 刷新列表', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>
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
        /*查看*/
        $('.view').click(function(){
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

</script>
