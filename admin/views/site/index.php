<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use mdm\admin\components\MenuHelper;

admin\assets\IndexAsset::register($this);

$this->title = '鲜橙置换';
$url=Yii::$app->params['base_url'].Yii::$app->params['base_file'];
?>

<style>
    .navbar-form-custom{ width:90%;}
    .navbar-form-custom .form-group a#head-title{
        text-align:left;
        padding: 15px;
        font-size: 18px;
        color: #666666;
        margin-left: 15px
    }
    @media screen and (max-width: 1023px) {
        .navbar-form-custom{ width:85%;}
        .navbar-form-custom .form-group a#head-title{
            font-size:16px;
        }
    }
</style>
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close">
            <i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element" style="text-align: center">
                        <span>
                            <img id="wa_logo" alt="image" class="img-circle" style="width:96px;margin-top:4px;border-radius:128px;box-shadow:0px 0px 12px #7E7E7E;"
                                 src="<?=empty($user->a_logo) ? ($url.'/photo/logo/profile_small.gif'):($user->a_logo);?>" />
                        </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear">
                                <span class="block m-t-xs"><strong class="font-bold"><?= $user->a_name ?></strong></span>
                                <span class="text-muted text-xs block"><?= $user_info?><b class="caret"></b></span>
                            </span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li>
                                <a class="J_menuItem" href="<?=Url::to(['manager/index']) ?>">修改头像</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="<?=Url::toRoute('site/logout')?>">安全退出</a>
                            </li>
                        </ul>
                    </div>
                    <div class="logo-element">H+</div>
                </li>

                <?php  foreach($menu as $v1){?>
                    <?php  $data = json_decode($v1['mam_data'], true);?>

                    <li><!--一级级菜单-->
                        <?php if(array_key_exists('_child',$v1)){?>
                            <a  href="#">
                                <i class="<?=$data['icon']?>"></i>
                                <span class="nav-label"><?= $v1['mam_name']?></span>
                                <span class="fa arrow"></span>
                            </a>

                            <ul class="nav nav-second-level">
                                <?php  foreach($v1['_child'] as $v2){?>

                                    <?php $data2 = json_decode($v2['mam_data'], true);?>
                                    <?php if(array_key_exists('_child',$v2)){?>
                                        <li><!--二级菜单-->
                                            <a href="#">
                                                <?php if($data2['icon']){?>
                                                    <i class="<?=$data2['icon']?>"></i>
                                                <?php }?>
                                                <?= $v2['mam_name']?>
                                                <span class="fa fa-arrow"></span>
                                            </a>
                                            <?php if(!empty($v2['_child'])){?>
                                                <ul class="nav nav-third-level collapse">
                                                    <?php  foreach($v2['_child'] as $v3){?>
                                                        <li><!--三级菜单-->
                                                            <a class="J_menuItem" href="<?= Url::toRoute($v3['mam_route']);?>" data-index="0">
                                                                <?= $v3['mam_name']?>
                                                            </a>
                                                        </li>
                                                    <?php }?>
                                                </ul>
                                            <?php }?>
                                        </li>
                                    <?php }else{?>
                                        <li><!--二级菜单-->
                                            <a class="J_menuItem" href="<?= Url::toRoute($v2['mam_route']);?>" data-index="0">
                                                <?= $v2['mam_name']?>
                                            </a>
                                        </li>
                                    <?php }?>
                                <?php }?>
                            </ul>
                        <?php }else{ ?>
                            <a class="J_menuItem" href="<?= Url::toRoute($v1['mam_route']);?>" data-index="0">
                                <?php if($data['icon']){ ?><i class="<?=$data['icon']?>"></i><?php }?><?= $v1['mam_name']?>
                            </a>
                        <?php }?>
                    </li>
                <?php }?>

            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <!--顶部导航开始-->
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-outline btn-primary dim " href="#">
                        <i class="fa fa-bars"></i>
                    </a>
                    <div role="search" class="navbar-form-custom" method="post" action="search_results.html">
                        <div class="form-group">
                            <input placeholder="H+" class="form-control" name="top-search" id="top-search" type="text" readonly="readonly">
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div class="row content-tabs">
            <button class="roll-nav roll-left J_tabLeft" onclick="javascript:history.go(-1)">
                <i class="fa fa-backward"></i>
            </button>
            <nav class="page-tabs J_menuTabs">
                <div class="page-tabs-content">
                    <a href="<?=Url::toRoute(['site/welcome'])?>" class="active J_menuTab" >主页</a>
                </div>
            </nav>
            <button class="roll-nav roll-right J_tabRight">
                <i class="fa fa-forward"></i>
            </button>
            <a href="<?=Url::toRoute('site/logout')?>" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i> 退出</a>
        </div>
        <!--顶部导航结束-->
        <!--中间内容开始-->
        <div class="row J_mainContent" id="content-main">
            <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="<?= Url::toRoute('site/welcome')?>" frameborder="0" data-id="index_v2.html" seamless></iframe>
        </div>
        <!--中间内容结束-->
        <!--底部导航开始-->
        <div class="footer">
            <div class="pull-right">
                &copy; 2017-2018
                <a href="http://www.zi-han.net/" target="_blank">H+'s blog</a>
            </div>
        </div>
        <!--底部导航结束-->
    </div>
    <!--右侧部分结束-->

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