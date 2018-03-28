<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "c_message".
 *
 * @property string $m_id
 * @property integer $m_type
 * @property integer $m_user_id
 * @property string $m_author
 * @property string $m_content
 * @property string $m_url
 * @property string $created_time
 * @property integer $m_is_read
 */
class CMessage extends \yii\db\ActiveRecord
{
    public $is_all_user;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['m_type', 'm_user_id','m_is_read'], 'integer'],
           // [['m_user_id'], 'required'],
            [['created_time'], 'safe'],
            [['m_author'], 'string', 'max' => 50],
            [['m_content'], 'string', 'max' => 500],
            [['m_url'],'string','max' =>1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'm_id' => 'M ID',
            'm_type' => 'M Type',
            'm_user_id' => 'M User ID',
            'm_author' => '创建者',
            'm_content' => '消息内容',
            'm_url' =>'图片链接',
            'created_time' => '发送时间',
        ];
    }
}
