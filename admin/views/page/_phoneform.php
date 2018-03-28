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
\yii\web\YiiAsset::register($this);
?>
<style>
    .cpage-form{width: 70%;height:200px;margin:0 auto;margin-top:10%;padding-top:10px;border:2px solid #ECECEC;border-radius: 5px;}
.cpage-form a,.page-header h3{font-size: 2rem;}
.cpage-form a i{padding-right: .5rem}
.cpage-form .col-md-2{width: 8%;}
.cpage-form .col-md-10{width: 92%}
</style>

<div class="cpage-form" >

    <?php $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_HORIZONTAL,
        'options'=>[
          //  'onsubmit'=>'return checktext()'
        ]
    ]);
    ?>
    <?php

      echo  $form->field($model, 'p_content')->textInput()->label('客服电话');
    ?>
    <div style="text-align: center">
        <?=Html::submitButton('保存', ['class' =>'btn btn-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
