<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */
\admin\assets\WarningAsset::register($this);
\admin\assets\TableAsset::register($this);
$this->title = '积分规则';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="icon pull-left"><i class="fa fa-list-ol text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?=Html::encode($this->title)?></h5>
                </div>
                <div class="ibox-content">
                    <?php
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'export' =>false,
                        'options' => ['id'=>'grid'],
                        'columns' => [
                            [
                                'header' =>'序号',
                                'class' => 'yii\grid\SerialColumn'
                            ],
                            [
                                'attribute' => 'fr_item',
                                'value' =>function($data){
                                    return '【'.$data->fr_item.'】';
                                }
                            ],
                            'fr_score',
                            'created_time',
                            [
                                'header' =>'操作',
                                'class' => 'yii\grid\ActionColumn',
                                'template' =>'{update}&nbsp;&nbsp;{delete}',
                                'buttons' => [
                                    'update' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-edit"></i> 编辑','javascript:void(0);', [
                                            'class' =>'btn btn-outline btn-warning btn-sm update',
                                            'id' => 'info',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#info-modal',
                                            'data-id' => $model->fr_id,
                                            'data-url' => $url,
                                        ]);
                                    },

                                    'delete' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-trash"></i> 删除','javascript:void(0);', [
                                            'class' =>'btn btn-outline btn-danger btn-sm delete',
                                            'data-url' => $url,
                                            'onclick' => 'confirm_del(this)'
                                        ]);
                                    }
                                ]
                            ],
                        ],
                        'responsive'=>true,
                        'hover'=>true,
                        'condensed'=>true,
                        'toolbar' => [
                            ['content' => Html::a('<i class="fa fa-plus"></i>新建规则', 'javascript:void(0);', [
                                    'class' => 'btn btn-outline btn-primary create',
                                    'id' => 'info',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#info-modal',
                                    'data-url' => \yii\helpers\Url::toRoute('create'),
                                ])
                            ]
                        ],
                        'panel' => [
                            'heading'=>false,
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
//新增banner
Modal::begin([
    'id' => 'info-modal', //与上面的data-target值保持一致
]);
Modal::end();
?>

<script>
    /*新建积分规则*/
    $('.create').click(function(){
        showPage($(this));
    });

    /*编辑积分规则*/
    $('.update').click(function(){
        showPage($(this));
    });

</script>