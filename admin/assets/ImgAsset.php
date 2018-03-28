<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/8/1
 * Time: 16:36
 */

namespace admin\assets;


use yii\web\AssetBundle;

class ImgAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/plugins/fancybox/jquery.fancybox.css'
    ];
    public $js = [
        'js/plugins/fancybox/jquery.fancybox.js',
        'js/wine/ll_showimg.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_END
    ];
}