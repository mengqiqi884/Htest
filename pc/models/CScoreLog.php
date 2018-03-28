<?php

namespace admin\models;

use Yii;
use v1\models\CUser;
/**
 * This is the model class for table "c_score_log".
 *
 * @property string $sl_id
 * @property integer $sl_user_id
 * @property integer $user_name
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
 * @property string $end_time
 * @property integer $is_del
 */
class CScoreLog extends \yii\db\ActiveRecord
{
    public $user_name;
    public $end_time;
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
            [['sl_remarks'], 'string', 'max' => 500],

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
            'user_name' =>'用户姓名',
            'sl_state' => '状态',
            'sl_good_id' => 'Sl Good ID',
            'sl_goodsname' => '兑换商品',
            'sl_score' => '兑换的积分数',
            'sl_receivename' => '邮寄姓名',
            'sl_receivephone' => '邮寄联系方式',
            'sl_receiveaddress' => '邮寄地址',
            'sl_operater' => '操作员',
            'sl_logistics' => '物流公司',
            'sl_number' => '物流单号',
            'sl_remarks' => '备注',
            'created_time' => '兑换时间',
            'is_del' => 'Is Del',
        ];
    }

    public function getuser()
    {
        return $this->hasOne(CUser::className(), ['u_id'=>'sl_user_id'])->onCondition(['c_user.is_del'=>0]);
    }
}
