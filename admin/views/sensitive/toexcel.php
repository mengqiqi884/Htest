<?php

use yii\helpers\Html;
use \kartik\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var admin\models\CCar $model
 */

$this->title = '导入excel';
$this->params['breadcrumbs'][] = ['label' => 'Csensitives', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox-title">
                <div class="row">
                    <label class="control-label col-md-2">下载模板:</label>
                    <a href="<?= Yii::$app->params['base_url'] . Yii::$app->params['base_file'] . '/download/敏感词汇模板.xls' ?>">
                        下载
                    </a>
                </div>

                <div class="hr-line-dashed"></div> <!--分割线-->

                <form id='myupload' method='post' enctype='multipart/form-data'>
                    <div class="row">
                        <label class="control-label col-md-2">请选择excel文件</label>
                        <div class="col-md-10">
                            <?= \kartik\file\FileInput::widget([
                                'model' => $model,
                                'attribute' => 'Cfile',
                                'options' => [],
                                'pluginOptions' => [
                                    'uploadUrl' => \yii\helpers\Url::toRoute(['sensitive/excel-import']),
                                    // 异步上传需要携带的其他参数，比如产品id等
                                    'uploadAsync' => true,
                                    'autoReplace' => true,
                                    'overwriteInitial' => true,  //防止覆盖
                                    'browseOnZoneClick' => true,
                                    'dropZoneEnabled' => false,//是否显示拖拽区域，默认不写为true，但是会占用很大区域
                                    'showUpload' => false,
                                    'showRemove' => false,
                                    'maxFileCount' => 1,
                                    'allowedFileExtensions' => ['xls', 'xlsx'],//接收的文件后缀
                                    // 如果要设置具体图片上的移除、上传和展示按钮，需要设置该选项
                                    'fileActionSettings' => [
                                        // 设置具体图片的查看属性为false,默认为true
                                        'showZoom' => false,
                                        // 设置具体图片的上传属性为true,默认为true
                                        'showUpload' => true,
                                        // 设置具体图片的移除属性为true,默认为true
                                        'showRemove' => false,
                                    ],
                                    'previewFileIcon' => "<i class='glyphicon glyphicon-king'></i>",
                                    'msgFilesTooMany' => "选择上传的文件数量({n}) 超过允许的最大数值{m}！",

                                ],
                                //一些事物行为
                                'pluginEvents' => [
                                    // 上传成功后的回调方法，需要的可查看data后再做具体操作，一般不需要设置
                                    "fileuploaded" => "function (event, data, id, index) {
                                        console.log(data);

                                        layer.msg(data.response.message);

                                        var iframe_index= parent.layer.getFrameIndex(window.name);

                                        if(data.response.state==200){
                                            parent.location.href=toRoute('sensitive/index');
                                        }else{
                                            parent.layer.close(iframe_index); //执行关闭iframe
                                        }
                                    }",
                                ],
                            ]);
                            ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


