<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

/**
 * @var yii\web\View $this
 * @var admin\models\CPartner $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="cpartner-form" style="margin:0 auto;">

    <?php
    $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_HORIZONTAL,
        'options'=>[
            'enctype' => 'multipart/form-data',
            'onsubmit' => 'return checkImg();'
        ]
    ]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'columnSize' => 'sm',
        'attributes' => [
            'p_url' => ['type' => Form::INPUT_TEXT, 'options' => ['maxlength' => 200]],
            'color_logo' => [
                'label' =>'彩色图片(尺寸1:1)',
                'type' => Form::INPUT_WIDGET, 'widgetClass' => \kartik\file\FileInput::className(),
                'options' => [
                    'options' => [
                        'accept' => 'image/*',
                        'showUpload' => false,
                        'showRemove' => false,
                    ],
                    'pluginOptions' => [
                        'initialPreview' =>$initialPreview,
                        'initialPreviewConfig' =>[],
                        'uploadUrl' => \yii\helpers\Url::toRoute(['/upload/upload?type=partner_colorlogo']),
                        // 异步上传需要携带的其他参数，比如产品id等
                        'uploadAsync' => true,
                        'browseOnZoneClick' => true,
                        'showUpload'=>true,
                        'showRemove'=>false,
                        'maxFileCount' => 1,
                        // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
                        'fileActionSettings' => [
                            // 设置具体图片的查看属性为false,默认为true
                            'showZoom' => false,
                            // 设置具体图片的上传属性为true,默认为true
                            'showUpload' => false,
                            // 设置具体图片的移除属性为true,默认为true
                            'showRemove' => false,
                        ],
                    ],
                    'pluginEvents' => [
                        'fileuploaderror' => "function(object,data){
                                                         $('.fileinput-upload-button').attr('disabled',true);
                                                        }",
                        'fileerror' => "function(object,data){
                                                         $('.fileinput-upload-button').attr('disabled',true);
                                                        }",
                        'fileclear' => "function(){
                                                        $('.fileinput-upload-button').attr('disabled',false);
                                            $('#cpartner-p_colorlogo').val('');
                                            }",
                        'fileuploaded' => "function (object,data){
                                        $('#cpartner-p_colorlogo').val(data.response.imageUrl.imgfile);
                                    }",
                        //错误的冗余机制
                        'error' => "function (){
                                        alert('data.error');
                                    }"
                    ]
                ]
            ],
            'dark_logo' => [
                'label' =>'灰白图片(尺寸1:1)',
                'type' => Form::INPUT_WIDGET, 'widgetClass' => \kartik\file\FileInput::className(),
                'options' => [
                    'options' => [
                        'accept' => 'image/*',
                        'showUpload' => false,
                        'showRemove' => false,
                    ],
                    'pluginOptions' => [
                        'initialPreview' =>$initialPreview2,
                        'initialPreviewConfig' =>[],
                        'uploadUrl' => \yii\helpers\Url::toRoute(['/upload/upload?type=partner_darklogo']),
                        // 异步上传需要携带的其他参数，比如产品id等
                        'uploadAsync' => true,
                        'browseOnZoneClick' => true,
                        'showUpload'=>true,
                        'showRemove'=>false,
                        'maxFileCount' => 1,
                        // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
                        'fileActionSettings' => [
                            // 设置具体图片的查看属性为false,默认为true
                            'showZoom' => false,
                            // 设置具体图片的上传属性为true,默认为true
                            'showUpload' => false,
                            // 设置具体图片的移除属性为true,默认为true
                            'showRemove' => false,
                        ],
                    ],
                    'pluginEvents' => [
                        'fileuploaderror' => "function(object,data){
                                                         $('.fileinput-upload-button').attr('disabled',true);
                                                        }",
                        'fileerror' => "function(object,data){
                                                         $('.fileinput-upload-button').attr('disabled',true);
                                                        }",
                        'fileclear' => "function(){
                                                        $('.fileinput-upload-button').attr('disabled',false);
                                            $('#cpartner-p_darklogo').val('');
                                            }",
                        'fileuploaded' => "function (object,data){
                                        $('#cpartner-p_darklogo').val(data.response.imageUrl.imgfile);
                                    }",
                        //错误的冗余机制
                        'error' => "function (){
                                        alert('data.error');
                                    }"
                    ]
                ]
            ],
        ]
    ]);
    echo $form->field($model, 'p_colorlogo')->hiddenInput()->label(false);
    echo $form->field($model, 'p_darklogo')->hiddenInput()->label(false);
    echo "<div style='text-align: center'>";
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo "</div>";
    ActiveForm::end(); ?>

</div>
<script>
    function checkImg(){
        var p_logo1 = $('#cpartner-p_colorlogo').val();
        if(p_logo1.length==0){
            $('.field-cpartner-p_colorlogo div.help-block').html('<i style="color:red">*请上传合作伙伴图片(彩色)</i>');
            return false;
        }
        var p_logo2 = $('#cpartner-p_darklogo').val();
        if(p_logo2.length==0){
            $('.field-cpartner-p_darklogo div.help-block').html('<i style="color:red">*请上传合作伙伴图片(灰白)</i>');
            return false;
        }
        return true;
    }
</script>