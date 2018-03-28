<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CMessage $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<?=Html::cssFile('@web/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css')?>
<style>
    .field-cmessage-m_user_id{display: none;}
    #cmessage-m_user_id{border: 1px solid #e5e6e7;height:40px;width:100%}
    .radio input[type="radio"], .radio-inline input[type="radio"]{margin-top: 4px!important;margin-left: -17px!important;}
</style>

<div class="row">
    <div class="col-sm-12">
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?=Html::encode($this->title)?></h5>
                </div>
                <div class="ibox-content">
                    <div class="well">
                        <h3> <i class="fa fa-tags"></i>备注：</h3>
                        <p class="text-danger">1)当发送对象为“所有用户”时，用户的联系方式可不写；否则，请填写“用户联系方式”</p>
                        <p class="text-danger">2)若对所有用户发送消息，数据可能会过大，慎用！！！</p>
                    </div>

                    <div class="row">
                        <?php
                        $form = ActiveForm::begin([
                            'type'=>ActiveForm::TYPE_HORIZONTAL,
                            'options' => [
                                'onsubmit' =>'return checkInput();'
                            ]
                        ]);
                        ?>

                        <div class="form-group field-cmessage-is_all_user">
                            <label class="control-label col-md-2" for="cmessage-is_all_user">发送对象</label>
                            <div class="col-md-10">
                                <input name="CMessage[is_all_user]" value="" type="hidden">
                                <div id="cmessage-is_all_user">
                                    <div class="radio radio-inline radio-primary">
                                        <input name="CMessage[is_all_user]" value="-1" checked="" data-index="0" type="radio">
                                        <label> 全部用户</label>
                                    </div>
                                    <div class="radio radio-inline radio-primary">
                                        <input name="CMessage[is_all_user]" value="1" data-index="1" type="radio">
                                        <label> 指定用户</label>
                                    </div>
                                </div>
                            </div>
                        </div>
<!--                        --><?//=$form->field($model, 'is_all_user')->textInput()->radioList(['-1'=>'全部用户','1'=>'指定用户'])->label('发送对象')?>

                        <?=$form->field($model, 'm_user_id')->widget(\yii\jui\AutoComplete::className(),[
                            'clientOptions'=>[
                                'source' =>\v1\models\CUser::GetAllUser()
                            ],
                        ])->label('用户账号')?>

                        <?=$form->field($model,'m_content',[
                            'template' => "{label}\n<div class=\"col-md-10\">{input}<div class='hint-block'></div></div>\n{error}\n",
                        ])->textarea(['rows'=>'6'])->label('消息内容')?>

                        <div class="hr-line-dashed"></div><!--分割线-->

                        <div class="form-group">
                            <div class="col-sm-4 pull-right">
                                <?=Html::submitButton('<i class="fa fa-save"></i> 保存内容',['class'=>'btn btn-primary','id'=>'save_btn']);?>
                                <?=Html::resetButton('<i class="fa fa-trash"></i> 取消',['class'=>'btn btn-white']);?>
                            </div>
                        </div>


                        <?php
                            ActiveForm::end();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?=Html::cssFile('@web/css/plugins/iCheck/custom.css')?>
<?//=Html::jsFile('@web/js/plugins/iCheck/icheck.min.js')?>

<script>
    $(function(){
        //单选框的选中样式
//        $(".i-checks").iCheck({radioClass:"iradio_square-green"});

        /*统计textareahai还剩多少个字*/
        //先选出 textarea 和 统计字数 dom 节点
        var textArea = $("textarea#cmessage-m_content");
        var word =$("div.hint-block");
        //调用
        var max =200;
        var curLength;
        textArea[0].setAttribute("maxlength", max);
        curLength = textArea.val().length;

        word.html(curLength+'/'+max);
        textArea.on('input propertychange', function () {
            word.html($(this).val().length+'/'+max);
        });

        //选择发送对象
        $('input:radio').click(function(){

            var v = $(this).val();

            if(v==1){ //指定用户
                $('.field-cmessage-m_user_id').show();
            }else{
                $('.field-cmessage-m_user_id').hide();
            }
        });
    });

    function checkInput(){
        var is_alluser=$('input[name="CMessage[is_all_user]"]:checked').val();
        if(is_alluser==1){ //指定用户
            var user_tel=$('input#cmessage-m_user_id').val();
            if(user_tel.length==0){
                $('.field-cmessage-m_user_id .help-block').html('*请填写用户的联系方式');
                return false;
            }
        }
        var content=$('textarea#cmessage-m_content').val();
        if(content.length==0){
            $('.field-cmessage-m_content .help-block').html('*请填写消息内容');
            return false;
        }
        return true;
    }

</script>