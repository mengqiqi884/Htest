<?php

namespace admin\models;

use v1\models\CUser;
use Yii;

/**
 * This is the model class for table "c_score_list".
 *
 * @property string $sl_id
 * @property integer $sl_log_id
 * @property integer $sl_user_id
 * @property string $user_name
 * @property string $sl_rule
 * @property integer $sl_score
 * @property string $sl_act
 * @property string $created_time
 * @property string $end_time
 */
class CScoreList extends \yii\db\ActiveRecord
{
    public $user_name;
    public $end_time;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_score_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sl_user_id', 'sl_rule'], 'required'],
            [['sl_user_id', 'sl_score','sl_user_id'], 'integer'],
            [['created_time'], 'safe'],
            [['sl_rule'], 'string', 'max' => 200],
            [['sl_act'], 'string', 'max' => 10],

            [['user_name'],'string','max'=>200],
            [['end_time'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sl_id' => 'Sl ID',
            'sl_user_id' => 'Sl User ID',
            'sl_rule' => '方式',
            'sl_score' => '积分数',
            'sl_act' => 'Sl Act',
            'created_time' => '时间',
        ];
    }

    public function getuser()
    {
        return $this->hasOne(CUser::className(), ['u_id'=>'sl_user_id'])->onCondition(['c_user.is_del'=>0]);
    }
}
