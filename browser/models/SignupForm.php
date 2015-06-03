<?php

namespace app\models;

use yii\base\Model;
use app\models\User;

/**
 * LoginForm is the model behind the login form.
 */
class SignupForm extends Model
{
    public $username;
    public $password;
    public $confirm_password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password', 'confirm_password'], 'required'],
            
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'string', 'min' => 4, 'max' => 50],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This user already exist'],
            
            ['password', 'string', 'min' => 6],
            
            ['confirm_password', 'string', 'min' => 6],
            ['confirm_password', 'validatePasswords'],
        ];
    }
    
    public function validatePasswords(){
        if (!$this->hasErrors()) {
            if(!(strcasecmp($this->password, $this->confirm_password) === 0)){
                $this->addError('confirm_password', 'The entered confirm password do not match.');
            }
        }
    }
    
    /**
     * Signup new user.
     * @return User|null whether the user is signup in successfully
     */
    public function signup()
    {
        if($this->validate()){
            $user = new User();
            $user->username = $this->username;
            $user->setPassword($this->password);
            $user->save();
            return $user;
        }
        return null;
    }
}
