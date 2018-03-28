<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/7/28
 * Time: 9:50
 */

namespace admin\assets;


use yii\web\AssetBundle;

class TableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/plugins/dataTables/dataTables.bootstrap.css',
        'css/ll_table.css'
    ];
    public $js = [
//        'js/jquery.min.js?v=2.1.4',
//        'js/bootstrap.min.js?v=3.3.5',
//        'js/plugins/jeditable/jquery.jeditable.js',
        'js/plugins/dataTables/jquery.dataTables.js',
        'js/plugins/dataTables/dataTables.bootstrap.js',
//        'js/content.min.js?v=1.0.0',
        'js/wine/ll_table.js'
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_END
    ];
}