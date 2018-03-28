<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var admin\models\CGoods $model
 * @var yii\widgets\ActiveForm $form
 */
//\admin\assets\VideoAsset::register($this);
?>
<style>
    /*.cmessage-form form{height:260px;}*/
    /*.cgoods-form table{width:99%;color:#333333;border-collapse: collapse;font-size:14px;margin-left:0;margin-bottom: 10px}*/
    /*.cmessage-form table td{border-width: 1px;padding:8px;border-style: solid;border-color: #666666;}*/
    /*.form-horizontal .control-label{text-align: center}*/
    /*.field-cgoods-g_pic .col-md-2, .field-cgoods-url .col-md-2, .container .col-md-2{width:16%}*/
    /*.field-cgoods-g_pic .col-md-10, .field-cgoods-url .col-md-10, .container .col-md-10{margin-left:-2%;width:78%}*/

    .help-block{color: #e51717}
</style>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading"> <?=Html::encode($this->title)?> </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin([
                        'type'=>ActiveForm::TYPE_HORIZONTAL,
                        'options' => [
                            'id' =>'goods-form',
                            'enctype' => 'multipart/form-data',
                            'onsubmit' =>'return checkInput();'
                        ]
                    ]); ?>

                    <?=$form->field($model, 'g_pic')->hiddenInput()->label(false)?>
                    <?= $form->field($model,'url')->hiddenInput()->label(false);?>


                    <?=$form->field($model, 'g_name')->textInput()->label('商品名称')?>
                    <div class="hr-line-dashed"></div><!--分割线-->

                    <?=$form->field($model, 'g_instroduce')->textInput()->label('介绍')?>
                    <div class="hr-line-dashed"></div><!--分割线-->

                    <?=$form->field($model, 'g_amount')->textInput()->label('库存')?>
                    <div class="hr-line-dashed"></div><!--分割线-->

                    <?=$form->field($model, 'g_score')->textInput()->label('兑换积分数')?>
                    <div class="hr-line-dashed"></div><!--分割线-->

                    <?=$form->field($model,'pic')->widget(\kartik\file\FileInput::className(),[
                        'options'=>[
                            'accept'=>'image/*',
                        ],
                        'pluginOptions'=>[
                            'previewFileType' => 'image',
                            'initialPreview' =>$initialPreview,
                            'initialPreviewConfig' =>$initialPreviewConfig,
                            // 'initialPreviewAsData' => true,
                            'uploadUrl' => \yii\helpers\Url::toRoute(['upload/upload?type=goods']),
                            'uploadAsync' => true,
                            'showUpload'=>false,
                            'showRemove'=>false,
                            'autoReplace'=>true,
                            'dropZoneEnabled' => false,//是否显示拖拽区域，默认不写为true，但是会占用很大区域
                            // 展示图片区域是否可点击选择多文件
                            'browseOnZoneClick' => true,
                            'maxImageWidth' => '100',//限制图片的最大宽度
                            'maxImageHeight' => '100',//限制图片的最大高度
                            'maxFileCount'=>1,
                        ],
                        #图片上传成功后，调用fileuploaded，将图片路径传给隐藏input[class='product_img'],若上传多张图，则以 “[xxxx],[xxxxxxxx]”格式
                        'pluginEvents' => [
                            'filepredelete' => "function(event, key) {
                        return (!confirm('确认要删除'));
                    }",
                            'fileuploaded' => 'function(event, data, previewId, index) {
                        $("input.pic_img").val(data.response.imageUrl.imgfile);
                         console.log(data.response.imageUrl.imgfile);
                    }',
                            'filedeleted' => 'function(event, key) {
                        var ov=$("input.pic_img").val();
                        $("input.pic_img").val(ov.replace(""));
                         console.log(key);
                        return alert("图片已经删除")
                    }',
                        ]
                    ])->label('商品缩略图');
                    ?>
                    <div class="hr-line-dashed"></div><!--分割线-->

                    <label class="control-label col-md-2" for="ffilms-video">视频花絮</label>
                    <div id="ossfile" style="color: red;font-size: 1.6rem;position:absolute;left:20%width: 12%;">你的浏览器不支持flash,Silverlight或者HTML5！</div>
                    <div  id="container" class="col-md-10">
                        <a id="selectfiles" href="javascript:;" class='btn btn-outline btn-default' ><i class="fa fa-film"></i> 选择文件</a>
                        <a id="postfiles" href="javascript:;" class='btn btn-outline btn-primary'><i class="fa fa-upload"></i> 开始上传</a>
                    </div>
                    <pre id="console"></pre>
                    <pre class="hint-block file-error-message col-md-10 pull-right" style="margin-top:10px;padding:2px;"><ul><li>视频大小不超过10mb</li><li>仅支持.MP4,.AVI,.WMV,.RMVB,.MKV格式的视频文件</li></ul></pre>


                    <div class="form-group">
                        <div class="col-sm-4 pull-right">
                            <?=Html::submitButton('<i class="fa fa-save"></i> 保存内容',['class'=>'btn btn-primary','id'=>'save_btn']);?>
                            <?=Html::resetButton('<i class="fa fa-trash"></i> 取消',['class'=>'btn btn-white']);?>
                        </div>
                    </div>

                    <?php ActiveForm::end();?>
                </div>
            </div>
        </div>
    </div>
</div>

<!--直接上传视频至oss的js-->
<?=Html::cssFile('@web/ossupload/style.css')?>
<?=Html::jsFile('@web/ossupload/lib/plupload-2.1.2/js/plupload.full.min.js')?>
<?=Html::jsFile('@web/ossupload/upload.js')?>

<script>
    function checkInput(){
        var gname=$('input#cgoods-g_name').val();
        var gpic=$('input.pic_img').val();
        var score=$('input#cgoods-g_score').val();

        if(gname.length==0){
            $('.field-cgoods-g_name .help-block').html('*商品名称不能为空');return false;
        }
        if(gpic.length==0){
            $('.field-cgoods-g_pic .help-block').html('*请上传商品图片');return false;
        }
        if(score=='' || score==null){
            $('.field-cgoods-g_score .help-block').html('*请填写需要兑换的积分数');return false;
        }
        return true;
    }
</script>