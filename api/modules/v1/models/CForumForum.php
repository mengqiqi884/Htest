<?php

namespace v1\models;

use Yii;

/**
 * This is the model class for table "c_forum_forum".
 *
 * @property string $ff_id
 * @property string $ff_name
 * @property string $ff_logo
 * @property integer $is_del
 */
class CForumForum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_forum_forum';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ff_name', 'ff_logo'], 'required'],
            [['is_del'], 'integer'],
            [['ff_name'], 'string', 'max' => 100],
            [['ff_logo'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ff_id' => 'Ff ID',
            'ff_name' => 'Ff Name',
            'ff_logo' => 'Ff Logo',
            'is_del' => 'Is Del',
        ];
    }
}
