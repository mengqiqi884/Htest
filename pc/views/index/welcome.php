<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Modal;
?>
<?=Html::cssFile('@web/css/car/style.css')?>
<style>
    .modal-dialog{width: 1000px;}
</style>
<div class="wrapper wrapper-content">

    <div class="div-wrap clearfix">
        <div class="car-box green clearfix">
            <img src="../../img/zh.png" class="fr" title="账号管理">
            <div class="box-left fl">
                <p>账号管理</p>
                <ul class="clearfix">
                    <li>
                        <span><i class="fa fa-caret-down"></i>新建</span>
                        <a id="info" class="create_operator" href="javascript:void(0);" data-toggle="modal" data-target="#info-modal" data-url=<?=Url::toRoute(['operator/operator-create'],true)?>>运营人员</a>
                        <a id="info" class="create_role" href="javascript:void(0);" data-toggle="modal" data-target="#info-modal" data-url=<?=Url::toRoute(['operator/role-create'],true)?>>角色</a>
                        <a id="info" class="create_agent" href="javascript:void(0);" data-toggle="modal" data-target="#info-modal" data-url=<?=Url::toRoute(['agent/create'],true)?>>4S店</a>
                    </li>
                    <li>
                        <span></span>
                        <a href="<?=Url::toRoute(['operator/operator-index'],true)?>" target="_blank">运营列表</a>
                        <a href="<?=Url::toRoute(['user/index'],true)?>" target="_blank">用户列表</a>
                        <a href="<?=Url::toRoute(['operator/role-index'],true)?>" target="_blank">角色列表</a>
                        <a href="<?=Url::toRoute(['agent/index'],true)?>" target="_blank">4S店列表</a>
                    </li>
                </ul>
            </div>
        </div><!-- car-box -->
        <div class="car-box red clearfix">
            <img src="../../img/xt.png" class="fr" title="系统管理">
            <div class="box-left fl">
                <p>系统管理</p>
                <ul class="clearfix">
                    <li>
                        <a href="<?=Url::toRoute(['car/index'],true)?>" target="_blank">车型管理</a>
                        <a href="<?=Url::toRoute(['banner/index'],true)?>" target="_blank">广告图展示</a>
                        <a href="<?=Url::toRoute(['message/index'],true)?>" target="_blank">消息推送</a>
                        <a href="<?=Url::toRoute(['page/index'],true)?>" target="_blank">用户协议</a>
                    </li>
                </ul>
            </div>
        </div><!-- car-box -->
        <div class="car-box blue clearfix">
            <img src="../../img/yy.png" class="fr" title="置换预约管理">
            <div class="box-left fl">
                <p>置换预约管理</p>
                <ul class="clearfix">
                    <li>
                        <a href="<?=Url::toRoute(['orders/index-ordering'],true)?>" target="_blank">预约中</a>
                        <a href="<?=Url::toRoute(['orders/index-ordered'],true)?>" target="_blank">预约完成</a>
                        <a href="<?=Url::toRoute(['orders/index-order-dismiss'],true)?>" target="_blank">预约取消</a>
                    </li>
                </ul>
            </div>
        </div><!-- car-box -->
        <div class="car-box yellow clearfix">
            <img src="../../img/car.png" class="fr" title="汽车管理">
            <div class="box-left fl">
                <p>汽车管理</p>
                <ul class="clearfix">
                    <li>
                        <a href="<?=Url::toRoute(['carreplace/index'],true)?>" target="_blank">汽车列表</a>
                    </li>
                </ul>
            </div>
        </div><!-- car-box -->
        <div class="car-box pink clearfix">
            <img src="../../img/jr.png" class="fr" title="金融产品管理">
            <div class="box-left fl">
                <p>金融产品管理</p>
                <ul class="clearfix">
                    <li>
                        <a id="info" class="create_product" href="javascript:void(0);" data-toggle="modal" data-target="#info-modal" data-url=<?=Url::toRoute(['products/create'],true)?>>新建金融产品</a>
                        <a href="<?=Url::toRoute(['products/index'],true)?>" target="_blank">产品列表</a>
                    </li>
                </ul>
            </div>
        </div><!-- car-box -->
        <div class="car-box hotyellow clearfix">
            <img src="../../img/sq.png" class="fr" title="社区管理">
            <div class="box-left fl">
                <p>社区管理</p>
                <ul class="clearfix">
                    <li>
                        <a href="<?=Url::toRoute(['score-list/index'],true)?>" target="_blank">积分列表</a>
                        <a href="<?=Url::toRoute(['forum-rule/index'],true)?>" target="_blank">积分规则</a>
                        <a href="<?=Url::toRoute(['goods/index'],true)?>" target="_blank">商品管理</a>
                        <a href="<?=Url::toRoute(['score-log/index'],true)?>" target="_blank">兑换记录</a>
                    </li>
                    <li>
                        <a href="<?=Url::toRoute(['forums/index'],true)?>" target="_blank">帖子列表</a>
                        <a href="<?=Url::toRoute(['sensitive/index'],true)?>" target="_blank">敏感词汇</a>
                    </li>
                </ul>
            </div>
        </div><!-- car-box -->
    </div><!-- div-wrap -->
</div>

<?php
//弹框
Modal::begin([
    'id' => 'info-modal', //与上面的data-target值保持一致
]);
Modal::end();
?>


<script>

    /*新建运营人员*/
    $('.create_operator').click(function () {
        showPage($(this));
    });

    /*新建角色*/
    $('.create_role').click(function () {
        showPage($(this));
    });

    /*新建4s店*/
    $('.create_agent').click(function () {
        showPage($(this));
    });

    /*新建金融产品*/
    $('.create_product').click(function () {
        showPage($(this));
    });

    function showPage(obj) {
        $.fn.modal.Constructor.prototype.enforceFocus = function () {
        }; //防止select2无法输入
        $.get(obj.attr("data-url"), {},
            function (data) {
                $(".modal-body").html(data);
            }
        );
    }

</script>