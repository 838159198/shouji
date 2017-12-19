<?php

/**
 * 任务
 * This is the model class for table "{{task}}".
 *
 * The followings are the available columns in table '{{task}}':
 * @property string $id
 * @property string $publish
 * @property string $accept
 * @property integer $createtime
 * @property integer $motifytime
 * @property string $title
 * @property string $content
 * @property integer $type
 * @property integer $status
 * @property integer $isshow
 * @property string $mid
 *
 * The followings are the available model relations:
 * @property TaskWhen[] $taskWhens
 * @property Manage $managePublish
 * @property Manage $manageAccept
 * @property Member $member
 */
class Task extends CActiveRecord
{
    /** 任务进度 - 进行中 */
    const STATUS_NORMAL = DefaultParm::DEFAULT_ZERO;
    /** 任务进度 - 已有回复 */
    const STATUS_SUBMIT = DefaultParm::DEFAULT_ONE;
    /** 任务进度 - 已完成 */
    const STATUS_DONE = DefaultParm::DEFAULT_TWO;
    /** 任务进度 - 已删除 */
    const STATUS_DEL = DefaultParm::DEFAULT_THREE;

    /** 任务查看状态 - 已查看 */
    const ISSHOW_TRUE = DefaultParm::DEFAULT_ONE;
    /** 任务查看状态 - 未查看 */
    const ISSHOW_FALSE = DefaultParm::DEFAULT_ZERO;

    /** 任务类型 - 新客户任务 */
    const TYPE_NEW = DefaultParm::DEFAULT_ONE;
    /** 任务类型 - 降量任务 */
    const TYPE_DROP = DefaultParm::DEFAULT_TWO;
    /** 任务类型 - 周任务 */
    const TYPE_WEEK = DefaultParm::DEFAULT_THREE;
    /** 任务类型 - 其他任务 */
    const TYPE_OTHER = DefaultParm::DEFAULT_FOUR;
    /** 任务类型 - 回访任务 */
    const TYPE_VISIT = DefaultParm::DEFAULT_FIVE;

    /** 任务流程 - 评分后已查看 */
    const TYPE_IS_SHOW_TRUE = DefaultParm::DEFAULT_ONE;
    /** 任务流程 - 评分后未查看 */
    const TYPE_IS_SHOW_FALSE = DefaultParm::DEFAULT_ZERO;

    private $connection = null;
    private $command = null;

    /**
     * 任务查看状态
     * @return array
     */
    public static function getIsShowList()
    {
        return array(0 => '否', 1 => '是');
    }

    /**
     * 任务进度状态
     * @return array
     */
    public static function getStatusList()
    {
        return array(0 => '进行中', 1 => '已回复', 2 => '已完成', 3 => '已删除');
    }
//	/**
//	 * 任务类型
//	 * @return array
//	 */
//	public static function getTaskType() {
//		return array (self::TYPE_NEW => 1, self::TYPE_DROP => 2,
//					self::TYPE_WEEK => 3,  self::TYPE_OTHER=> 4,
//					self::TYPE_VISIT =>5 );
//	}

    /**
     * 任务类型
     * @param args .. 任务类型id
     * @return array
     */
    public static function getTypeList()
    {
        $types = array(self::TYPE_NEW => '新用户任务', self::TYPE_DROP => '降量任务', self::TYPE_WEEK => '周任务', self::TYPE_VISIT => '回访任务', self::TYPE_OTHER => '其他任务');

        $args = func_get_args();
        if (empty ($args)) {
            return $types;
        } else {
            $_types = array();
            foreach ($args as $arg) {
                if (!isset ($types [$arg]))
                    continue;
                $_types [$arg] = $types [$arg];
            }
            return $_types;
        }
    }

