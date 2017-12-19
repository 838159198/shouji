<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class ManageIdentity extends CUserIdentity
{
    private $_id,$_uid, $_auth, $_role,$_group;
    const ERROR_USERNAME_STOP = 403;
    const ERROR_STATUS_IDENTITY = 101;

    public function getId()
    {
        return parent::getId();
    }
    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        $manage=Manage::model()->find('LOWER(username)=?',array(strtolower($this->username)));
        if(empty($manage))
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        elseif($manage['status']!=1)
            $this->errorCode=self::ERROR_USERNAME_STOP;
        else if(!$manage->validatePassword($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            //$this->_id=$manage->id;
            $this->setUid($manage->id);
            $this->setState("manage_id",$manage->id);
            $this->setState("manage_name",$manage->name);
            $this->setState("manage_username",$manage->username);
            $this->setState("type","manage");
            $this->setState("manage_auth",$manage->auth);
            $this->setState("manage_group",$manage->group);
            //用户登录日志
            $time = time();
            $ip = Yii::app()->request->userHostAddress;
            $logmodel = new ManageLoginLog();
            $logmodel -> uid = $manage -> id;
            $logmodel -> overtime = $time;
            $logmodel ->overip = $ip;
            $logmodel -> insert();
            //最后登录时间和ip
            $manage -> overtime = $time;
            $manage -> overip = $ip;
            $manage -> update();
            $this->errorCode=self::ERROR_NONE;
        }
        return $this->errorCode==self::ERROR_NONE;
    }

    /**
     * @return integer the ID of the user record
     */
    /*public function getId()
    {
        return $this->_id;
    }*/
    public function setAuth($auth)
    {
        $this->_auth = $auth;

    }

    public function getAuth()
    {

        return $this->_auth;
    }

    public function setUid($uid)
    {
        $this->_uid = $uid;
    }

    public function getUid()
    {
        return $this->_uid;
    }

    public function setRole($role)
    {
        $this->_role = $role;
    }

    public function getRole()
    {
        return $this->_role;
    }

    public function setGroup($group)
    {
        $this->_group = $group;
    }

    public function getGroup()
    {
        return $this->_group;
    }

}