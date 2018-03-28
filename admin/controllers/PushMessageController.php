<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/1/20
 * Time: 10:45
 */

namespace admin\controllers;


use common\helpers\ArrayHelper;

class PushMessageController extends BaseController
{
    /*
     * system_message :系统消息
     */
    public static function PushNotes($registerid,$content,$type){
        $title="系统通知";
        $result='';
        if(!empty($registerid)){
            $result=ArrayHelper::pushNotifyByReg($registerid,$title,$content,'iOS sound',array('type'=>'note','state'=>$type));
        }
       // $rs=$result==""?'推送失败-registerid为空':($result==false?'推送失败':'推送成功');

    }
}