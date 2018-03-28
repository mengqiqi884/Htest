<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CForumRule $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
    #cforumrule-fr_score {width:90px;margin: 0 2px;float: left}
</style>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading"> <?=Html::encode($this->title)?> </div>
                <div class="panel-body">
                    <?php
                    $form = ActiveForm::begin([
                        'type'=>ActiveForm::TYPE_HORIZONTAL,
                        //验证场景规则
                        'enableAjaxValidation' => true,
                        'validationUrl'=>\yii\helpers\Url::toRoute(['valid-form','id'=>empty($model['fr_id'])?0:$model['fr_id']]),
                    ]);
                    ?>

<!--                    --><?//= $form->field($model, 'fr_fup')->widget(\kartik\widgets\Select2::className(),[
//                        'data' => \admin\models\CForumForum::GetForumsName(),
//                        'options' => ['prompt' => '--请选择--']
//                    ]);?>

                    <?= $form->field($model,'fr_item')->textInput(['placeholder'=>'例如：发表主题'])->label();?>
                    <div class="hr-line-dashed"></div><!--分割线-->

                    <?= $form->field($model, 'fr_score',[
                        'template' => '{label}' .
                                        '<div class="col-md-10"><a class="btn btn-default pull-left" onmouseup="sub()" onmousedown="sub()">-</a>' .
                                      '{input}' .
                                        '<a class="btn btn-default" onmouseup="add()" onmousedown="add()">+</a></div>'
                    ])->textInput(['value'=>'2'])->label();?>
                    <div class="hr-line-dashed"></div><!--分割线-->

                    <div class="form-group">
                        <div class="col-sm-6 pull-right">
                            <?=Html::submitButton('<i class="fa fa-save"></i> 保存内容',['class'=> 'btn btn-primary','id'=>'save_btn']);?>
                            <?=Html::resetButton('<i class="fa fa-trash"></i> 取消',['class'=>'btn btn-white']);?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var score_input = $('#cforumrule-fr_score');

    function sub()
    {
        var num = score_input.val();
        if(num>1){
           score_input.attr('value',parseInt(num)-1);
        }else{
           score_input.attr('value',num);
        }
    }
    function add()
    {
        var num = score_input.val();
        score_input.attr('value', parseInt(num)+1);
    }
</script>