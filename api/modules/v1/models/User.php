<?php

namespace v1\models;

use common\models\MyActiveRecord;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "user".
 *
 * @property string $user_id
 * @property integer $login_id
 * @property string $phone
 * @property string $name
 * @property string $city
 * @property string $lng
 * @property string $lat
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
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
            [['login_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['phone'], 'string', 'max' => 11],
            [['name', 'lng', 'lat'], 'string', 'max' => 100],
            [['city'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'login_id' => 'Login ID',
            'phone' => 'Phone',
            'name' => 'Name',
            'city' => 'City',
            'lng' => 'Lng',
            'lat' => 'Lat',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_del' => 'Is Del',
        ];
    }

    /*
     * 用户表与用户地址表建立关联
     */
    public function getUserAddress(){
        // 用户表和用户地址表通过  UserAddress.user_id-> user_id 关联建立一对一关系
        return $this->hasMany(UserAddress::className(), ['user_id' => 'user_id']);
    }

    /*
     * 用户表与登陆表建立关联
     */
    public function getLogin(){
        // 用户表和登陆表通过  MerLogin.login_id-> login_id 关联建立一对一关系
        return $this->hasOne(MerLogin::className(), ['login_id' => 'login_id'])->onCondition(['is_del'=>0]);
    }

    /*
    * 新添用户
    */
    public static function AddUser($phone){
        //去登陆表查看是否存在该用户
        $login=MerLogin::find()->where(['is_del'=>0,'type'=>3,'account'=>$phone])->one();

        $transaction = Yii::$app->db->beginTransaction();
        try{
            if(empty($login)){
                $login=new MerLogin;
                $login->account=$phone;
                $login->type=3;
                $login->token=Yii::$app->security->generateRandomString();
                $login->role='1000';
                if(!$login->save()){
                    throw new Exception;
                }
            }
            $user=new User;
            $user->login_id=$login->login_id;
            $user->phone=$phone;
            $user->name=substr_replace($phone,'****',3,4);
            if(!$user->save()){
                throw new Exception;
            }
            $transaction->commit();
            return [$user->user_id, $user->name]; //用户id
        }catch(Exception $e){
            $transaction->rollBack();
            return 0;
        }
    }
}
