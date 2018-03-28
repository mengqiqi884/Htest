<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/10/26
 * Time: 10:04
 */

namespace admin\controllers;



use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class TestController extends BaseController
{

    public function actionIndex(){
        //获取缓存组件
        $cache = \Yii::$app->cache;

        //往缓存中写数据
//        $cache->add('key','hello world');

        //修改缓存中数据
//        $cache->set('key','hello , mooke');

        //缓存有效期的设置(s)
//        $cache->set('key','hello !' , 15);

        //删除某条缓存
//        $cache->delete('key');

        //清空所有的缓存
//        $cache->flush();

        //获取缓存数据
        $data = $cache->get('key');

        var_dump($data);
    }
}