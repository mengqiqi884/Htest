<?php

namespace admin\models;

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
    public $Cfile;
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

            [['s_name'], 'validItem'], //添加场景
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            's_id' => 'S ID',
            's_name' => '名称',
            'created_time' => 'Created Time',
            'is_del' => 'Is Del',
        ];
    }

    //场景说明
    public function validItem()
    {
        $query=self::find();
        if(!empty($this->s_name)){
            $query->where(['s_name'=>$this->s_name]);
        }
        if(!empty($this->s_id)){
            $query->andWhere(['<>','s_id',$this->s_id]);
        }
        $model=$query->one();
        if(!empty($model)){
            $this->addError('s_name','*该敏感词汇已存在');
        }
    }
}
