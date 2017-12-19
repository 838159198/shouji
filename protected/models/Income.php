<?php

/**
 * This is the model class for table "{{income}}".
 *
 * The followings are the available columns in table '{{income}}':
 * @property string $uid
 * @property string $mrid
 * @property string $data
 * @property string $createtime
 * @property integer $status
 *
 * @property MemberInfo $u
 * @property MemberResource $mr
 * MSG：保留接口，代码不做更改
 */
abstract class Income extends CActiveRecord implements Iincome
{
    const STATUS_TRUE = 1;
    const STATUS_FALSE = 0;

    /** XML导入 */
    const TYPE_URL = 'url';
    /** Excel导入 */
    const TYPE_EXCEL = 'excel';
    /** 文本导入 */
    const TYPE_TEXT = 'text';
    /** 网站计算导入 */
    const TYPE_SELF = 'self';

    /* @var $importDate string 导入日期 */
    private $importDate;
    /* @var $resourceModel Resource 资源对象 */
    private $resourceModel;

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * 不同导入类型的业务列表
     * @return array
     */
    /*public static function getIncomeTypeList()
    {
        return array(
            self::TYPE_URL => array(

            ),
            self::TYPE_EXCEL => array(

            ),
            self::TYPE_TEXT => array(

            ),
            self::TYPE_SELF => array(
                Ad::TYPE_UCLLQ,
                Ad::TYPE_BDLLQ,
                Ad::TYPE_YYB,
                Ad::TYPE_PPZS,
                Ad::TYPE_SGSC,
                Ad::TYPE_TAOBAO,
                Ad::TYPE_QQLLQ,
                Ad::TYPE_JIUYOU,
                Ad::TYPE_BAIDU,
                Ad::TYPE_KYLS,
                Ad::TYPE_WEIXIN,
                Ad::TYPE_SZDH,
                Ad::TYPE_YSDQ360,
                Ad::TYPE_SDXW,
                Ad::TYPE_ZHWNL,
                Ad::TYPE_AYD,
                Ad::TYPE_ZSSQ,
                Ad::TYPE_TQ,
                Ad::TYPE_KXXXL,
                Ad::TYPE_DZDP,
                Ad::TYPE_JD,
                Ad::TYPE_ZKNS,
                Ad::TYPE_AZLLQ,
                Ad::TYPE_ZMTQ,
                Ad::TYPE_YYSD,
                Ad::TYPE_WDJ,
                Ad::TYPE_TXXW,
                Ad::TYPE_TXSP,
                Ad::TYPE_AQZM,
                Ad::TYPE_BDDT,
                Ad::TYPE_JYYXZX,
                Ad::TYPE_LLQ360,
                Ad::TYPE_QSBK,
                Ad::TYPE_GDDT,
                Ad::TYPE_WZDQ,
                Ad::TYPE_YYZX,
                Ad::TYPE_LLQ,
                Ad::TYPE_ZYSCK,
                Ad::TYPE_QYYD,
                Ad::TYPE_AQWS360,
                Ad::TYPE_XFSRF,
                Ad::TYPE_LBQLDS,
                Ad::TYPE_AQY,
                Ad::TYPE_WNL,
                Ad::TYPE_PPS,
                Ad::TYPE_BDSJZS,
                Ad::TYPE_2345YDW,
                Ad::TYPE_JJDDZ,
                Ad::TYPE_2345SJZS,
                Ad::TYPE_2345YSDQ,
                Ad::TYPE_CBDH,
                Ad::TYPE_TXSJGJ,
                Ad::TYPE_SJZS360,
                Ad::TYPE_MEIPAI,
                Ad::TYPE_MEITUAN,
                Ad::TYPE_WYYYY,
                Ad::TYPE_2345WPLLQ,
                Ad::TYPE_2345TQW,

                Ad::TYPE_SHSP,
                Ad::TYPE_ZYSC,
                Ad::TYPE_JRTT,
                Ad::TYPE_KWYY,
                Ad::TYPE_BFYY,
                Ad::TYPE_SDXW
            ),
        );
    }*/
    public static function getIncomeTypeList()
    {
        $arr=array();
        $arr[self::TYPE_URL]=array();
        $arr[self::TYPE_EXCEL]=array();
        $arr[self::TYPE_TEXT]=array();
        $arr[self::TYPE_SELF]=Ad::getAplus();
        return $arr;
    }


