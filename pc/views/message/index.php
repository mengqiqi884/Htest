<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Modal;
\admin\assets\TableAsset::register($this);
\admin\assets\WarningAsset::register($this);
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = '消息推送';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .cmessage-index .modal-dialog{width: 900px;height:450px;}
    .cmessage-index .modal-content{height:100%;}

</style>
<?=Html::jsFile('@web/js/wine/wine.js');?>

    <?php
    //弹出操作成功提示
    if( Yii::$app->getSession()->hasFlash('success') ) {
        echo \yii\bootstrap\Alert::widget([
            'options' => [
                'class' => 'alert-success alert-dismissable', //这里是提示框的class
            ],
            'body' => Yii::$app->getSession()->getFlash('success'), //消息体
        ]);
    }
    //弹出操作失败提示
    if( Yii::$app->getSession()->hasFlash('error') ) {
        echo \yii\bootstrap\Alert::widget([
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
                    <span class="icon pull-left"><i class="fa fa-comments-o text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?=Html::encode($this->title)?></h5>
                </div>
                <div class="ibox-content">
                    <?php
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'export' =>false,
                        'toggleData' => false,
                        'options'=>['id'=>'grid'],
//                        'pjax' => true,
                        'columns' => [
                            ['class'=>'yii\grid\CheckboxColumn'],
                            'created_time',
                            'm_author',
                            'm_content',
                        ],
                        'responsive'=>true,
                        'hover'=>true,
                        'condensed'=>true,

                        'panel' => [
                            'heading'=>false,
                            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 新建推送',Url::toRoute('create'), [
                                    'class' => 'btn btn-outline btn-primary dim create'
                                ])
                                .'&nbsp;&nbsp;'
                                .Html::a('<i class="glyphicon glyphicon-exclamation-sign"></i> 批量删除', 'javascript:void(0);', [
                                    'class' => 'btn btn-outline btn-warning dim gridview',
                                    'onclick' => 'deleteAll(this)',
                                    'title' => Url::toRoute(['message/ajax-delete-all'])
                                ]),

                            'after'=>Html::a('<i class="fa fa-refresh"></i>', ['index'], ['class' => 'btn btn-outline']),
                            'showFooter'=>false,
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(".gridview").on("click", function () {
        var keys = $("#grid").yiiGridView("getSelectedRows");
        if(keys.length==0){
            swal({title:"",text:"请选择需要删除的用户",type:"error"});
        }else{
            $.ajax({
                type:"POST",
                url:"ajax-delete-all",
                data:{"mids":keys},
                dataType:"json",
                success:function(data){
                    if(data.state==200){
                        swal({title:"",text:data.message,type:"success"});
                        window.location.reload();
                    }else{
                        swal({title:"",text:data.message,type:"error"});
                    }
                },
                error:function(data){
                    swal({title:"",text:'您没有此操作的权限',type:"error"});
                }
            });
        }

    });
</script>