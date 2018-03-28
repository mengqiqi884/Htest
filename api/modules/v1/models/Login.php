<?php

namespace v1\models;

use common\models\MyActiveRecord;
use Yii;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "login".
 *
 * @property string $login_id
 * @property integer $user_id
 * @property string $account
 * @property string $token
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class Login extends MyActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'login';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['account'], 'string', 'max' => 11],
            [['token'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login_id' => 'Login ID',
            'user_id' => 'User ID',
            'account' => 'Account',
            'token' => 'Token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_del' => 'Is Del',
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        // 去掉一些包含敏感信息的字段
        $fields['type'] = function(){return $this->userInfo->type;};
        $fields['paypwd'] =function(){return $this->userInfo->paypwd;};
        return $fields;
    }

    /**
     * 根据用户名密码验证用户
     */
    public static function findByUser($username){
//        $password = md5($password);
        return static::findOne(['account'=>$username,'is_del'=>0]);
    }

    public function  getUserInfo(){
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
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
    public static function findIdentityByAccessToken($token,$type = null)
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

    /**
     *
     */
    public static function myIdentifyByAccessToken(){
        $token = Yii::$app->request->post('token','');
        if(empty($token)){
            echo json_encode(['code'=>'401','message'=>'无效请求']);
            exit;
        }
        $model = static::findOne(['token' => $token,'is_del'=>1]);
        if(empty($model)){
            echo json_encode(['code'=>'401','message'=>'无效请求']);
            exit;
        }
        return $model;
    }



}
