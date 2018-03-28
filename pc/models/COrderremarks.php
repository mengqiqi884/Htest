<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "c_orderremarks".
 *
 * @property string $or_id
 * @property integer $or_order_id
 * @property string $or_author
 * @property string $or_content
 * @property string $created_time
 * @property integer $is_del
 */
class COrderremarks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_orderremarks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['or_order_id', 'or_author'], 'required'],
            [['or_order_id', 'is_del'], 'integer'],
            [['created_time'], 'safe'],
            [['or_author'], 'string', 'max' => 200],
            [['or_content'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'or_id' => '序号',
            'or_order_id' => 'Or Order ID',
            'or_author' => '备注者',
            'or_content' => '备注内容',
            'created_time' => '备注时间',
            'is_del' => 'Is Del',
        ];
    }
}
