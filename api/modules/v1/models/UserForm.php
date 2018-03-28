<?php

namespace v1\models;

use Yii;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;

class UserForm extends \yii\base\Model
{
    /**
     * @inheritdoc
     */
    public $logo;
    public $sex;
    public $username;
    public $brand_id;
    public $age;

    public function rules()
    {
        return [
            [['nickname', 'district'], 'string', 'max' => 100],
            [['logo'],'image', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            [['sex','age'],'integer'],
            [['logo','sex','nickname','district'],'safe']
        ];

    }
    
    public function upload($logo,$pic_path)
    {   
        $res = $logo->saveAs($pic_path);
        return $res;
    }

    public function behaviors(){
        return [
            TimestampBehavior::className()
        ];
    }

}
