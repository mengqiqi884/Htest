<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use mdm\admin\AnimateAsset;
use yii\web\YiiAsset;
use yii\helpers\Json;

/**
 * @var yii\web\View $this
 * @var admin\models\CAgent $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<?=Html::cssFile('@web/css/plugins/jsTree/style.min.css')?>
<?=Html::jsFile('@web/js/plugins/jsTree/jstree.min.js')?>
<style>
    .help-block{
        color: #e51717;
    }
</style>
<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading"><?=$model->isNewRecord? '新增角色':'编辑角色'?></div>
                <div class="panel-body">
                    <?php
                    $form = ActiveForm::begin([
                        'type' => ActiveForm::TYPE_HORIZONTAL,
//                        'action' => \yii\helpers\Url::toRoute('ajax-saverole?id='.$model->i_id.'&isnew='.$model->isNewRecord),
//                        'options' => [
//                            'onsubmit' => 'return check_Input()'
//                        ]
                    ]);
                    ?>
                    <p class="text-danger">注：带 * 号为必填项！</p>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'name')->textInput(['placeholder'=>'请输入名称'])->label('名称：*');?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'description')->textInput(['placeholder'=>'请输入描述，限字100字'])->hint('0/100')->label('描述：*');?>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div><!--分割线-->

                    <div class="col-sm-12">
                        <label class="control-label col-md-1" for="authitem-a_permisson">选择授权：*</label>
                        <div id="field-authitem-a_permisson" class="col-md-11">
                            <!--jstree遍历全部权限-->
                            <div id="jstree"></div>
                            <div class="help-block"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 pull-right" style="margin: 5px">
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

<script>
    $(function() {
        //当前角色名称
        var role_name = $('input#authitem-name').val();
        //初始化树（权限）
        $('#jstree').jstree({
            "core":{
                'data':{
                    'url':toRoute('operator/asyn-data'),
                    'data':function(node){
                        return {'id':node.id,'name':role_name}
                    }
                }
            },
            "plugins": [
                "checkbox",
            ],
            "checkbox": {  // 去除checkbox插件的默认效果
                tie_selection: false,
                keep_selected_style: false,
                whole_node: false
            },
        });

        /*统计描述 还还剩多少个字*/
        //先选出 描述 字段 和 统计字数 dom 节点
        var textArea = $("input#authitem-description");
        var word = $("div.hint-block");
        //调用
        var max = 100;
        var curLength;
        textArea[0].setAttribute("maxlength", max);
        curLength = textArea.val().length;

        word.html(curLength + '/' + max);
        textArea.on('input propertychange', function () {
            word.html($(this).val().length + '/' + max);
        });

    });
    ////////////////////////////////////////表单验证 start//////////////////////////////////////////////////////////////////////////
    $('button#save_btn').click(function(ev){
        //获取选中的权限编号
        var id_array=new Array();
        var idstr = '';

        $('div#jstree').find('a').each(function(){
            if($(this).hasClass('jstree-checked')){
                id_array.push($(this).parent('li').attr('id'));//向数组中添加元素
            }
        });
        console.log(id_array);

        if(id_array.length==0){
            $('#field-authitem-a_permisson .help-block').html('*请至少选则一个授权');
            return false;
        }else{
            idstr=id_array.join(',');//将数组元素连接起来以构建一个字符串
        }

        var account = $('input#authitem-name').val();
        var des = $('input#authitem-description').val();

        //验证其他输入值
        if (!checkValue(account)) {
            $('.field-authitem-name .help-block').html('*请填写名称');
            return false;
        } else {
            $('.field-authitem-name .help-block').html('');
        }

        if (!checkValue(des)) {
            $('.field-authitem-description .help-block').html('*请填写描述');
            return false;
        } else {
            $('.field-authitem-description .help-block').html('');
        }

        //保存值
        var url = toRoute('operator/ajax-saverole?id=<?=$model->i_id?>&isnew=<?=$model->isNewRecord?>');

        $.post(url,{name:account,description:des,permissons:idstr},function(data){
            if(data.state=='200'){
                window.location.href=data.message;
            }else{
                alert(data.message);
            }
        },'json');
        ev.preventDefault(); //阻止表单提交
    });
</script>