<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/4/7
 * Time: 9:07
 */
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Html;
?>
<div class="forums-form">
    <?php
    $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_HORIZONTAL,
    ]);

    echo  $form->field($model, 'f_id')->hiddenInput()->label(false);
    echo  $form->field($model, 'f_state')->dropDownList([-1 => '非禁用',1=>'禁用']);
   // echo  $form->field($model, 'f_forbidden_reason')->textInput()->label('禁用理由');
    echo "<div class='form-group field-forums-f_forbidden_reason' style='".($model->f_state==1?'display:block':'display:none')."'>
            <label class='control-label col-md-2' for='forums-f_forbidden_reason'>禁用理由</label>
            <div class='col-md-10'>
                <input id='forums-f_forbidden_reason' class='form-control' type='text'  value='".$model->f_forbidden_reason."' name='CForums[f_forbidden_reason]'>
                <div class='help-block'></div>
            </div>
          </div>";

    echo '<div style="text-align: center">';
    echo Html::submitButton('<i class="glyphicon glyphicon-ok"></i>', ['class' =>'btn btn-sm btn-primary kv-editable-submit']);
    echo '</div>';
    ActiveForm::end();
    ?>
</div>
<script>
    $(function(){
        $('select#cforums-f_state').change(function(){
            if($(this).find('option:selected').val()==1){  //禁用
                $('div.field-forums-f_forbidden_reason').show();
            }else{
                $('div.field-forums-f_forbidden_reason').hide();
            }
        })
    })

</script>