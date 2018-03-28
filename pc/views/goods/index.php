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
 */
$this->title = '商品列表';
$this->params['breadcrumbs'][] = $this->title;

\admin\assets\ImgAsset::register($this);
?>

<style>
    .btn-space{margin-left: 5px!important;}
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
                    <span class="icon pull-left"><i class="fa fa-truck text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?=Html::encode($this->title)?></h5>
                </div>
                <div class="ibox-content">
                    <?php
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'export' =>false,
                        'toggleData' => false,
                        'options'=>['id'=>'grid'],
                        'pjax' =>true,
                        'columns' => [
                            [
                                'header' =>'序号',
                                'class' => 'kartik\grid\SerialColumn'
                            ],
                            [   //详细扩展
                                'class' => 'kartik\grid\ExpandRowColumn',
                                'width' => '80px',
                                'value' => function($data,$key,$index,$column){
                                    return GridView::ROW_COLLAPSED;
                                },
                                'detail' => function($data,$key,$index,$column){
                                    $searchModel = new \admin\models\ScoreLogSearch();

                                    $dataProvider = $searchModel->search(['ScoreLogSearch'=>['sl_good_id'=>$data->g_id]]);
                                    return Yii::$app->controller->renderPartial('/goods/view',[
                                        'searchModel'=> $searchModel,
                                        'dataProvider'=>$dataProvider,
                                    ]);
                                },
                                'detailAnimationDuration' => 100,
                                'headerOptions' => ['class'=>'kartik-sheet-style'],
                            ],
                            'g_name',
                            [
                                'attribute' =>'g_pic',
                                'format' =>'raw',
                                'value' =>function($data){
                                    $img_url = Yii::$app->params['base_url'].Yii::$app->params['base_file'].$data->g_pic;
                                    return "<a href='" .$img_url. "' class='fancybox' title='" . $data->g_name. "'>" .
                                        "<img src='". $img_url ."' width='50' height='50' class='img-renct1'>" .
                                    "</a>";
                                }
                            ],
                            'g_instroduce',
                            'g_score',
                            'g_sellout',
                            'g_amount',
                            [
                                'attribute' => 'g_state',
                                'width'=>'5%',
                                'format'=>'html',
                                'value' =>function($data){
                                    if($data->g_state == 1){
                                        return Html::a('<i class="glyphicon glyphicon-ok text-success"></i>&nbsp;已上架',
                                            ['delete','id'=>$data->g_id], [
                                            'data-confirm' => '是否确定下架该商品？'
                                        ]);
                                    }else if($data->g_state == 2){
                                        return Html::a('<i class="glyphicon glyphicon-remove"></i>&nbsp;已下架',
                                            ['delete','id'=>$data->g_id], [
                                            'data-confirm' => '是否确定上架该商品？'
                                        ]);
                                    }
                                }
                            ],
                            [
                                'attribute' => 'created_time',
                                'width'=>'10%',
                            ],
                            [
                                'header' =>'操作',
                                'class' => 'kartik\grid\ActionColumn',
                                'template' => '{update}',
                                'buttons' => [
                                    'update' => function ($url, $model,$key) {
                                        return Html::a('<i class="fa fa-edit"></i> 修改',$url, [
                                            'class' =>'btn btn-outline btn-warning btn-sm update',
                                            'id' => 'info'
                                        ]);
                                    }
                                ],
                            ],
                        ],
                        'responsive'=>true,
                        'hover'=>true,
                        'condensed'=>true,
                        'floatHeader'=>false,
                        'toolbar' => [
                            ['content' => Html::a('<i class="fa fa-plus"></i> 新增商品', ['create'], [
                                'class' => 'btn btn-primary btn-outline create',
                                ]) .
                                Html::a('<i class="fa fa-refresh"></i> 刷新', ['index'], ['class' => 'btn btn-default btn-space btn-outline'])
                            ]
                        ],
                        'panel' => [
                            'heading'=>false,
                            'after'=>'<div style="margin-left:20px">{summary}</div>',
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
    //新增banner
    Modal::begin([
        'id' => 'info-modal', //与上面的data-target值保持一致
    ]);
    Modal::end();
?>

<script>
    /*新建商品*/
    $('.create').click(function(){
        showPage($(this));
    });

    /*编辑商品*/
    $('.update').click(function(){
        showPage($(this));
    });

    function showPage(obj){
        $(".modal-body").empty();
        $.get(obj.attr("data-url"),{},
            function(data){
                $(".modal-body").html(data);
            }
        )
    }

</script>