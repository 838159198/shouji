<?php

/**
 * The followings are the available columns in table 'tbl_user':
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $joinip
 * @property string $overip
 * @property integer $jointime
 * @property integer $overtime
 * @property string $auth
 * @property integer $status
 * @property string $role
 * @property string $name
 * @property integer $sex
 * @property integer $ismarry
 * @property integer $phone
 * @property string $picture
 * @property integer $birthday
 * @property string $remark
 * @property string $idcard
 * @property integer $promotion
 * @property string $mark
 * @property integer $pro_time
 * @property integer $department
 * @property string $qq
 * @property string $email
 * @property string $profile
 */
class Manage extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return static the static model class
     */
    public  $password2;
    /** 状态-可用 */
    const STATUS_TRUE = 1;
    /** 状态-不可用 */
    const STATUS_FALSE = 0;

    /** 客服周任务申请最低权限 */
    const MANAGE_POWER = 5;
    /** 状态列表 */
    public static $statusList = array(0 => '锁定', 1 => '正常');

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{manage}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //创建
            array('username, password,jointime, overtime,name,sex', 'required','on'=>'newadd'),
            array('username','length','min'=>3,'max'=>30,'on'=>'newadd'),
            array('username','unique','on'=>'newadd'),
            array('mail','email','on'=>'newadd'),
            array('name','length','min'=>2,'max'=>30,'on'=>'newadd'),
            array('qq,phone', 'numerical', 'integerOnly'=>true,'on'=>'newadd'),
            array('phone','length','is'=>11,'on'=>'newadd'),
            array('qq','length','max'=>11,'min'=>5,'on'=>'newadd'),
            array('idcard','match','pattern'=>'/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/','on'=>'newadd',"message"=>"请填写正确的身份证号码"),
            //编辑资料
            array('username, name,sex', 'required','on'=>'editadd'),
            array('username','length','min'=>3,'max'=>30,'on'=>'editadd'),
            array('username','unique','on'=>'editadd'),
            array('name','length','min'=>2,'max'=>30,'on'=>'editadd'),
            array('mail','email','on'=>'editadd'),
            array('qq,phone', 'numerical', 'integerOnly'=>true,'on'=>'editadd'),
            array('phone','length','is'=>11,'on'=>'editadd'),
            array('qq','length','max'=>11,'min'=>5,'on'=>'editadd'),
            array('idcard','match','pattern'=>'/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/','on'=>'editadd',"message"=>"请填写正确的身份证号码"),
            /*修改自己的资料*/
            array('username, name,sex,qq', 'required','on'=>'myinfo'),
            array('username','length','min'=>3,'max'=>30,'on'=>'myinfo'),
            array('username','unique','on'=>'myinfo'),
            array('name','length','min'=>2,'max'=>30,'on'=>'myinfo'),
            array('mail','email','on'=>'myinfo'),
            array('qq,phone', 'numerical', 'integerOnly'=>true,'on'=>'myinfo'),
            array('phone','length','is'=>11,'on'=>'myinfo'),
            array('qq','length','max'=>11,'min'=>5,'on'=>'myinfo'),
            array('idcard','match','pattern'=>'/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/','on'=>'myinfo',"message"=>"请填写正确的身份证号码"),


            /*修改自己密码*/
            array('password,password2', 'required','on'=>'mypassword'), //用户名、密码、确认密码必须填写
            array('password,password2', 'length', 'min' => 6, 'max' => 16,'on'=>'mypassword'), //密码长度为6-16
            array('password2', 'compare', 'compareAttribute' => 'password','on'=>'mypassword'), //密码与确认密码必须一致
            /*array('username, password,mail, joinip, overip, jointime, overtime, auth, role, name', 'required'),
            array('jointime, overtime, status, sex,  phone, birthday', 'numerical', 'integerOnly'=>true),
            array('username, password, name', 'length', 'max'=>32),
            array('joinip, overip', 'length', 'max'=>15),
            array('role', 'length', 'max'=>11),
            array('remark, qq', 'length', 'max'=>255),
            array('idcard', 'length', 'max'=>20),*/
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, username, password, joinip, overip, jointime, overtime, auth, status, role, name, sex,  phone, birthday, remark,   department, qq,mail,profile', 'safe', 'on'=>'search'),
        );

    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'posts' => array(self::HAS_MANY, 'Post', 'author_id'),
            'mangegroup' => array(self::BELONGS_TO, 'ManageGroup', 'group'),
            'r' => array(self::BELONGS_TO, 'Role', 'role'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'username' => '帐号',
            'password' => '密码',
            'joinip' => '注册IP',
            'overip' => '登陆IP',
            'jointime' => '注册时间',
            'overtime' => '登陆时间',
            'auth' => '权限',
            'status' => '状态',
            'role' => '角色',
            'name' => '姓名',
            'sex' => '0=>男，1=>女',
            'ismarry' => '0->未婚,1->已婚',
            'phone' => '联系电话',
            'birthday' => '出生日期',
            'remark' => '备注信息',
            'idcard' => '有效证件号码',
            'department' => '部门id',
            'qq' => '工作QQ',
            'mail'=>'电子邮件',
            'group'=>'用户组',
            'password2'=>'重复密码',
            'profile' => 'Profile',
        );
    }
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('username',$this->username,true);
        $criteria->compare('password',$this->password,true);
        $criteria->compare('joinip',$this->joinip,true);
        $criteria->compare('overip',$this->overip,true);
        $criteria->compare('jointime',$this->jointime);
        $criteria->compare('overtime',$this->overtime);
        $criteria->compare('auth',$this->auth,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('role',$this->role,true);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('sex',$this->sex);
        $criteria->compare('phone',$this->phone);

        $criteria->compare('birthday',$this->birthday);
        $criteria->compare('remark',$this->remark,true);
        $criteria->compare('idcard',$this->idcard,true);

        $criteria->compare('department',$this->department);
        $criteria->compare('qq',$this->qq,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort'=>array(
                'defaultOrder'=>'id DESC', //设置默认排序
                //       'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }
    /**
     * Checks if the given password is correct.
     * @param string the password to be validated
     * @return boolean whether the password is valid
     */
    /*public function validatePassword($password)
    {
        return CPasswordHelper::verifyPassword($password,$this->password);
    }*/
    public function validatePassword($password)
    {
        return $this->createPassword($password) === $this->password;
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
    /**
     * 获取当前登录的管理员的权限，
     * return int
     */
    public function getRoleByUid($id)
    {
        $sql = 'SELECT role FROM app_manage WHERE id = \'' . $id . '\' ';
        $role = Yii::app()->db->createCommand($sql)->queryAll();
        return $role[0]['role'];
    }
    /**
     * 获取用户名by id
     */
    public function getNameById($id)
    {
        $sql = 'SELECT name FROM app_manage WHERE id = \'' . $id . '\' ';
        $name = Yii::app()->db->createCommand($sql)->queryAll();
        return $name[0]['name'];
    }
    /**
     * 根据角色列表获取管理员列表
     * @param array $roleList
     * @return array
     */
    public function getByRole($roleList)
    {
        $c = new CDbCriteria();
        $c->compare('t.status', self::STATUS_TRUE);
        $c->addInCondition('role', $roleList);
        $c->with = 'r';
        return $this->findAll($c);
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
     * 性别
     * */
    public function getXsex()
    {
        if($this->sex==0){
            $data = "男";
        }elseif($this->sex==1){
            $data = "女";
        }else{
            $data = "发生错误";
        }
        return $data;
    }

    /**
     * 获取较色列表
     */
    public function getManageList()
    {
        $res = Manage::model()->findAllByAttributes(array('status' =>1));
        return $res;
    }
    /**
     * Generates the password hash.
     * @param string password
     * @return string hash
     */
    public function hashPassword($password)
    {
        return CPasswordHelper::hashPassword($password);
    }
}
