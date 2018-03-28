<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\SensitiveSearch $searchModel
 */
\admin\assets\WarningAsset::register($this);
$this->title = '敏感词汇列表';
$this->params['breadcrumbs'][] = $this->title;
?>

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
                    <span class="icon pull-left"><i class="fa fa-file text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?=Html::encode($this->title);?></h5>
                </div>
                <div class="ibox-content">
                    <?php
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'export' =>false,
                        'toggleData' => false,
                        'options' =>['id' =>'grid'],
                        'pjax' => true,  //pjax is set to always true for this demo
                        'pjaxSettings' => [
                            'options' => [
                                'id' => 'sensitive_pjax',
                            ],
                            'neverTimeout' => true,
                        ],
                        'columns' => [
                            [
                                'header' => '序号',
                                'width' => '20px',
                                'class' => 'kartik\grid\SerialColumn',

                            ],
                            [
                                'attribute' => 's_name',
                                'width' => '300px'
                            ],

                            [
                                'header' =>'操作',
                                'class' => 'kartik\grid\ActionColumn',

                                'template' =>'{update}&nbsp;&nbsp;{delete}',
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-edit"></i> 编辑','javascript:void(0);', [
                                            'class' => 'btn btn-outline btn-warning btn-sm update',
                                            'id' => 'info',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#info-modal',
                                            'data-url' => $url,
                                        ]);
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-trash"></i> 删除','javascript:void(0);', [
                                            'class' => 'btn btn-outline btn-danger btn-sm delete',
                                            'onclick' => 'confirm_del(this)',
                                            'data-url' => $url,
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
                            'heading'=>false,
                            'type'=>'',
                            'before' => Html::a('<i class="fa fa-plus"></i> 新增敏感词汇', 'javascript:void(0);', [
                                    'class' => 'btn btn-outline btn-success create',
                                    'id' => 'info',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#info-modal',
                                    'data-url' => \yii\helpers\Url::toRoute('create'),
                                ]).'&nbsp;&nbsp;'.Html::a('<i class="glyphicon glyphicon-log-in"></i> Excel导入', 'javascript:void(0);', [
                                    'class' => 'btn btn-outline btn-success export',
                                ]),
                            'after'=>false,
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
    //更新物流
    Modal::begin([
        'id' => 'info-modal', //与上面的data-target值保持一致
    ]);
    Modal::end();
?>

<script>
    $(function () {

        $('.update').click(function(){
            showPage($(this));
        });

        $('.create').click(function(){
            showPage($(this));
        });

        $('.export').click(function(){
            var dialog = layer.open({
                type: 2,
                title: 'Excel导入',
                shadeClose: true,
                shade: 0.8,
                area: ['500px','500px'],
                content: '../sensitive/to-excel' //iframe的url
            });
        });
    });
</script>