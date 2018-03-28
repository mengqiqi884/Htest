<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var admin\models\CPage $model
 * @var yii\widgets\ActiveForm $form
 */
\admin\assets\EditeAsset::register($this);
?>
<style>
    /*文本编辑器最大高度*/
    .h-200{max-height: 700px;overflow-y: auto;overflow-x:hidden }
    .h-200 .col-md-10{width: 100%}
    .modal-backdrop.in{opacity: 0;display: none}
    .col-sm-9{margin-top: 10px;}
</style>

<div class="col-sm-9 animated fadeInRight" >

    <?php $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_HORIZONTAL,
        'options'=>[
            'onsubmit'=>'return checktext()'
        ]
    ]);
    ?>

    <div class="mail-text h-200">
        <?php
//        echo  $form->field($model, 'p_content')->textarea(['rows'=>6,'class'=>'summernote'])->label(false); //法一
            echo $form->field($model, 'p_content')->widget(\yii\redactor\widgets\Redactor::className(),[
                'clientOptions' => [
                    'imageManagerJson' => ['/redactor/upload/image-json'],
                    'lang' => 'zh_cn',  //语言
                    'plugins' => ['fontcolor','imagemanager'], //插件
                ]
            ])->label(false);   //法二（图片保存在photo/goods/detail/,详见admin/main.php）
        ?>
        <div class="clearfix"></div>
    </div>
    <div class="mail-body text-right tooltip-demo">
        <?=Html::submitButton('<i class="fa fa-reply"></i> 保存', ['class' =>'btn btn-sm btn-primary']); ?>
        <?=Html::resetButton('<i class="fa fa-times"></i> 放弃',['class'=>'btn btn-white btn-sm'])?>
    </div>
    <div class="clearfix"></div>




<!--    <div style="text-align: right">-->
<!--        --><?//=Html::submitButton('保存', ['class' =>'btn btn-primary']); ?>
<!--    </div>-->
    <?php ActiveForm::end(); ?>
</div>
