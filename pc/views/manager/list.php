<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '管理员列表';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="wrapper wrapper-content">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <p>
                <a href="<?= Url::toRoute('manager/create')?>">
                    <button class="btn btn-info" ><li class="fa fa-plus"></li> 新增管理员</button>
                </a>
            </p>
            <input name="manager_page" value="1" type="hidden">

            <div class="row" style="margin-bottom: 5px">
                <div class="col-lg-2">
                    <div class="input-group">
                        <span class="input-group-addon" style="background-color: #21b9bb;border-color: #21b9bb;color: #FFF;">登陆名</span>
                        <input autocomplete="off" style="border-radius: 4px;" class="form-control input-sm" id="searchUsername" type="text"
                               value="<?=empty($searchArr) ? '':(empty($searchArr['admin_name'])? '':$searchArr['admin_name']) ?>"
                            >
                        <div class="input-group-btn">
                            <button style="display: none;" type="button" class="btn btn-default dropdown-toggle" data-toggle="">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            </ul>
                        </div>
                    </div>
                </div>
<!--                <div class="col-lg-2">-->
<!--                    <div class="input-group">-->
<!--                        <span class="input-group-addon" style="background-color: #21b9bb;border-color: #21b9bb;color: #FFF">手机号</span>-->
<!--                        <input autocomplete="off" style="border-radius: 4px;" class="form-control input-sm" id="searchPhone" type="text"-->
<!--                               value="--><?//=empty($searchArr) ? '':(empty($searchArr['wa_phone'])? '':$searchArr['wa_phone']) ?><!--"-->
<!--                            >-->
<!--                        <div class="input-group-btn">-->
<!--                            <button style="display: none;" type="button" class="btn btn-default dropdown-toggle" data-toggle="">-->
<!--                                <span class="caret"></span>-->
<!--                            </button>-->
<!--                            <ul class="dropdown-menu dropdown-menu-right" role="menu">-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="col-lg-2">-->
<!--                    <div class="input-group">-->
<!--                        <span class="input-group-addon" style="background-color: #21b9bb;border-color: #21b9bb;color: #FFF">姓 名 </span>-->
<!--                        <input autocomplete="off" style="border-radius: 4px;" class="form-control input-sm" id="searchName" type="text"-->
<!--                               value="--><?//=empty($searchArr) ? '':(empty($searchArr['wa_name'])? '':$searchArr['wa_name']) ?><!--"-->
<!--                            >-->
<!--                        <div class="input-group-btn">-->
<!--                            <button style="display: none;" type="button" class="btn btn-default dropdown-toggle" data-toggle="">-->
<!--                                <span class="caret"></span>-->
<!--                            </button>-->
<!--                            <ul class="dropdown-menu dropdown-menu-right" role="menu">-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="col-lg-2">
                    <?= Html::dropDownList('level', empty($searchArr) ? 0:(empty($searchArr['admin_type'])? 0:$searchArr['admin_type']), $itemArr,['prompt' => '请选择用户组', 'class' => 'form-control input-sm','style'=>'font-size:13px']) ?>
                </div>
                <div class="col-lg-3">
                    <button class="btn btn-info btn-sm" id="searchManager"><li class="fa fa-search"></li>搜 索</button>
                    <!--<button class="btn btn-info btn-sm" onclick="goPage(1)"><li class="fa fa-refresh"></li>清 空</button>-->
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>编号</th>
                                        <th>登录名</th>
                                        <th>密码(不可见)</th>
                                        <th>用户组</th>
                                        <th>是否可登录</th>
                                        <th>最近登录时间</th>
                                        <th>最近登录IP</th>
                                        <th>状态</th>
                                        <th>创建时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($admin as $val):?>
                                        <tr>
                                            <td id="wa_id"><?=$val['admin_id'] ?></td>
                                            <td><?=$val['admin_name'] ?></td>
                                            <td><?='----------' ?></td>
                                            <td><?=$val['admingroup']['item_name'] ?></td>
                                            <td>
                                                <!--turn on/off-->
                                                <div class="switch">
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" <?php
                                                        if(Yii::$app->user->identity->a_type >= $val['admin_type']){
                                                            echo 'disabled';
                                                        }else{
                                                            echo '';
                                                        }
//                                                        if($val['wa_lock']==0){
//                                                            echo ' checked';
//                                                        }else{
//                                                            echo ' ';
//                                                        }
                                                        ?> class="onoffswitch-checkbox" id="admin<?=$val['admin_id'] ?>">
                                                        <label class="onoffswitch-label" for="admin<?=$val['admin_id'] ?>">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>

                                            <td><?=$val['last_login_time'] ?></td>
                                            <td><?=$val['login_IP'] ?></td>
                                            <td><?php
                                                if($val['admin_status']==0){
                                                    echo '<p><span class="label label-default"><i class="fa fa-times"></i> 禁 用</span></p>';
                                                }else{
                                                    echo '<p><span class="label label-info"><i class="fa fa-check"></i> 正 常</span></p>';
                                                }
                                                ?>
                                            </td>
                                            <td><?=$val['created_time'] ?></td>
                                            <td>
                                                <a class="btn btn-primary btn-xs" href="<?=Url::toRoute(['manager/update','id'=>$val['admin_id']])?>">
                                                    <i class="fa fa-edit"> 编辑</i>
                                                </a>

                                                <?php
                                                if($val['admin_type']>Yii::$app->user->identity->admin_type&&$val['admin_status']<>0){
                                                ?>
                                                <a class="btn btn-warning btn-xs" id="manager_del">
                                                    <i class="fa fa-trash-o"> 禁用</i>
                                                </a>
                                                <?php
                                                }elseif($val['admin_type']>Yii::$app->user->identity->admin_type&&$val['admin_status']==0){
                                                ?>
                                                <a href="#" class="btn btn-success btn-xs" id="manager_recover">
                                                    <i class="fa fa-undo"> 恢复</i>
                                                </a>
                                                <?php
                                                }else{
                                                ?>
                                                    <a href="#" class="btn btn-warning btn-xs">
                                                        <i class="fa fa-warning"> 不可删</i>
                                                    </a>
                                                <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                                <!--分页-->
                                <div class="f-r" style="text-align: center">
                                    <?= LinkPager::widget([
                                        'pagination'=>$pages,
                                        'linkOptions' => ['onclick' => 'return goPage(this)'],
                                        'firstPageLabel' => '首页',
                                        'nextPageLabel' => '下一页',
                                        'prevPageLabel' => '上一页',
                                        'maxButtonCount' => 8,
                                        'lastPageLabel' => '末页',
                                    ]) ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?=Html::jsFile('@web/js/wine/wine.js?_'.time())?>
<?=Html::jsFile('@web/js/wine/manager.js?_'.time())?>