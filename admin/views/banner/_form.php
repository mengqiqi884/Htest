<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CBanner $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="row">
    <div class="col-sm-12">
        <div class="wrapper wrapper-content animated fadeInUp">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>新增广告图</h5>
                </div>
                <div class="ibox-content">
                        <?php
                        $form = ActiveForm::begin([
                            'type'=>ActiveForm::TYPE_HORIZONTAL,
                            'options' =>[
                                'id' =>'banner-form',
                                'enctype' => 'multipart/form-data',
                                'onsubmit' =>'return checkInput();'
                            ]
                        ]);
                        ?>
                        <?=$form->field($model, 'b_location')->dropDownList([''=>'--请选择--','1'=>'首页','2'=>'用车报告','3'=>'维修保养'],[$model->isNewRecord?'':'disabled'=>true])?>
                        <div class="hr-line-dashed"></div><!--分割线-->

                        <?=$form->field($model, 'b_title')->textInput()?>
                        <div class="hr-line-dashed"></div><!--分割线-->

                        <?php
                        if($model->isNewRecord){   //新增
                            echo $form->field($model, 'pic')->widget(\kartik\widgets\FileInput::className(),[
                                'options'=>[
                                    'accept'=>'image/*',
                                    'multiple' => true,
                                ],

                                'pluginOptions'=>[
                                    'previewFileType' => 'image',
                                    'initialPreview' =>$initialPreview,
                                    'initialPreviewConfig' =>$initialPreviewConfig,
                                    'dropZoneEnabled' => true,//是否显示拖拽区域，默认不写为true，但是会占用很大区域
                                    'uploadUrl' => \yii\helpers\Url::toRoute(['/upload/upload?type=banner']),
                                    'overwriteInitial'=>false,  //防止覆盖
                                    'uploadAsync' => true,
                                    'showUpload'=>false,
                                    'showRemove'=>false,
                                    'autoReplace'=>false,
                                    // 展示图片区域是否可点击选择多文件
                                    'browseOnZoneClick' => true,
                                    // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
                                    'fileActionSettings' => [
                                        // 设置具体图片的查看属性为false,默认为true
                                        'showZoom' => true,
                                        // 设置具体图片的上传属性为true,默认为true
                                        'showUpload' => true,
                                        // 设置具体图片的移除属性为true,默认为true
                                        'showRemove' => true,
                                    ],
                                    'minFileCount' => 1,
                                    'maxFileCount'=>5,
                                ],
                                #图片上传成功后，调用fileuploaded，将图片路径传给隐藏input[class='product_img'],若上传多张图，则以 “[xxxx],[xxxxxxxx]”格式
                                'pluginEvents' => [
                                    'filepredelete' => "function(event, key) {
                        return (!confirm('确认要删除'));
                    }",
                                    'fileuploaded' => 'function(event, data, previewId, index) {
                        $("input#cbanner-b_img").val($("input#cbanner-b_img").val()+"["+data.response.imageUrl.imgfile+"],");
                         console.log(data.response.imageUrl.imgfile);
                    }',
                                    'filedeleted' => 'function(event, key) {
                        var ov=$("input#cbanner-b_img").val();
                        $("input#cbanner-b_img").val(ov.replace("["+key+"],",""));
                         console.log(key);
                        return alert("图片已经删除")
                    }',
                                ]
                            ])->hint('最多可以上传5张图片（维修保养只显示第一张图片）');

                        }else{   //修改
                            echo $form->field($model, 'b_img')->widget(\kartik\widgets\FileInput::className(),[
                                'options'=>[
                                    'accept'=>'image/*',
                                ],
                                'pluginOptions'=>[
                                    'previewFileType' => 'image',
                                    'initialPreview' =>$initialPreview,
                                    'initialPreviewConfig' =>$initialPreviewConfig,
                                    'uploadUrl' => \yii\helpers\Url::toRoute(['/upload/upload?type=banner']),
                                    'uploadAsync' => true,
                                    'showUpload'=>false,
                                    'showRemove'=>false,
                                    'autoReplace'=>false,
                                    // 展示图片区域是否可点击选择多文件
                                    'browseOnZoneClick' => true,
                                    // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
                                    'fileActionSettings' => [
                                        // 设置具体图片的查看属性为false,默认为true
                                        'showZoom' => true,
                                        // 设置具体图片的上传属性为true,默认为true
                                        'showUpload' => true,
                                        // 设置具体图片的移除属性为true,默认为true
                                        'showRemove' => true,
                                    ],
                                    'minFileCount' => 1,
                                    'maxFileCount'=>1,
                                ],
                                #图片上传成功后，调用fileuploaded，将图片路径传给隐藏input[class='product_img'],若上传多张图，则以 “[xxxx],[xxxxxxxx]”格式
                                'pluginEvents' => [
                                    'filepredelete' => "function(event, key) {
                        return (!confirm('确认要删除'));
                    }",
                                    'fileuploaded' => 'function(event, data, previewId, index) {
                        $("input.banner_img").val(data.response.imageUrl.imgfile);
                         console.log(data.response.imageUrl.imgfile);
                    }',
                                    'filedeleted' => 'function(event, key) {
                        var ov=$("input.banner_img").val();
                        $("input.banner_img").val("");
                         console.log(key);
                        return alert("图片已经删除")
                    }',
                                ]
                            ]);
                        }
                        ?>
                        <?=$form->field($model, 'b_img')->hiddenInput()->label('')?>
                        <div class="hr-line-dashed"></div><!--分割线-->

                        <?=$form->field($model, 'b_url')->textInput()?>
                        <div class="hr-line-dashed"></div><!--分割线-->

                        <?=$form->field($model, 'b_sortorder')->textInput()?>
                        <div class="hr-line-dashed"></div><!--分割线-->

                        <div style="text-align: center">
                            <?=Html::submitButton('确定', ['class' => 'btn btn-primary']);?>
                        </div>
                        <?php
                        ActiveForm::end();
                        ?>
<!--                    </div>-->
                </>
            </div>
        </div>
    </div>
</div>

<script>
    function checkInput(){

        var selected=$('select#cbanner-b_location').val();
        if(selected==''){
            $('.field-cbanner-b_location .help-block').html('*请选择广告图的位置');
            return false;
        }
        var img=$('.banner_img').val();
        if(img.length==0){
            $('.field-cbanner-b_img .help-block').html('*请上传图片');
            return false;
        }else{
            var strs= new Array(); //定义一数组
            strs=img.split(","); //字符分割
            var left_img=$('.field-cbanner-b_img .hint-block label').html();
            if(strs-1>left_img){
                $('.field-cbanner-b_img .help-block').html("*最多只能上传5张");
                return false;
            }
        }
        return true;

    }

    $(function(){
        $('select#cbanner-b_location').change(function(){
            var selected=$(this).val();
            if(selected==null || selected==''){
                layer.msg('请选择广告图的位置');
            }else{
                $.post('../banner/ajax-get-sort',{'location':selected},function(data){
                    if(data.state=='200'){
                        $('input#cbanner-b_sortorder').attr('value',data.message);
                        $('.field-cbanner-b_img .hint-block').html('您已上传'+data.count+'张,还可上传<label>'+(5-data.count)+'</label>张');
                    }
                });
            }
        })
    })
</script>