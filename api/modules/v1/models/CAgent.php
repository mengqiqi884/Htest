<?php

namespace v1\models;

use Yii;

/**
 * This is the model class for table "c_agent".
 *
 * @property string $a_id
 * @property string $a_account
 * @property string $a_pwd
 * @property string $a_name
 * @property string $a_areacode
 * @property string $a_brand
 * @property string $a_address
 * @property string $a_concat
 * @property string $a_phone
 * @property string $a_email
 * @property string $a_position
 * @property integer $a_state
 * @property string $created_time
 * @property string $updated_time
 * @property integer $is_del
 */
class CAgent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_agent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['a_account', 'a_pwd', 'a_name', 'a_areacode', 'a_brand'], 'required'],
            [['a_state', 'is_del'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['a_account', 'a_pwd'], 'string', 'max' => 32],
            [['a_name'], 'string', 'max' => 50],
            [['a_areacode', 'a_address', 'a_position'], 'string', 'max' => 200],
            [['a_brand', 'a_concat', 'a_email'], 'string', 'max' => 100],
            [['a_phone'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'a_id' => 'A ID',
            'a_account' => 'A Account',
            'a_pwd' => 'A Pwd',
            'a_name' => 'A Name',
            'a_areacode' => 'A Areacode',
            'a_brand' => 'A Brand',
            'a_address' => 'A Address',
            'a_concat' => 'A Concat',
            'a_phone' => 'A Phone',
            'a_email' => 'A Email',
            'a_position' => 'A Position',
            'a_state' => 'A State',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'is_del' => 'Is Del',
        ];
    }
}
