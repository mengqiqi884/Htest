<?php

namespace v1\models;

use Yii;

/**
 * This is the model class for table "c_city".
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property integer $level
 * @property integer $status
 * @property string $parent
 * @property integer $is_hot
 * @property string $create_time
 */
class CCity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level', 'status', 'is_hot'], 'integer'],
            [['create_time'], 'safe'],
            [['id'], 'string', 'max' => 150],
            [['name'], 'string', 'max' => 90],
            [['code', 'parent'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            'level' => 'Level',
            'status' => 'Status',
            'parent' => 'Parent',
            'is_hot' => 'Is Hot',
            'create_time' => 'Create Time',
        ];
    }
}
