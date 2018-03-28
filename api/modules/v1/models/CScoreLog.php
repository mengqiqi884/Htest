<?php

namespace v1\models;

use Yii;

/**
 * This is the model class for table "c_score_log".
 *
 * @property string $sl_id
 * @property integer $sl_user_id
 * @property integer $sl_state
 * @property integer $sl_good_id
 * @property string $sl_goodsname
 * @property integer $sl_score
 * @property string $sl_receivename
 * @property string $sl_receivephone
 * @property string $sl_receiveaddress
 * @property string $sl_operater
 * @property string $sl_logistics
 * @property string $sl_number
 * @property string $sl_remarks
 * @property string $created_time
 * @property integer $is_del
 */
class CScoreLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_score_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sl_user_id', 'sl_good_id', 'sl_receivename', 'sl_receivephone', 'sl_receiveaddress'], 'required'],
            [['sl_user_id', 'sl_state', 'sl_good_id', 'sl_score', 'is_del'], 'integer'],
            [['created_time'], 'safe'],
            [['sl_goodsname', 'sl_receiveaddress', 'sl_logistics'], 'string', 'max' => 200],
            [['sl_receivename', 'sl_operater'], 'string', 'max' => 100],
            [['sl_receivephone'], 'string', 'max' => 11],
            [['sl_number'], 'string', 'max' => 50],
            [['sl_remarks'], 'string', 'max' => 500]
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
            'sl_state' => 'Sl State',
            'sl_good_id' => 'Sl Good ID',
            'sl_goodsname' => 'Sl Goodsname',
            'sl_score' => 'Sl Score',
            'sl_receivename' => 'Sl Receivename',
            'sl_receivephone' => 'Sl Receivephone',
            'sl_receiveaddress' => 'Sl Receiveaddress',
            'sl_operater' => 'Sl Operater',
            'sl_logistics' => 'Sl Logistics',
            'sl_number' => 'Sl Number',
            'sl_remarks' => 'Sl Remarks',
            'created_time' => 'Created Time',
            'is_del' => 'Is Del',
        ];
    }
}