    public static function getTypeList2()
    {
        $types = array(self::TYPE_NEW => '新用户任务', self::TYPE_DROP => '降量任务', self::TYPE_WEEK => '周任务', self::TYPE_VISIT => '回访任务', self::TYPE_OTHER => '其他任务');

        $args = func_get_args();
        if (empty ($args)) {
            return $types;
        } else {
            $_types = array();
            foreach ($args as $arg) {
                if (!isset ($types [$arg]))
                    continue;
                $_types [$arg] = $types [$arg];
            }
            return $_types;
        }
    }

    /**
     * @param $id
     * @return string
     */
    public static function getTypeName($id)
    {
        $typeList = self::getTypeList();
        return isset ($typeList [$id]) ? $typeList [$id] : '';
    }

    /**
     * @param $status
     * @return string
     */
    public static function getStatusName($status)
    {
        $_statusList = self::getStatusList();
        return isset ($_statusList [$status]) ? $_statusList [$status] : '';
    }

    /**
     * @param $isshow
     * @return string
     */
    public static function getIsShowName($isshow)
    {
        $_isShowList = self::getIsShowList();
        return isset ($_isShowList [$isshow]) ? $_isShowList [$isshow] : '';
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Task the static model class
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
        return '{{task}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(array('publish, accept, mid, createtime, motifytime, title, content, status', 'required'), array('status, isshow, type', 'numerical', 'integerOnly' => true), array('publish, accept, mid', 'length', 'max' => 11), array('title', 'length', 'max' => 50), array('content', 'length', 'max' => 255), // The following rule is used by search().
// Please remove those attributes that should not be searched.
            array('id, publish, accept, mid, createtime, motifytime, title, content, type, status, isshow', 'safe', 'on' => 'search'));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array('taskWhens' => array(self::HAS_MANY, 'TaskWhen', 'tid'), 'managePublish' => array(self::BELONGS_TO, 'Manage', 'publish'), 'manageAccept' => array(self::BELONGS_TO, 'Manage', 'accept'), 'member' => array(self::BELONGS_TO, 'Member', 'mid'),

            //(表的别名)								(表名)(Member表的关联)
            'manageTest' => array(self::HAS_MANY, 'Member', 'id'));
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array('id' => 'ID', 'publish' => '发布人', 'accept' => '接收人', 'createtime' => '发布时间', 'motifytime' => '更改时间', 'title' => '标题', 'content' => '内容', 'type' => '任务类型', 'status' => '状态', 'isshow' => '上级查看', 'mid' => '绑定会员');
    }


    public function delTaskByTidList($tid_list)
    {
        $sql = 'UPDATE app_task SET status = \'' . Task::STATUS_DEL . '\' WHERE FIND_IN_SET(id,\'' . $tid_list . '\') ';
        $res = Yii::app()->db->createCommand($sql)->execute();
        return $res;
    }

    public function checkStatusByTidList($tid_list)
    {
        $sql = 'SELECT status FROM app_task WHERE FIND_IN_SET(id,\'' . $tid_list . '\')';
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     * 客服列表
     */
    public function manageCheckList()
    {
        $sql = 'SELECT m.role,m.name,m.id,r.name AS rname FROM app_manage AS m
				JOIN app_role AS r
				ON m.role  = r.id
        		WHERE m.role>=\'' . Role::ROLE_AMALDAR . '\' AND m.role<=\'' . Role::PRACTICE_STAFF . '\'  AND m.status !=0';
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     * 管理员列表
     */
    public function manageList()
    {
        $sql = 'SELECT * FROM app_manage WHERE role=  \'' . Role::ROLE_ADMIN . '\' OR role =  \'' . Role::ROLE_AMALDAR . '\' AND status !=  \'' . DefaultParm::DEFAULT_ZERO . '\' ';
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     * 修改任务状态
     * Enter description here ...
     * @param unknown_type $status
     * @param unknown_type $id
     */
    public function getUpdataStatus($status, $id)
    {
        //客服是否提交任务与管理员是否评分
        $sql = 'SELECT tw.status AS tw_status,tw.score,t.status FROM app_task_when AS tw
    			JOIN app_task AS t
    			ON tw.tid = t.id
    			WHERE tw.tid = \'' . $id . '\' ';
        $stat = Yii::app()->db->createcommand($sql)->queryAll();

        $res = '';
        if ($stat [0] ['tw_status'] == TaskWhen::STATUS_SUBMAIT) {
            if ($stat [0] ['score'] != TaskWhen::ZERO_STAR) { //如果管理员已经评分
                if ($stat [0] ['status'] == Task::STATUS_DONE) { //任务表显示任务完成
                    $sql = 'UPDATE app_task set status =\'' . $status . '\' WHERE id = \'' . $id . '\'';
                    $res = Yii::app()->db->createcommand($sql)->execute();
                } else {
                    $res = DefaultParm::DEFAULT_ZERO; //修改失败
                }
            } else {
                $res = DefaultParm::DEFAULT_TWO; //还未评分
            }
        } else {
            $res = DefaultParm::DEFAULT_THREE; //客服还未提交任务
        }

        return $res;

    }

    /**
     * 任务是否被审核
     * Enter description here ...
     * @param unknown_type $isshow
     * @param unknown_type $id
     */
    public function getUpdataShow($isshow, $id)
    {
        $sql = 'UPDATE app_task set isshow =\'' . $isshow . '\' WHERE id = \'' . $id . '\'';
        return Yii::app()->db->createcommand($sql)->execute();
    }

    /**
     * 查询当前任务客户的详细信息
     */
    public function getMemberMsgByTid($tid)
    {

        //查找关联的member_resource
        $sql = 'SELECT mr.* FROM app_member_resource AS mr
    			JOIN app_member AS m
    			JOIN app_task AS t
    			ON t.mid = m.id AND  m.id = mr.uid  
    			WHERE  m.status != \'' . DefaultParm::DEFAULT_ZERO . '\' AND t.id = \'' . $tid . '\' ';
        $count = Yii::app()->db->createcommand($sql)->queryAll();
        //可能是表缺失，测试···
        if (empty ($count)) {
            $sql1 = 'SELECT m.id AS mid,m.tel,m.holder,m.clients,m.agent,m.type,m.category,m.regist_tel,
    			t.createtime AS ttime,m.jointime,m.overtime,t.createtime,t.title,t.content,
    			at.a_time,tw.porttime,at.type
    			FROM app_task  AS t
    			JOIN app_member AS m
    			JOIN app_ask_task AS at
    			JOIN app_task_when AS tw
    			ON  t.mid = m.id AND at.tw_id = tw.id AND at.t_id = t.id
    			WHERE  m.status != \'' . DefaultParm::DEFAULT_ZERO . '\' AND t.id = \'' . $tid . '\' ';
        } else {
            //如果有
            $sql1 = 'SELECT m.id AS mid,m.tel,m.holder,m.clients,m.agent,m.type,m.category,m.regist_tel,
	    			mr.bod,mr.type AS mrtype,t.createtime AS ttime,m.jointime,m.overtime,t.createtime,t.title,t.content,
	    			at.a_time,tw.porttime,at.type
	    			FROM app_task  AS t
	    			JOIN app_member AS m
	    			JOIN app_member_resource AS mr
	    			JOIN app_ask_task AS at
    				JOIN app_task_when AS tw
	    			ON  t.mid = m.id AND m.id = mr.uid AND at.tw_id = tw.id AND at.t_id = t.id
	    			WHERE  m.status != \'' . DefaultParm::DEFAULT_ZERO . '\' AND t.id = \'' . $tid . '\' ';
        }
        return Yii::app()->db->createcommand($sql1)->queryAll();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.


        $criteria = new CDbCriteria ();

        $criteria->compare('id', $this->id, true);
        $criteria->compare('publish', $this->publish, true);
        $criteria->compare('accept', $this->accept, true);
        $criteria->compare('createtime', $this->createtime, true);
        $criteria->compare('motifytime', $this->motifytime, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('status', $this->status);
        $criteria->compare('isshow', $this->isshow);
        $criteria->compare('mid', $this->mid);
        $criteria->order = 'id DESC';

        return new CActiveDataProvider ($this, array(
            'criteria' => $criteria,
            'pagination' => array('pagesize' => Common::PAGE_SIZE)
        ));
    }

    /**
     * @param $uid 发布人ID
     * @param $status 状态
     * @return CActiveDataProvider
     */
    public function getListByPublish($uid, $status)
    {
        $c = new CDbCriteria ();
        $c->compare('t.`publish`', $uid);
        $c->compare('t.`status`', $status);
        $c->with = 'manageAccept';
        return new CActiveDataProvider ($this, array('criteria' => $c, 'pagination' => array('pageSize' => Common::PAGE_SIZE)));
    }

    /**
     * @param $uid 接收人ID
     * @param $status 状态
     * @return CActiveDataProvider
     */
    public function getListByAccept($uid, $status)
    { //多表联查，function relations定义关联的表，自身查找task表
        $c = new CDbCriteria ();
        $c->compare('t.`accept`', $uid); //task表接收任务人的id
        $c->compare('t.`status`', $status); //task表任务的状态
        $c->with = 'managePublish'; //关联 Manage表中的publish
        return new CActiveDataProvider ($this, array('criteria' => $c, 'pagination' => array('pageSize' => Common::PAGE_SIZE)));
    }

    /**
     * 根据用户ID获取任务信息
     * @param $mid
     * @return Task[]
     */
    public function getListByMemberId($mid)
    {
        $c = new CDbCriteria ();
        $c->addCondition('t.`mid` = :mid');
        $c->addCondition('t.`status` != :status');
        $c->params = array(':mid' => $mid, ':status' => self::STATUS_DEL);
        $c->with = 'manageAccept';
        return $this->findAll($c);
    }

    /**
     * 根据用户ID获取任务信息（未结束）
     * @param $midList
     * @return Task[]
     */
    public function getListByMemberIdList($midList)
    {
        $c = new CDbCriteria ();
        $c->addCondition('t.`status` < :status');
        $c->params = array(':status' => self::STATUS_DONE);
        $c->addInCondition('t.`mid`', $midList);
        $c->with = 'member';
        /* @var $tasks Task[] */
        $tasks = $this->findAll($c);
        $data = array();
        foreach ($tasks as $task) {
            $data [$task->mid] = $task;
        }
        return $data;
    }



    /**
     * 获取可操作任务的任务类别，任务数量
     */
    public function getAllKindTaskCountById($id)
    {
        $arr = array();
        //提醒时间到期的任务数量
        $time = time();
        $sql1 = 'SELECT COUNT(tw.id) AS count FROM app_task_when AS tw
        		JOIN app_task AS t
        		ON tw.tid = t.id 
        		WHERE  tw.remind != 0  AND \'' . $time . '\'>= tw.remind  AND tw.status != \'' . TaskWhen::STATUS_SUBMAIT . '\' AND t.accept = \'' . $id . '\'';
        $r_task_count = Yii::app()->db->createCommand($sql1)->queryAll();

        $r_task_count = $r_task_count [0] ['count'];

        //正在等待审核的任务
        $sql2 = 'SELECT COUNT(id) AS count FROM app_ask_task WHERE t_status= \'' . AskTask::STATUS_AASK . '\' AND is_allow =  \'' . AskTask::IS_ALLOW_WAIT . '\'  AND  f_id = \'' . $id . '\'';
        $w_a_task_count = Yii::app()->db->createCommand($sql2)->queryAll();
        $w_a_task_count = $w_a_task_count [0] ['count'];

        //回访任务数量
        $sql3 = 'SELECT COUNT(id) AS count FROM app_ask_task WHERE type =  \'' . AskTask::TYPE_VISIT . '\' AND t_status = \'' . AskTask::STATUS_AASK . '\'  AND f_id = \'' . $id . '\'';
        $visit_task_count = Yii::app()->db->createCommand($sql3)->queryAll();
        $visit_task_count = $visit_task_count [0] ['count'];
        $arr['remind'] = $r_task_count;
        $arr['w_allow'] = $w_a_task_count;
        $arr['visite'] = $visit_task_count;
        return $arr;
    }

    /**
     * 獲取任務狀態
     */
    public function getTaskStatusById($t_id)
    {

        return Task::model()->findAllByPk($t_id);
    }

    /**
     * 獲取任務狀態
     */
    public function getTaskStatusInArr($tid_list)
    {

        return Task::model()->findAllByPk($tid_list);
    }

    /**
     *
     * @return AskTask[]
     */
    public function getListByUids($uids)
    {
        $criteria = new CDbCriteria ();
        $criteria->addInCondition('mid', $uids);
        $list = $this->findAll($criteria);
        $data = array();
        foreach ($list as $task) {
            $data [$task->mid] = $task;
        }
        return $data;
    }

    /**
     * 未上报任务数量
     */
    public function getCountNoProTask($id)
    {
        $sql_count = "SELECT COUNT(t.id) AS count FROM app_task AS t
					JOIN app_ask_task AS at
					JOIN app_task_when AS tw
					ON at.t_id = t.id AND at.tw_id = tw.id AND tw.tid = t.id
					WHERE t.accept = $id AND 
					tw.status !=" . TaskWhen::STATUS_SUBMAIT . " AND tw.isfail = " . TaskWhen::IS_FAIL_FALSE . " AND
					t.status = " . Task::STATUS_NORMAL . " AND
					at.t_status != " . AskTask::STATUS_DEL . " AND at.t_status !=" . AskTask::STATUS_APRO . " AND at.t_status !=" . AskTask::STATUS_ADONE . " ";
        $num = AskTask::model()->countBySql($sql_count);
        return $num;
    }

    /**
     * 查看任务信息
     * @param $at_id
     * @return AskTask
     */
    public function getAtByAtId($at_id)
    {
        $c = new CDbCriteria();
        $c->compare('id', $at_id);
        return $this->find($c);
//		return AskTask::model()->findByPk ($at_id);
    }


    /**
     * 最近一次发布任务的时间
     */
    public function getMaxCreatetime($createtime = '')
    {
        $time = (isset($createtime) && !empty($createtime)) ? $createtime : DateUtil::time();
        $sql = 'SELECT MAX(t.createtime) AS count FROM app_task AS t
				JOIN app_task_when AS tw
				ON t.id = tw.tid
				WHERE t.createtime < \'' . $time . '\'
				AND t.status = \'' . Task::STATUS_NORMAL . '\'
				AND tw.status = \'' . TaskWhen::STATUS_NORMAL . '\'
				AND tw.isfail = \'' . TaskWhen::IS_FAIL_FALSE . '\' ';
        $maxcreatetime = Yii::app()->db->createCommand($sql)->queryAll();
        $maxcreatetime = $maxcreatetime [0] ['count'];
        $sql1 = 'SELECT t.id,t.publish,t.accept,t.createtime,mi.username  FROM app_task AS t
				JOIN app_member AS mi 
				JOIN app_task_when AS tw
				ON mi.id = t.mid AND t.id = tw.tid
				WHERE t.createtime = \'' . $maxcreatetime . '\'  ';
        $last_task = Yii::app()->db->createCommand($sql1)->queryAll();


        return $last_task;

    }

    public function delManageAllMsgAndTasks($manageid)
    {
        $success = '';

        //查询客服所属的所有用户id
        $sql = 'SELECT id FROM app_member WHERE manage_id = \'' . $manageid . '\' ';
        $memberList = Yii::app()->db->createCommand($sql)->queryAll();
        $mem = array();
        foreach ($memberList AS $ke => $va) {

            $mem[$ke] = $va['id'];
        }

        $member = implode(",", $mem);


        //查询所有属于该客服的任务
        $sql2 = 'SELECT id,t_id,tw_id FROM app_ask_task WHERE f_id = \'' . $manageid . '\'';
        $task_id_list = Yii::app()->db->createCommand($sql2)->queryAll();

        $at = array();
        $t = array();
        $tw = array();
        $task = array();

        foreach ($task_id_list AS $key => $val) {

            $at[$key] = $val['id'];
            $t[$key] = $val['t_id'];
            $tw[$key] = $val['tw_id'];
        }
        $num = count($at);
        $task['at'] = implode(",", $at);
        $task['t'] = implode(",", $t);
        $task['tw'] = implode(",", $tw);

        $t = Yii::app()->db->beginTransaction();
        try {

            $sql3 = 'UPDATE app_ask_task SET t_status = 5 WHERE f_id = \'' . $manageid . '\' AND  FIND_IN_SET(id,\'' . $task['at'] . '\') ';
            $sql4 = 'UPDATE app_task SET status = 3 WHERE accept = \'' . $manageid . '\' AND  FIND_IN_SET(id,\'' . $task['t'] . '\') ';
            // $sql5 = 'UPDATE app_task_when SET t_status = 5 WHERE f_id = \''.$manageid.'\' AND  FIND_IN_SET(id,\''.$task['tw'].'\') ';

            $sql6 = 'UPDATE app_member  SET manage_id = " " WHERE  FIND_IN_SET(id,\'' . $member . '\') ';

            $sql7 = 'UPDATE app_task_earnings SET payback = 0,ispay = 0 WHERE uid = \'' . $manageid . '\' ';

            $sql8 = 'UPDATE app_manage SET status = 0 WHERE id = \'' . $manageid . '\' ';

            $res3 = Yii::app()->db->createCommand($sql3)->execute();
            $res4 = Yii::app()->db->createCommand($sql4)->execute();
            // $res5 = Yii::app()->db->createCommand($sql5)->execute();

            $res6 = Yii::app()->db->createCommand($sql6)->execute();

            $res7 = Yii::app()->db->createCommand($sql7)->execute();

            $res8 = Yii::app()->db->createCommand($sql8)->execute();
            $t->commit();
            $success = 1;
            return $success;
        } catch (Exception $e) {
            $t->rollback();

            $success = 0;
            return $success;
        }

    }

    public function getOptionM_id($manage_id)
    {

        if (isset($manage_id) && ($manage_id != '')) {

            $manage_id = "  AND at.f_id = " . $manage_id ;
        } else {

            $manage_id = '';
        }

        return $manage_id ;
    }

    public function getOptionT_type($task_type)
    {

        if (isset($task_type) && ($task_type != '')) {

            $task_type = " AND t.type = " . $task_type . " AND at.type = " . $task_type;
        } else {

            $task_type = '';
        }

        return $task_type;
    }

    public function getOptionT_status($task_status)
    {
        if (isset($task_status) && ($task_status != '')) {

            switch ($task_status) {
                case "0": //等待审批

                    $task_status = " AND at.is_allow = " . AskTask::IS_ALLOW_WAIT .
                        " AND at.t_status = " . AskTask::STATUS_AASK;
                    break;
                case "1": //正在执行

                    $task_status = " AND at.t_status   = " . AskTask::STATUS_AASK .
                        "  AND t.status  = " . Task::STATUS_NORMAL .
                        "  AND tw.status = " . TaskWhen::STATUS_NORMAL;
                    break;
                case "2": //上报成功

                    $task_status = " AND at.t_status   = " . AskTask::STATUS_APRO .
                        "  AND t.status  = " . Task::STATUS_SUBMIT .
                        "  AND tw.status = " . TaskWhen::STATUS_SUBMAIT .
                        "  AND tw.isfail = " . TaskWhen::IS_FAIL_FALSE;
                    break;
                case "3": //上报失败

                $task_status = " AND at.t_status   = " . AskTask::STATUS_APRO .
                    "  AND t.status  = " . Task::STATUS_SUBMIT .
                    "  AND tw.status = " . TaskWhen::STATUS_SUBMAIT .
                    "  AND tw.isfail = " . TaskWhen::IS_FAIL_TRUE;
                    break;
                case "4": //完成并评分

                    $task_status = " AND at.t_status   = " . AskTask::STATUS_DEL .
                        "  AND t.status  = " . Task::STATUS_DEL .
                        "  AND tw.isfail = " . TaskWhen::IS_FAIL_FALSE .
                        "  AND tw.score != ''";
                    break;
            }

        } else {

            $task_status = '';
        }

        return $task_status;
    }


    public function getOptionM_name($member_name){

        if (isset($member_name) && ($member_name != '')) {

            $sql = 'SELECT '.' id FROM app_member WHERE username = \''.$member_name.'\' ';
            $memner_id = Yii::app()->db->createCommand($sql)->queryAll();
            $memner_id = isset($memner_id[0]['id']) ? $memner_id[0]['id'] : 0;

            $member_name = " AND at.m_id = " . $memner_id . " AND t.mid = " . $memner_id;
        } else {

            $member_name = '';
        }

        return $member_name;
    }

    public function getOptionS_time($task_ss,$task_se){

        if ((isset($task_ss) && ($task_ss != '')) && ((isset($task_se) && ($task_se != '')))) {

            $task_ss = " AND at.a_time BETWEEN $task_ss AND $task_se";
        } else {

            $task_ss = '';
        }

        return $task_ss;
    }

    public function getOptionP_time($task_ps,$task_pe){

        if ((isset($task_ps) && ($task_ps != '')) && ((isset($task_pe) && ($task_pe != '')))) {

            $task_ps = " AND tw.porttime BETWEEN $task_ps AND $task_pe";
        } else {

            $task_ps = '';
        }

        return $task_ps;
    }

    public function changeSearchOption($manage_id = '', $task_type = '', $task_status = '', $TABLE ,$member_name = '',
                                       $task_ss = '',$task_se = '',$task_ps ='',$task_pe ='')
    {
        $task_ss = strtotime($task_ss);
        $task_se = strtotime($task_se);
        $task_ps = strtotime($task_ps);
        $task_pe = strtotime($task_pe);
        $OPTION  = '';


        /******存在task_type开始******/
        if (($task_status == 0) ) {
            $OPTION['FIND'] =  " at.*,mi.username,mi.cataid,mi.category,ma.username AS mname";
            $OPTION['JOIN'] = '';
            $OPTION['ON']   = '';
            $OPTION['task_type'] = " AND at.type = " . $task_type;

        }
        $OPTION['FIND'] =  " at.*,tw.porttime,tw.pay_back,tw.b_pay,tw.a_pay,
                            mi.username,mi.cataid,mi.category,
                            ma.username AS mname";

        $OPTION['JOIN'] = "JOIN " . $TABLE['task'] . "JOIN " . $TABLE['taskwhen'] .
                          "JOIN " . $TABLE['member'] ."JOIN " .$TABLE['manage'];

        $OPTION['ON']   = "ON at.t_id = t.id AND at.tw_id = tw.id AND tw.tid = t.id
                           AND at.m_id = mi.id AND ma.id = at.f_id";

        $OPTION['task_type']    = Task::model()->getOptionT_type($task_type);

        /******存在manage_id开始******/
        $OPTION['manage_id']    = Task::model()->getOptionM_id($manage_id);

        /******存在task_status开始******/
        $OPTION['task_status']  = Task::model()->getOptionT_status($task_status);

        /******存在member_name开始******/
        $OPTION['member_name']  = Task::model()->getOptionM_name($member_name);

        /******存在send_time开始******/
        $OPTION['task_sta_time']  = Task::model()->getOptionS_time($task_ss,$task_se);

        /******存在pro_time开始******/
        $OPTION['task_pro_time']  = Task::model()->getOptionP_time($task_ps,$task_pe);

        return $OPTION;
    }
}
