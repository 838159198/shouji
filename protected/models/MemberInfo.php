<?php

/**
 * This is the model class for table "{{member_info}}".
 *
 * The followings are the available columns in table '{{member_info}}':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $tel
 * @property string $mail
 * @property string $qq
 * @property string $bank
 * @property string $bank_no
 * @property string $bank_site
 * @property integer $province
 * @property integer $city
 * @property integer $county
 * @property string $holder
 * @property string $id_card
 * @property string $clients
 * @property string $jointime
 * @property string $overtime
 * @property string $scale
 * @property integer $status
 * @property string $content
 * @property integer $agent
 * @property integer $type
 * @property string $alias
 * @property string $point
 * @property string $regist_tel
 * @property string $manage_id
 * @property string $category
 * @property string $reg_ip
 * @property string $login_ip
 * 单价递减策略 Price strategy of diminishing(PSOD)，新注册用户收入达到一定值后执行的一项特殊单价策略
 * @property string $psod_status
 * @property string $psod_count
 * @property string $psod_date
 * @property integer $psod_skip
 *
 * @property MemberBind $bind
 */
class MemberInfo extends CActiveRecord implements IUser, IObject
{
    /** 状态-可用 */
    const STATUS_TRUE = 1;
    /** 状态-不可用 */
    const STATUS_FALSE = 0;

    /** 普通用户 */
    const TYPE_USER = 0;
    /** 代理商 */
    const TYPE_AGENT = 1;
    /** 综合包用户 */
    const TYPE_SYNTHSIZE = 2;
    /** 综合包用户 */
    const TYPE_ADVERTISER = 9;

    /** 用户重置密码 */
    const RESET_PASSWORD = '123456';

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


    private static $typeList = array('0' => '普通用户', '1' => '代理商', '2' => '综合包用户');
    private static $psodStatusList = array('0' => '未使用', '1' => '使用中', '2' => '已使用');
    private static $psodSkipList = array('0' => '否', '1' => '是');


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
     * 用户类型对应的关键字列表
     * @return array
     */
    public static function getTypeKeyList()
    {
        return array(
            self::TYPE_USER => Common::USER_TYPE_MEMBER,
            self::TYPE_AGENT => Common::USER_TYPE_AGENT,
            self::TYPE_SYNTHSIZE => Common::USER_TYPE_SYNTHSIZE,
            self::TYPE_ADVERTISER => Common::USER_TYPE_ADVERTISER,
        );
    }

