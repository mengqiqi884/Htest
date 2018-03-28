<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use mdm\admin\components\MenuHelper;

$this->title = '代客泊车';
?>
<?=Html::jsFile('@web/js/plugins/metisMenu/jquery.metisMenu.js')?>
<?=Html::jsFile('@web/js/plugins/slimscroll/jquery.slimscroll.min.js')?>
<?=Html::jsFile('@web/js/hplus.min.js')?>
<?=Html::jsFile('@web/js/contabs.min.js')?>
<?=Html::jsFile('@web/js/plugins/pace/pace.min.js')?>

<style>
  /*  .minimalize-styl-2{ margin:0;font-size: 28px;}*/
    .navbar-form-custom{ width:900px;}
</style>
<div id="wrapper">
        <!--左侧导航开始-->
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="nav-close"><i class="fa fa-times-circle"></i>
            </div>
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element" style="text-align: center">
                            <span><img id="wa_logo" alt="image" class="img-circle" style="width:96px;margin-top:4px;border-radius:128px;box-shadow:0px 0px 12px #7E7E7E;"
                                       src="<?=empty($user->admin_logo) ? ('../../photo/logo/profile_small.gif'):('../../photo/'.$user->admin_logo);?>" />
                            </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                               <span class="block m-t-xs"><strong class="font-bold"><?= $user->admin_name ?></strong></span>
                                <span class="text-muted text-xs block"><?= $user_info?><b class="caret"></b></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a class="J_menuItem" href="<?=Url::to(['manager/index']) ?>">修改头像</a>
                                </li>
                                <li><a class="J_menuItem" href="<?= Url::toRoute(['manager/update','id'=>$user->getId()])?>">个人资料</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="<?=Url::toRoute('site/logout')?>">安全退出</a>
                                </li>
                            </ul>
                        </div>
                        <div class="logo-element">M+
                        </div>
                    </li>

                    <?php  foreach($menu as $v1):?>
                        <?php  $data = json_decode($v1['mam_data'], true);?>
                        <li><!--一级级菜单-->
                            <?php if(array_key_exists('_child',$v1)):?>
                                <a  href="#">
                                    <i class="<?=$data['icon']?>"></i>
                                    <span class="nav-label"><?= $v1['mam_name']?></span>
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <?php  foreach($v1['_child'] as $v2):?>
                                        <?php $data2 = json_decode($v2['mam_data'], true);?>
                                        <?php if(array_key_exists('_child',$v2)):?>
                                            <li><!--二级菜单-->
                                                <a href="#">
                                                    <?php if($data2['icon']):?><i class="<?=$data2['icon']?>"></i><?php endif;?><?= $v2['mam_name']?>
                                                    <span class="fa arrow"></span>
                                                </a>
                                                <?php if(!empty($v2['_child'])):?>
                                                    <ul class="nav nav-third-level collapse">
                                                        <?php  foreach($v2['_child'] as $v3):?>
                                                            <li><!--三级菜单-->
                                                                <a class="J_menuItem" href="<?= Url::toRoute($v3['mam_route']);?>" data-index="0"><?= $v3['mam_name']?></a>
                                                            </li>
                                                        <? endforeach;?>
                                                    </ul>
                                                <?php  endif;?>
                                            </li>
                                        <?php else:?>
                                            <li><!--二级菜单-->
                                                <a class="J_menuItem" href="<?= Url::toRoute($v2['mam_route']);?>" data-index="0"><?= $v2['mam_name']?></a>
                                            </li>
                                        <?php  endif;?>
                                    <? endforeach;?>
                                </ul>
                            <?php else:?>
                                <a class="J_menuItem" href="<?= Url::toRoute($v1['mam_route']);?>" data-index="0">
                                    <?php if($data['icon']):?><i class="<?=$data['icon']?>"></i><?php endif;?><?= $v1['mam_name']?>
                                </a>
                            <?php endif;?>
                        </li>
                    <?php endforeach;?>

                </ul>
            </div>
        </nav>
        <!--左侧导航结束-->
        <!--右侧部分开始-->
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                        <form role="search" class="navbar-form-custom">
                            <div class="form-group">
                                <a class="form-control" href="<?=Url::toRoute('site/index') ?>" style="text-align:left;padding: 15px;font-size: 20px;color: #666666;margin-left: 15px">
                                    您好<?=$user->admin_name?>,欢迎使用‘pnpark’。您上次登录的时间是 <?=$user->last_login_time?>,IP是<?=$user->login_IP?>
                                </a>
                            </div>
                        </form>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="dropdown hidden-xs">
                            <a class="right-sidebar-toggle" aria-expanded="false">
                                <i class="fa fa-tasks"></i> 主题
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="row content-tabs">


                <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
                </button>
                <nav class="page-tabs J_menuTabs">
                    <div class="page-tabs-content">
