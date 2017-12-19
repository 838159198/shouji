<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
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
            'username'=>'用户名',
            'password'=>'密码',
			'rememberMe'=>'保持登录(公共电脑请勿勾选)',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		$this->_identity=new UserIdentity($this->username,$this->password);
		if(!$this->_identity->authenticate())
            if ($this->_identity->errorCode === UserIdentity::ERROR_USERNAME_INVALID) {
                $this->addError('username', '不存在的用户名，请仔细检查账号！');
            }elseif($this->_identity->errorCode === UserIdentity::ERROR_USERNAME_STOP){
                $this->addError('username', '此账户已被封号，不能使用');
            }elseif($this->_identity->errorCode === UserIdentity::ERROR_PASSWORD_INVALID){
                $this->addError('password', '密码错误');
            } else {
                $this->addError('password', '错误的用户名或密码');
            }
			//$this->addError('password','Incorrect username or password.');
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			//Yii::app()->user->login($this->_identity,$duration);
			$user = Yii::app()->user;
			$identity = $this->_identity;
			$user->login($identity, $duration);
			$user->setState('member_uid', $identity->getUid());
			$user->setState('member_username', $this->username);
			$user->setState('member_manage', false); //是否管理员登陆
			$user->setState('member_agent', $identity->getAgent());
			$user->setState('member_point', $identity->getPoint());
			$user->setState('member_scale', $identity->getScale());
			$user->setState('member_cooperate', ''); //如果是从合作网站登录，保存合作网站的地址
			$user->setState('type', Member::getTypeKey($identity->getType()));
			return true;
		}
		else
			return false;
	}
}
