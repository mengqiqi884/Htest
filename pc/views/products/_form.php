<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\file\FileInput;
/**
 * @var yii\web\View $this
 * @var admin\models\CProducts $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
    .col-sm-12,.col-sm-4,.col-lg-6,.col-lg-4,.col-lg-3{padding-left: 0!important;padding-right: 0!important;}
</style>


<div class="row">
    <div class="col-sm-12">
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox float-e-margins">
                <div class="ibox-title" style="padding: 14px 5px 7px;">
                    <?php $form = ActiveForm::begin([
                        'type'=>ActiveForm::TYPE_HORIZONTAL,
                        'options' => [
                            'id' =>'products-form',
                            'enctype' => 'multipart/form-data',
                            'onsubmit' =>'return checkInput();'
                        ]
                    ]); ?>

                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'p_name',[
                                'template' => "{label}\n<div class=\"col-md-9\">{input}\n{error}</div>",
                                'labelOptions' => ['class' => 'col-lg-3 control-label'],     //自定义field样式
                            ])->textInput() ?>
                        </div>

                        <div class="col-lg-6">
                            <?= $form->field($model, 'p_sortorder',[
                                'template' => "{label}\n<div class=\"col-md-9\">{input}\n{error}</div>",
                                'labelOptions' => ['class' => 'col-lg-3 control-label'],     //自定义field样式
                            ])->textInput() ?>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'p_month12',[
                                'template' => "{label}\n<div class=\"col-md-9\">{input}\n{error}</div>",
                                'labelOptions' => ['class' => 'col-lg-3 control-label'],     //自定义field样式
                            ])->textInput(['placeholder'=>'例如：2.3'])->hint('%')->label('12期') ?>
                        </div>

                        <div class="col-lg-4">
                            <?= $form->field($model, 'p_month24',[
                                'template' => "{label}\n<div class=\"col-md-9\">{input}\n{error}</div>",
                                'labelOptions' => ['class' => 'col-lg-3 control-label'],     //自定义field样式
                            ])->textInput(['placeholder'=>'例如：2.3'])->hint('%')->label('24期') ?>
                        </div>

                        <div class="col-lg-4">
                            <?= $form->field($model, 'p_month36',[
                                'template' => "{label}\n<div class=\"col-md-9\">{input}\n{error}</div>",
                                'labelOptions' => ['class' => 'col-lg-3 control-label'],     //自定义field样式
                            ])->textInput(['placeholder'=>'例如：2.3'])->hint('%')->label('36期') ?>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <input type="hidden" value="<?=$model->p_content?>" name="CProducts[pic]" class="product_img">
                    <?= $form->field($model, 'p_content[]')->widget(FileInput::className(),[
                        'options'=>[
                            'accept'=>'image/*',
                            'multiple' => true,
                        ],

                        'pluginOptions'=>[
                            'previewFileType' => 'image',
                            'initialPreview' =>$initialPreview,
                            'initialPreviewConfig' =>$initialPreviewConfig,
                            'dropZoneEnabled' => false,//是否显示拖拽区域，默认不写为true，但是会占用很大区域
                            'uploadUrl' => \yii\helpers\Url::toRoute(['upload/upload?type=product']),
                            'uploadAsync' => true,
                            'showUpload'=>false,
                            'showRemove'=>false,
                            'autoReplace'=>true,
                            'minFileCount' => 1,
                            'maxFileCount'=>10,
                            // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
                            'fileActionSettings' => [
                                // 设置具体图片的查看属性为false,默认为true
                                'showZoom' => false,
                                // 设置具体图片的上传属性为true,默认为true
                                'showUpload' => true,
                                // 设置具体图片的移除属性为true,默认为true
                                'showRemove' => true,
                            ],
                        ],
                        #图片上传成功后，调用fileuploaded，将图片路径传给隐藏input[class='product_img'],若上传多张图，则以 “[xxxx],[xxxxxxxx]”格式
                        'pluginEvents' => [
                            'filepredelete' => "function(event, key) {
                        return (!confirm('确认要删除'));
                    }",
                            'fileuploaded' => 'function(event, data, previewId, index) {
                        $("input.product_img").val($("input.product_img").val()+"["+data.response.imageUrl.imgfile+"],");
                         console.log(data.response.imageUrl.imgfile);
                    }',
                            'filedeleted' => 'function(event, key) {
                        var ov=$("input.product_img").val();
                        $("input.product_img").val(ov.replace("["+key+"],",""));
                         console.log(key);
                        return alert("图片已经删除")
                    }',
                        ]
                    ])->hint('可以上传多张图片')?>

                    <div class="hr-line-dashed"></div><!--分割线-->

                    <div class="form-group">
                        <div class="col-sm-4 pull-right">
                            <?=Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> 新增':'<i class="fa fa-edit"></i> 编辑',['class'=>'btn btn-primary']);?>
                            <?=Html::resetButton('<i class="fa fa-trash"></i> 重置',['class'=>'btn btn-white']);?>
                        </div>
                    </div>

                    <?php ActiveForm::end()?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        //移除所有图片
        $('button.fileinput-remove').click(function(){
            //获取上传的隐藏的图片
            var uploaded_pics= $("input.product_img").val();
            if(uploaded_pics.length!=0){
                $("input.product_img").attr("value","");
                $.post('delete-all-pics',{'pics':uploaded_pics},function(data){
//                    if(data.state=='200'){
//                        layer.alert('删除成功');
//                    }
                },'json');
            }
        });
//        //移除指定图片
//        $('.file-drop-zone button.kv-file-remove').each(function(){
//            $(this).click(function(){
//                var upload_img=$(this).parents('.file-thumbnail-footer').prev().find('img');
//                if(upload_img.attr('src').length!=0){
//                    upload_img.attr('src','');
//                }
//            })
//        })
    });

    function checkInput(){
        var pname=$('#cproducts-p_name').val();
        var pmonth12=$('#cproducts-p_month12').val();
        var pmonth24=$('#cproducts-p_month24').val();
        var pmonth36=$('#cproducts-p_month36').val();
        var ppics=$('.product_img').val();
        var psort=$('#cproducts-p_sortorder').val();

        if(pname==null || pname.length==0){
            $('.field-cproducts-p_name .help-block').html('*产品名称不能为空'); return false;
        }
        if(pmonth12==null || pmonth12.length==0){
            $('.field-cproducts-p_month12 .help-block').html('*贷款期数不能为空'); return false;
        }
        if(pmonth24==null || pmonth24.length==0){
            $('.field-cproducts-p_month24 .help-block').html('*贷款期数不能为空'); return false;
        }
        if(pmonth36==null || pmonth36.length==0){
            $('.field-cproducts-p_month36 .help-block').html('*贷款期数不能为空'); return false;
        }
        if(ppics==null || ppics.length==0){
            $('.field-cproducts-p_content .help-block').html('*请上传申请流程图'); return false;
        }
        if(psort==null || psort.length==0){
            $('.field-cproducts-p_sortorder .help-block').html('*产品序号不能为空'); return false;
        }
        return true;
    }
</script>