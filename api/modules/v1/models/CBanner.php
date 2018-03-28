<?php

namespace v1\models;

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
            [['b_img', 'b_sortorder'], 'required'],
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
            'b_location' => 'B Location',
            'b_img' => 'B Img',
            'b_url' => 'B Url',
            'b_title' => 'B Title',
            'content' => 'Content',
            'b_sortorder' => 'B Sortorder',
            'created_time' => 'Created Time',
        ];
    }
}
