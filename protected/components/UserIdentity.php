<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id, $_uid, $_holder, $_agent, $_type, $_point, $_scale;
    const ERROR_USERNAME_STOP = 403;

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
        $member_model = Member::model();
        $member=$member_model->find('LOWER(username)=?',array(strtolower($this->username)));
        if(empty($member))
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        elseif($member['status']!=1)
            $this->errorCode=self::ERROR_USERNAME_STOP;
        else if(!$member->validatePassword2($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            //$this->_id=$member->id;
            //name
            //$this->username=$member->username;
            //username
            $this->setUid($member->id);
            $this->setType($member->type);
            $this->setState("member_id",$member->id);
            $this->setState("member_holder",$member->holder);
            $this->setState("member_username",$member->username);
            $this->setState("member_agent",$member->agent);
            $this->setState("type","member");
            //用户登录日志
            $time = time();
            $ip = Yii::app()->request->userHostAddress;
            $logmodel = new MemberLoginLog();
            $logmodel -> uid = $member -> id;
            $logmodel -> overtime = $time;
            $logmodel ->overip = $ip;
            $logmodel -> insert();
            //最后登录时间和ip
            $member -> overtime = $time;
            $member -> login_ip = $ip;
            $member -> update();

            $this->errorCode=self::ERROR_NONE;
        }
        return $this->errorCode==self::ERROR_NONE;

/*		$user=User::model()->find('LOWER(username)=?',array(strtolower($this->username)));
		if($user===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(!$user->validatePassword($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->_id=$user->id;
			$this->username=$user->username;
			$this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode==self::ERROR_NONE;*/
	}

	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
    {
        return parent::getId();
    }
    public function getHolder()
    {
        return $this->_holder;
    }

    public function getUid()
    {
        return $this->_uid;
    }

    public function getAgent()
    {
        return $this->_agent;
    }

    public function setAgent($agent)
    {
        $this->_agent = $agent;
    }

    public function setHolder($holder)
    {
        $this->_holder = $holder;
    }

    public function setUid($uid)
    {
        $this->_uid = $uid;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->_type = $type;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param float $point
     */
    public function setPoint($point)
    {
        $this->_point = $point;
    }

    /**
     * @return float
     */
    public function getPoint()
    {
        return $this->_point;
    }

    /**
     * @return float
     */
    public function getScale()
    {
        return $this->_scale;
    }

    /**
     * @param float $scale
     */
    public function setScale($scale)
    {
        $this->_scale = $scale;
    }
}