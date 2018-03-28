<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CAgent $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<?=Html::jsFile('@web/js/wine/wine.js')?>
<style>
    .help-block{color: #e51717;}
</style>

<div class="cagent-form">

    <?php
    $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_HORIZONTAL,
        'options' =>[
            'onSubmit'=>'return checkInput();'
        ],
    ]);
    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'a_newpwd'=>['type'=> Form::INPUT_PASSWORD, 'options'=>['maxlength'=>32],'label' =>'新密码'],

            'a_confirmpwd'=>['type'=> Form::INPUT_PASSWORD, 'options'=>['maxlength'=>32],'label' =>'确认密码'],

        ]

    ]);

    echo "<div style='text-align: center'>";
    echo Html::submitButton('保存', ['class' =>'btn btn-primary']);
    echo "</div>";
    ActiveForm::end(); ?>

</div>
<script>

    function checkInput(){
        var pwd_new=$('input#cagent-a_newpwd').val();
        var pwd_con=$('input#cagent-a_confirmpwd').val();

        if(!checkPassword(pwd_new)){
            $('.field-cagent-a_newpwd .help-block').html('*密码不能为空且只能为英文或者数字的6~15个字符');
            return false;
        }else {
            $('.field-cagent-a_newpwd .help-block').html('');
        }

        if(pwd_new.length==0 || pwd_new=='' || pwd_new==null) {
            $('.field-cagent-a_newpwd .help-block').html('*请输入新密码');
            return false;
        }else {
            $('.field-cagent-a_newpwd .help-block').html('');
        }

        if(pwd_con.length==0 || pwd_con=='' || pwd_con==null) {
            $('.field-cagent-a_confirmpwd .help-block').html('*请输入确认密码');
            return false;
        }else {
            $('.field-cagent-a_confirmpwd .help-block').html('');
        }

        if(pwd_con!=pwd_new) {
            $('.field-cagent-a_confirmpwd .help-block').html('*两次密码不一致');
            return false;
        }else {
            $('.field-cagent-a_confirmpwd .help-block').html('');
        }

        return true;
    }
</script>