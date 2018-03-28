<?php

namespace v1\models;

use Yii;

/**
 * This is the model class for table "c_score_list".
 *
 * @property string $sl_id
 * @property integer $sl_user_id
 * @property integer $sl_log_id
 * @property string $sl_rule
 * @property integer $sl_score
 * @property string $sl_act
 * @property string $created_time
 */
class CScoreList extends \yii\db\ActiveRecord
{
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
            [['sl_user_id', 'sl_score','sl_log_id'], 'integer'],
            [['created_time'], 'safe'],
            [['sl_rule'], 'string', 'max' => 200],
            [['sl_act'], 'string', 'max' => 10],
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
            'sl_rule' => 'Sl Rule',
            'sl_score' => 'Sl Score',
            'sl_act' => 'Sl Act',
            'created_time' => 'Created Time',
        ];
    }
}
