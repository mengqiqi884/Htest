<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use mdm\admin\AnimateAsset;
use yii\web\YiiAsset;
use yii\helpers\Json;

/**
 * @var yii\web\View $this
 * @var admin\models\CAgent $model
 * @var yii\widgets\ActiveForm $form
 */

AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
    'routes' => $routes
]);
?>


<div class="row">
    <div class="col-sm-12">
        <div class="wrapper wrapper-content animated fadeInUp">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?=$model->isNewRecord ? '新增运营人员':'编辑运营人员'?></h5>
                </div>
                <div class="ibox-content">
                    <?php
                    $form = ActiveForm::begin([
                        'type' => ActiveForm::TYPE_HORIZONTAL,
                        'options' => [
                            'onsubmit' => 'return checkInput();'
                        ]
                    ]);
                    ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'a_name')->textInput()->label('登录名（*）')?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'a_type')->textInput(['value' => '运营人员', 'readonly' => true])?>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div> <!--分割线-->

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'a_newpwd')->passwordInput([
                                'style'=>($model->isNewRecord ? 'display:block':'display:none')])
                                ->label($model->isNewRecord ? '初始密码':false);  //初始密码
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'confirm_password')->passwordInput([
                                'style'=>($model->isNewRecord ? 'display:block':'display:none')])
                                ->label($model->isNewRecord ? '确认密码':false); //确认密码
                            ?>
                        </div>
                    </div>
                    <div class="hr-line-dashed" style="<?=!$model->isNewRecord ? 'display:none':''?>"></div> <!--分割线-->

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'a_position')->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'a_realname')->textInput()->label('姓 名：（*）')?>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div> <!--分割线-->

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'a_phone')->textInput()->label('联系方式（*）') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'a_email')->textInput();?>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div> <!--分割线-->


                    <?php
                    $animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
                    ?>
                    <!--角色-->
                    <input type="hidden" value="<?=\admin\models\Admin::getUserRoleName($model->a_role)?>" name="Admin[a_role]" id="admin-a_role">
                    <div class="form-group field-admin-a_role" style="margin-bottom: 10px;">
                        <label class="control-label col-md-2" for="pvalet-v_diseases_positive">角 色：（必选）</label>
                        <!--左侧-->
                        <div class="col-sm-5" style="width:36.6667%">
                            <div class="input-group">
                                <input class="form-control search" data-target="avaliable" placeholder="搜索">
                                <span class="input-group-btn">
                                    <?= Html::a('<span class="glyphicon glyphicon-refresh"></span>', ['refresh','id'=>$model->a_role], [
                                        'class' => 'btn btn-default',
                                        'id' => 'btn-refresh',
                                        'title' => '刷新'
                                    ]) ?>
                                </span>
                            </div>
                            <!--所有的角色-->
                            <select multiple size="20" class="form-control list" data-target="avaliable" id="roles_list" style="height:250px;"></select>
                        </div>
                        <!--中间按钮-->
                        <div style="width:3%;float:left;">
                            <br><br><br><br><br>
                            <?= Html::a('&gt;&gt;' . $animateIcon, 'javascript:void(0);', [
                                'id'=>'assign',
                                'class' => 'btn btn-success btn-assign',
                                'data-target' => 'avaliable',
                                'data-id'=> $model->isNewRecord?'0':$model->a_id,
                                'title' => '移入'
                            ]) ?><br><br>
                        </div>
                        <!--右侧-->
                        <div class="col-sm-5" style="width:36.6667%;">
                            <input class="form-control search" data-target="assigned" placeholder="搜索">
                            <!--当前的角色-->
                            <select multiple size="20" class="form-control list" data-target="assigned" id="thisrole" style="height:250px;"></select>
                        </div>
                        <div class="help-block"></div>
                    </div>

                    <div style="text-align: center">
                        <?=Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var _opts =<?=$opts?>;

    $("i.glyphicon-refresh-animate").hide();

    function updateRoutes(r) {
        _opts.routes.avaliable = r.avaliable;
        _opts.routes.assigned = r.assigned;
        search("avaliable");
        search("assigned");
    }

    $(".btn-assign").each(function(index){
        $(this).click(function (){
            if(index==0) {  //移入
                var routes = $("#roles_list").find("option:selected").val(); //选中的值
                var ids = $(this).attr("data-id");
                if (routes) {
                    $("#thisrole").find("option").remove();
                    $("#thisrole").append("<option value='" + routes + " '>" + routes + "</option>");
                } else {
                    layer.msg("请选择一个需要的角色");
                }
            }
//            }else{  //移出
//                var len=$("#thisrole").find("option").length;
//                if(len){
//                    layer.msg("请保留一个角色");
//                }else{
//                    var selected=$("#thisrole").find("option:selected").val();
//                    if(selected){
//                        $("#thisrole option:selected").remove();
//                    }else{
//                        layer.msg("请选择一个不需要的角色");
//                    }
//                }
//            }
        })
    });

    $("#btn-refresh").click(function () {
        var $icon = $(this).children("span.glyphicon");
        $icon.addClass("glyphicon-refresh-animate");
        $.post($(this).attr("href"), function (r) {
            updateRoutes(r);
        }).always(function () {
            $icon.removeClass("glyphicon-refresh-animate");
        });
        return false;
    });

    $(".search[data-target]").keyup(function () {
        search($(this).data("target"));
    });


    function search(target) {

        var $list = $('select.list[data-target=' + target + ']');
        $list.html("");
        var q = $('.search[data-target="' + target + '"]').val();
        $.each(_opts.routes[target], function () {
            var r = this;
            if (r.indexOf(q) >= 0) {
                $("<option>").text(r).val(r).appendTo($list);
            }
        });
    }

    search("avaliable");
    search("assigned");

