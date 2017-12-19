<?php

/**
 * This is the model class for table "{{mail}}".
 *
 * The followings are the available columns in table '{{mail}}':
 * @property integer $id
 * @property integer $send
 * @property integer $recipient
 * @property integer $content
 * @property string $jointime
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property MailContent $MailContent
 * @property Manage $manage
 */
class Mail extends CActiveRecord
{
    /** 状态-未读 */
    const STATUS_NONE = 0;
    /** 状态-已读 */
    const STATUS_READ = 1;
    /** 状态-客户删除 */
    const STATUS_MEMBER_DEL = 2;
    /** 状态-管理员删除 */
    const STATUS_ADMIN_DEL = 3;

    private static $_statusList = array(
        0 => '未读',
        1 => '已读',
        2 => '客户删除',
        3 => '管理员删除'
    );

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return self::$_statusList;
    }

    /**
     * @param integer $status
     * @return string
     */
    public static function getStatusName($status)
    {
        return isset(self::$_statusList[$status]) ? self::$_statusList[$status] : '';
    }


    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Mail the static model class
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
        return '{{mail}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('send, recipient, content, status', 'numerical', 'integerOnly' => true),
            array('jointime', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, send, recipient, content, jointime, status', 'safe', 'on' => 'search'),
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
            'MailContent' => array(self::BELONGS_TO, 'MailContent', 'content'),
            'manage' => array(self::BELONGS_TO, 'Manage', 'send'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'send' => '发件人',
            'recipient' => '收件人',
            'content' => '内容',
            'jointime' => '发送时间',
            'status' => '状态',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('send', $this->send);
        $criteria->compare('recipient', $this->recipient);
        $criteria->compare('content', $this->content);
        $criteria->compare('jointime', $this->jointime, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    /**
     * @param $id
     * 获取title
     */
    public function getTitleById($id)
    {
        $sql = 'SELECT title FROM app_mail as m left join app_mail_content as mc on mc.id=m.content WHERE m.id = \'' . $id . '\' ';
        $name = Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($name))
        {
            return $name[0]['title'];
        }
        else
        {
            return "异常数据";
        }

    }
    /**
     * @param $id
     * @return Mail
     */
    public function getById($id)
    {
        $c = new CDbCriteria();
        $c->addCondition('t.id=:id');
        $c->params = array(':id' => $id);
        $c->with = array('MailContent', 'manage');
        return $this->find($c);
    }

    /**
     * 根据条件获取满足条件的行数
     * @param $status
     * @param null $uid
     * @return int
     */
    public function getCountByUid($status, $uid)
    {
        $c = new CDbCriteria();
        $c->addCondition('recipient=:uid');
        $c->addCondition('status' . $status);
        $c->params = array(':uid' => $uid);
        $c->with = 'MailContent';

        return $this->count($c);
    }

    /**
     * 根据收件人用户ID获取站内信信息
     * @param $status string 状态
     * @param int $uid
     * @return CActiveDataProvider
     */
    public function getListByUid($status, $uid)
    {
        $c = new CDbCriteria();
        $c->addCondition('t.recipient=:uid');
        $c->addCondition('t.status' . $status);
        $c->params = array(':uid' => $uid);
        $c->order = 't.jointime desc';
        $c->with = array('MailContent', 'manage');

        return new CActiveDataProvider($this, array(
            'criteria' => $c,
            'pagination' => array(
                'pageSize' => Common::PAGE_SIZE,
            ),
        ));
    }

    /**
     * 查看站内信详情
     *ZLB
     * 2017-09-08
     */
    public function research($id,$status){
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('send', $this->send);
        $criteria->compare('recipient', $this->recipient);
        $criteria->compare('content', $this->content);
        $criteria->compare('jointime', $this->jointime, true);
        $criteria->compare('status', $this->status);
        $criteria->condition='mc.id='.$id;
        if(isset($status) && $status !=''){
           $criteria->condition.=' and t.status='.$status; 
        }
        
        $criteria->join=' left join `app_mail_content` as mc on mc.id=t.content ';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
    }
}