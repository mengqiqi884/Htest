<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/7/26
 * Time: 14:58
 */

namespace admin\assets;


use yii\web\AssetBundle;
use yii\web\View;

class IndexAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
//        'js/jquery.min.js?v=2.1.4',
//        'js/bootstrap.min.js?v=3.3.5',
        'js/plugins/metisMenu/jquery.metisMenu.js',
        'js/plugins/slimscroll/jquery.slimscroll.min.js',
//        'js/plugins/layer/layer.js',
        'js/hplus.min.js?v=4.0.0',
        'js/contabs.min.js',
    ];
    public $jsOptions = [
        'position' => View::POS_END
    ];
//    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
//        'admin\assets\BootboxAsset'
//    ];
}