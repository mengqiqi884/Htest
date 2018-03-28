<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/7/31
 * Time: 15:08
 */

namespace admin\assets;


use yii\web\AssetBundle;

class EditeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/plugins/iCheck/custom.css',
        'css/plugins/summernote/summernote.css',
        'css/plugins/summernote/summernote-bs3.css'
    ];
    public $js = [
//        'js/jquery.min.js?v=2.1.4',
//        'js/bootstrap.min.js?v=3.3.5',
//        'js/content.min.js?v=1.0.0',
        'js/plugins/iCheck/icheck.min.js',
        'js/plugins/summernote/summernote.min.js',
        'js/plugins/summernote/summernote-zh-CN.js',
        'js/wine/ll_edittextarea.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_END
    ];
}