<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;


/**
 * @var yii\web\View $this
 * @var admin\models\CAgent $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
/*    */
    /*.cagent-form fieldset .row:last-child{position: absolute;left:44%;bottom:64px;width: 30%}*/
    /*.select2-container--open{ z-index: 9999;}*/
    .cagent-form .help-block,.musted{color: #e51717}
</style>

<div class="row">
    <div class="col-sm-12">
        <div class="wrapper wrapper-content animated fadeInUp">
            <div class="ibox float-e-margins col-sm-4">
                <div class=" panel panel-primary">
                    <div class="panel-heading">
                        <?=$model->isNewRecord? '新增4s店':'编辑4s店'?>
                    </div>
                    <div class="panel-body">
                        <h3>注：带 <span class="musted">*</span> 号为必填项！</h3>
                        <div class="hr-line-dashed"></div>

                        <?php
                        $form = ActiveForm::begin([
                            'type'=>ActiveForm::TYPE_HORIZONTAL,
                            'options' =>[
                                'onSubmit' =>'return checkInput();'
                            ]
                        ]);
                        ?>

                        <?= $form->field($model, 'a_account')->textInput()->label('用户名：*'); ?>

                        <?= $form->field($model,'a_brand')->widget(\kartik\widgets\Select2::className(),[
                            'data'=>\admin\models\CCar::GetAllBrand(),
                            'options'=>['placeholder'=>'--请选择--'],
                            'pluginOptions'=>['allowClear'=>true],
                        ])->label('品牌：*'); ?>

                        <div class="hr-line-dashed"></div>

                        <div class="row" style="<?= $model->isNewRecord ? '':'display:none'?>">
                            <?= $form->field($model, 'a_newpwd')->passwordInput([
                                'style'=>($model->isNewRecord ? 'display:block':'display:none')])
                                ->label($model->isNewRecord ? '初始密码：*':false);  //初始密码
                            ?>

                            <?= $form->field($model, 'a_confirmpwd')->passwordInput([
                                'style'=>($model->isNewRecord ? 'display:block':'display:none')])
                                ->label($model->isNewRecord ? '确认密码：*':false); //确认密码
                            ?>
                            <div class="hr-line-dashed"></div>
                        </div>

                        <?= $form->field($model, 'a_concat')->textInput()->label('联系人：*'); ?>

                        <?= $form->field($model, 'a_position')->textInput()->label('职位：*'); ?>
                        <div class="hr-line-dashed"></div>

                        <?= $form->field($model, 'a_phone')->textInput()->label('联系方式：*'); ?>

                        <?= $form->field($model, 'a_email')->textInput()->label('邮箱：*'); ?>
                        <div class="hr-line-dashed"></div>

                        <?= $form->field($model, 'a_address')->textInput()->label('地址：*'); ?>

                        <?= $form->field($model, 'a_name')->textInput()->label('4s店名称：*'); ?>
                        <div class="hr-line-dashed"></div>

                        <div class="row">
                            <label class="control-label col-md-2" for="cagent-a_name">城市选择：*</label>
                            <div class="col-md-10" style="padding-left: 2px!important;">
                                <div class="col-md-4">
                                    <?= $form->field($model,'province')->widget(\kartik\widgets\Select2::className(),[
                                        'data'=>\admin\models\CCity::GetAllProvince(),
                                        'options'=>['placeholder'=>'--省--'],
                                        'pluginOptions'=>['allowClear'=>true],
                                    ])->label(false); ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($model,'city')->widget(\kartik\widgets\DepDrop::className(),[
                                        'type' => \kartik\widgets\DepDrop::TYPE_SELECT2,
                                        'data'=>$model->city===''?[]:\admin\models\CCity::getOwners($model->city),
                                        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                                        'pluginOptions'=>[
                                            'placeholder'=>'--市--',
                                            'depends'=>['cagent-province'],
                                            'url' =>\yii\helpers\Url::toRoute(['orders/child-city']),
                                            'loadingText' => '查找...',
                                        ]
                                    ])->label(false);?>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div style="text-align: center">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function checkInput(){
        var account=$('input#cagent-a_account').val();
        var brand=$('select#cagent-a_brand').find("option:selected").val();
        var concat=$('input#cagent-a_concat').val();
        var position=$('input#cagent-a_position').val();

        var phone=$('input#cagent-a_phone').val();
        var email=$('input#cagent-a_email').val();
        var address=$('input#cagent-a_address').val();
        var name=$('input#cagent-a_name').val();

        var province=$('select#cagent-province').find("option:selected").val();
        var city=$('select#cagent-city').find("option:selected").val();

        var isnew=<?=$model->isNewRecord? 1: 0?>;

        if(isnew){
            var pwd= $('input#cagent-a_newpwd').val();
            var com_pwd= $('input#cagent-a_confirmpwd').val();
            if(!checkValue(pwd) ||!checkValue(com_pwd)){
                $('.field-cagent-a_newpwd .help-block').html('*请输入密码');
                return false;
            }

            if(pwd!=com_pwd){
                $('.field-cagent-a_confirmpwd .help-block').html('*两次密码不一致');
                return false;
            }
        }

        //验证其他输入值
        if(!checkValue(account)) {
            $('.field-cagent-a_account .help-block').html('*请填写用户名');
            return false;
        } else {
            $('.field-cagent-a_account .help-block').html('');
        }

        if(!checkValue(brand)) {
            $('.field-cagent-a_brand .help-block').html('*请选择品牌');
            return false;
        } else {
            $('.field-cagent-a_brand .help-block').html('');
        }

        if(!checkValue(concat)) {
            $('.field-cagent-a_concat .help-block').html('*请填写联系人');
            return false;
        } else {
            $('.field-cagent-a_concat .help-block').html('');
        }

        if(!checkValue(position)) {
            $('.field-cagent-a_position .help-block').html('*请填写职务');
            return false;
        } else {
            $('.field-cagent-a_position .help-block').html('');
        }

        if(!checkValue(address)) {
            $('.field-cagent-a_address .help-block').html('*请填写地址');
            return false;
        } else {
            $('.field-cagent-a_address .help-block').html('');
        }

        if(!checkValue(name)) {
            $('.field-cagent-a_name .help-block').html('*请填写4s店名称');
            return false;
        } else {
            $('.field-cagent-a_name .help-block').html('');
        }

        if(!checkValue(province) || !checkValue(city)) {
            $('.field-cagent-province .help-block').html('*请选择所在城市');
            return false;
        } else {
            $('.field-cagent-province .help-block').html('');
        }

        //验证手机号
        if(!checkPhone(phone)) {
            $('.field-cagent-a_phone .help-block').html('*请输入正确的手机号');
            return false;
        }else{
            $('.field-cagent-a_phone .help-block').html('');
        }
        //验证邮箱
        if(!checkEmail(email)) {
            $('.field-cagent-a_email .help-block').html('*请输入正确的邮箱');
            return false;
        }else{
            $('.field-cagent-a_email .help-block').html('');
        }

        return true;
    }

</script>