<?php

/**
 * This is the model class for table "{{ask_task}}".
 *
 * The followings are the available columns in table '{{ask_task}}':
 * @property integer $id
 * @property integer $a_id
 * @property integer $m_id
 * @property string $a_time
 * @property string $allow_time
 * @property integer $is_allow
 * @property integer $t_status
 * @property integer $tw_id
 */
class AskTask extends CActiveRecord
{
    /** 任务状态  -可申请*/
    const STATUS_CASK = DefaultParm::DEFAULT_ONE;
    /** 任务进度 - 已被申请 */
    const STATUS_AASK = DefaultParm::DEFAULT_TWO;
    /** 任务进度 - 已上报 */
    const STATUS_APRO = DefaultParm::DEFAULT_THREE;
    /** 任务进度 - 已完成 */
    const STATUS_ADONE = DefaultParm::DEFAULT_FOUR;
    /** 任务状态 - 已删除*/
    const STATUS_DEL = DefaultParm::DEFAULT_FIVE;

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

    /** 任务未批准 */
    const IS_ALLOW_FALSE = DefaultParm::DEFAULT_ZERO;
    /** 任务准许 */
    const IS_ALLOW_TRUE = DefaultParm::DEFAULT_ONE;
    /** 任务等待查看 */
    const IS_ALLOW_WAIT = DefaultParm::DEFAULT_TWO;

    /**
     * Returns the static model of the specified AR class.
     * @return AskTask the static model class
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
        return '{{ask_task}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('a_id, m_id, a_time', 'required'),
            array('a_id, m_id, is_allow, t_status ,tw_id', 'numerical', 'integerOnly' => true),
            array('allow_time', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, a_id, m_id, a_time, allow_time, is_allow, t_status', 'safe', 'on' => 'search'),
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
            'memberinfo' => array(self::HAS_ONE, 'MemberInfo', array('m_id' => 'id'))
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'a_id' => 'A',
            'm_id' => 'M',
            'a_time' => 'A Time',
            'allow_time' => 'Allow Time',
            'is_allow' => 'Is Allow',
            't_status' => 'T Status',
        );
    }

    /**
     * 拒绝任务
     */
    public function delAsktaskById($at_id){
        $sql3 = 'UPDATE app_ask_task SET t_status = \'' . AskTask::STATUS_DEL . '\'  WHERE id = \'' . $at_id . '\' ';
        return Yii::app()->db->createCommand($sql3)->execute();
    }
    /**
 * 拒绝任务---见习客服
 */
    public function updAsktaskById($at_id){
        $sql3 = 'UPDATE app_ask_task SET availability=2, t_status = \'' . AskTask::STATUS_AASK . '\'  WHERE id = \'' . $at_id . '\' ';
        return Yii::app()->db->createCommand($sql3)->execute();
    }
    /**
     * 批量拒绝任务
     */
    public function delAsktaskByIdList($msg,$is_allow,$str){
        $sql = 'UPDATE app_ask_task SET content = \'' . $msg . '\',allow_time = \'' . time() . '\',
                        t_status = \''.AskTask::STATUS_DEL.'\',is_allow = \'' . $is_allow . '\' WHERE FIND_IN_SET(id,\'' . $str . '\')';
        return  Yii::app()->db->createCommand($sql)->execute();
    }
    /**
     * 查看是否存在等在批准的任务
     */
    public function checkStatusByTask()
    {
        $arr = array();
        //待批准任务
        $res = AskTask::model()->count("is_allow=:is_allow", array(":is_allow" => AskTask::IS_ALLOW_WAIT));

        //待审核
      //  $res1 = AskTask::model()->count("t_status=:t_status", array(":t_status" => AskTask::STATUS_APRO));
        $sql1 = "SELECT at.*,m.name,m.id AS manage_id,mi.username,mi.category,mi.holder,mi.id AS member_id
                FROM app_ask_task AS at" . "
                JOIN app_manage AS m
                JOIN app_member AS mi
                JOIN app_task_when AS tw
                ON at.m_id = mi.id AND m.id = at.f_id AND tw.id = at.tw_id
                WHERE at.t_status != 5 AND at.t_status = '3' AND m.id != 0 ";
        $model1 = Yii::app()->db->createCommand($sql1)->queryAll();
        $res1 = count($model1);
       // $res1 = AskTask::model()->countBySql($sql);

        //可清除
//        $sql = 'SELECT count(at.id) AS count FROM app_ask_task AS at
//				JOIN app_task AS t
//				ON at.t_id = t.id
//			    WHERE at.t_status = \'' . AskTask::STATUS_ADONE . '\' AND t.status = \'' . Task::STATUS_DONE . '\' ';
       $sql2 = "SELECT at.*,m.name,m.id AS manage_id,mi.username,mi.category,mi.holder,mi.id AS member_id
                FROM app_ask_task AS at
                JOIN app_manage AS m
                JOIN app_member AS mi
                ON at.m_id = mi.id AND m.id = at.f_id
                WHERE at.t_status != 5 AND at.t_status = '4'/* AND manage_id != 0 */";
        $model2 = Yii::app()->db->createCommand($sql2)->queryAll();
        $res2 = count($model2);
       // $res1 = AskTask::model()->countBySql($sql);

       // $res2 = AskTask::model()->countBySql($sql);
        $arr['allow'] = $res;
        $arr['score'] = $res1;
        $arr['del'] = $res2;

        return $arr;
    }


