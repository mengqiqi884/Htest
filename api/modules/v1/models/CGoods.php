<?php

namespace v1\models;

use Yii;

/**
 * This is the model class for table "c_goods".
 *
 * @property string $g_id
 * @property string $g_name
 * @property string $g_pic
 * @property string $g_instroduce
 * @property integer $g_sellout
 * @property integer $g_amount
 * @property integer $g_score
 * @property integer $g_state
 * @property string $created_time
 * @property integer $is_del
 */
class CGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['g_name', 'g_pic'], 'required'],
            [['g_sellout', 'g_amount', 'g_score', 'g_state', 'is_del'], 'integer'],
            [['created_time'], 'safe'],
            [['g_name'], 'string', 'max' => 100],
            [['g_pic'], 'string', 'max' => 200],
            [['g_instroduce'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'g_id' => 'G ID',
            'g_name' => 'G Name',
            'g_pic' => 'G Pic',
            'g_instroduce' => 'G Instroduce',
            'g_sellout' => 'G Sellout',
            'g_amount' => 'G Amount',
            'g_score' => 'G Score',
            'g_state' => 'G State',
            'created_time' => 'Created Time',
            'is_del' => 'Is Del',
        ];
    }
}
