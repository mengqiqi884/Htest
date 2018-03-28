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
<?= Html::jsFile('@web/js/wine/wine.js') ?>
<style>
    .help-block {
        color: #e51717;
    }
</style>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox-title">
                <?php
                $form = ActiveForm::begin([
                    'type' => ActiveForm::TYPE_HORIZONTAL,
                    'action' => Url::toRoute(['car/ajax-savecarxi']),
                    'options' => [
                        'class' => 'ccar-form',
                        'enctype' => 'multipart/form-data',
                        'onsubmit' => 'return false;',
                    ]
                ]);
                ?>
                <input type="hidden" id="level" value="<?= $level ?>">
                <input type="hidden" id="pid" value="<?= $parent ?>"><!--当 添加 时：此参数为父级c_parent,当 编辑 时：此参数为该记录的c_code-->
                <input type="hidden" id="is_new" value="<?= $model->isNewRecord ? '1' : '0' ?>">

                <!--上-->
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, 'c_title',[
                            'template' => "{label}\n<div class=\"col-md-9\">{input}\n{error}</div>",
                            'labelOptions' => ['class' => 'col-lg-3 control-label'],     //自定义field样式
                        ])->textInput() ?>
                    </div>
                    <div class="col-lg-6">
                        <input type='hidden' value='<?= $model->c_logo ?>' name='CCar[logo]' class='brand_img'>
                        <?= $form->field($model, 'c_logo',[
                            'template' => "{label}\n<div class=\"col-md-9\">{input}\n{error}</div>",
                            'labelOptions' => ['class' => 'col-lg-3 control-label'],     //自定义field样式
                        ])->widget(\kartik\file\FileInput::className(), [
                            'options' => [
                            ],
                            'pluginOptions' => [
                                'previewFileType' => 'image',
                                'initialPreview' => $initialPreview,
                                'initialPreviewConfig' => $initialPreviewConfig,
                                'uploadUrl' => \yii\helpers\Url::toRoute(['upload/upload?type=logo']),
                                // 异步上传需要携带的其他参数，比如产品id等
                                'uploadAsync' => true,
                                'autoReplace' => true,
                                'browseOnZoneClick' => true,
                                'dropZoneEnabled' => false,//是否显示拖拽区域，默认不写为true，但是会占用很大区域
                                'showUpload' => false,
                                'showRemove' => false,
                                'maxImageWidth' => '100',//限制图片的最大宽度
                                'maxImageHeight' => '100',//限制图片的最大高度
                                'maxFileCount' => 1,
                                'previewFileIcon' => "<i class='glyphicon glyphicon-king'></i>",
                                'msgFilesTooMany' => "选择上传的文件数量({n}) 超过允许的最大数值{m}！",
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
                                return alert("图片已经删除")
                            }',
                            ]
                        ])->label('车系logo');
                        ?>
                    </div>
                </div>

                <!--一行显示多个field-->
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, 'c_engine',[
                            'template' => "{label}\n<div class=\"col-md-9\">{input}\n{error}</div>",
                            'labelOptions' => ['class' => 'col-lg-3 control-label'],     //自定义field样式
                        ])->textInput(['placeholder'=>'例如：2.0T 190马力 L4'])->label('发动机')?>
                    </div>
                    <div class="col-lg-6">
                        <?=$form->field($model, 'c_volume',[
                            'template' => "{label}\n<div class=\"col-md-9\">{input}\n{error}</div>",
                            'labelOptions' => ['class' => 'col-lg-3 control-label'],
                        ])->textInput(['placeholder'=>'例如：4818*1843*1432'])->label('长*宽*高');?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <?=$form->field($model, 'c_sortorder',[
                            'template' => "{label}\n<div class=\"col-md-9\">{input}\n{error}</div>",
                            'labelOptions' => ['class' => 'col-lg-3 control-label'],
                        ])->textInput()->label('排序');?>
                    </div>
                    <div class="col-lg-6">
                        <?=$form->field($model, 'c_type',[
                            'template' => "{label}\n<div class=\"col-md-9\">{input}\n{error}</div>",
                            'labelOptions' => ['class' => 'col-lg-3 control-label'],
                        ])->dropDownList(['1' => '轿车', '2' => '跑车', '3' => 'SUV','4' =>'MPV','5' =>'商用车'],['prompt'=>'--请选择--'])->label('车辆类型');?>
                    </div>
                </div>

                <!--分割线-->
                <div class="hr-line-dashed"></div>

                <input type='hidden' value='<?= $model->c_imgoutside ?><!--' name='CCar[outimg]' class='out_img'>
                <?= $form->field($model, 'c_imgoutside[]')->widget(\kartik\file\FileInput::className(), [
                    'options' => [
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'previewFileType' => 'image',
                        'initialPreview' => $initialPreview1,
                        'initialPreviewConfig' => $initialPreviewConfig1,
                        'overwriteInitial' => false,  //防止覆盖
                        'uploadUrl' => \yii\helpers\Url::toRoute(['upload/upload?type=out']),
                        // 异步上传需要携带的其他参数，比如产品id等
                        'uploadAsync' => true,
                        'browseOnZoneClick' => true,
                        'showUpload' => false,
                        'showRemove' => false,
                        'minFileCount' => 1,
                        'maxFileCount' => 2,
                        'previewFileIcon' => "<i class='glyphicon glyphicon-king'></i>",
                        'msgFilesTooMany' => "选择上传的文件数量({n}) 超过允许的最大数值{m}！",
                        'allowedFileExtensions'=> ['jpg', 'png','gif'],//接收的文件后缀
                    ],
                    #图片上传成功后，调用fileuploaded，将图片路径传给隐藏input[class='product_img'],若上传多张图，则以 “[xxxx],[xxxxxxxx]”格式
                    'pluginEvents' => [
                        'filepredelete' => "function(event, key) {
                                                return (!confirm('确认要删除'));
                                            }",
                        'fileuploaded' => 'function(event, data, previewId, index) {
                                                $("input.out_img").val($("input.out_img").val()+"["+data.response.imageUrl.imgfile+"],");
                                                console.log(data.response.imageUrl.imgfile);
                                            }',
                        'filedeleted' => 'function(event, key) {
                                                var ov=$("input.out_img").val();
                                                $("input.out_img").val(ov.replace("["+key+"],",""));
                                                console.log(key);
                                                return alert("图片已经删除")
                                            }',
                    ]
                ])->label('汽车外观图片');
                ?>

                <!--分割线-->
                <div class="hr-line-dashed"></div>

                <input type='hidden' value='<?=$model->c_imginside?>' name='CCar[inimg]' class='in_img'>
                <?=$form->field($model, 'c_imginside[]')->widget(\kartik\file\FileInput::className(),[
                        'options'=>[
                            'multiple' => true,
                        ],
                        'pluginOptions'=>[
                            'previewFileType' => 'image',
                            'initialPreview' =>$initialPreview2,
                            'initialPreviewConfig' =>$initialPreviewConfig2,
                            'uploadUrl' => \yii\helpers\Url::toRoute(['upload/upload?type=in']),
                            'overwriteInitial'=>false,  //防止覆盖
                            // 异步上传需要携带的其他参数，比如产品id等
                            'uploadAsync' => true,
                            'browseOnZoneClick' => true,
                            'showUpload'=>true,
                            'showRemove'=>false,
                            'minFileCount' => 1,
                            'maxFileCount' => 8,
                        ],
                        #图片上传成功后，调用fileuploaded，将图片路径传给隐藏input[class='product_img'],若上传多张图，则以 “[xxxx],[xxxxxxxx]”格式
                       'pluginEvents' => [
                            'filepredelete' => "function(event, key) {
                                return (!confirm('确认要删除'));
                            }",
                            'fileuploaded' => 'function(event, data, previewId, index) {
                                $("input.in_img").val($("input.in_img").val()+"["+data.response.imageUrl.imgfile+"],");
                                console.log(data.response.imageUrl.imgfile);
                            }',
                            'filedeleted' => 'function(event, key) {
                                var ov=$("input.in_img").val();
                                $("input.in_img").val(ov.replace("["+key+"],",""));
                                console.log(key);
                                return alert("图片已经删除")
                            }',
                        ]
                    ])->label('汽车内饰图片');
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
    $(function () {
        $('.ccar-form button#save_btn').click(function () {

            var index = parent.layer.getFrameIndex(window.name);

            var level = $('#level').val();
            var pid = $('#pid').val();
            var is_new = $('#is_new').val();

            //公共字段
            var title = $('#ccar-c_title').val();
            if (title == '' || title == null || title.length == 0) {
                layer.msg('*名称不能为空');
                $('.field-ccar-c_title .hepl-block').html('*名称不能为空');
                return false;
            }

            var logo = $('.brand_img').val();
            if (logo == '' || logo == null || logo.length == 0) {
                layer.msg('*请上传图片');
                $('.field-ccar-c_logo .hepl-block').html('*图片不能为空');
                return false;
            }
            var out_img = $('.out_img').val();
            if (out_img == '' || out_img == null || out_img.length == 0) {
                layer.msg('*请上传外观图片');
                $('.field-ccar-c_imgoutside .hepl-block').html('*请上传外观图片');
                return false;
            }
            var in_img = $('.in_img').val();
            if (in_img == '' || in_img == null || in_img.length == 0) {
                layer.msg('*请上传内饰图片');
                $('.field-ccar-c_imginside .hepl-block').html('*请上传内饰图片');
                return false;
            }
            var sort = $('#ccar-c_sortorder').val();
            if (sort == '' || sort == null || sort.length == 0) {
                layer.msg('*排序不能为空');
                $('.field-ccar-c_imginside .hepl-block').html('*排序不能为空');
                return false;
            }

            var engine = $('#ccar-c_engine').val();
            var volume = $('#ccar-c_volume').val();

            var c_type = $('select#ccar-c_type').val();
            if (c_type == '' || c_type == null || c_type.length == 0) {
                layer.msg('*请上传内饰图片');
                $('.field-ccar-c_type .hepl-block').html('*请上传内饰图片');
                return false;
            }
            //需要上传的参数
            var data = {
                'level': level,
                'pid': pid,
                'title': title,
                'logo': logo,
                'out_img': out_img,
                'in_img': in_img,
                'engine': engine,
                'volume': volume,
                'sort': sort,
                'c_type': c_type,
                'is_new': is_new
            };

            //加载圈
            ShowLoad();
            $.post($('form#w0').attr('action'), data, function (data) {
                if (data.state == '200') {
                    //返回列表页
                    parent.location.href = toRoute('car/index');
                } else {
                    parent.layer.close(index); //执行关闭iframe
                    ShowMessage(data.state, '添加失败');
                }
            }, 'json');
        })
    });
</script>
