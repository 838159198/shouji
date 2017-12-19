<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ManageLoginForm extends CFormModel
{
    public $username;
    public $password;
    public $rememberMe;
    public $verifyCode;

    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // username and password are required
            array('username, password', 'required'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
            // password needs to be authenticated
            array('password', 'authenticate'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements()),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'rememberMe' => '保持登录状态',
            'verifyCode' => '验证码',
            'username' => '用户名',
            'password' => '密码',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute,$params)
    {
        $this->_identity=new ManageIdentity($this->username,$this->password);
        if(!$this->_identity->authenticate()){

            if ($this->_identity->errorCode === ManageIdentity::ERROR_USERNAME_INVALID) {
                $this->addError('username', '不存在的用户名，请仔细检查账号！');
            }elseif($this->_identity->errorCode === ManageIdentity::ERROR_USERNAME_STOP){
                $this->addError('username', '此账户已被封号，不能使用');
            }elseif($this->_identity->errorCode === ManageIdentity::ERROR_PASSWORD_INVALID){
                $this->addError('password', '密码错误');
            } else {
                $this->addError('password', '错误的用户名或密码');
            }
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
        if($this->_identity===null)
        {
            $this->_identity=new ManageIdentity($this->username,$this->password);
            $this->_identity->authenticate();
        }
        if($this->_identity->errorCode===ManageIdentity::ERROR_NONE)
        {
            $duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
            /*Yii::app()->user->login($this->_identity,$duration);
            return true;*/
            $user = Yii::app()->user;
            $identity = $this->_identity;
            $user->login($identity, $duration);
            $user->setState('uid', $identity->getUid());
            $user->setState('username', $this->username);
            $user->setState('auth', $identity->getAuth());
            $user->setState('role', $identity->getRole());
            $user->setState('group', $identity->getGroup());
            $user->setState('type', Common::USER_TYPE_MANAGE);
            return true;
        }
        else
            return false;
    }
}