    /**
     * 根据用户类型返回对应的关键字
     * @param $type
     * @return string
     */
    public static function getTypeKey($type)
    {
        $arr = MemberInfo::getTypeKeyList();
        return isset($arr[$type]) ? $arr[$type] : Common::USER_TYPE_MEMBER;
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
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MemberInfo the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

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
            array('username,password', 'required', 'on' => 'insert'), //用户名、密码、确认密码必须填写
            array('username', 'length', 'min' => 5, 'max' => 16, 'on' => 'insert'), //用户名长度为5-16
            array('password', 'length', 'min' => 6, 'max' => 16,'tooShort'=>'密码长度至少6位','on' => 'insert'), //密码长度为6-16
            array('username', 'unique', 'className' => 'MemberInfo', 'attributeName' => 'username', 'on' => 'insert'), //验证用户名是否唯一
            array('tel, qq', 'required', 'on' => 'insert,update'),
            array('status, agent, type, psod_skip', 'numerical', 'integerOnly' => true, 'on' => 'insert,update'),
            array('tel', 'length', 'is' => 11, 'on' => 'insert,update','message'=>'请填写正确的手机号码'),//手机号11位
            array('id_card, regist_tel', 'length', 'max' => 20, 'on' => 'insert,update'),
            array('bank_no', 'length', 'max' => 50, 'on' => 'insert,update'),
            array('bank_no', 'customValidator', 'on' => 'insert,update'),
            array('mail, clients', 'length', 'max' => 30, 'on' => 'insert,update'),
            array('alias', 'length', 'max' => 32, 'on' => 'insert,update'),
            array('qq, agent, manage_id, category', 'length', 'max' => 11, 'on' => 'insert,update'),
            array('bank', 'length', 'max' => 4, 'on' => 'insert,update'),
            array('bank_site', 'length', 'max' => 50, 'on' => 'insert,update'),
            array('holder, scale, point', 'length', 'max' => 10, 'on' => 'insert,update'),
            array('jointime, overtime', 'length', 'max' => 12, 'on' => 'insert,update'),
            array('psod_status, psod_count, psod_date, content', 'length', 'max' => 255, 'on' => 'insert,update'),
            array('province, city, county, status, agent, type', 'numerical', 'integerOnly' => true, 'on' => 'insert,update'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, username, password, tel, mail, qq, bank, bank_no, bank_site, province, city, county, holder, id_card, clients, jointime, overtime, scale, status, content, agent, type, alias, point, regist_tel, manage_id, psod_status, psod_count, psod_date, category', 'safe', 'on' => 'search'),
        );
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
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'bind' => array(self::HAS_ONE, 'MemberBind', array('uid' => 'id')),
            'resourcegroup' => array(self::HAS_ONE, 'MemberResourceGroup', array('memberId' => 'id')),
            'memberbill' => array(self::HAS_ONE, 'MemberBill', array('uid' => 'id'))
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
            'password' => '登录密码',
            'tel' => '电话',
            'mail' => 'E-mail',
            'qq' => 'QQ',
            'bank' => '开户银行',
            'bank_no' => '银行账号/支付宝账号',
            'bank_site' => '开户地区',
            'province' => '省',
            'city' => '市',
            'county' => '区县',
            'holder' => '开户人',
            'id_card' => '身份证号',
            'clients' => '终端数量',
            'jointime' => '注册时间',
            'overtime' => '登陆时间',
            'scale' => '分成比例', //可由代理商设置，如：scale=0.2，10元，用户得8元，代理商的2元
            'status' => '状态',
            'content' => '备注',
            'agent' => '代理商',
            'type' => '用户身份',
            'alias' => '别名',
            'point' => '代理商扣点',
            'regist_tel' => '注册用电话',
            'manage_id' => '客服',
            'psod_status' => '策略状态',
            'psod_count' => '使用次数',
            'psod_date' => '开始日期',
            'psod_skip' => '略过单价递减策略',
            'category' => '用户类别',
        );
    }

    /**
     * @param array $params
     * @return CActiveDataProvider
     */
    public function search($params = null, $id)
    {

        $role = Manage::model()->getRoleByUid($id);

        $c = new CDbCriteria;

        if (($role <= 5)) {
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
            $c->compare('type', $this->type);
            $c->compare('alias', $this->alias, true);
            $c->compare('point', $this->point, true);

            $c->compare('manage_id', $this->manage_id, true);
            $c->compare('psod_status', $this->psod_status, true);
            $c->compare('psod_count', $this->psod_count, true);
            $c->compare('psod_date', $this->psod_date, true);
            $c->compare('psod_skip', $this->psod_skip);
            $c->compare('status', $this->status);
        } else {
            $c->compare('tel', trim($this->tel), false);
            $c->compare('mail', trim($this->mail), false);
            $c->compare('qq', trim($this->qq), false);
            $c->compare('holder', trim($this->holder), false);
            $c->compare('regist_tel', $this->regist_tel, false);
            $c->compare('id', $this->id, false);
            $c->compare('username', trim($this->username), false);
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
            $c->compare('type', $this->type);
            $c->compare('alias', $this->alias, false);
            $c->compare('point', $this->point, false);

            $c->compare('manage_id', $this->manage_id, false);
            $c->compare('psod_status', $this->psod_status, false);
            $c->compare('psod_count', $this->psod_count, false);
            $c->compare('psod_date', $this->psod_date, false);
            $c->compare('psod_skip', $this->psod_skip);
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
                /* @var $modal MemberInfo */
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
            $c->with[] = 'bind';
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
    public function validatePassword($password)
    {
        return $this->createPassword($password) === $this->password;
    }
    public function validatePassword1($password)
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
        return md5(trim($password));
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
        );
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
     * 根据ID获取对象
     * @param $id
     * @return MemberInfo|null
     */
    public function getById($id)
    {
        if (empty($id)) return null;
        return $this->findByPk($id);
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
     * @return MemberInfo|null
     */
    public function getByTel($tel)
    {
        if (empty($tel)) return null;
        return $this->find("regist_tel=:tel", array(':tel' => $tel));
    }

    /**
     * 根据用户名查询用户
     * @param string $username
     * @return MemberInfo
     */
    public  function getByUserName($username)
    {
        if (empty($username)) return null;
        $username = strtolower($username);
        return $data=$this->find("LOWER(username)=:username", array(':username' => $username));
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
     * @return MemberInfo[]
     */
    public function getByUserNameList($usernameList)
    {
        if (empty($usernameList)) return null;
        if (is_array($usernameList) == false) {
            return array();
        }

        $c = new CDbCriteria();
        $c->addInCondition('username', $usernameList);
        /* @var $list MemberInfo[] */
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
     * @return MemberInfo|null
     */
    public function getByAlias($alias)
    {
        if (empty($alias)) return null;
        return $this->find("alias=:alias", array(':alias' => $alias));
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
        $c->compare('status', MemberInfo::STATUS_TRUE);
        return new CActiveDataProvider($this, array(
            'criteria' => $c,
            'pagination' => array(
                'pageSize' => Common::PAGE_SIZE,
            ),
        ));
    }
    /*
     * 用户类型
     * */
    public static function getXtype($type)
    {
        //，0普通用户，1代理商,9广告商
        $data = "";
        if($type==1){
            $data = "<font color=#006600>代理商</font>";
        }
        elseif($type==9){
            $data = "<font color=#337ab7;>广告商</font>";
        }
        elseif($type==0){
            $data = "普通用户";
        }else{
            $data = "<font color=#ff0000>发生错误</font>";
        }
        return $data;
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
        $c->compare('t.status', MemberInfo::STATUS_TRUE);
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
     * 查找所有广告商列表
     * @param int $type
     * @param array $param
     * @return CActiveDataProvider
     */
    public function getAdvertiserList($type)
    {
        $c = new CDbCriteria();
        $c->compare('type', $type);
        $c->with = 'memberbill';

        return new CActiveDataProvider($this, array(
            'criteria' => $c,
            'sort'=>array(
                'defaultOrder' => 'memberbill.surplus ASC'
            ),
            'pagination' => array(
                'pageSize' => Common::PAGE_SIZE_LESS,
            ),
        ));
    }
    /**
     * 根据代理商ID查询该代理商名下的用户总数
     * @param $agentId
     * @return MemberInfo[]
     */
    public function getListByAgent($agentId)
    {
        $c = new CDbCriteria();
        $c->compare('agent', $agentId);
        $c->compare('status', MemberInfo::STATUS_TRUE);
        /* @var $memberList MemberInfo[] */
        $memberList = $this->findAll($c);
        $arr = array();
        foreach ($memberList as $member) {
            $arr[$member->id] = $member;
        }
        return $arr;
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
     * @return MemberInfo[]
     */
    public function getByIdList($ids)
    {
        $c = new CDbCriteria();
        $c->addInCondition('id', $ids);
        /* @var $list MemberInfo[] */
        $list = $this->findAll($c);
        $data = array();
        foreach ($list as $v) {
            $data[$v->id] = $v;
        }
        unset($list);
        return $data;
    }

    /**
     * 获取有代理商用户的ID，代理商ID，代理商扣点列表
     * @return array
     */
    public function getMemberPointList()
    {
        $sql = 'SELECT m.id as mid,a.id as aid,m.scale as point FROM ele_member_info AS m INNER JOIN ele_member_info AS a ON m.agent=a.id';
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
        $sql4 = 'UPDATE ele_member_info SET manage_id =  \'' . DefaultParm::DEFAULT_ZERO . '\'  WHERE id = \'' . $mi_id . '\' ';
        return Yii::app()->db->createCommand($sql4)->execute();
    }

    /**
     * 批量删除任务释放用户
     */
    public function delTaskReleaseMemberByMidList($str1)
    {
        $sql1 = 'UPDATE ele_member_info SET manage_id =  \'' . DefaultParm::DEFAULT_ZERO . '\'  WHERE FIND_IN_SET(id,\'' . $str1 . '\') ';
        return Yii::app()->db->createCommand($sql1)->execute();
    }

    /**
     * 锁定用户manage_id
     * 发布任务
     */
    public function updateManageidByIdList($f_id, $str)
    {
        $sql = "UPDATE ele_member_info SET manage_id = $f_id WHERE id IN ($str) ";
        return Yii::app()->db->createCommand($sql)->execute();
    }

    /**
     * 获取客服最后联系时间
     */
    public function getLastContactByMid($mid)
    {
        //$sql = 'SELECT *,COUNT(uid) AS count FROM ele_advisory_records WHERE uid = \''.$mid.'\' ';
        $sql = 'SELECT mid,uid,COUNT(`uid`) AS `count`,`jointime`
                FROM (SELECT * FROM ele_advisory_records
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
                    " . "  FROM  ele_member_info AS mi
                    WHERE $categoty_non  $register2
                     mi.id NOT IN(SELECT uid FROM ele_advisory_records) $ORDER1 ";
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
                        FROM ele_advisory_records AS a
                         JOIN ele_member_info AS mi
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
                        FROM ele_advisory_records AS a
                         JOIN ele_member_info AS mi
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
    public static function getUsernameByMid($mid)
    {
        $member = MemberInfo::model()->findByPk($mid);
        return $member->username;
    }

    public static function getUidByName($name)
    {
        $member = MemberInfo::model()->find("LOWER(username)=:username", array(':username' => $name));
        if($member){
            return $member->id;
        }

        return null;
    }
}