////////////////////////////////////////表单验证 start//////////////////////////////////////////////////////////////////////////
    function checkInput() {
        var account = $('input#admin-a_name').val();
        var position = $('input#admin-a_position').val();

        var phone = $('input#admin-a_phone').val();
        var email = $('input#admin-a_email').val();

        var realname = $('input#admin-a_realname').val();
        var role = $("#thisrole option").map(function(){return $(this).val();}).get().join(", "); //必须传，否则model里的外键会报错;

        var isnew=<?=$model->isNewRecord ? 1 :0?>;
        if(isnew){
            var pwd= $('input#admin-a_newpwd').val();
            var com_pwd= $('input#admin-confirm_password').val();
            if(!checkValue(pwd) ||!checkValue(com_pwd)){
                $('.field-admin-a_newpwd .help-block').html('*请输入密码');
                return false;
            }
            if(pwd!=com_pwd){
                $('.field-admin-confirm_password .help-block').html('*两次密码不一致');
                return false;
            }
        }
        //验证其他输入值
        if (!checkValue(account)) {
            $('.field-admin-a_name .help-block').html('*请填写用户名');
            return false;
        }

        //职务
//        if (!checkValue(position)) {
//            $('.field-admin-a_position .help-block').html('*请填写职务');
//            return false;
//        } else {
//            $('.field-admin-a_position .help-block').html('');
//        }


        //验证手机号
        if (!checkPhone(phone)) {
            $('.field-admin-a_phone .help-block').html('*请输入正确的手机号');
            return false;
        }
        //验证邮箱
        if (email!="" && !/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/.test(email) ) {
            $('.field-admin-a_email .help-block').html('*请输入正确的邮箱');
            return false;
        }


        if (!checkValue(realname)) {
            $('.field-admin-a_realname .help-block').html('*请填写姓名');
            return false;
        }
        if (role=='' || role==',' ) {
            $('.field-admin-a_role .help-block').html('*请选择角色');
            return false;
        }

        $('#admin-a_role').val(role);
        return true;
    }

</script>