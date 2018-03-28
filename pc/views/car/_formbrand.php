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
<?=Html::jsFile('@web/js/plugins/layer/layer.js')?>
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
                    'action' =>Url::toRoute(['car/ajax-savebrand']),
                    'options'=>[
                        'class' => 'ccar-form',
                        'enctype' => 'multipart/form-data',
                        'onsubmit'=>'return false;',
                    ]
                ]);
                ?>
                <input type="hidden" id="level" value="<?=$level?>">
                <input type="hidden" id="pid" value="<?=$parent?>"> <!--当 添加 时：此参数为父级c_parent,当 编辑 时：此参数为该记录的c_code-->
                <input type="hidden" id="is_new" value="<?=$model->isNewRecord?'1':'0'?>">

                <?= $form->field($model, 'c_title')->textInput()->label('品牌名称');?>

                <div class="hr-line-dashed"></div><!--分割线-->

                <input type='hidden' value='<?=$model->c_logo?>' name='CCar[logo]' class='brand_img'>
                <?= $form->field($model, 'c_logo')->widget(\kartik\file\FileInput::className(),[
                    'options'=>[
                        'accept'=>'image/*',
                    ],
                    'pluginOptions'=>[
                        'previewFileType' => 'image',
                        'initialPreview' =>$initialPreview,
                        'initialPreviewConfig' =>$initialPreviewConfig,
                        'dropZoneEnabled' => false,//是否显示拖拽区域，默认不写为true，但是会占用很大区域
                        'uploadUrl' => \yii\helpers\Url::toRoute(['/upload/upload?type=logo']),
                        // 异步上传需要携带的其他参数，比如产品id等
                        'uploadAsync' => true,
                        'browseOnZoneClick' => true,
                        'showUpload'=>false,
                        'showRemove'=>false,
                        'maxFileCount' => 1,
                    ],
                    #图片上传成功后，调用fileuploaded，将图片路径传给隐藏input[class='product_img'],若上传多张图，则以 “[xxxx],[xxxxxxxx]”格式
                    'pluginEvents' => [
                        'filepredelete' => "function(event, key) {
                                    return (!confirm('确认要删除'));
                                }",
                        'fileuploaded' => 'function(event, data, previewId, index) {
                                    $("input.brand_img").val(data.response.imageUrl.imgfile);
                                    console.log(data.response.imageUrl.imgfile);
                                }',
                        'filedeleted' => 'function(event, key) {
                                    var ov=$("input.brand_img").val();
                                    $("input.brand_img").val("");
                                    console.log(key);
                                    alert("图片已经删除");
                                }',
                    ]
                ])->label('品牌logo');
                ?>
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

            var  index= parent.layer.getFrameIndex(window.name);

            var level=$('#level').val();
            var pid=$('#pid').val();
            var is_new=$('#is_new').val();

            //公共字段
            var title=$('#ccar-c_title').val();
            if(title=='' || title==null || title.length==0){
                layer.msg('*名称不能为空');
                $('.field-ccar-c_title .help-block').html('*名称不能为空'); return false;
            }

            var logo=$('input.brand_img').val();
            if(logo=='' || logo==null || logo.length==0){
                layer.msg('*请上传图片');
                $('.field-ccar-c_logo .help-block').html('*图片不能为空'); return false;
            }
            //需要上传的参数
            var data={'level':level, 'pid':pid,'title':title,'logo':logo,'is_new':is_new}; //json格式

            //加载圈
            ShowLoad();
            $.post($('form#w0').attr('action'),data,function(data){
                parent.layer.close(index); //执行关闭iframe

                if(data.state=='200'){
                    //返回列表页
                    parent.location.href=toRoute('car/index');
                }else{

                    ShowMessage(data.state,'添加失败');
                }
            },'json');
        })
    });
</script>
