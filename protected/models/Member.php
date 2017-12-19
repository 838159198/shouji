<?php

/**
 * This is the model class for table "{{member}}".
 *
 * The followings are the available columns in table '{{member}}':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $tel
 * @property string $mail
 * @property string $qq
 * @property string $bank
 * @property string $bank_no
 * @property string $bank_site
 * @property string $holder
 * @property string $id_card
 * @property string $clients
 * @property string $jointime
 * @property string $overtime
 * @property string $scale
 * @property integer $status
 * @property string $content
 * @property string $agent
 * @property integer $type
 * @property string $alias
 * @property string $point
 * @property integer $province
 * @property integer $city
 * @property integer $county
 * @property string $regist_tel
 * @property string $manage_id
 * @property string $category
 * @property string $reg_ip
 * @property string $login_ip
 * @property string $invitationcode
 * @property string $subagent
 * @property string $weixin_openid
 * @property string $weixin_name
 * @property string $qrcode
 */
class Member extends CActiveRecord
{
    public $verifyCode,$password2,$verifyCode2;
	/** 普通用户 */
	const TYPE_USER = 0;
	/** 代理商 */
	const TYPE_AGENT = 1;
	/** 代理商子用户 */
	const TYPE_SYNTHSIZE = 2;
    /** 原线下地推用户 */
    const TYPE_DITUI= 3;
    /** 新线下地推用户 */
    const TYPE_NEWDT= 8;
    /** 经销商用户 */
    const TYPE_DEALER= 4;
    /** 用户-微信短信网站 */
    const TYPE_MSG= 5;
    /** 用户-广告合作 */
    const TYPE_ADVERT= 6;
    /** 其它用户 */
    const TYPE_OTHER= 7;

    /** 状态-可用 */
    const STATUS_TRUE = 1;
    /** 状态-不可用 */
    const STATUS_FALSE = 0;

    /** 用户重置密码 */
    const RESET_PASSWORD = 'sutui521';

    /** 单价策略-未使用 */
    const PSOD_STATUS_NONE = 0;
    /** 单价策略-使用中 */
    const PSOD_STATUS_ON = 1;
    /** 单价策略-已使用 */
    const PSOD_STATUS_OFF = 2;
    /** 是否跳过单价策略-是 */
    const PSOD_SKIP_TRUE = 1;
    /** 是否跳过单价策略-否 */
    const PSOD_SKIP_FALSE = 0;

    /** 默认代理商扣点 */
    const DEFAULT_POINT = 0.05;
    /** 客户默认分成比例 */
    const DEFAULT_SCALE = 0.05;

    /** 用户类型 - 已在做 */
    const TYPE_Y = 1;
    /** 用户类型 - 未上量 */
    const TYPE_W = 2;
    /** 用户类型 - 无效 */
    const TYPE_WU = 3;
    /** 用户类型 - 待定 */
    const TYPE_D = 4;
    /** 用户类型 - 流失中 */
    const TYPE_L = 5;


    private static $typeList = array('0' => '普通用户', '1' => '代理商', '2' => '代理商子用户', '3' => '原线下门店',4=>"批发商",5=>"微信/QQ/网站",6=>"广告合作",7=>"其它",'8' => '线下门店',);
    private static $psodStatusList = array('0' => '未使用', '1' => '使用中', '2' => '已使用');
    private static $psodSkipList = array('0' => '否', '1' => '是');
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{member}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //更新基本信息
            array('qq,tel,mail,status', 'required','on'=>'edit'),
            array('mail','email','on'=>'edit'),
            array('weixin_name','length','min'=>5,'on'=>'edit'),
            array('weixin_name','match','not'=>true,'pattern'=>'/[\x{4e00}-\x{9fa5}]/u','on'=>'edit,reg'),//微信号不能为汉字
            array('mail', 'length', 'max'=>30,'on'=>'edit'),
            array('qq,tel,subagent', 'numerical', 'integerOnly'=>true,'on'=>'edit'),
            array('tel','length','is'=>11,'on'=>'edit'),
            array('qq','length','max'=>11,'min'=>5,'on'=>'edit'),
            array('status', 'in', 'range' => array(0, 1),'on'=>'edit'),
            array('content', 'length', 'max'=>200,'on'=>'edit'),
            array('tel','match','pattern'=>'/^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])[0-9]{8}$/','on'=>'edit'),
            //银行信息
            array('holder,id_card,province, city, bank, bank_no, bank_site', 'required','on'=>'bank'),
            array('holder','length','max'=>10,'min'=>2,'on'=>'bank'),
            array('province, city, county', 'numerical', 'integerOnly'=>true,'on'=>'bank'),
            array('id_card','match','pattern'=>'/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/','on'=>'bank',"message"=>"请填写正确的身份证号码"),
            //注册
            array('qq,username,password,password2,tel,verifyCode,type', 'required','on'=>'reg,tgzc'),
            array('username','unique','on'=>'reg,tgzc'),
            array('username', 'length', 'min' => 5, 'max' => 15,'on'=>'reg,tgzc'),
            array('username','match','pattern'=>'/^[a-z0-9\-_]+$/','on'=>'reg,tgzc','message'=>'用户名必须为英文字母和数字'),

            array('password,password2', 'length', 'min' => 6, 'max' => 32,'on'=>'reg,tgzc'), //密码长度为6-16
            array('password2', 'compare', 'compareAttribute' => 'password','on'=>'reg,tgzc','message'=>'密码与确认密码必须一致'), //密码与确认密码必须一致
            array('qq,tel', 'numerical', 'integerOnly'=>true,'on'=>'reg,tgzc'),
            array('tel','length','is'=>11,'on'=>'reg,tgzc'),
            array('qq','length','max'=>11,'min'=>5,'on'=>'reg,tgzc'),
            array('weixin_name','length','min'=>5,'on'=>'reg'),
            array('tel','match','pattern'=>'/^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])[0-9]{8}$/','on'=>'reg,tgzc'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(),'on'=>'reg'),
            //array('invitationcode','length','is'=>6,'on'=>'reg,tgzc'),
            array('invitationcode', 'checkCode','on'=>'reg,tgzc'),

           // array('verifyCode2', 'captcha','captchaAction'=>'domains/captcha', 'allowEmpty' => !CCaptcha::checkRequirements(),'on'=>'reg'),

