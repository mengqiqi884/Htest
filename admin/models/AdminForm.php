<?php
namespace admin\models;

use admin\models\Admin;
use Yii;
use yii\base\Exception;
use yii\base\Model;
/**
 * Login form
 */
class AdminForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username' => '登录名',
            'password' => '登录密码',
        ];
    }


    public function checkStatus($status){
        if(!$this->hasErrors()){
            if($status != 1){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }

    public function checkRole($role){
        if(!$this->hasErrors()){
            if(!in_array($role,[174,176])){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }

    public function validatePassword()
    {
        $user = $this->getUser();
        if(!$user){
            $this->addError('username','用户不存在');
        }elseif (!$this->checkPassword($user->a_pwd)) {
            $this->addError('password', '密码错误');
        }elseif (!$this->checkStatus($user->a_state)) {
            $this->addError('username', '该账号状态异常,请联系管理员');
        }elseif (!$this->checkRole($user->a_role)) {
            $this->addError('username', '该用户非后台管理员');
        }
//        }elseif ($user->wa_lock!=0) {
//            $this->addError('wa_username', '该用户已被锁定');
//        }
    }

    public function checkPassword($password){
        if(!$this->hasErrors()){
            return $password == md5($this->password);
        }else{
            return false;
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }


    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Admin::findIdentityByUsername($this->username);
        }

        return $this->_user;
    }

    public function UpdateModel(){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $user =$this->getUser();
            $user->a_token = Yii::$app->security->generateRandomString();
            $user->created_time = date('Y-m-d H:i:s',time());
            $user->updated_time = date('Y-m-d H:i:s',time());
            $r=$user->save();

            $transaction->commit();//提交
            if(!$r){
                return false;
            }else{
                return true;
            }
        }catch(Exception $e){
            $transaction->rollBack();
        }
    }

}
