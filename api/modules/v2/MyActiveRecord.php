<?php
namespace v2;

use Yii;
use yii\behaviors\TimestampBehavior;

class MyActiveRecord extends V2ActiveRecord
{
    //搜索的最小距离
    const DISTANCE=5000;

    public function behaviors()
    {
        return [
             TimestampBehavior::className()
        ];
    }

    public static function insertTime(){
        return date('Y-m-d H:i:s');
    }

}
