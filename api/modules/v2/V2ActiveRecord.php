<?php
namespace v2;

use Yii;
use yii\db\ActiveRecord;

class V2ActiveRecord extends ActiveRecord
{

    public static function getDb()
    {
       return V2::getInstance()->get('db');
    }
}
