<?php

namespace admin\models;

use admin\models\CUser;
use Yii;

/**
 * This is the model class for table "c_forums".
 *
 * @property string $f_id
 * @property string $l_type1
 * @property string $l_input
 * @property integer $f_fup
 * @property integer $f_user_id
 * @property string $f_user_nickname
 * @property string $f_title
 * @property string $f_content
 * @property string $f_pic
 * @property integer $f_views
 * @property integer $f_replies
 * @property integer $f_is_top
 * @property integer $f_is_first_top
 * @property integer $is_week_new
 * @property integer $f_state
 * @property string $f_forbidden_reason
 * @property string $f_car_cycle
 * @property string $f_car_miles
 * @property string $f_car_describle
 * @property string $created_time
 * @property string $end_time
 * @property string $updated_time
 * @property integer $is_del
 */
class CForums extends \yii\db\ActiveRecord
{
    public $l_type1;
    public $l_input;
    public $end_time;
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
           // [['f_fup', 'f_user_nickname', 'f_title'], 'required'],
            [['f_fup', 'f_user_id', 'f_views', 'f_replies', 'f_is_top', 'f_is_first_top','is_week_new', 'f_state', 'is_del'], 'integer'],
            [['f_content'], 'string'],
            [['created_time', 'updated_time'], 'safe'],
            [['f_user_nickname'], 'string', 'max' => 150],
            [['f_title', 'f_pic', 'f_car_describle'], 'string', 'max' => 200],
            [['f_car_cycle', 'f_car_miles'], 'string', 'max' => 100],
            [['f_forbidden_reason'],'string','max'=>500,'tooLong'=>'最多不超过500字'],

            [['l_type1','l_input','end_time'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'f_id' => 'F ID',
            'f_fup' => '板块',
            'f_user_id' => 'F User ID',
            'f_user_nickname' => '发帖人',
            'f_title' => '帖子标题',
            'f_content' => 'F Content',
            'f_pic' => 'F Pic',
            'f_views' => '浏览量',
            'f_replies' => '回复量',
            'f_is_top' => '是否置顶',
            'f_is_first_top' => '是否第一次置顶',
            'is_week_new' => '每周一新',
            'f_state' => '帖子状态',
            'f_car_cycle' => 'F Car Cycle',
            'f_car_miles' => 'F Car Miles',
            'f_car_describle' => 'F Car Describle',
            'created_time' => '发帖时间',
            'updated_time' => 'Updated Time',
            'is_del' => 'Is Del',
        ];
    }

    public function getuser()
    {
        return $this->hasOne(CUser::className(), ['u_id'=>'f_user_id'])->onCondition(['c_user.is_del'=>0]);
    }
}
