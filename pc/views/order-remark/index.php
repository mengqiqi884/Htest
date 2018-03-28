<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = '备注列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?=Html::jsFile('@web/js/plugins/layer/layer.js')?>

<style>
    /*分页*/
    .corderremarks-index #page{position:absolute;right:25px;top:50px;}
</style>
<div class="corderremarks-index">
    <p>
        <?php echo Html::a('<i class="glyphicon glyphicon-plus"></i> 新增备注','#', [
            'class' => 'btn btn-success create',
//            'id' => 'info',
//            'data-toggle' => 'modal',
//            'data-target' => '#info-modal',
            'data-url' => \yii\helpers\Url::toRoute(['create?oid='.$oid]),
        ]) ?>
    </p>
    <div id="w0" class="grid-view">
        <div class="summary" style="height: 50px">
           共<b><?php echo $count;?></b>条数据.
            <!--分页-->
            <div id="page"></div>
        </div>
        <table class="table table-striped table-bordered ">
            <thead>
            <tr>
                <th>序号</th>
                <th>备注者</th>
                <th>备注内容</th>
                <th>备注时间</th>
            </tr>
            </thead>
            <tbody id="remarks"></tbody>
        </table>
    </div>

<script>
    /*备注*/
    $('.create').click(function(){
//        layer.open({
//            type: 2,
//            title:  '新增备注',
//            shadeClose: true,
//            shade: 0.8,
//            maxmin: true, //开启最大化最小化按钮
//            area: ['600px', '300px'],
//            content: $(this).attr('data-url')
//        });

        $.get($(this).attr("data-url"), {},
            function (data) {
                $(".modal-body").html(data);
            }
        );
    });


</script>
