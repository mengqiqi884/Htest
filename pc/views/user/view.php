<?php

use yii\helpers\Html;
use \yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var admin\models\CUser $model
 */

$this->title = $model->u_id;
$this->params['breadcrumbs'][] = ['label' => 'Cusers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    /*头像*/
    .author-people{vertical-align:middle;}
    .author-people img {
        height: 32px;
        width: 32px;
    }
    /*!*预约置换车辆*!*/
    /*.tab-content > .tab-pane {*/
        /*display: block;*/
    /*}*/
    .table-bordered, .table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
        border: none;
        border-bottom: 1px solid #ddd;
    }
</style>

<div class="row">
    <div class="col-sm-9">
        <div class="wrapper wrapper-content animated fadeInUp">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="m-b-md author-people">
                                <img alt="image" class="img-circle" onclick="showImg()" src="<?=empty($model->u_headImg) ? Url::to('@web/img/icons/user.png'):$model->u_headImg?>">
                                <h2><?=$model->u_nickname?></h2>
                            </div>
                            <dl class="dl-horizontal">
                                <dt>状&nbsp;&nbsp;态：</dt>
                                <dd><?=$model->u_state ==1 ? '<i class="fa fa-toggle-on text-success"></i>(启用)':'<i class="fa fa-toggle-off text-danger"></i>(禁用)'?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <dl class="dl-horizontal">
                                <dt>账&nbsp;&nbsp;号：</dt>
                                <dd><?=$model->u_phone?></dd>
                                <dt>性&nbsp;&nbsp;别：</dt>
                                <dd><?=$model->u_sex==1 ?'男':'女'?></dd>
                                <dt>注册时间：</dt>
                                <dd><?=$model->created_time?></dd>
                            </dl>
                        </div>
                        <div class="col-sm-7" id="cluster_info">
                            <dl class="dl-horizontal">
                                <dt>积&nbsp;&nbsp;分：</dt>
                                <dd><?=$model->u_score?></dd>
                                <dt>车辆数：</dt>
                                <dd><?=$model->u_cars?></dd>
                                <dt>帖子数：</dt>
                                <dd><?=$model->u_forums?></dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row m-t-sm">
                        <div class="col-sm-12">
                            <div class="panel blank-panel">
                                <div class="panel-heading">
                                    <div class="panel-options">
                                        <ul class="nav nav-tabs">
                                            <li><a href="<?=Url::toRoute(['user/' . $model->u_id . '#tab-1'])?>" data-toggle="tab">相关车辆</a></li>
                                            <li class=""><a href="<?=Url::toRoute(['user/' . $model->u_id . '#tab-2'])?>" data-toggle="tab">相关置换预约</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab-1">
                                            <div class="feed-activity-list">
                                                <div class="ibox-content" style="border: none">
                                                    <?=\kartik\grid\GridView::widget([
                                                        'dataProvider' => $allcars,
                                                        'summaryOptions'=>['class'=>'hidden'], //关闭总页数
                                                        'export' => false , //不需要导出
                                                        'columns' => [
                                                            [
                                                                'label' => '车型',
                                                                'attribute' => 'r_volume_id',
                                                                'format' => 'html',
                                                                'value' => function($model) {
                                                                    $brand_name = \admin\models\CarSearch::getCarTitle($model->r_brand); //品牌
                                                                    $modal_name = \admin\models\CarSearch::getCarTitle($model->r_car_id); //车系
                                                                    $grande_name = \admin\models\CarSearch::getCarTitle($model->r_volume_id); //车型
                                                                    return '<span class="btn btn-white btn-sm">' . $brand_name . ' - ' . $modal_name . ' - ' . $grande_name . '</span>';
                                                                },
                                                            ],
                                                            'r_cardtime',
                                                            [
                                                                'attribute' => 'r_city',
                                                                'value' => function($model){
                                                                    return \admin\models\CCity::getSelectedCityName($model->r_city);
                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'r_price',
                                                                'value' => function($model){
                                                                    return \common\helpers\StringHelper::change_numbertomillion($model->r_price);
                                                                }
                                                            ],

                                                            [
                                                                'attribute' => 'r_state',
                                                                'format' => 'html',
                                                                'value' => function($model){
                                                                    return '<span class="label label-primary">' .
                                                                    ($model->r_state==1 ? '置换中': ($model->r_state==2 ? '已删除': '已置换' ) ).
                                                                    '</span>';
                                                                }
                                                            ],
                                                            [
                                                                'attribute' =>  'is_forbidden',
                                                                'format' => 'html',
                                                                'value' => function($model){
                                                                    return $model->is_forbidden==0 ? '<i class="fa fa-toggle-on text-success"></i>(启用)' : '<i class="fa fa-toggle-off text-danger"></i>(禁用)';
                                                                }
                                                            ],

                                                            'created_time'
                                                        ],
                                                        'hover'=>true,
                                                        'responsive'=>true,
                                                        'condensed'=>true,
                                                        'floatHeader'=>false,
                                                    ]); ?>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane" id="tab-2">
                                            <div class="ibox-content" style="border: none">
                                                <?=\kartik\grid\GridView::widget([
                                                    'dataProvider' => $allorders,
                                                    'summaryOptions'=>['class'=>'hidden'], //关闭总页数
                                                    'export' => false , //不需要导出
                                                    'columns' => [
                                                        [
                                                            'attribute' => 'o_state',
                                                            'format' => 'html',
                                                            'value' => function($model) {
                                                                return ($model->o_state == 1 ? '<span class="label label-primary"><i class="fa fa-check"></i>预约完成' :
                                                                    ($model->o_state == 2 ? '<span class="label label-danger"><i class="fa fa-times"></i>预约取消</span>':'<span class="label label-default"><i class="fa fa-spinner"></i>预约中</span>'));
                                                            },
                                                        ],
                                                        'o_code',
                                                        [
                                                            'attribute' => 'o_fee',
                                                            'value' => function($model){
                                                                return \common\helpers\StringHelper::change_numbertomillion($model->o_fee);
                                                            }
                                                        ],
                                                        'created_time',
                                                        [
                                                            'class' => 'yii\grid\ActionColumn',
                                                            'header' => '操作',
                                                            'template' => '{view}',
                                                            'buttons' => [
                                                                'view' => function ($url, $model) {
                                                                    return Html::a('<i class="fa fa-eye"></i> 查看', $url, [
                                                                        'title' => Yii::t('app', '查看'),
                                                                        'class' => 'btn btn-outline btn-info',
                                                                    ]);
                                                                }
                                                            ],
                                                        ],
                                                    ],
                                                    'hover'=>true,
                                                    'responsive'=>true,
                                                    'condensed'=>true,
                                                    'floatHeader'=>false,
                                                ]); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?=Url::to('@web/js/content.min.js')?>"></script>