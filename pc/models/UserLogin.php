<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "user_login".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $username
 * @property string $password
 * @property string $token
 * @property string $last_login_time
 * @property string $reg_id
 * @property integer $reg_type
 * @property integer $status
 *
 * @property UserInfo $u
 */
class UserLogin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_login';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'reg_type', 'status'], 'integer'],
            [['token'], 'required'],
            [['last_login_time'], 'safe'],
            [['username', 'password'], 'string', 'max' => 50],
            [['token'], 'string', 'max' => 100],
            [['reg_id'], 'string', 'max' => 32],
            [['uid', 'status'], 'exist', 'skipOnError' => true, 'targetClass' => UserInfo::className(), 'targetAttribute' => ['uid' => 'id', 'status' => 'status']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'username' => 'Username',
            'password' => 'Password',
            'token' => 'Token',
            'last_login_time' => 'Last Login Time',
            'reg_id' => 'Reg ID',
            'reg_type' => 'Reg Type',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getU()
    {
        return $this->hasOne(UserInfo::className(), ['id' => 'uid', 'status' => 'status']);
    }
}
