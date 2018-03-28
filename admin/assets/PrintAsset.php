<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/8/16
 * Time: 16:32
 */

namespace admin\assets;

use yii\web\AssetBundle;

class PrintAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
//        'js/jquery.PrintArea.min.js',
        'js/wine/printThis.js',
        'js/wine/ll_print.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_END
    ];
}