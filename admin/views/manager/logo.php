<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<?=Html::cssFile('@web/css/logo.css')?>
<script type="text/javascript" src="<?=Url::to('@web/js/wine/wine.js?_'.time()) ?>"></script>
<script type="text/javascript" src="<?=Url::to('@web/js/upload.js?_'.time()) ?>"></script>
<div class="wrapper wrapper-content">
<div class="container">
    <div class="imageBox">
        <div class="thumbBox"></div>
        <div class="spinner" style="display: none">请选择图像</div>
    </div>
    <div class="action">
        <!-- <input type="file" id="file" style=" width: 200px">-->
        <div class="new-contentarea tc">
            <a href="javascript:void(0)" class="upload-img">
                <label for="upload-file">选择头像</label>
            </a>
            <input type="file" style="cursor: pointer" name="upload-file" id="upload-file" />
        </div>
        <input type="button" id="btnCrop"  class="Btnsty_peyton" value="裁切">
        <input type="button" id="btnZoomIn" class="Btnsty_peyton" value="+"  >
        <input type="button" id="btnZoomOut" class="Btnsty_peyton" value="-" >
    </div>
    <div class="cropped"></div>
</div>
</div>
<script type="text/javascript">
    $(window).load(function() {
        photo="<?=$logo ?>";
        var options =
        {
            thumbBox: '.thumbBox',
            spinner: '.spinner',
            imgSrc: photo
        }
        var cropper = $('.imageBox').cropbox(options);
        $('#upload-file').on('change', function(){
            $('#btnCrop').removeAttr('disabled');
            $('#btnZoomIn').removeAttr('disabled');
            $('#btnZoomOut').removeAttr('disabled');
            var reader = new FileReader();
            reader.onload = function(e) {
                options.imgSrc = e.target.result;
                cropper = $('.imageBox').cropbox(options);
            }
            reader.readAsDataURL(this.files[0]);
            this.files = [];
        });
        $('#btnCrop').on('click', function(){
            var img = cropper.getDataURL();
            $('.cropped').html('<p style="font-size: 15px;color: #0e9aef ">请选择您的头像</p>');
            $('.cropped').append('<img id="img1" onclick="Upload(this)" src="'+img+'" align="absmiddle" style="cursor:pointer;width:64px;margin-top:4px;border-radius:64px;box-shadow:0px 0px 12px #7E7E7E;" ><p>64px*64px</p>');
            $('.cropped').append('<img id="img2" onclick="Upload(this)" src="'+img+'" align="absmiddle" style="cursor:pointer;width:128px;margin-top:4px;border-radius:128px;box-shadow:0px 0px 12px #7E7E7E;"><p>128px*128px</p>');
            $('.cropped').append('<img id="img3" onclick="Upload(this)" src="'+img+'" align="absmiddle" style="cursor:pointer;width:180px;margin-top:4px;border-radius:180px;box-shadow:0px 0px 12px #7E7E7E;"><p>180px*180px</p>');
        });
        $('#btnZoomIn').on('click', function(){
            cropper.zoomIn();
        });
        $('#btnZoomOut').on('click', function(){
            cropper.zoomOut();
        });
        if(photo==''){
            $('#btnCrop').attr('disabled',true);
            $('#btnZoomIn').attr('disabled',true);
            $('#btnZoomOut').attr('disabled',true);
        }else {
            $('#btnCrop').removeAttr('disabled');
            $('#btnZoomIn').removeAttr('disabled');
            $('#btnZoomOut').removeAttr('disabled');
        }
    });
</script>
<div style="text-align:center;">
</div>