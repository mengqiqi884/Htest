<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CAgent $model
 */

$this->params['breadcrumbs'][] = ['label' => 'Cagents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\admin\assets\FootTableAsset::register($this);
?>

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-sm-6" style="margin: 0 auto;float: none!important;">
            <div class="ibox float-e-margins">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <span class="icon pull-left"><i class="fa fa-fire"></i>&nbsp;&nbsp;</span>
                        <h5>已发布的车型</h5>
                    </div>
                    <div class="panel-body">
                        <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
                            <thead>
                            <tr>

                                <th data-toggle="true">序号</th>
                                <th>车型</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <?php
                            if($model){
                                echo '<tbody>';
                                foreach($model as $car){
                            ?>
                            <tr>
                                <td><?= $car->r_id?></td>
                                <td><?= \admin\models\CarSearch::getUserCar($car->r_brand . '-' . $car->r_car_id . '-' . $car->r_volume_id)?></td>
                                <td>
                                    <?php
                                    $title = '';
                                    $url = '';
                                    $class='';
                                    $data_confirm = '';
                                    switch ($car->r_state) {
                                        case 1:
                                            $title = '置换中';
                                            $url = ['carreplace/delete', 'id' => $car->r_id];
                                            $class = 'btn btn-primary btn-xs';
                                            $data_confirm = '点击”确定“以后，车型下架，将不会前端展示。';
                                            break;
                                        case 2:
                                            $title = '已下架';
                                            $url = ['carreplace/delete', 'id' => $car->r_id];
                                            $class = 'btn btn-danger btn-xs';
                                            $data_confirm = '点击”确定“以后，车型将可以置换，会前端展示。';
                                            break;
                                        case 3:
                                            $title = '已置换';
                                            $url = 'javascript:void(0);';
                                            $class = 'btn btn-default btn-xs';
                                            $data_confirm = '';
                                            break;
                                    }
                                    echo Html::a($title,$url , [
                                        'class' => $class,
                                        'data-confirm' => $data_confirm
                                    ]);
                                    ?>
                                </td>
                            </tr>
                            <?php
                                }
                                echo '</tbody>';
                                echo '<tfoot>' .
                                        '<tr>' .
                                            '<td colspan="5">' .
                                                '<ul class="pagination pull-right"></ul>' .
                                            '</td>' .
                                        '</tr>' .
                                    '</tfoot>';
                            }else{
                            ?>
                                <tbody>
                                <tr><td>暂无数据</td></tr>
                                </tbody>
                            <?php
                            }
                            ?>
                        </table>

<!--                        --><?//=\kartik\grid\GridView::widget([
//                            'dataProvider' => $dataProvider,
//                            'pager' => [
//                                'options'=>['class'=>'hidden'] //关闭分页
//                            ],
//                            'summaryOptions'=>['class'=>'hidden'], //关闭总页数
//                            'options'=>['id'=>'grid'],
//                            'export' => false , //不需要导出
//                            'toggleData' => false, //不需要“全部显示按钮”
//                            'pjax' => true,
//                            'pjaxSettings' => [
//                                'options' => [
//                                    'id' => 'userinfo'
//                                ],
//                                'neverTimeout' => true,
//                            ],
//                            'columns' => [
//                                [
//                                    'label' => '序号',
//                                    'attribute' =>'r_id',
//                                    'width' => '15%',
//                                ],
//                                [
//                                    'label' => '车型',
//                                    'attribute' => 'r_car',
//                                    'headerOptions' => ['width' =>'150'],
//                                    'value' => function($data){
//                                        return \admin\models\CarSearch::getUserCar($data->r_brand . '-' . $data->r_car_id . '-' . $data->r_volume_id);
//                                    }
//                                ],
//                                [
//                                    'class' => 'yii\grid\ActionColumn',
//                                    'header' => '操作',
//                                    'template' => '{view}',
//                                    'buttons' => [
//                                        'view' => function ($url, $model) {
//                                            $title = '';
//                                            $url = '';
//                                            $class='';
//                                            $data_confirm = '';
//                                            switch ($model->r_state) {
//                                                case 1:
//                                                    $title = '置换中';
//                                                    $url = ['carreplace/delete', 'id' => $model->r_id];
//                                                    $class = 'btn btn-primary btn-xs';
//                                                    $data_confirm = '点击”确定“以后，车型下架，将不会前端展示。';
//                                                    break;
//                                                case 2:
//                                                    $title = '已下架';
//                                                    $url = ['carreplace/delete', 'id' => $model->r_id];
//                                                    $class = 'btn btn-danger btn-xs';
//                                                    $data_confirm = '点击”确定“以后，车型将可以置换，会前端展示。';
//                                                    break;
//                                                case 3:
//                                                    $title = '已置换';
//                                                    $url = 'javascript:void(0);';
//                                                    $class = 'btn btn-default btn-xs';
//                                                    $data_confirm = '';
//                                                    break;
//                                            }
//
//                                            return Html::a($title,$url , [
//                                                'class' => $class,
//                                                'data-confirm' => $data_confirm
//                                            ]);
//                                        }
//                                    ],
//                                ],
//                            ],
//                            'hover'=>true,
//                            'responsive'=>true,
//                            'condensed'=>true,
//                            'floatHeader'=>false,
//                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
