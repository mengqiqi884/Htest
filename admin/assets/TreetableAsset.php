<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/8/2
 * Time: 9:01
 */

namespace admin\assets;


use yii\web\AssetBundle;

class TreetableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'maxazan/css/themes/default/easyui.css',
        'maxazan/css/themes/icon.css',
        'maxazan/css/demo.css'
    ];
    public $js = [
        'maxazan/js/jquery.easyui.min.js',
        'js/plugins/layer/layer.js',
        'js/wine/ll_treetable.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_END
    ];
}