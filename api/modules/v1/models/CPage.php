<?php

namespace v1\models;

use Yii;

/**
 * This is the model class for table "c_page".
 *
 * @property string $p_id
 * @property string $p_content
 * @property string $p_remark
 */
class CPage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['p_content'], 'required'],
            [['p_content'], 'string'],
            [['p_remark'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'p_id' => 'P ID',
            'p_content' => 'P Content',
            'p_remark' => 'P Remark',
        ];
    }
}
