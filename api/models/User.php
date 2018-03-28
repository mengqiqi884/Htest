<?php

namespace api\models;

use common\models\MyActiveRecord;
use Yii;
/**
 * This is the model class for table "user".
 *
 * @property string $user_id
 * @property string $username
 * @property integer $sex
 * @property string $logo
 * @property integer $type
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 * @property integer $brand_id
 * @property integer $address
 * @property integer $lnt
 * @property integer $lat
 */
class User extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sex', 'type', 'created_at', 'updated_at', 'is_del', 'brand_id'], 'integer'],
            [['username'], 'string', 'max' => 100],
            [['logo'], 'string', 'max' => 500],
            [['paypwd'],'string','max'=>32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'username' => 'Username',
            'sex' => '性别',
            'logo' => 'Logo',
            'type' => '1锁客 2锁有者',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_del' => 'Is Del',
            'brand_id' => '汽车品牌',
            'address' => '详细地址',
            'lnt' => '经度',
            'lat' => '纬度',
        ];
    }


    /**
     * 根据给到的ID查询身份。
     *
     * @param string|integer $id 被查询的ID
     * @return IdentityInterface|null 通过ID匹配到的身份对象
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }


    /**
     * 根据 token 查询身份。
     *
     * @param string $token 被查询的 token
     * @return IdentityInterface|null 通过 token 得到的身份对象
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token,'is_del'=>0]);
    }

    /**
     * @return int|string 当前用户ID
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * @return string 当前用户的（cookie）认证密钥
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

}
