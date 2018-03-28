<?php

namespace v1\models;

use Yii;

/**
 * This is the model class for table "c_forums".
 *
 * @property string $f_id
 * @property integer $f_fup
 * @property integer $f_user_id
 * @property string $f_user_nickname
 * @property string $f_pic
 * @property string $f_title
 * @property string $f_content
 * @property integer $f_views
 * @property integer $f_replies
 * @property integer $f_is_top
 * @property integer $f_is_first_top
 * @property integer $f_state
 * @property string $f_car_cycle
 * @property string $f_car_miles
 * @property string $f_car_describle
 * @property string $created_time
 * @property string $updated_time
 * @property integer $is_del
 */
class CForums extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_forums';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['f_fup', 'f_user_nickname', 'f_title'], 'required'],
            [['f_fup', 'f_user_id', 'f_views', 'f_replies', 'f_is_top', 'f_is_first_top', 'f_state', 'is_del'], 'integer'],
            [['f_content'], 'string'],
            [['created_time', 'updated_time'], 'safe'],
            [['f_user_nickname'], 'string', 'max' => 150],
            [['f_pic', 'f_title', 'f_car_describle'], 'string', 'max' => 200],
            [['f_car_cycle', 'f_car_miles'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'f_id' => 'F ID',
            'f_fup' => 'F Fup',
            'f_user_id' => 'F User ID',
            'f_user_nickname' => 'F User Nickname',
            'f_pic' => 'F Pic',
            'f_title' => 'F Title',
            'f_content' => 'F Content',
            'f_views' => 'F Views',
            'f_replies' => 'F Replies',
            'f_is_top' => 'F Is Top',
            'f_is_first_top' => 'F Is First Top',
            'f_state' => 'F State',
            'f_car_cycle' => 'F Car Cycle',
            'f_car_miles' => 'F Car Miles',
            'f_car_describle' => 'F Car Describle',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'is_del' => 'Is Del',
        ];
    }
}
