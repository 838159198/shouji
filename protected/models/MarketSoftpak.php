<?php

/**
 * This is the model class for table "{{market_softpak}}".
 *
 * The followings are the available columns in table '{{market_softpak}}':
 * @property string $id
 * @property string $uid
 * @property string $type
 * @property string $version
 * @property string $url
 * @property string $codeurl
 * @property string $serial_number
 * @property integer $status
 * @property integer $allot
 * @property integer $closed
 */
class MarketSoftpak extends CActiveRecord
{
    /** 自动分配 */
    const ALLOT_AUTO = 0;
    /** 人工分配 */
    const ALLOT_MANUAL = 1;
    /** 所有 */
    const ALLOT_ALL = -1;

    /** 已封号 */
    const CLOSED_TRUE = 1;
    /** 未封号 */
    const CLOSED_FALSE = 0;

    /** 状态-未分配 */
    const STATUS_TRUE = 1;
    /** 状态-已分配 */
    const STATUS_FALSE = 0;


    /**
     * 业务ID分配方式
     * @return array
     */
    public static function getAllotList()
    {
        return array(
            '0' => '系统分配',
            '1' => '管理员分配',
        );
    }

    private $_username = null;
    public function getUsername()
    {
        if ($this->_username === null && $this->member !== null)
        {
            $this->_username = $this->member->username;
        }
        return $this->_username;
    }
    public function setUsername($value)
    {
        $this->_username = $value;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return RomSoftpak the static model class
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
        return '{{market_softpak}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid,type, serial_number,url', 'required'),
            array('uid,status, allot, closed', 'numerical', 'integerOnly' => true),
            array('type', 'length', 'max' => 10),
            array('serial_number', 'length', 'max' => 6),
            array('url,codeurl', 'length', 'max' => 80),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, type, serial_number, url, codeurl,version, status, allot, closed,username', 'safe', 'on' => 'search'),
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
            'member' => array(self::BELONGS_TO, 'Member', 'uid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'uid' => '用户ID',
            'type' => '软件类型',
            'serial_number' => '软件序列号',
            'version' => '软件版本号',
            'status' => '状态',
            'allot' => '分配类型',
            'closed' => '是否已被封',
            'username' => '用户名',
            'url' => '软件地址',
            'codeurl' => '二维码地址'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */

    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.uid', $this->uid, true);
        $criteria->addCondition("t.uid!=0");
        $criteria->compare('t.type', $this->type, true);
        $criteria->compare('t.serial_number', $this->serial_number, true);
        $criteria->compare('t.version', $this->version, true);
        $criteria->compare('t.status', $this->status, true);
        $criteria->compare('t.allot', $this->allot, true);
        $criteria->compare('t.closed', $this->closed, true);
        $criteria->compare('t.url', $this->url, true);
        $criteria->compare('t.codeurl', $this->codeurl, true);

        $criteria->with = 'member';
        $criteria->compare('member.username', $this->username,true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort'=>array(
                'defaultOrder'=>'t.id DESC', //设置默认排序
                //       'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
            ),
        ));
    }

    /**
     * 获得最新一条软件序列号
     * @param $type
     * @param int $allot 分配状态0.自动；1.手动；-1.所有
     * @return MarketSoftpak[]
     */
    public function getListGroupByType($type, $allot = self::ALLOT_AUTO)
    {
        $closed=self::CLOSED_FALSE;//未被封号
        $c = new CDbCriteria();
        $c->compare('status', self::STATUS_TRUE); //有效状态
        $c->compare('type', $type);
        $c->compare('closed', $closed);
        if ($allot >= 0) {
            $c->compare('allot', $allot);
        }
        $c->order = 'id asc';
        $c->limit = 1;

        return $this->findAll($c);
    }


}