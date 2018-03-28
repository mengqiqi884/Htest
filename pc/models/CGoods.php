<?php

namespace admin\models;

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
    public $url;
    public $pic;
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
            [['g_name'], 'required'],
            [['g_sellout', 'g_amount', 'g_score', 'g_state', 'is_del'], 'integer'],
            [['created_time'], 'safe'],
            [['g_name'], 'string', 'max' => 100],
            [['g_pic'], 'string', 'max' => 200],
            [['g_instroduce'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'g_id' => 'G ID',
            'g_name' => '商品名称',
            'g_pic' => '商品图片',
            'g_instroduce' => '商品介绍',
            'g_sellout' => '已售',
            'g_amount' => '库存',
            'g_score' => '兑换积分数',
            'g_state' => '状态',
            'created_time' => '上架时间',
            'is_del' => 'Is Del',
        ];
    }
}
