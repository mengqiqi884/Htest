<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/8/15
 * Time: 10:32
 */

namespace admin\assets;

use yii\web\AssetBundle;

class FootTableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/plugins/footable/footable.core.css',
    ];
    public $js = [
        'js/plugins/footable/footable.all.min.js',
        'js/wine/ll_foottable.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_END
    ];
}