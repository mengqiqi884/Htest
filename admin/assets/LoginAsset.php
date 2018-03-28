<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/7/26
 * Time: 8:58
 */

namespace admin\assets;
use yii\web\AssetBundle;

class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/login.min.css'
    ];
    public $js = [
    ];
//    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
//        'admin\assets\BootboxAsset'
//    ];
}