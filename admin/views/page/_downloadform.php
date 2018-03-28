<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var admin\models\CPage $model
 * @var yii\widgets\ActiveForm $form
 */
\yii\web\YiiAsset::register($this);
?>
<style>
.cpage-form{width: 70%;height:500px;margin:0 auto;margin-top:10%;padding-top:10px;border:2px solid #ECECEC;border-radius: 5px;}
.cpage-form a,.page-header h3{font-size: 2rem;}
.cpage-form a i{padding-right: .5rem}
.cpage-form .col-md-2{width: 8%;}
.cpage-form .col-md-10{width: 90%}
.show_code { width: 250px;height: 250px;margin:5% auto;}

</style>

<div class="cpage-form" >

    <?php $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_HORIZONTAL,
        'options' =>[
            'onsubmit' =>'return false;'
        ]
    ]);
    ?>
    <?php

    echo $form->field($model, 'p_url')->textInput()->label('下载地址');
    ?>
    <div style="text-align: center">
        <?=Html::submitButton('生成二维码', ['class' =>'btn btn-primary','onclick'=>'generate_code();']); ?>
        <div class="show_code">
            <?php
                if($model->p_content) {
                    echo "<img src=".Yii::$app->params['base_url'].Yii::$app->params['base_file'].$model->p_content." width='200px' height='200px'>";
                }
            ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<script>
    function generate_code(){
        var code = $('#cpage-p_url').val();
        if(code.length==0){
            $('.field-cpage-p_url .help-block').html('<i style="color:red;">*请填写APP下载地址</i>');
        }else{
            $.post('qrcode',{code:code},function(data){
                if(data.status==200) {
                    $('.show_code').html("<img src="+data.message+" width='200px' height='200px'>");
                }else{
                    layer.msg('您没有权限');
                }
            },'json');
        }
    }
</script>