<!--                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>-->
                        <a href="javascript:;" class="active J_menuTab" data-id="index_v1.html">首页</a>

                    </div>
                </nav>
                <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
                </button>
                <div class="btn-group roll-nav roll-right">
                    <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span>

                    </button>
                    <ul role="menu" class="dropdown-menu dropdown-menu-right">
                        <li class="J_tabShowActive"><a>定位当前选项卡</a>
                        </li>
                        <li class="divider"></li>
                        <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                        </li>
                        <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                        </li>
                    </ul>
                </div>
                <a href="<?=Url::toRoute('site/logout')?>" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i> 退出</a>
            </div>
            <div class="row J_mainContent" id="content-main">
                <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="<?= Url::toRoute('index/welcome')?>" frameborder="0" data-id="index_v1.html" seamless></iframe>
            </div>
            <div class="footer">
                <div class="pull-right">&copy; 2016 <a href='#' target="_self">Manager</a>
                </div>
            </div>
        </div>
        <!--右侧部分结束-->
        <!--右侧边栏开始-->
        <div id="right-sidebar">
            <div class="sidebar-container">

                <ul class="nav nav-tabs navs-3">

                    <li class="active">
                        <a data-toggle="tab" href="#tab-1">
                            <i class="fa fa-gear"></i> 主题
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="sidebar-title">
                            <h3> <i class="fa fa-comments-o"></i> 主题设置</h3>
                            <small><i class="fa fa-tim"></i> 你可以从这里选择和预览主题的布局和样式，这些设置会被保存在本地，下次打开的时候会直接应用这些设置。</small>
                        </div>
                        <div class="skin-setttings">
                            <div class="title">主题设置</div>
                            <div class="setings-item">
                                <span>收起左侧菜单</span>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="collapsemenu">
                                        <label class="onoffswitch-label" for="collapsemenu">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setings-item">
                                <span>固定顶部</span>

                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="fixednavbar" class="onoffswitch-checkbox" id="fixednavbar">
                                        <label class="onoffswitch-label" for="fixednavbar">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setings-item">
                                <span>
                        固定宽度
                    </span>

                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="boxedlayout" class="onoffswitch-checkbox" id="boxedlayout">
                                        <label class="onoffswitch-label" for="boxedlayout">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="title">皮肤选择</div>
                            <div class="setings-item default-skin nb">
                                <span class="skin-name ">
                         <a href="#" class="s-skin-0">
                             默认皮肤
                         </a>
                    </span>
                            </div>
                            <div class="setings-item blue-skin nb">
                                <span class="skin-name ">
                        <a href="#" class="s-skin-1">
                            蓝色主题
                        </a>
                    </span>
                            </div>
                            <div class="setings-item yellow-skin nb">
                                <span class="skin-name ">
                        <a href="#" class="s-skin-3">
                            黄色/紫色主题
                        </a>
                    </span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!--右侧边栏结束-->

    </div>

<script>
        $('.text-muted').click(function(){
            $('.animated').hide();
            $('.animated').fadeIn();
        });
        $('.animated').hover(function(){
            $('.animated').show();
        },function(){
            $('.animated').hide();
        });
    $(window).ready(function(){
        wa_logo = $('#wa_logo')[0];
        var img = new Image();
        // 开始加载图片
        img.src = wa_logo.src;
//      //为Image对象添加图片加载失败的处理方法
        img.onerror = function() {
            wa_logo.src = '../../photo/logo/profile_small.gif'
        }
    });
</script>