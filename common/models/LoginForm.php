<?php
namespace common\models;

use Yii;
use yii\base\Model;
use admin\models\Admin;
/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    //public $rememberMe = false;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            ['username', 'required','message'=>'用户名不能为空'],
            ['password','required','message'=>'密码不能为空'],
            // rememberMe must be a boolean value
           // ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['username','validateType']
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if(!$user){
                $this->addError($attribute,'用户不存在');
            }else if (!$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码错误');
            }
        }
    }

    public function validateType($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $user_type = $user->cliendInfo->ci_type;
            if(!$user->checkType($user_type)) {
                $this->addError($attribute, '你无权登录');
            }
        }
    }
    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 3600 * 24 * 30);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Admin::findByUsername($this->username);
        }

        return $this->_user;
    }
}
