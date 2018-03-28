<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "c_forum_rule".
 *
 * @property string $fr_id
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
            [['fr_item','fr_score'], 'required'],
            [['fr_score', 'is_del'], 'integer'],
            [['created_time'], 'safe'],
            [['fr_item'], 'string', 'max' => 200],

            [['fr_item'], 'validItem'], //添加场景
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fr_id' => 'Fr ID',
            'fr_item' => '规则项',
            'fr_score' => '奖励积分',
            'created_time' => '设置日期',
            'is_del' => 'Is Del',
        ];
    }

    //场景说明
    public function validItem()
    {
        $query=self::find();
        if(!empty($this->fr_item)){
            $query->where(['fr_item'=>$this->fr_item]);
        }
        if(!empty($this->fr_id)){
            $query->andWhere(['<>','fr_id',$this->fr_id]);
        }
        $model=$query->one();
        if(!empty($model)){
            $this->addError('fr_item','*该积分规则已设置');
        }
    }
}
