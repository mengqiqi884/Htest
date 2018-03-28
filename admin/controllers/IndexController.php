<?php

namespace admin\controllers;
use admin\models\Dics;
use admin\models\Log;
use admin\models\Menu;
use admin\models\PasswordForm;
use admin\models\PPoint;
use admin\models\PUser;
use admin\models\PValet;
use yii\data\Pagination;

use Yii;

class IndexController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionWelcome()
    {
        //当日新增会员数
//        $day_user_increasenumber=PUser::getEveryDayUsers('day');
//        //当日新增泊车人员数
//        $day_valet_increasenumber=PValet::getEveryDayValet('day');
//        //当日新增泊车点数
//        $day_point_increasenumber=PPoint::getEveryDayPoint('day');
//
//        //当月新增会员数
//        $month_user_increasenumber=PUser::getEveryDayUsers('month');
//        //当月新增泊车人员数
//        $month_valet_increasenumber=PValet::getEveryDayValet('month');
//        //当月新增泊车点数
//        $month_point_increasenumber=PPoint::getEveryDayPoint('month');
//
//        //会员数
//        $user_total=PUser::find()->count();
//        //泊车人员数
//        $valet_total=PValet::find()->where(['is_del'=>0])->count();
//        //泊车点数
//        $point_total=PPoint::find()->where(['is_del'=>0])->count();
//
//        //数据库最新备份时间
//        $new_databasetime=Dics::getMessageType('数据库备份');
//        $data=[
//            'day_user_increasenumber'=>$day_user_increasenumber,
//            'day_valet_increasenumber'=>$day_valet_increasenumber,
//            'month_user_increasenumber'=>$month_user_increasenumber,
//            'day_point_increasenumber'=>$day_point_increasenumber,
//            'month_valet_increasenumber'=>$month_valet_increasenumber,
//            'month_point_increasenumber'=>$month_point_increasenumber,
//
//            'user_total'=>$user_total,
//            'valet_total'=>$valet_total,
//            'point_total'=>$point_total,
//
//            'database_time'=>$new_databasetime
//        ];
        //最近登录记录
        return $this->render('welcome');
    }

}

