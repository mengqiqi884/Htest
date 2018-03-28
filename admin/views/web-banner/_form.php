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
<style>
    .cbanner-form{margin-top: 10px;}
    .cbanner-form .help-block,.hint-block{color: #e51717}
</style>
<div class="cbanner-form">

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

    <?=$form->field($model, 'type')->dropDownList([''=>'--请选择--','1'=>'首页','2'=>'社区'],[$model->isNewRecord?'':'disabled'=>true])->label('类型选择')?>

    <?=$form->field($model, 'pic')->hiddenInput()->label(false) ?>
    <?php
    if($model->isNewRecord){   //新增
        echo $form->field($model, 'p_img')->widget(\kartik\widgets\FileInput::className(),[
            'options'=>[
                'accept'=>'image/*',
                'multiple' => true,
            ],

            'pluginOptions'=>[
                'previewFileType' => 'image',
                'initialPreview' =>'',
                'initialPreviewConfig' =>['width'=>'150px'],
                // 'initialPreviewAsData' => true,
                'uploadUrl' => \yii\helpers\Url::toRoute(['/upload/upload?type=pc_banner']),
                'overwriteInitial'=>false,  //防止覆盖
                'uploadAsync' => true,
                'showUpload'=>true,
                'showRemove'=>true,
                'autoReplace'=>false,
                // 展示图片区域是否可点击选择多文件
                'browseOnZoneClick' => true,
                // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
                'fileActionSettings' => [
                    // 设置具体图片的查看属性为false,默认为true
                    'showZoom' => true,
                    // 设置具体图片的上传属性为true,默认为true
                    'showUpload' => false,
                    // 设置具体图片的移除属性为true,默认为true
                    'showRemove' => false,
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
                        $("input#cpcbanner-pic").val($("input#cpcbanner-pic").val()+"["+data.response.imageUrl.imgfile+"],");
                         console.log(data.response.imageUrl.imgfile);
                    }',
                'filedeleted' => 'function(event, key) {
                        var ov=$("input#cpcbanner-pic").val();
                        $("input#cpcbanner-pic").val(ov.replace("["+key+"],",""));
                         console.log(key);
                        return alert("图片已经删除")
                    }',
            ]
        ])->label('图片')->hint('注：最多可以上传5张图片<br> 1.首页图片比例 60：11； <br> 2.社区图片比例 2:1');

    }else{   //修改
        echo $form->field($model, 'p_img')->widget(\kartik\widgets\FileInput::className(),[
            'options'=>[
                'accept'=>'image/*',
            ],

            'pluginOptions'=>[
                'previewFileType' => 'image',
                'initialPreview' =>$initialPreview,
                'initialPreviewConfig' =>['width'=>'150px'],
                // 'initialPreviewAsData' => true,
                'uploadUrl' => \yii\helpers\Url::toRoute(['/upload/upload?type=pc_banner']),
                'uploadAsync' => true,
                'showUpload'=>true,
                'showRemove'=>true,
                'autoReplace'=>false,
                // 展示图片区域是否可点击选择多文件
                'browseOnZoneClick' => true,
                // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
                'fileActionSettings' => [
                    // 设置具体图片的查看属性为false,默认为true
                    'showZoom' => true,
                    // 设置具体图片的上传属性为true,默认为true
                    'showUpload' => false,
                    // 设置具体图片的移除属性为true,默认为true
                    'showRemove' => false,
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
                        $("input#cpcbanner-pic").val(data.response.imageUrl.imgfile);
                         console.log(data.response.imageUrl.imgfile);
                    }',
                'filedeleted' => 'function(event, key) {
                        var ov=$("input#cpcbanner-pic").val();
                        $("input#cpcbanner-pic").val("");
                         console.log(key);
                        return alert("图片已经删除")
                    }',
            ]
        ])->label('图片')->hint('注：<br> 1.首页图片比例 60：11； <br> 2.社区图片比例？');
    }
    ?>


    <?=$form->field($model, 'url')->textInput()->label('图片链接')?>

    <div style="text-align: center">
        <?=Html::submitButton('确定', ['class' => 'btn btn-primary']);?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script>
    function checkInput(){

        var selected=$('select#cpcbanner-type').val();
        if(selected==''){
            $('.field-cpcbanner-type .help-block').html('*请选择广告图的位置');
            return false;
        }
        var img=$('#cpcbanner-pic').val();
        if(img.length==0){
            $('.field-cpcbanner-pic .help-block').html('*请上传图片');
            return false;
        }else{
            var strs= new Array(); //定义一数组
            strs=img.split(","); //字符分割
            var left_img=$('.field-cpcbanner-pic .hint-block label').html();
            if(strs-1>left_img){
                $('.field-cpcbanner-pic .help-block').html("*最多只能上传5张");
                return false;
            }
        }
        return true;

    }

    $(function(){
        $('select#cpcbanner-type').change(function(){
            var selected=$(this).val();
            if(selected==null || selected==''){
                layer.msg('请选择广告图的位置');
            }else{
                $.post('../web-banner/ajax-get-sort',{'location':selected},function(data){
                    if(data.state=='200'){
                        $('input#cbanner-b_sortorder').attr('value',data.message);
                        $('.field-cbanner-b_img .hint-block').html('您已上传'+data.count+'张,还可上传<label>'+(5-data.count)+'</label>张');
                    }
                });
            }
        })
    })
</script>