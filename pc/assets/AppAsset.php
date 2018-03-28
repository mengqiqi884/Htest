<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace pc\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/normalize.css',
        'css/bootstrap.min.css',
        'css/font-awesome.min.css?v=4.4.0',
        'css/animate.min.css',
        'css/style.css',
        'css/responsive.css',
//        'css/hero-slider-style.css'

    ];
    public $js = [
        'js/jquery-1.11.1.min.js',
        'js/jquery.min.js?v=2.1.4',
        'js/bootstrap.min.js?v=3.3.5',
//        'js/hero-slider-main.js', //滑动
        'js/wow.min.js',
        'js/main.js',
        'js/ll_pc.js',

//        'js/plugins/layer/layer.js',
//        'js/wine/wine.js'
    ];
//    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
//        'admin\assets\BootboxAsset'
//    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
}
