<?php

namespace admin\models;

use v1\models\CUser;
use Yii;

/**
 * This is the model class for table "c_useradvice".
 *
 * @property string $a_id
 * @property integer $user_id
 * @property integer $content
 * @property string $created_time
 * @property integer $is_del
 */
class CUseradvice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_useradvice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'content',], 'required'],
            [['user_id', 'is_del'], 'integer'],
            [['content'],'string'],
            [['created_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'a_id' => '序号',
            'user_id' => '用户昵称',
            'content' => '意见内容',
            'created_time' => '反馈日期',
            'is_del' => 'Is Del',
        ];
    }

    public function getuser()
    {
        return $this->hasOne(CUser::className(), ['u_id'=>'user_id'])->onCondition(['c_user.is_del'=>0]);
    }
}