    /**
     * 根据导入类型获取使用该类型的业务列表
     * @param $type
     * @return array
     */
    public static function getIncomeType($type)
    {
        $list = self::getIncomeTypeList();
        return isset($list[$type]) ? $list[$type] : array();
    }

    /**
     * 根据业务类型名称，获取该业务导入类型
     * @param $name
     * @return string
     */
    public static function getTypeByResourceName($name)
    {
        $list = self::getIncomeTypeList();
        foreach ($list as $type => $item) {
            if (in_array($name, $item)) {
                return $type;
            }
        }
        return '';
    }

    /**
     * 设置导入日期
     * @param $importDate
     */
    public function setImportDate($importDate)
    {
        $this->importDate = $importDate;
    }

    /**
     * 获取导入日期
     * @return string
     */
    public function getImportDate()
    {
        return $this->importDate;
    }

    /**
     * @param $type
     * @return Resource
     * @throws Exception
     */
    public function getResourceModel($type)
    {
        if (is_null($this->resourceModel) || $this->resourceModel->keyword != $type) {
            $this->resourceModel = Resource::model()->getByKeyword($type);
        }
        if (is_null($this->resourceModel)) {
            throw new Exception('错误，没有此资源对象');
        }
        return $this->resourceModel;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Income the static model class
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
        return '{{income}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid, mrid, createtime, status', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('uid, mrid', 'length', 'max' => 11),
            array('data', 'length', 'max' => 7),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('uid, mrid, data, createtime, status', 'safe', 'on' => 'search'),
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
            'u' => array(self::BELONGS_TO, 'MemberInfo', 'uid'),
            'mr' => array(self::BELONGS_TO, 'MemberResource', 'mrid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'uid' => 'Uid',
            'mrid' => 'Mrid',
            'data' => 'Data',
            'createtime' => 'Createtime',
            'status' => 'Status',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('uid', $this->uid, true);
        $criteria->compare('mrid', $this->mrid, true);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('createtime', $this->createtime, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 获取xml数据的url地址
     * @param int $time
     * @return string
     */
    public function getApiUrl($time = null)
    {
        return '';
    }

    /**
     * 使用数组向数据库批量添加数据
     * @param $type
     * @param $array
     */
    public function insertByArray($type, $array)
    {
        
        $json='';
        foreach ($array as $v) {
            if ($v['data'] == 0) continue;
            $model = IncomeFactory::factory($type);
            $model->setIsNewRecord(true);
            $model->unsetAttributes();
            $model->attributes = $v;
            $model->insert();
            $json.=json_encode($v);
        }

        /*start 写入收入操作日志log_income中 2017-11-15*/
        if(!empty($json)){
            $content='表[income'.$type.']中导入'.$json;
        }else{
            $content='表[income'.$type.']中导入(空)';
        }
        $mid=yii::app()->user->getState('uid');
        $ip=$_SERVER['SERVER_ADDR'];
        $title='数据导入';
        $before_content='';
        $this->addlogincome($mid,0,$content,$ip,$before_content,$title);
       /*end 写入收入操作日志log_income中 2017-11-15*/

    }

    /**
     * 根据日期获得当天数据
     * @param $uid
     * @param $date
     * @return Income
     */
    public function getDataProviderByDate($uid, $date)
    {
        $c = new CDbCriteria();
        $c->condition = 'uid=:uid and createtime=:dates';

        $c->params = array(
            ':uid' => $uid,
            ':dates' => $date,
        );

        return $this->find($c);
    }

    /**
     * 根据年月获取收入数据
     * @param $yearMonth
     * @param $uid
     * @return array
     */
    public function getListByDate($yearMonth, $uid)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'uid=:uid AND createtime LIKE :dates';
        $criteria->params = array(
            ':uid' => $uid,
            ':dates' => $yearMonth . '%',
        );
        return $this->findAll($criteria);
    }

    /**
     * 获取有数据的最后一天日期
     * @return string
     */
    public function getLastDate()
    {
        /* @var $model Income */
        $model = $this->find('1=1 ORDER BY `createtime` DESC');
        return is_null($model) ? '' : $model->createtime;
    }

    /**
     * 根据Y-m获取收益合计
     * @param $month
     * @param int $uid
     * @return double
     */
    public function getSumByMonth($month, $uid)
    {
        $table = $this->tableName();
        $sql = 'SELECT SUM(data) AS num FROM ' . $table . ' WHERE uid = :uid AND  createtime LIKE :month';
        $command = Yii::app()->db->createCommand($sql);
        $sum = $command->queryScalar(array(
            ':uid' => $uid,
            ':month' => $month . '%'
        ));
        return empty($sum) ? 0 : $sum;
    }

    /**
     * 获取用户所有收入
     * @param $uid
     * @return double
     */
    public function getSumByUid($uid)
    {
        $table = $this->tableName();
        $sql = 'SELECT SUM(data) AS num FROM ' . $table . ' WHERE uid = :uid';
        $command = Yii::app()->db->createCommand($sql);
        $sum = $command->queryScalar(array(
            ':uid' => $uid
        ));
        return empty($sum) ? 0 : $sum;
    }

    /**
     * 根据日期区间获取收益数据列表
     * @param $uid
     * @param $firstDate
     * @param string $lastDate
     * @return array
     */
    public function getListByDateInterval($uid, $firstDate, $lastDate = '')
    {
        $c = new CDbCriteria();
        $c->addCondition('`uid`=:uid');
        $c->addCondition('`status`=:status');
        $c->addCondition('`createtime`>:first');
        if (!empty($lastDate)) {
            $c->addCondition('`createtime`<:last');
            $c->params[':last'] = $lastDate;
        }
        $c->params[':first'] = $firstDate;
        $c->params[':uid'] = $uid;
        $c->params[':status'] = Common::STATUS_TRUE;
        $c->order = '`createtime`';
        return $this->findAll($c);
    }


    /**
     * 根据日期获取用户收入合计列表
     * @param $firstDate
     * @param $lastDate
     * @return Income[]
     */
    public function getMemberDataSumListByDate($firstDate, $lastDate)
    {
        $c = new CDbCriteria();
        $c->select = '`uid`,sum(`data`) as data';
        $c->addCondition('`createtime`>=:first');
        $c->addCondition('`createtime`<=:last');
        $c->addCondition('t.`status`=:status');
        $c->group = '`uid`';
//        $c->with = 'u';
        $c->params = array(
            ':first' => $firstDate,
            ':last' => $lastDate,
            ':status' => self::STATUS_TRUE
        );

        /* @var $list Income[] */
        $list = $this->findAll($c);
        $data = array();
        foreach ($list as $l) {
            $data[$l->uid] = $l;
        }

        return $data;
    }

    /**
     * 如果封号，该ID封号日期之后的数据全部失效
     * @param $mrid
     * @param $createtime
     * @return mixed
     */
    public function closeIncome($mrid, $createtime)
    {
        $this->updateAll(array(
                'status' => Common::STATUS_FALSE
            ),
            '`mrid`=:mrid AND `createtime`>=:createtime',
            array(':mrid' => $mrid, ':createtime' => $createtime));
    }

    /**
     * 根据业务ID获取收入列表
     * @param $mrid
     * @param string $date
     * @return Income[]
     */
    public function getListByMrid($mrid, $date = '')
    {
        $c = new CDbCriteria();
        $c->compare('mrid', $mrid);
        if (DateUtil::isDate($date)) {
            $c->compare('createtime >', $date);
        }
        $c->order = 'createtime desc';
        return $this->findAll($c);
    }

    /**
     * 根据业务ID获取当天收入数据
     * @param $uid
     * @param $date
     * @return Income
     */
    public function getByuid($uid, $date)
    {
        $c = new CDbCriteria();
        $c->compare('uid', $uid);
        $c->compare('createtime', $date);
        return $this->find($c);
    }

    /**
     * 根据天数，获取有收入的用户ID列表
     * @param $datenum
     * @return int[]
     */
    public function getHaveIncomeUidList($datenum)
    {
        if (empty($datenum) || is_nan($datenum)) {
            return array();
        }

        $c = new CDbCriteria();
        $c->addCondition('createtime >=:date');
        $c->group = 'uid';
        $c->params = array(':date' => date('Y-m-d', strtotime('-' . $datenum . ' day')));
        $modelList = $this->findAll($c);
        $uidList = array();
        foreach ($modelList as $model) {
            /* @var $model Income */
            $uidList[] = $model->uid;
        }
        return $uidList;
    }

    /**
     * 根据日期获取当天业务的收入数据
     * @param string $date
     * @param string $key (uid|mrid) 组成数组下标的属性
     * @return Income[]
     */
    public function getListByDateTime($date, $key = 'uid')
    {
        $c = new CDbCriteria();
        $c->compare('createtime', $date);
        /** @var $list Income[] */
        $list = $this->findAll($c);
        $arr = array();
        foreach ($list as $item) {
            switch ($key) {
                case 'uid':
                    $arr[$item->uid] = $item;
                    break;
                case 'mrid':
                    $arr[$item->mrid] = $item;
                    break;
            }
        }
        return $arr;
    }

    /**
     * 根据日期清理记录，返回清理数
     * @param $date
     * @return int
     */
    public function clear($date)
    {
        $c = new CDbCriteria();
        $c->compare('createtime', $date);

        /** @var $list Income[] */
        $list = $this->findAll($c);
        $json='';
        if (empty($list)){
            /*写入收益操作日志 2017-11-14 start*/
                if(!empty($json)){
                    $content='清除表[income'.$this->getType().']中'.$json;
                }else{
                    $content='清除表[income'.$this->getType().']中（空）';
                }
                $mid=yii::app()->user->getState('uid');
                $ip=$_SERVER['SERVER_ADDR'];
                $before_content='';
                $title="清理已导入业务数据";
                Income::addlogincome($mid,0,$content,$ip,$before_content,$title);  
        /*2017-11-14 end*/
            return 0;
        }else{
            $incomeLog = new IncomeClearLog();
            $clearTime = DateUtil::getDate(null, DateUtil::F_DATE_TIME);
            foreach ($list as $income) {
                $incomeLog->unsetAttributes();
                $incomeLog->isNewRecord = true;
                $incomeLog->attributes = $income->attributes;
                $incomeLog->type = $this->getType();
                $incomeLog->cleartime = $clearTime;
                $incomeLog->insert();
                $json.=json_encode($income->attributes);
            }

            /*写入收益操作日志 2017-11-14 start*/
                if(!empty($json)){
                    $content='清除表[income'.$this->getType().']中'.$json;
                }else{
                    $content='清除表[income'.$this->getType().']中（空）';
                }
                $mid=yii::app()->user->getState('uid');
                $ip=$_SERVER['SERVER_ADDR'];
                $before_content='';
                $title="清理已导入业务数据";
                Income::addlogincome($mid,0,$content,$ip,$before_content,$title);  
            /*2017-11-14 end*/
            return $this->deleteAll($c); 
        }

        

        

        
    }
    /**
     * 有关操作钱的业务都在app_log_income中记录
     *
     * 2017-11-14
     * 
     * zlb
     */
    public static function addlogincome($mid,$uid,$content,$ip,$before_content,$title){
        //写入收入操作日志
        $time=date('Y-m-d H:i:s');
        $sql="INSERT INTO `app_log_income` 
        (`uid`,`mid`,`content`,`ip`,`createtime`,`before_content`,`title`) 
        VALUES 
        ({$uid},{$mid},'{$content}','{$ip}','{$time}','{$before_content}','{$title}')";

        yii::app()->db->createCommand($sql)->execute();
    }


}