            // @todo Please remove those attributes that should not be searched.
            array('id, username,utm_source, password, tel, mail, qq, bank, bank_no, bank_site, holder, id_card, clients, jointime, overtime, scale, status, content, agent, type, alias, point, province, city, county, regist_tel, manage_id, category, reg_ip, login_ip ,invitationcode,subagent,weixin_openid,weixin_name,qrcode', 'safe', 'on'=>'search'),
        );
	}

    public function checkCode($attribute,$params){
        if($this->invitationcode==""){}
        else
        {
            $code=substr($this->invitationcode, 0, 2);
            $code1=substr($this->invitationcode, 2, 4);
            if($code!="st" &&  $code!="sp"){
                $this->addError($attribute, '非法字符!');
            }
            if(is_numeric($code1)==false){
                $this->addError($attribute, '非法字符!');
            }
        }
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => '用户名',
			'password' => '密码',
            'password2'=>'重复密码',
			'tel' => '手机号码',
			'mail' => '电子邮件',
			'qq' => 'QQ号码',
			'bank' => '开户银行',
			'bank_no' => '银行卡号',
			'bank_site' => '开户地址',
			'holder' => '收款人',
			'id_card' => '身份证号码',
			'clients' => 'Clients',
			'jointime' => '注册时间',
			'overtime' => '最后登录时间',
			'scale' => 'Scale',
			'status' => '用户状态',
			'content' => '备注',
			'agent' => '代理商ID',
			'type' => '用户类型',
			'alias' => '别名',
			'point' => '扣点',
			'province' => '省',
			'city' => '市',
			'county' => '县',
			'regist_tel' => '注册电话',
			'manage_id' => '客服ID',
			'category' => '用户类型',
			'reg_ip' => 'Reg Ip',
			'login_ip' => 'Login Ip',
            'invitationcode'=>'邀请码',
			'weixin_openid'=>'微信号',
            'weixin_name'=>'微信号',
            'subagent'=>'二级代理商',
            'uninstall'=>'卸载标志',
            'source_id'=>'推广渠道'
		);
	}



	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Member the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    /**
     * 获得支持银行列表
     * @return array
     */
    public static function getBankList()
    {
        return array(
            '工商银行' => '中国工商银行',
            '建设银行' => '中国建设银行',
            '农业银行' => '中国农业银行',
            '招商银行' => '中国招商银行',
            '支付宝' => '支付宝(到账时间以支付宝规则为准)',
            '中国银行' => '中国银行',
            '交通银行' => '交通银行',
            '邮政储蓄' => '中国邮政储蓄银行',
            '广发银行' => '广东发展银行',
            '光大银行' => '中国光大银行',
            '平安银行' => '平安银行',
            '华夏银行' => '华夏银行',
            '兴业银行' => '兴业银行',
            '浦发银行' => '上海浦东发展银行',
            '渤海银行' => '渤海银行',
        );
    }
    public function validatePassword($password)
    {
        return $this->createPassword($password) === $this->password;
    }
    public function validatePassword2($password)
    {
        return $password === $this->password;
    }

    /**
     * 获得加密密码
     * @param $password
     * @return string
     */
    public function createPassword($password)
    {
        return md5(strrev(md5(strrev(trim($password)))));
    }
    /*
   * 状态
   * */
    public function getXstatus()
    {
        $data = "";
        if($this->status==1){
            $data = "<font color=#006600><b>正常</b></font>";
        }elseif($this->status==0){
            $data = "<font color=#ff0000><b>锁定</b></font>";
        }else{
            $data = "<font color=#000000><b>发生错误</b></font>";
        }
        return $data;
    }
    /*
     * 用户类型
     * */
    public static function getXtype($type)
    {
        //，0普通用户，1代理商,2代理商子用户
        $data = "";
        if($type==1){
            $data = "<font color=#006600>代理商</font>";
        }
        elseif($type==2){
            $data = "<font color=#337ab7>代理商子用户</font>";
        }
        elseif($type==3){
            $data = "<font color=#f0070c>原线下门店</font>";
        }
        elseif($type==4){
            $data = "<font color=#f0070c>批发商</font>";
        }
        elseif($type==5){
            $data = "<font color=#f0070c>微信/QQ/网站</font>";
        }
        elseif($type==6){
            $data = "<font color=#f0070c>广告合作</font>";
        }
        elseif($type==7){
            $data = "<font color=#f0070c>其它</font>";
        }
        elseif($type==8){
            $data = "<font color=#f0070c>线下门店</font>";
        }
        elseif($type==0){
            $data = "普通用户";
        }else{
            $data = "<font color=#ff0000>发生错误</font>";
        }
        return $data;
    }
    /*
     * 邀请码
     * */
    public static function getInvitationcode($invitationcode)
    {
        $data = array("st0001"=>"裴建男","st9900"=>"周坤",);
        $result="";
        if(!empty($invitationcode))
        {
            foreach($data as $key=>$val)
            {
                if($key==$invitationcode)
                {
                    $result=$val;
                    break;
                }
            }
        }
        else
        {

        }


        return $result;
    }
    /*
    * 销售可申请
    * */
    public static function getTaskStatus($username)
    {
        $m=Member::model()->find('username=:username',array(':username'=>$username));
        $s=SerachInfo::model()->find('username=:username',array(':username'=>$username));
        if($m["type"]=="3")
        {
            if($s["reg_status"]=="1" && $s["status"]=="1")
            {
                $result="<font color=darkgreen>有效</font>";
            }
            elseif($s["reg_status"]=="1" && $s["status"]=="0")
            {
                $result="<font color=#f0070c>未审核</font>";
            }
            elseif($s["reg_status"]=="1" && $s["status"]=="2")
            {
                $result="<font color=#f0070c>无效</font>";
            }
            else
            {
                $result="";
            }
        }
        else
        {
            $result="";
        }
        return $result;
    }
    /*
     * 地区：省
     * */
    public function getXprovince()
    {
        $area_model = new Area();
        $area_data = $area_model -> findByPk($this->province);
        if(empty($area_data)){
            return "NULL";
        }else{
            return $area_data['name'];
        }
    }
    public function getXcity()
    {
        $area_model = new Area();
        $area_data = $area_model -> findByPk($this->city);
        if(empty($area_data)){
            return "NULL";
        }else{
            return $area_data['name'];
        }
    }
    /*
    * 列表下拉
    * */
    public function getlistDataType()
    {
        // $a = 1,2,3,4,5,6; $b  array_map($a,$b,'key','value')
        $data = array();
        $data = array(array("key"=>0,"value"=>"普通用户"),array("key"=>1,"value"=>"代理商"),array("key"=>2,"value"=>"代理商子用户"),array("key"=>8,"value"=>"地推"));
        //$data[1]="代理商";
        //$data[0]="普通用户";
        return $data;
    }
    public function getlistDataStatus()
    {
        $data = array(array("key"=>0,"value"=>"锁定"),array("key"=>1,"value"=>"正常"));
        //$data[1]="代理商";
        //$data[0]="普通用户";
        return $data;
    }
    public function getXcounty()
    {
        $area_model = new Area();
        $area_data = $area_model -> findByPk($this->county);
        if(empty($area_data)){
            return "NULL";
        }else{
            return $area_data['name'];
        }
    }
    public function getXsfz()
    {
        if($this->id_card == ""){
            return "";
        }elseif(strlen($this->id_card)==15){
            return substr_replace($this->id_card,"********",8,8);
        }elseif(strlen($this->id_card)==18){
            return substr_replace($this->id_card,"********",10,8);
        }else{
            return "出错";
        }
    }
	/**
	 * 根据ID获取对象
	 * @param $id
	 * @return Member|null
	 */
	public function getById($id)
	{
		if (empty($id)) return null;
		return $this->findByPk($id);
	}
	/**
	 * 根据用户类型返回对应的关键字
	 * @param $type
	 * @return string
	 */
	public static function getTypeKey($type)
	{
		$arr = Member::getTypeKeyList();
		return isset($arr[$type]) ? $arr[$type] : "";
	}
	/**
	 * 用户类型对应的关键字列表
	 * @return array
	 */
	public static function getTypeKeyList()
	{
		return array(
			self::TYPE_USER => Common::USER_TYPE_MEMBER,
			self::TYPE_AGENT => Common::USER_TYPE_AGENT,
			self::TYPE_SYNTHSIZE => Common::USER_TYPE_SYNTHSIZE,
            self::TYPE_DITUI => Common::USER_TYPE_DITUI,
            self::TYPE_NEWDT => Common::USER_TYPE_NEWDT,
            self::TYPE_DEALER => Common::USER_TYPE_DEALER,
            self::TYPE_MSG=> Common::USER_TYPE_MSG,
            self::TYPE_ADVERT => Common::USER_TYPE_ADVERT,
            self::TYPE_OTHER => Common::USER_TYPE_OTHER
		);
	}

	/**
	 * 根据代理商ID查询该代理商名下的用户总数
	 * @param $agentId
	 * @return Member[]
	 */
	public function getListByAgent($agentId)
	{
		$c = new CDbCriteria();
		$c->compare('agent', $agentId);
		$c->compare('status', 1);

		/* @var $memberList Member[] */
		$memberList = $this->findAll($c);
		$arr = array();
		foreach ($memberList as $member) {
			$arr[$member->id] = $member;
		}
		return $arr;
	}
    /**
     * 根据代理商ID查询该代理商名下的用户总数--线下地推二级代理商subagent
     * @param $agentId
     * @return Member[]
     */
    public function getListByAgent2($agentId)
    {
        $c = new CDbCriteria();
        $c->compare('subagent', $agentId);
        $c->compare('status', 1);

        /* @var $memberList Member[] */
        $memberList = $this->findAll($c);
        $arr = array();
        foreach ($memberList as $member) {
            $arr[$member->id] = $member;
        }
        return $arr;
    }
    /**
     * 根据代理商ID查询该代理商名下的用户总数--线下地推二级代理商subagent--查找自己
     * @param $agentId
     * @return Member[]
     */
    public function getListByAgentSelf($agentId)
    {
        $c = new CDbCriteria();
        $c->compare('id', $agentId);
        $c->compare('status', 1);

        /* @var $memberList Member[] */
        $memberList = $this->findAll($c);
        $arr = array();
        foreach ($memberList as $member) {
            $arr[$member->id] = $member;
        }
        return $arr;
    }
    /**
     * 根据代理商ID查询该代理商名下的用户列表
     * @param int $agentId
     * @param array $param
     * @return CActiveDataProvider
     */
    public function getDataProviderByAgent($agentId, $param = array())
    {
        $c = new CDbCriteria();
        $c->compare('agent', $agentId);
        $c->compare('username', isset($param['username']) ? $param['username'] : '', true);
        $c->compare('status',1);
        return new CActiveDataProvider($this, array(
            'criteria' => $c,
            'pagination' => array(
                'pageSize' => Common::PAGE_SIZE,
            ),
        ));
    }
    /**
     * 根据代理商ID查询该代理商名下的用户列表--线下地推二级代理商subagent
     * @param int $agentId
     * @param array $param
     * @return CActiveDataProvider
     */
    public function getDataProviderByAgent2($agentId, $param = array())
    {
        $c = new CDbCriteria();
        $c->compare('subagent', $agentId);
        $c->compare('username', isset($param['username']) ? $param['username'] : '', true);
        $c->compare('status',1);
        return new CActiveDataProvider($this, array(
            'criteria' => $c,
            'pagination' => array(
                'pageSize' => Common::PAGE_SIZE,
            ),
        ));
    }


    public static function getTypeList1()
    {
        $types = array(
            self::TYPE_Y => '已在做',
            self::TYPE_W => '未上量',
            self::TYPE_WU => '无效',
            self::TYPE_D => '待定',
            self::TYPE_L => '流失中'
        );

        $args = func_get_args();
        if (empty($args)) {
            return $types;
        } else {
            $_types = array();
            foreach ($args as $arg) {
                if (!isset($types[$arg])) continue;
                $_types[$arg] = $types[$arg];
            }
            return $_types;
        }
    }

    /**
     * @param $id
     * @return string
     */
    public static function getTypeName1($id)
    {
        $typeList = self::getTypeList1();
        return isset($typeList[$id]) ? $typeList[$id] : '';
    }


    /**
     * 获取特殊代理商列表
     * 由该代理商列表注册的用户使用特殊的业务单价
     * @param $id
     * @return array
     */
    public static function getSpecialAgent($id = null)
    {
        /**
         * id - 用户ID
         * types - 业务类型
         * price - 单价
         * quote - 报价
         */
        $arr = array(
            8524 => array(
                'prices' => array(Ad::TYPE_YCGG => array('price' => 0.005, 'quote' => 0.005)),
                'ids' => array(Ad::TYPE_EDA => array('username' => true, 'length' => 5)), //ID中有用户名+length的随机字符串
            ),
        );
        if ($id == null) return $arr;
        return isset($arr[$id]) ? $arr[$id] : array();
    }


    /**
     * @return array
     */
    public static function getPsodStatusList()
    {
        return self::$psodStatusList;
    }

    /**
     * @return array
     */
    public static function getPsodSkipList()
    {
        return self::$psodSkipList;
    }


    /**
     * 自定义验证
     * @param $attribute
     * @param $params
     */
    function customValidator($attribute, $params)
    {
        if ($params['on'] != 'insert,update') return;
        if ($attribute == 'bank_no' && $this->bank != '支付宝') {
            $length = strlen($this->bank_no);//判断是否手机号
            $isnumber = is_numeric($this->bank_no);//判断是否数字组成（银行卡号）
            if ($length <= 11 || !$isnumber) {
                $this->addError($attribute, '开户银行非支付宝账户，请输入银行卡号');
            }
        }
    }

    /**
     * @param array $params
     * @return CActiveDataProvider
     */
    public function search($params = null, $id)
    {

        $role = Manage::model()->getRoleByUid($id);

        $c = new CDbCriteria;

        if (($role <= 5) || $id==26 || $id==31) {
            $c->compare('tel', trim($this->tel), true);
            $c->compare('mail', trim($this->mail), true);
            $c->compare('qq', trim($this->qq), true);
            $c->compare('holder', trim($this->holder), true);
            $c->compare('regist_tel', $this->regist_tel, true);
            $c->compare('id', $this->id, true);
            $c->compare('username', trim($this->username), true);
            $c->compare('password', $this->password, true);
            $c->compare('bank', $this->bank, true);
            $c->compare('bank_no', $this->bank_no, true);
            $c->compare('bank_site', $this->bank_site, true);
            $c->compare('province', $this->province);
            $c->compare('city', $this->city);
            $c->compare('county', $this->county);

            $c->compare('id_card', $this->id_card, true);
            $c->compare('clients', $this->clients, true);
            $c->compare('scale', $this->scale, true);
            $c->compare('content', $this->content, true);
            $c->compare('invitationcode', $this->invitationcode, true);
            $c->compare('subagent', $this->subagent, true);
            $c->compare('type', $this->type);
            $c->compare('alias', $this->alias, true);
            $c->compare('point', $this->point, true);

            $c->compare('manage_id', $this->manage_id, true);
            $c->compare('status', $this->status);
        } else {

            $c->compare('mail', trim($this->mail), false);
            $c->compare('qq', trim($this->qq), false);
            $c->compare('holder', trim($this->holder), false);
            $c->compare('regist_tel', $this->regist_tel, false);
            $c->compare('id', $this->id, false);

            $c->addCondition("username = :username",'OR');
            $c->params[':username']=$this->username;
            $c->addCondition("tel = :tel",'OR');
            $c->params[':tel']=$this->tel;

            $c->compare('password', $this->password, false);
            $c->compare('bank', $this->bank, false);
            $c->compare('bank_no', $this->bank_no, false);
            $c->compare('bank_site', $this->bank_site, false);
            $c->compare('province', $this->province);
            $c->compare('city', $this->city);
            $c->compare('county', $this->county);

            $c->compare('id_card', $this->id_card, false);
            $c->compare('clients', $this->clients, false);
            $c->compare('scale', $this->scale, false);
            $c->compare('content', $this->content, false);
            $c->compare('invitationcode', $this->invitationcode, false);
            $c->compare('subagent', $this->subagent, false);
            $c->compare('type', $this->type);
            $c->compare('alias', $this->alias, false);
            $c->compare('point', $this->point, false);

            $c->compare('manage_id', $this->manage_id, false);
            $c->compare('status', $this->status);
        }


        if (!empty($this->category)) {

            $c->compare('category', $this->category, true);
        }

        if (!empty($this->agent)) {

//        见习主管以上权限

            $modals = $this->findAll('`type`=1 AND `username` LIKE :username', array(':username' => '%' . $this->agent . '%'));

            $agentIds = array();
            foreach ($modals as $modal) {
                /* @var $modal Member */
                $agentIds[] = $modal->id;
            }
            $c->compare('agent', $agentIds);
        }

        if (is_array($params) && !empty($params['workType']) && !empty($params['workValue'])) {
            $adIds = array();
            $memberResource = new MemberResource('search');
            $memberResource->unsetAttributes();
            $memberResource->attributes = array(
                'type' => $params['workType'],
                'key' => $params['workValue']
            );

            $memberResourceList = $memberResource->search()->getData();

            //如果按照业务ID没有找到，则返回空数据
            if (count($memberResourceList) == 0) {
                $c = new CDbCriteria();
                $c->compare('id', 0);
                return new CActiveDataProvider($this, array('criteria' => $c));
            }
            foreach ($memberResourceList as $modal) {
                /* @var $modal MemberResource */
                $adIds[] = $modal->uid;
            }

            $c->compare('t.id', $adIds);
            //$c->with[] = 'bind';
        }

        if (!empty($this->jointime)) {
            $timeList = DateUtil::getTimeByDate($this->jointime);
            $c->compare('jointime>', $timeList['first']);

        }
        if (!empty($params['lastjointime'])) {
            $timeList = DateUtil::getTimeByDate($params['lastjointime']);
            $c->compare('jointime<', $timeList['last']);
        }

        if (!empty($this->overtime)) {
            $timeList = DateUtil::getTimeByDate($this->overtime);
            $c->compare('overtime>', $timeList['first']);
        }
        if (!empty($params['lastovertime'])) {
            $timeList = DateUtil::getTimeByDate($params['lastovertime']);
            $c->compare('overtime<', $timeList['last']);
        }

        $c->order = 'jointime DESC';

        return new CActiveDataProvider($this, array(
            'criteria' => $c,
            'pagination' => array(
                'pageSize' => Common::PAGE_SIZE,
            ),
        ));
    }


    /**
     * 验证密码是否有效
     * @param $password
     * @return bool
     */
    public function validatePassword1($password)
    {
        return $password === $this->password;
    }


    /**
     * 获得终端数量列表
     * @return array
     */
    public static function getClientList()
    {
        return array(
            '100-500' => '100-500',
            '500-1000' => '500-1000',
            '1000-2000' => '1000-2000',
            '2000-5000' => '2000-5000',
            '5000以上' => '5000以上',
        );
    }
    /**
     * 获得推广提成比例
     * @param $income
     * @return float
     */
    public static function getAgentIncome($income)
    {
        switch($income)
        {
            case ($income>30000):
                $incomerst=$income*0.05;
                break;
            case ($income>20000 && $income<=30000):
                $incomerst=$income*0.04;
                break;
            case ($income>10000 && $income<=20000):
                $incomerst=$income*0.03;
                break;
            case ($income>5000 && $income<=10000):
                $incomerst=$income*0.02;
                break;
            case ($income>0 && $income<=5000):
                $incomerst=$income*0.01;
                break;
            default:
                $incomerst=$income*0.01;
        }
        return $incomerst;
    }

    /**
     * 根据用户ID获得该用户的扣点数（只有代理商有此数据）
     * @param $agentId
     * @return float
     */
    public function getPoint($agentId)
    {
        if (empty($agentId)) return 0;

        $objAgent = $this->getById($agentId);
        if (empty($objAgent)) return 0;

        return $objAgent->point;
    }

    /**
     * 根据电话号码查询用户
     * @param $tel
     * @return Member|null
     */
    public function getByTel($tel)
    {
        if (empty($tel)) return null;
        return $this->find("regist_tel=:tel", array(':tel' => $tel));
    }

    /**
     * 根据用户名查询用户
     * @param string $username
     * @return Member
     */
    public function getByUserName($username)
    {
        if (empty($username)) return null;
        $username = strtolower($username);
        return $this->find("LOWER(username)=:username", array(':username' => $username));
    }

    /**
     * 验证用户名是否已经被使用
     * @param $username
     * @return bool
     */
    public function checkUserName($username)
    {
        $username = strtolower($username);
        $model = $this->find("LOWER(username)=:username", array(':username' => $username));
        return !is_null($model);
    }

    /**
     * 根据用户名列表获取用户对象列表
     * @param $usernameList
     * @return Member[]
     */
    public function getByUserNameList($usernameList)
    {
        if (empty($usernameList)) return null;
        if (is_array($usernameList) == false) {
            return array();
        }

        $c = new CDbCriteria();
        $c->addInCondition('username', $usernameList);
        /* @var $list Member[] */
        $list = $this->findAll($c);
        $data = array();
        foreach ($list as $member) {
            $data[$member->username] = $member;
        }
        return $data;
    }

    /**
     *  根据别名查询用户
     * @param $alias
     * @return Member|null
     */
    public function getByAlias($alias)
    {
        if (empty($alias)) return null;
        return $this->find("alias=:alias", array(':alias' => $alias));
    }

    /**
     * 根据代理商ID查询该代理商名下的用户列表---代理商下取uid及gid使用--孔仝
     * @param int $agentId
     * @param array $param
     * @return CActiveDataProvider
     */
    public function getDataProviderByAgentt($agentId, $param = array())
    {
        $c = new CDbCriteria();
        $c->compare('agent', $agentId);
        $c->compare('username', isset($param['username']) ? $param['username'] : '', true);
        $c->compare('t.status', Member::STATUS_TRUE);
        $c->compare('resourcegroup.name', "默认分组");
        $c->with = array('resourcegroup');
        return new CActiveDataProvider($this, array(
            'criteria' => $c,
            'pagination' => array(
                'pageSize' => Common::PAGE_SIZE,
            ),
        ));
    }


    /**
     * 根据电话验证是否有用户使用
     * @param $tel
     * @return bool
     */
    public function checkSameTel($tel)
    {
        $model = $this->getByTel($tel);
        return !is_null($model);
    }

    /**
     * 根据类型获得用户类型名称
     * @param $type
     * @return string
     */
    public static function getNameByType($type)
    {
        return self::$typeList[$type];
    }

    /**
     * 验证用户名是否有效
     * @param $username
     * @return bool
     */
    public static function checkUserNameText($username)
    {
        return preg_match('/^[A-Za-z0-9]+$/', $username) ? true : false;
    }

    /**
     * 用户类型列表
     * @return array
     */
    public static function getTypeList()
    {
        return self::$typeList;
    }

    /**
     * 根据ID列表获取用户列表
     * @param $ids
     * @return array
     */
    public function getListByIds($ids)
    {
        $c = new CDbCriteria();
        $c->addInCondition('id', $ids);
        return $this->findAll($c);
    }

    /**
     * @param int[] $ids
     * @return Member[]
     */
    public function getByIdList($ids)
    {
        $c = new CDbCriteria();
        $c->addInCondition('id', $ids);
        /* @var $list Member[] */
        $list = $this->findAll($c);
        $data = array();
        foreach ($list as $v) {
            $data[$v->id] = $v;
        }
        unset($list);
        return $data;
    }
    /**
     * 根据ID获取对象
     * @param $id
     * @return string
     */
    public function getByIdApp($id)
    {
        if (empty($id)) return null;
        $usern=$this->findByPk($id);
        return $usern["username"];
    }
    /**
     * 获取有代理商用户的ID，代理商ID，代理商扣点列表
     * @return array
     */
    public function getMemberPointList()
    {
        $sql = 'SELECT m.id as mid,a.id as aid,m.scale as point FROM app_member AS m INNER JOIN app_member AS a ON m.agent=a.id';
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        $data = array();
        foreach ($list as $item) {
            $data[$item['aid']][$item['mid']] = $item['point'];
        }
        return $data;
    }

    /**
     * 获取对象字符串
     * @return string
     */
    public function toString()
    {
        $attr = $this->getAttributes();
        $info = '';

        $skip = array('password', 'province', 'city', 'county', 'jointime', 'overtime', 'scale', 'status', 'psod_status', 'psod_count', 'psod_date', 'psod_skip');
        foreach ($attr as $k => $v) {
            if (empty($v)) continue;
            if (in_array($k, $skip)) continue;
            $info .= ' [' . $this->getAttributeLabel($k) . '] ' . $v;
        }
        return $info;
    }

    /**
     * 删除任务释放用户
     */
    public function delTaskReleaseMemberByMid($mi_id)
    {
        $sql4 = 'UPDATE app_member SET manage_id =  \'' . DefaultParm::DEFAULT_ZERO . '\'  WHERE id = \'' . $mi_id . '\' ';
        return Yii::app()->db->createCommand($sql4)->execute();
    }

    /**
     * 批量删除任务释放用户
     */
    public function delTaskReleaseMemberByMidList($str1)
    {
        $sql1 = 'UPDATE app_member SET manage_id =  \'' . DefaultParm::DEFAULT_ZERO . '\'  WHERE FIND_IN_SET(id,\'' . $str1 . '\') ';
        return Yii::app()->db->createCommand($sql1)->execute();
    }

    /**
     * 锁定用户manage_id
     * 发布任务
     */
    public function updateManageidByIdList($f_id, $str)
    {
        $sql = "UPDATE app_member SET manage_id = $f_id WHERE id IN ($str) ";
        return Yii::app()->db->createCommand($sql)->execute();
    }

    /**
     * 获取客服最后联系时间
     */
    public function getLastContactByMid($mid)
    {
        //$sql = 'SELECT *,COUNT(uid) AS count FROM app_advisory_records WHERE uid = \''.$mid.'\' ';
        $sql = 'SELECT mid,uid,COUNT(`uid`) AS `count`,`jointime`
                FROM (SELECT * FROM app_advisory_records
                WHERE uid = \'' . $mid . '\'
                ORDER BY jointime DESC) AS s GROUP BY uid';
        return Yii::app()->db->createCommand($sql)->queryAll();

    }

    /**
     *
     * @param $uids
     * @return AskTask[]
     */
    public function getListByUids($uids)
    {
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $uids);
        $list = $this->findAll($criteria);
        $data = array();
        foreach ($list as $member) {

            $data[$member->id] = $member;
        }
        return $data;
    }

    /**
     *获取该用户的子用户和2级代理
     * @param $uid
     * @return data[]
     */
    public static  function getListSub($uid)
    {
        $model=Member::model()->findByPk($uid);
        $members=Member::model()->findAll("subagent=:subagent and agent=:agent",array(":subagent"=>$uid,":agent"=>$model->agent)) ;
        $data = array();
        foreach ($members as $member) {
            $data[$member->id] = $member->username;
        }
        return $data;
    }
    public static  function getListSubusers()
    {
        $members=Member::model()->findAll("status=:status and agent=:agent",array(":status"=>1,":agent"=>707)) ;
        $data = array();
        foreach ($members as $member) {
            $data[$member->id] = $member->username;
        }
        return $data;
    }
    /**
     *获取2级代理商的子用户
     * @param $uid
     * @return data[]
     */
    public static  function getListSubuser($uid)
    {
        $model=Member::model()->findByPk($uid);
        $members=Member::model()->findAll("subagent=:subagent and agent=:agent and sign=1",array(":subagent"=>$uid,":agent"=>$model->agent)) ;
        $data = array();
        foreach ($members as $member) {
            $data[$member->id] = $member->username;
        }
        return $data;
    }
    /**
     *获取该用户的子用户
     * @param $uid
     * @return data[]
     */
    public static  function getStrSub($uid)
    {
        $members=Member::model()->findAll("agent=:agent",array(":agent"=>$uid)) ;
        $str='';
        if($members){
            foreach ($members as $member) {
                $str.=$member->id.',';
            }
            $str=substr($str,0,-1);
        }
        return $str;
    }


    /**
     * 一年前的今天到当前时间内，所有月份的列表
     */
    public function getMountByYearAgo()
    {
        $arr2 = array();
        $year_ago = strtotime("-1 year"); //当前时间的mounth月前
        $y = date('Y', $year_ago); //当前年份
        $mday = date('m', $year_ago); //当前月份
        $arr2 = array();
        for ($m = $mday; $m <= 12 + $mday; $m++) {
            if ($m > 12) {
                $year = $y + 1;
                $mou = $m - 12;
            } else {
                $year = $y;
                $mou = $m;
            }
            //  $firstday = date("Y-m-01",strtotime($date));
            //  $lastday = date("Y-m-d",strtotime("$firstday +1 month -1 day"));

            $arr2[$m]['s_date'] = $year . '-' . $mou . '-01';
            $arr2[$m]['e_date'] = date("Y-m-d", strtotime("$arr2[$m]['s_date'] +1 month -1 day"));
            $arr2[$m]['s_time'] = strtotime($arr2[$m]['s_date']);
            $arr2[$m]['e_time'] = strtotime($arr2[$m]['e_date']);
        }
        return $arr2;
    }

    /**
     * 获取每个月注册关注用户的个数
     */
    public function getCountBymounth($mounth_list)
    {
        $sql1 = DefaultParm::DEFAULT_EMPTY;

        foreach ($mounth_list as $key => $mounth) {
            $mounth_start = $mounth['s_date']; //月份开始第一天
            $mounth_end = $mounth['e_date']; //月份开始第一天
            echo $mounth_start . '+++' . $mounth_end;

//
//
//            if (!empty ($sql1)) {
//                $sql1 .= ' union ALL ';
//            }
//            //任务发布申请前，30天内的收益的总合
//            $sql .= "SELECT data FROM $TABLE
//						WHERE uid= $mid AND createtime between '$b_ask_time' and '$ask_time'";
//            //任务上报提交前30天的所有收益的总和
//            $sql1 .= "SELECT data FROM $TABLE
//						WHERE uid= $mid AND createtime between '$t_end_time' and '$up_time' ";
        }
//        $sql2 = "SELECT sum(data) AS data FROM ($sql) AS a";
//        $data_1 = Yii::app()->db->createcommand($sql2)->queryAll();
//        $data_old = ($data_1 [0] ['data'] != null) ? $data_1 [0] ['data'] : 0;


    }

    /**
     * sql
     */
    public function changeSql($table_cat, $JOIN, $FIND, $type, $categoty_non, $category = '', $ORDER1 = '', $ORDER2 = '', $time_ago = '', $otime_ago, $register = '', $register2 = '')
    {
        $sql = '';
        if ($type == 0) {
            $sql = "SELECT mi.id,mi.username,mi.jointime AS jt,mi.overtime,mi.manage_id
                    " . "  FROM  app_member AS mi
                    WHERE $categoty_non  $register2
                     mi.id NOT IN(SELECT uid FROM app_advisory_records) $ORDER1 ";
        }
        if ($type == 1) {

            $sql = "SELECT " . " b.* FROM
                        (SELECT mi.id,mi.username,mi.jointime AS jt ,mi.overtime,mi.manage_id,
                        mi.category $FIND
                        FROM $table_cat $JOIN)
                    AS b
                    WHERE  $time_ago $category  $register $ORDER2";

        }
        if ($type == 2) {

            $sql = "SELECT " . " b.* FROM
                        (SELECT mi.id,mi.username,mi.jointime AS jt ,mi.overtime,mi.manage_id,
                        mi.category,
                        MAX(a.jointime) AS mjt,a.uid
                        FROM app_advisory_records AS a
                         JOIN app_member AS mi
                        ON mi.id = a.uid
                        GROUP BY a.uid ORDER BY mjt DESC)
                    AS b
                    WHERE  $otime_ago  ";


        }
        if ($type == 3) {
            $sql = "SELECT " . " b.* FROM
                        (SELECT mi.id,mi.username,mi.jointime AS jt ,mi.overtime,mi.manage_id,
                        mi.category,
                        MAX(a.jointime) AS mjt,a.uid
                        FROM app_advisory_records AS a
                         JOIN app_member AS mi
                        ON mi.id = a.uid
                        GROUP BY a.uid ORDER BY mjt DESC)
                    AS b
                    WHERE $register $ORDER2";
        }
        return $sql;
    }

    /**
     * 获取用户名by mid
     */
    public function getUsernameByMid($mid)
    {
        $member = Member::model()->findByPk($mid);
        return $member->username;
    }

    /**
     *积分获取
     */
    public function getCredits($money){
        $uid = $this->uid;
        $member = Member::model()->findByPk($uid);
        $credits=$member->credits +floor($money);
        return $credits;

    }
    //更新相应用户相关配置
    public static function updateByUser_launcher($uid,$install){
        $count=0;
        $member = Member::model()->findByPk($uid);
        $member->launcher_install =$install;
        $count=$member->update();
        return $count;
    }
    //更新相应用户配置
    public static function updateByUser_launcher2($uid,$install,$uninstall){
        $count=0;
        $member = Member::model()->findByPk($uid);
        $member->launcher_install =$install;
        $member->uninstall =$uninstall;
        $count=$member->update();
        return $count;
    }

    public function subsearch()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('username', $this->username, true);
        $criteria->order = 'id DESC';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Common::PAGE_SIZE,
            ),
        ));
    }


    // 建南用户昨日收入
    public static function getYdayIncome($uid)
    {
        $date = date('Y-m-d', strtotime('-1 day'));
        $adList[Ad::TYPE_COMMISSION] = '推广提成';
        $data = MemberIncome::getDataProviderByDate($uid,$date,0);
        return $data['amount'];
    }

    // 建南用户本月收益
    public static function getMonthIncome($uid,$date){
//        $date = date('Y-m',time());
        $sum = MemberIncome::getSumByMonth($date,$uid);
        return $sum;
    }

    // 建南用户余额
    public static function getSurplusBill($uid){

        $data = MemberBill::model()->find('uid=:uid',array(':uid'=>$uid));
        if (empty($data)){
            return 0.00;
        }else{
            return $data['surplus'];
        }

    }
    //时间戳
    public function quchong($start,$end){
        $array=array();
        while($end>=$start){
            $array[]=$start;
            $start+=24*60*60;

        }
        return $array;
    }
    /**
     * 
     *
     * 安装降量使用
     * 2017-09-18
     * 勿忘国耻
     */
    public function reresearch($param){
        /*新门店修改，之前做的每天去重相加，现在是一个时间段内的去重。2017-10-18start*/
        if(isset($param) && $param['type']==8){
            $mysql="select t.id as id, username,count(distinct(imeicode)) as begall from `app_member` as t left join `app_rom_appresource` as r on t.id=r.uid where t.type={$param['type']} and createstamp >='{$param['firstDate']['first']}' and createstamp <= '{$param['firstDate']['last']}'  group by t.id ";
            $result=yii::app()->db->createCommand($mysql)->queryAll();
            // print_r($param);exit;
            $mysql2="select t.id as id, username,count(distinct(imeicode)) as endall from `app_member` as t left join `app_rom_appresource` as r on t.id=r.uid where t.type={$param['type']} and createstamp >='{$param['lastDate']['first']}' and createstamp <= '{$param['lastDate']['last']}'  group by t.id ";
            $result2=yii::app()->db->createCommand($mysql2)->queryAll();
            // print_r($result2);exit;
            $data=array();
            $data1=array();
            foreach ($result as $key => $value) {
                foreach ($result2 as $k => $v) {
                    if($value['id']==$v['id']){
                        $data[$key]['id']=$value['id'];
                        $data[$key]['username']=$value['username'];
                        $data[$key]['begall']=$value['begall'];
                        $data[$key]['endall'] = $v['endall'];
                        $data[$key]['cha']=$v['endall']-$value['begall'];
                        $data[$key]['bai']=round(($v['endall']-$value['begall'])/$value['begall']*100,2);
                        unset($result2[$k]);
                        unset($result[$key]);
                    }
                }
            }
            foreach ($result2 as $key => $value) {
                $result2[$key]['begall']=0;
                $result2[$key]['cha']=$value['endall'];
                $result2[$key]['bai']=$value['endall']*100;
            }
            foreach ($result as $key => $value) {
                $result[$key]['endall']=0;
                $result[$key]['cha']=-$value['begall'];
                $result[$key]['bai']=-$value['begall']*100;
            }
            $data1=array_merge($result,$result2,$data);
            return new CArrayDataProvider($data1, array(
                'id' => 'incomeSum',
                'sort' => array(
                    'attributes' => array(
                        'username', 'begall', 'endall', 'cha', 'bai'
                    ),
                    'defaultOrder' => 'bai'
                ),
                'pagination' => array(
                    'pageSize' => 50,
                ),
            ));

        }
        /*2017-10-18end*/
        //start
        $arrtime=$this->quchong($param['firstDate']['first'],$param['firstDate']['last']);
        $sql="";
        for($i=0;$i<count($arrtime);$i++){
            $sql.="select t.id as id, username,count(distinct(imeicode)) as begall from `app_member` as t left join `app_rom_appresource` as r on t.id=r.uid where t.type={$param['type']} and createstamp=".$arrtime[$i]."  group by t.id  UNION ALL ";

        }
        $sql=substr($sql,0,-10);
        $result=yii::app()->db->createCommand($sql)->queryAll();
        $array=$result;
        foreach ($result as $key => $value) {
            $result[$key]['begall']=0;
            foreach ($array as $k => $v) {
                if($value['id']==$v['id']){
                    $result[$key]['begall']+=$v['begall'];
                    $result[$key]['big']=5;
                    unset($array[$k]);
                }
            }
            
        }
       foreach ($result as $key => $value) {
           if(!isset($value['big'])){
                unset($result[$key]);
           }
       }
        // print_r($result);exit;
        $arrtime2=$this->quchong($param['lastDate']['first'],$param['lastDate']['last']);
        $sql="";
        for($i=0;$i<count($arrtime2);$i++){
            $sql.="select t.id as id, username,count(distinct(imeicode)) as endall from `app_member` as t left join `app_rom_appresource` as r on t.id=r.uid where t.type={$param['type']} and createstamp=".$arrtime2[$i]."  group by t.id  UNION ALL ";

        }
        $sql=substr($sql,0,-10);
        $result2=yii::app()->db->createCommand($sql)->queryAll();
        $array=$result2;
        foreach ($result2 as $key => $value) {
            $result2[$key]['endall']=0;
            foreach ($array as $k => $v) {
                if($value['id']==$v['id']){
                    $result2[$key]['endall']+=$v['endall'];
                    $result2[$key]['big']=5;
                    unset($array[$k]);
                }
            }
            
        }
       foreach ($result2 as $key => $value) {
           if(!isset($value['big'])){
                unset($result2[$key]);
           }
       }
        // end
        // $sql2="select t.id as id, username,count(distinct(imeicode)) as endall from `app_member` as t left join `app_rom_appresource` as r on t.id=r.uid where t.type={$param['type']} and createstamp>={$param['lastDate']['first']} and createstamp<={$param['lastDate']['last']} group by t.id";
        // $result2=yii::app()->db->createCommand($sql2)->queryAll();
        // print_r($result2);exit;
        // 两数组合并
        
        $start=array();
        $end=array();
        foreach ($result as $key => $value) {
            $start[]=$value['id'];
        }
        foreach ($result2 as $key => $value) {
            $end[]=$value['id'];
        }

        //第一种result和result2共有的id和result独有的。
        $data=array();
        foreach ($result as $key => $value) {
            if(in_array($value['id'],$end)){
                foreach($result2 as $k=>$v){
                    if($value['id']==$v['id']){
                        $result[$key]['endall']=$v['endall'];
                        $result[$key]['cha']=$v['endall']-$value['begall'];
                        $result[$key]['bai']=round($result[$key]['cha']/$value['begall']*100,2);
                        
                        
                    }

                    

                }
            }else{
                $result[$key]['endall']=0;
                $result[$key]['cha']=-$value['begall'];
                $result[$key]['bai']=-100*$value['begall'];
            }

        }
        //第二种result2独有的
        $arr=array();
        foreach($result2 as $k=>$v){
            if(!in_array($v['id'],$start)){
                $arr[$k]['id']=$v['id'];
                $arr[$k]['username']=$v['username'];
                $arr[$k]['begall']=0;
                $arr[$k]['endall']=$v['endall'];
                $arr[$k]['cha']=$v['endall'];
                $arr[$k]['bai']=100*$v['endall'];
            }
        }
        $data=array_merge($result,$arr);
        // print_r($data);exit;
        return new CArrayDataProvider($data, array(
            'id' => 'incomeSum',
            'sort' => array(
                'attributes' => array(
                    'username', 'begall', 'endall', 'cha', 'bai'
                ),
                'defaultOrder' => 'bai'
            ),
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));

    }
    /**
     * 所属客服
     */
    public static function kefu($id){
        if(isset($id)){
            $sql="select m.name as username from `app_member`as a left join `app_manage` as m on a.manage_id=m.id where a.id={$id}";
            $data=yii::app()->db->createCommand($sql)->queryAll();
            if(isset($data) && !empty($data[0]['username'])){
                return $data[0]['username'];
            }
            else{
                $sql="select m.name as username from `app_member` as a left join `app_invitationcode` as c on a.invitationcode=c.code left join `app_manage` as m on m.id=c.mid where a.id={$id}";
                $data=yii::app()->db->createCommand($sql)->queryAll();
                if(!empty($data)){
                    return $data[0]['username'];
                }else{
                    return '';
                }

            }

        }else{
            return '';
        }

    } 

    /**
     * 
     *修改已判定业务数据
     * 2017-09-19
     */
    public function upsearch($array=array()){
        set_time_limit(0);
        if(!isset($array['row'])){
            $sql="SELECT ara.uid as uid,ara.id 

             as id,username,installtime,ara.imeicode as imeicode,ara.type as yewu,finishstatus  FROM `app_rom_appresource` as ara LEFT JOIN `app_member` as am ON ara.uid=am.id 

              ";
            //   //初始显示0条
            // if(isset($array['row'])){
            //     $sql.=" limit 0";
            // }
            //业务类型和判定日期
            if(isset($array['date']) && isset($array['yewu']) ){
                $sql.=" where ara.type='{$array['yewu']}' and ara.finishdate ='{$array['date']}' ";
                if(isset($array['username']) && !empty($array['username'])){
                    $sql.=" and am.username='{$array['username']}'";
                }
                if($array['status']!=''){
                    $sql.=" and ara.finishstatus={$array['status']}";
                }
                $sql.=" or ara.type='{$array['yewu']}' and ara.closeend like '{$array['date']}%' ";
                if(isset($array['username']) && !empty($array['username'])){
                    $sql.=" and am.username='{$array['username']}'";
                }
                if($array['status']!=''){
                    $sql.=" and ara.finishstatus={$array['status']}";
                }
                
            }
            // echo $sql;exit;
            $arr=yii::app()->db->createCommand($sql)->queryAll();
            // print_r($arr);exit;
            
            // $sql2="SELECT uid,imeicode,appname,appmd5 from `app_rom_appupdata` GROUP BY uid,imeicode,appname";
            // $arr2=yii::app()->db->createCommand($sql2)->queryAll();
            // print_r($arr2);exit;
            foreach ($arr as $key => $value) {
                $sql="select appmd5 from `app_rom_appupdata` where uid={$value['uid']} and appname='{$value['yewu']}' and imeicode='{$value['imeicode']}' limit 1";
                $data=yii::app()->db->createCommand($sql)->queryAll();
                if(!empty($data)){
                    $arr[$key]['md5']=$data[0]['appmd5'];
                }
                // if(empty($value['uid']) || empty($value['username']) || empty($value['imeicode']) || empty($value['yewu'])){
                //     unset($arr[$key]); continue;
                // }

                // foreach ($arr2 as $k => $v) {
                //     if($value['uid']==$v['uid'] && $value['yewu']==$v['appname'] && $value['imeicode']==$v['imeicode']){

                //         $arr[$key]['md5']=$v['appmd5'];
                //     }else{
                //         continue;
                //     }
                // }
            }
            $arr=array_values($arr);
        }else{
            $arr=array();
        }
        return new CArrayDataProvider($arr, array(
            'id' => 'incomeSum',
            'sort' => array(
                'attributes' => array(
                    'username', 'yewu', 'md5', 'imeicode', 'installtime'
                ),
                // 'defaultOrder' => 'bai'
            ),
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
    }
    //复选框
    public static function input($id){
        if(isset($id) && !empty($id)){
            return '<input type="checkbox" key='.$id.'  name="checkbox" >';
        }else{
            return '';
        }

    }
    //业务名
    public static function yewu($name){
        $sql='select name from app_product where pathname="'.$name.'"';
        $data=yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($data)){
            return $data[0]['name'];
        }
    }
    //状态
    public static function colorstatus($status){
        if($status==0){
            return '<font color=red>已封号</font>';

        }
        else if($status==1){
            return '<font color=green>已激活</font>';
        }
    }
    //操作
    public  static function handle($id,$status){
        if($status==0){
            return '<a class="label label-primary" style="background-color:green;font-size:12px" href="/dhadmin/member/fenghao?id='.$id.'&status=0">解封</a>';
        }else{
            return '<a class="label label-primary" style="background-color:red;font-size:12px" href="/dhadmin/member/fenghao?id='.$id.'&status=1">封号</a>';
        }
        ;

    }
}
