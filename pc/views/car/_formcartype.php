<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var admin\models\CCar $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<?=Html::jsFile('@web/js/wine/wine.js')?>
<style>
    .help-block{color: #e51717;}
</style>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox-title">
                <?php
                $form = ActiveForm::begin([
                    'type'=>ActiveForm::TYPE_HORIZONTAL,
                    'action' =>Url::toRoute(['car/ajax-savecartype']),
                    'options'=>[
                        'class' => 'ccar-form',
                        'enctype' => 'multipart/form-data',
                        'onsubmit'=>'return false;',
                    ]
                ]);
                ?>
                <input type="hidden" id="level" value="<?=$level?>">
                <input type="hidden" id="pid" value="<?=$parent?>"><!--当 添加 时：此参数为父级c_parent,当 编辑 时：此参数为该记录的c_code-->
                <input type="hidden" id="is_new" value="<?=$model->isNewRecord?'1':'0'?>">

                <div class="row">
                    <div class="col-lg-6">
                        <?=$form->field($model, 'c_title',[
                            'template' => "{label}\n<div class=\"col-md-9\">{input}\n{error}</div>",
                            'labelOptions' => ['class' => 'col-lg-3 control-label'],
                        ])->textInput()->label('车型名称');?>
                    </div>
                    <div class="col-lg-6">
                        <?=$form->field($model, 'c_price',[
                            'template' => "{label}\n<div class=\"col-md-9\">{input}\n{error}</div>",
                            'labelOptions' => ['class' => 'col-lg-3 control-label'],
                        ])->textInput()->hint('元')->label('指导价');?>
                    </div>
                </div>

                <div class="hr-line-dashed"></div><!--分割线-->

                <?=$form->field($model, 'c_sortorder')->textInput()->label('排序(正序)');?>

                <div class="hr-line-dashed"></div><!--分割线-->

                <div class="form-group">
                    <div class="col-sm-4 pull-right">
                        <?=Html::submitButton('<i class="fa fa-save"></i> 保存内容',['class'=>'btn btn-primary','id'=>'save_btn']);?>
                        <?=Html::resetButton('<i class="fa fa-trash"></i> 取消',['class'=>'btn btn-white']);?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>


<script>
    $(function(){
        $('.ccar-form button#save_btn').click(function(){

            var  index= parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引

            var level=$('#level').val();
            var pid=$('#pid').val();
            var is_new=$('#is_new').val();

            //公共字段
            var title=$('#ccar-c_title').val();
            if(title=='' || title==null || title.length==0){
                layer.msg('*名称不能为空');
                $('.field-ccar-c_title .hepl-block').html('*名称不能为空'); return false;
            }

            var c_sort=$('#ccar-c_sortorder').val();
            if(c_sort=='' || c_sort==null || c_sort.length==0){
                layer.msg('*排序不能为空');
                $('.field-ccar-c_sortorder .hepl-block').html('*排序不能为空'); return false;
            }

            var c_price=$('#ccar-c_price').val();
            if(c_price=='' || c_price==null || c_price.length==0){
                layer.msg('*指导价不能为空');
                $('.field-ccar-c_price .hepl-block').html('*指导价不能为空'); return false;
            }
            //需要上传的参数
            var data={'level':level, 'pid':pid,'title':title,'sort':c_sort,'price':c_price,'is_new':is_new};

            //加载圈
            ShowLoad();
            $.post($('form#w0').attr('action'),data,function(data){

                if(data.state=='200'){
                    //返回列表页
                    parent.location.href=toRoute('car/index');
                }else{
                    parent.layer.close(index); //执行关闭iframe
                    ShowMessage(data.state,'添加失败');
                }
            },'json');
        })
    });
</script>
