<?php

namespace v1\models;

use Yii;

/**
 * This is the model class for table "c_forum_rule".
 *
 * @property string $fr_id
 * @property integer $fr_fup
 * @property string $fr_item
 * @property integer $fr_score
 * @property string $created_time
 * @property integer $is_del
 */
class CForumRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_forum_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fr_fup', 'fr_item'], 'required'],
            [['fr_fup', 'fr_score', 'is_del'], 'integer'],
            [['created_time'], 'safe'],
            [['fr_item'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fr_id' => 'Fr ID',
            'fr_fup' => 'Fr Fup',
            'fr_item' => 'Fr Item',
            'fr_score' => 'Fr Score',
            'created_time' => 'Created Time',
            'is_del' => 'Is Del',
        ];
    }
}
