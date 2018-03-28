<?php

namespace v1\models;

use Yii;

/**
 * This is the model class for table "c_carreplace_pics".
 *
 * @property string $rp_id
 * @property integer $rp_r_id
 * @property integer $rp_type
 * @property string $rp_pics
 * @property string $created_time
 * @property integer $is_del
 */
class CCarreplacePics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_carreplace_pics';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rp_r_id', 'rp_type', 'is_del'], 'integer'],
            [['created_time'], 'safe'],
            [['rp_pics'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rp_id' => 'Rp ID',
            'rp_r_id' => 'Rp R ID',
            'rp_type' => 'Rp Type',
            'rp_pics' => 'Rp Pics',
            'created_time' => 'Created Time',
            'is_del' => 'Is Del',
        ];
    }
}
