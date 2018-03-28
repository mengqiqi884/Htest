<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/8/9
 * Time: 11:21
 */

namespace admin\assets;

use yii\web\AssetBundle;
use yii\web\View;

class VideoAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'ossupload/style.css'
    ];
    public $js = [

        'ossupload/lib/plupload-2.1.2/js/plupload.full.min.js',
        'ossupload/upload.js'
//        'assets/c41a5643/js/fileinput.min.js',
//        'js/wine/ll_uploadvideo.js'
    ];
    public $jsOptions = [
        'position' => View::POS_END
    ];
}