<?php

namespace admin\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "c_user".
 *
 * @property string $u_id
 * @property integer $u_type
 * @property string $u_phone
 * @property string $u_pwd
 * @property string $u_headImg
 * @property string $u_nickname
 * @property string $u_sex
 * @property integer $u_age
 * @property integer $u_score
 * @property integer $u_cars
 * @property integer $u_forums
 * @property integer $u_state
 * @property string $u_token
 * @property string $u_register_id
 * @property integer $is_del
 * @property string $created_time
 * @property string $updated_time
 *
 * @property integer $l_type
 * @property string $l_input
 * @property string $end_time
 */
class CUser extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $l_type;
    public $l_input;
    public $end_time;

    const IS_DEL = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['u_type', 'u_age', 'u_score', 'u_cars', 'u_forums', 'u_state', 'is_del'], 'integer'],
            [['u_pwd'], 'required'],
            [['created_time', 'updated_time'], 'safe'],
            [['u_phone', 'u_token', 'u_register_id'], 'string', 'max' => 32],
            [['u_pwd'], 'string', 'max' => 32],
            [['u_headImg', 'u_nickname'], 'string', 'max' => 200],
            [['u_sex'], 'string', 'max' => 2],

            [['l_type','l_input', 'end_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'u_id' => 'U ID',
            'u_type' => 'U Type',
            'u_phone' => '手机号',
            'u_pwd' => 'U Pwd',
            'u_headImg' => '头像',
            'u_nickname' => '昵称',
            'u_sex' => '性别',
            'u_age' => '年龄(岁)',
            'u_score' => '积分数',
            'u_cars' => 'U Cars',
            'u_forums' => '发帖数',
            'u_state' => '用户状态',
            'u_token' => 'U Token',
            'u_register_id' => 'U Register ID',
            'is_del' => 'Is Del',
            'created_time' => '注册时间',
            'updated_time' => 'Updated Time',
        ];
    }

    /**
     * 根据 token 查询身份。
     *
     * @param string $token 被查询的 token
     * @return IdentityInterface|null 通过 token 得到的身份对象
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['u_token' => $token,'is_del'=>static::IS_DEL]);
    }

    public static function findIdentity($id){}

    public function getId(){
        return $this->u_id;
    }

    public function getAuthKey(){}

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey){}


    /*获取所有的用户账号、昵称*/
    public static function GetAllUser($type){
        $alluser = [];
        switch($type){
            case 'account':
                $user=self::find()->where(['is_del'=>0,'u_type'=>1,'u_state'=>1])->asArray()->all();
                foreach($user as $k=>$v){
                    $alluser[]=$v['u_phone'];
                }
                break;
            case 'nickname':
                $user=self::find()->where(['is_del'=>0,'u_type'=>1,'u_state'=>1])->asArray()->all();
                foreach($user as $k=>$v){
                    $alluser[]=$v['u_nickname'];
                }
                break;
        }
        return $alluser;
    }

    public static function getUserState($stats)
    {
        $str = '无';
        switch($stats){
            case 1: $str = '<span class="badge badge-success">启用</span>';break;
            case 2: $str = '<span class="badge badge-info">禁用</span>';break;
        }
        return $str;
    }
}
