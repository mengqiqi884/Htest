<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\AdminSearch $searchModel
 */

$this->title = '角色列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
//弹出操作成功提示
if (Yii::$app->getSession()->hasFlash('success')) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-success alert-dismissable', //这里是提示框的class
        ],
        'body' => Yii::$app->getSession()->getFlash('success'), //消息体
    ]);
}
//弹出操作失败提示
if (Yii::$app->getSession()->hasFlash('error')) {
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
                    <span class="icon pull-left"><i class="fa fa-fire text-navy"></i>&nbsp;&nbsp;</span>
                    <h5><?=Html::encode($this->title)?></h5>
                </div>
                <div class="ibox-content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'export' =>false,
                        'toggleData' => false,
                        'columns' => [
                            [
                                'header' => '序号',
                                'class' => 'yii\grid\SerialColumn'
                            ],
                            'name',
                            'description',
                            [
                                'attribute' => 'created_at',
                                'format' => 'html',
                                'value' => function($data) {
                                    return date('Y-m-d H:i:s' ,time());
                                }
                            ],
                            [
                                'attribute' =>  'updated_at',
                                'format' => 'html',
                                'value' => function($data) {
                                    return date('Y-m-d H:i:s' ,time());
                                }
                            ],

                            [
                                'header' => '操作',
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{update}',
                                'buttons' => [
                                    'update' => function ($url, $model, $key) {
                                        return Html::a('<i class="fa fa-edit"></i> 编辑', ['modify', 'id'=>$model->i_id], [
                                            'class' => 'btn btn-outline btn-warning btn-sm update',
//                                            'id' => 'info',
//                                            'data-toggle' => 'modal',
//                                            'data-target' => '#info-modal',
//                                            'data-url' => \yii\helpers\Url::toRoute(['modify?id='.$model->i_id])
                                        ]);
                                    }
                                ],
                            ],
                        ],
                        'responsive' => true,
                        'hover' => true,
                        'condensed' => true,
                        'floatHeader'=>false,

                        'panel' => [
                            'heading' => false,
                            'type' => '',
                            'before' => Html::a('<i class="fa fa-refresh"></i> 刷新', ['role-index'], ['class' => 'btn btn-outline btn-default']),
                            'showFooter' => false
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
//弹框
Modal::begin([
    'id' => 'info-modal', //与上面的data-target值保持一致
]);
Modal::end();
?>

<script>

    /*重置密码*/
    $('.reset').click(function () {
        showPage($(this));
    });


//    function showPage(obj) {
//        $.fn.modal.Constructor.prototype.enforceFocus = function () {
//        }; //防止select2无法输入
//        $.get(obj.attr("data-url"), {},
//            function (data) {
//                $(".modal-body").html(data);
//            }
//        );
//    }

</script>
