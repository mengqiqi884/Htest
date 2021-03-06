<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var admin\models\ScoreLogSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<?=Html::cssFile('@web/css/car/style.css');?>
<?=Html::jsFile('@web/js/wine/selectdate.js');?>
<style>
    .cscore-log-search input#scorelogsearch-user_name{border:1px solid #e5e6e7;height:35px;width: 200px}
</style>
<div class="cscore-log-search" style="margin:10px 15px">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' =>[
            'class' =>'form-inline'
        ]
    ]); ?>

    <?= $form->field($model, 'user_name')->widget(\yii\jui\AutoComplete::className(),[
        'clientOptions'=>[
            'source' =>\v1\models\CUser::GetAllUser()
        ],
    ])->label('用户名') ?>

    <?= $form->field($model, 'sl_state')->dropDownList(['-1'=>'全部','0'=>'待发货','1'=>'已发货'])->label('状态') ?>

    <?=$form->field($model, 'created_time')->textInput(['id'=>'LAY_demorange_s','placeholder'=>'开始日']) ?>

    <?=$form->field($model, 'end_time')->textInput(['id'=>'LAY_demorange_e','placeholder'=>'截止日'])->label('至') ?>

    <div class="form-group" style="margin-top: -10px;">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?=Html::a('重置', ['index'], ['','class' => 'btn btn-default','style'=>'margin-left:10px'])?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
