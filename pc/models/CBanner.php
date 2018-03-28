<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "c_banner".
 *
 * @property string $b_id
 * @property integer $b_location
 * @property string $b_img
 * @property string $b_url
 * @property string $b_title
 * @property string $content
 * @property integer $b_sortorder
 * @property string $created_time
 */
class CBanner extends \yii\db\ActiveRecord
{
    public $pic;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_banner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['b_location', 'b_sortorder'], 'integer'],
            [[ 'b_location','b_img'], 'required'],
            [['created_time'], 'safe'],
            [['b_img', 'b_url'], 'string', 'max' => 200],
            [['b_title'], 'string', 'max' => 100],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'b_id' => 'B ID',
            'b_location' => '页面位置',
            'b_img' => '图片',
            'b_url' => '跳转地址',
            'b_title' => '标题',
            'content' => '内容',
            'b_sortorder' => '排序',
            'created_time' => 'Created Time',
            'pic' => '广告图'
        ];
    }
}
