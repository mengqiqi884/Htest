<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/8/1
 * Time: 16:13
 */

namespace admin\assets;


use yii\web\View;
use yii\web\AssetBundle;

class WarningAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/plugins/sweetalert/sweetalert.css'
    ];
    public $js = [
        'js/plugins/sweetalert/sweetalert.min.js'
    ];
    public $jsOptions = [
        'position' => View::POS_END
    ];
}