<?php

namespace v1\models;

use Yii;

/**
 * This is the model class for table "c_sensitive".
 *
 * @property string $s_id
 * @property string $s_name
 * @property string $created_time
 * @property integer $is_del
 */
class CSensitive extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_sensitive';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['s_name'], 'required'],
            [['created_time'], 'safe'],
            [['is_del'], 'integer'],
            [['s_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            's_id' => 'S ID',
            's_name' => 'S Name',
            'created_time' => 'Created Time',
            'is_del' => 'Is Del',
        ];
    }
}
