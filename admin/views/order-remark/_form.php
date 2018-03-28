<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\COrderremarks $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
    .help-block{text-align: left;color: #e51717}
</style>

<div class="corderremarks-form" style="width: 90%;margin:0 auto;text-align: center">

    <?php
    $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_HORIZONTAL,
        'action'=>'javascript:void(0);'
    ]);
    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'or_order_id'=>['type'=> Form::INPUT_HIDDEN, 'options'=>['value'=>$oid],'label'=>false],

            'or_content'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'请输入备注内容', 'maxlength'=>1000,'rows'=>6],'label'=>false],
        ]

    ]);
    ?>
    <div style="text-align: center">
        <?php echo Html::submitButton('提交', ['class' =>'btn btn-primary ok']); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script>
    $('button.ok').bind('click',function() {
        var content=$('#corderremarks-or_content').val();
        var oid=$('#corderremarks-or_order_id').val();

        if(content.length==0){
            $('.field-corderremarks-or_content .help-block').html('*请填写备注内容');return false;
        }
        $.post('ajax-create',{oid:oid,content:content},function(data) {
            if(data.state=='200'){
                var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                parent.layer.close(index); //再执行关闭
            }
        },'json');
    })
</script>