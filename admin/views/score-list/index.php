<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\ScoreListSearch $searchModel
 */
\admin\assets\WarningAsset::register($this);
$this->title = '积分列表';
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
                    <span class="icon pull-left"><i class="fa fa-list-ol text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?=Html::encode($this->title)?></h5>
                </div>
                <div class="ibox-content">
                    <?php
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'export' =>false,
                        'pjax' => true,  //pjax is set to always true for this demo
                        'pjaxSettings' => [
                            'options' => [
                                'id' => 'score-list_pjax',
                            ],
                            'neverTimeout' => true,
                        ],
                        'options'=>['id'=>'grid'],
                        'columns' => [
                            [
                                'class' =>'yii\grid\CheckboxColumn',
                                'options' =>[
                                    'width' =>'50px'
                                ]
                            ],
                            [
                                'header'=>'序号',
                                'class' => 'yii\grid\SerialColumn',
                                'options' =>[
                                    'width' =>'50px'
                                ]
                            ],
                            [
                                'label'=>'用户名',
                                'attribute' =>'user_name',
                                'value' =>function($data){
                                    if($data->user){
                                        return $data->user->u_nickname . '【' .$data->user->u_phone . '】';
                                    }else{
                                        return '';
                                    }
                                },
                                'filterType'=>\yii\jui\AutoComplete::className(),
                                'filterWidgetOptions' => [
                                    'clientOptions' => [
                                        'source' => \admin\models\CUser::GetAllUser('nickname'),
                                    ],
                                ]
                            ],
                            [
                                'attribute' => 'sl_rule',
                                'filter' => false
                            ],
                            [
                                'attribute'=>'sl_score',
                                'value' =>function($data){
                                    switch($data->sl_act){
                                        case 'add':$str="+";break;
                                        case 'sub':$str="-";break;
                                        default:$str='有误';break;
                                    }
                                    return $str.$data->sl_score;
                                },
                                'filter' => false
                            ],
                            [
                                'attribute' => 'created_time',
                                'filterType' => GridView::FILTER_DATE_RANGE,
                                'filterWidgetOptions' => [
                                    'language' => 'zh-CN',
                                    'value' => '',
                                    'convertFormat' => true,
                                    'pluginOptions' => [
                                        'locale' => [
                                            'format' => 'Y-m-d',
                                            'separator' => ' to ',
                                        ],
                                        'opens' => 'left'
                                    ],
                                ]
                            ],
                        ],
                        'responsive'=>true,
                        'hover'=>true,
                        'condensed'=>true,
                        'floatHeader'=>false,
                        'toolbar' => [
                            ['content' => Html::a('<i class="fa fa-warning"></i> 批量删除',
                                'javascript:void(0);',[
                                    'class' => 'btn btn-warning gridview',
                                    'title' => \yii\helpers\Url::toRoute(['score-list/ajax-delete-all']),
                                    'onclick' => 'deleteAll(this)'
                                ])]
                        ],
                        'panel' => [
                            'heading'=>false,
//                            'before'=>,
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

<script>
    $(function () {
        $(document).ready(init());
        $(document).on('pjax:complete', function () {
            init();
        });
    });

    function init()
    {

    }

</script>