    /**
     * 查看任务表中信息，和任务中的用户名
     */
    public function getTaskMsgByTid($t_id)
    {
        $sql = 'SELECT t.*,mi.username FROM app_ask_task AS t
		 		JOIN app_member AS mi
		 		ON t.m_id = mi.id
		 		WHERE t.id = \'' . $t_id . '\'';

        return Yii::app()->db->createCommand($sql)->queryAll();
    }


    /**
     * 查询被拒绝的任务的个数
     */
    public function getNotAllowTaskByUid($id)
    {
        $sql = 'SELECT count(*) AS count FROM app_ask_task WHERE f_id = \'' . $id . '\' AND is_allow =  \'' . AskTask::IS_ALLOW_FALSE . '\' ';
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        return $res[0]['count'];
    }

    /**
     * 查询被拒绝任务,待审批的任务信息列表
     */
    public function getAskTaskStatusNoAllow($id, $is_allow)
    {
        $sql = 'SELECT at.id,at.m_id,at.a_time,mi.username,mi.id AS mid,at.type,at.content FROM app_ask_task AS at
				JOIN app_member AS mi
				ON mi.id = at.m_id 
				WHERE at.f_id = \'' . $id . '\' AND is_allow = \'' . $is_allow . '\' ';
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     * 查询提醒时间到期的任务列表
     */
    public function getRemindTask($id)
    {
        $time = time();
        $sql = 'SELECT at.id,at.m_id,at.a_time,mi.username,mi.id AS mid,at.type,at.content FROM app_ask_task AS at
				JOIN app_member AS mi
				JOIN app_task AS t
				JOIN app_task_when AS tw
				ON mi.id = at.m_id AND at.t_id = t.id AND at.tw_id = tw.id
				WHERE at.f_id = \'' . $id . '\'
				AND \'' . $time . '\'> tw.remind  AND tw.remind !=0  AND tw.status != \'' . TaskWhen::STATUS_SUBMAIT . '\' AND t.status = \'' . Task::STATUS_NORMAL . '\'  AND (at.t_status =  \'' . AskTask::STATUS_CASK . '\' OR at.t_status = \'' . AskTask::STATUS_AASK . '\')';
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     * 查询被拒绝的任务申请by atid
     */
    public function getATidByATid($at_id)
    {
        $sql = 'SELECT id,f_id,type,is_allow,t_status,m_id,t_id,tw_id FROM app_ask_task WHERE FIND_IN_SET(id,\'' . $at_id . '\')';
        return Yii::app()->db->createCommand($sql)->queryAll();

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
     * 修改任務內容
     */
    public function updateAtMsg($is_allow, $msg, $time, $a_id, $at_id)
    {

        $asktask = AskTask::model()->findByAttributes(array('id' => $at_id));

        $asktask->is_allow = $is_allow;
        $asktask->content = $msg;
        $asktask->allow_time = $time;
        $asktask->a_id = $a_id;
        return $asktask->update();

    }

    /**
     * 修改任务内容3
     */
    public function updateAtMsgById($is_allow, $tid, $twid, $msg, $atid)
    {
        $sql = 'UPDATE app_ask_task SET is_allow = \'' . $is_allow . '\',
				t_id = \'' . $tid . '\',tw_id = \'' . $twid . '\',
				content = \'' . $msg . '\',
				allow_time = \'' . time() . '\'
				WHERE id = \'' . $atid . '\'';
        return Yii::app()->db->createCommand($sql)->execute();
    }

    /**
     *
     * @return AskTask[]
     */
    public function getListByUids($uids, $t_type)
    {
        $criteria = new CDbCriteria();
        $criteria->addInCondition('m_id', $uids);
        $criteria->compare('t_type', $t_type);
        $list = $this->findAll($criteria);
        $data = array();
        foreach ($list as $ask) {
            $data[$ask->m_id] = $ask;
        }
        return $data;
    }


}