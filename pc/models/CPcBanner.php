<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "c_pc_banner".
 *
 * @property string $id
 * @property string $url
 * @property integer $type
 * @property string $pic
 * @property integer $is_del
 */
class CPcBanner extends \yii\db\ActiveRecord
{
    public $title;
    public $p_img;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_pc_banner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'is_del'], 'integer'],
            [['pic'], 'required'],
            [['url', 'pic','p_img'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => '图片路径',
            'title' => '标题',
            'type' =>'Type',
            'pic' => '图片',
            'is_del' => 'Is Del',
        ];
    }

    /*根据id查找位置*/
    public static function getLocationName($location){
        switch($location){
            case '1':$name="首页";break;
            case '2':$name="社区";break;
            default:$name="广告位置出错";break;
        }
        return $name;
    }
}
