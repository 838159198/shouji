<?php

/**
 * Created
 * User:
 * @name 平台数据统计与降量任务的发布
 */
class StatsController extends DhadminController
{
    public function actionIndex()
    {
        throw new CHttpException(404);
    }

    /**
     * @param string $firstDate
     * @param string $lastDate
     * @name 收入合计曲线图
     */
    public function actionGraphs($firstDate = '', $lastDate = '')
    {
        Script::registerScriptFile(Script::HIGHSTOCK);
        Script::registerScriptFile('manage/memberinfo.graphs.js');

        if (empty($firstDate)) $firstDate = date('Y-m-d', strtotime('-3 month'));
        if (empty($lastDate)) $lastDate = date('Y-m-d');

        $incomes = StatsModel::actionGraphs($firstDate, $lastDate);

        $this->render('graphs', array(
            'json' => json_encode($incomes),
            'first' => $firstDate,
            'last' => $lastDate,
        ));
    }

    /**
     * @name 降量分析
     */
    public function actionDropdata()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/common.js');
        Script::registerScriptFile('manage/stats.dropdata.js');
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerCssFile('asyncbox.css');

        $param = Yii::app()->request->getQuery('Param');
        if (empty($param)) {
            $firstDate = date('Y-m-d', strtotime('-2 day'));
            $lastDate = date('Y-m-d', strtotime('-1 day'));
            $typeList = Ad::getAdListKeys();
            $param = array(
                'firstDate' => array('first' => $firstDate, 'last' => $firstDate),
                'lastDate' => array('first' => $lastDate, 'last' => $lastDate),
                'type' => $typeList[0]
            );
        }
        $id = Yii::app()->user->manage_id;
        $model = Task::model()->manageCheckList();
        $data = StatsModel::actionDropdata($param, $id, 30);

        $this->render('dropdata', array(
            'data' => $data,
            'param' => $param,
            'model' => $model
        ));
    }

    /**
     * @name 首页 -降量-任务申请
     */
    public function actionAskForTask()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $m_id = ( int )Yii::app()->request->getParam('m_id'); //申请的用户的id member_id
            $type = ( int )Yii::app()->request->getParam('type'); //任务类型
            $f_id = Yii::app()->user->manage_id; //当前登录的客服id
            $time = time(); //当前时间
            //查看当前用户的任务条数

            $model = MemberInfo::model()->findByPk($m_id);
            if (($model->manage_id == DefaultParm::DEFAULT_ZERO) || empty($model->manage_id)) {

                $t = Yii::app()->db->beginTransaction();
                try {
                    $asktask = new AskTask();
                    $asktask->f_id = Yii::app()->user->manage_id;
                    $asktask->m_id = $m_id;
                    $asktask->a_time = $time;
                    $asktask->is_allow = AskTask::IS_ALLOW_WAIT;
                    $asktask->t_status = AskTask::STATUS_AASK;
                    $asktask->type = $type;
                    $asktask->insert();

                    $rec = MemberInfo::model()->updateByPk($m_id, array('manage_id' => $f_id));

                    $t->commit();
                    echo CJSON::encode(array('msg' => '0')); //收益发布成功

                } catch (Exception $e) {
                    $t->rollback();
                    echo CJSON::encode(array('msg' => '1')); //收益发布失败
                }

            } else {
                echo CJSON::encode(array('msg' => '1'));
                exit;

            }

        }
    }


    /**
     * 降量任务批量发布
     * @name 降量任务批量发布
     * @throws CHttpException
     */
    public function actionSendTask()
    {
        if (Yii::app()->request->isPostRequest) {
            if (isset(Yii::app()->request->isAjaxRequest)) {

                $selectsend = Yii::app()->request->getParam('selectsend');
                $content = Yii::app()->request->getParam('content');
                $title = Yii::app()->request->getParam('title');
                $f_id = (int)Yii::app()->request->getParam('a_id');
                $mid = (int)Yii::app()->request->getParam('mid');


                $id = Yii::app()->user->manage_id;
                $role = Manage::model()->getRoleByUid($id);
                if ($role > 4) {
                    echo 'no_power';
                    exit;

                }
                $str = '';
                $str = implode(",", $selectsend);
                $sql = 'SELECT manage_id FROM app_member WHERE FIND_IN_SET(id,\'' . $str . '\');';
                $res = Yii::app()->db->createCommand($sql)->queryAll();

                foreach ($res AS $key => $item) {
                    if ($item['manage_id'] != 0) {
                        echo 'a_in';
                        exit;
                    }
                }
                $t = Yii::app()->db->beginTransaction();
                try {

                    foreach ($selectsend as $send) {

                        //添加新任务
                        $model = new Task('insert');
                        $model->title = '降量任务__' . $title;
                        $model->content = date('Y-m-d', time()) . '发布降量任务</br>' . $content;
                        $model->type = Task::TYPE_DROP;
                        $model->accept = $f_id;
                        $model->publish = Yii::app()->user->manage_id;
                        $model->createtime = DateUtil::time();
                        $model->status = Task::STATUS_NORMAL;
                        $model->mid = $send;
                        $model->insert();

                        $taskWhen = new TaskWhen('insert');
                        $taskWhen->tid = $model->id;
                        $taskWhen->createtime = $model->createtime;
                        $taskWhen->insert();

                        $askTask = new AskTask('insert');
                        $askTask->allow_time = $model->createtime; //任务批准/发布时间
                        $askTask->a_time = $model->createtime; //任务批准/发布时间
                        $askTask->t_status = 2; //已申请
                        $askTask->f_id = $f_id; //申请人id/任务接收人id
                        $askTask->a_id = $model->publish; //发布任务的管理员id
                        $askTask->is_allow = 1; //批准任务
                        $askTask->tw_id = $taskWhen->id;
                        $askTask->t_id = $model->id;
                        $askTask->type = $model->type;
                        $askTask->m_id = $send;
                        $askTask->insert();

                    }
                    $sql = "UPDATE app_member SET manage_id = $f_id WHERE id IN ($str) ";
                    Yii::app()->db->createCommand($sql)->execute();
                    $t->commit();
                    echo 'success';
                } catch (Exception $e) {
                    $t->rollback();
                    echo 'error';
                }

            }
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * @name 降量分析Excel
     */
    public function actionDropdataexcel()
    {
        $param = Yii::app()->request->getQuery('Param');
        if (empty($param)) {
            $firstDate = date('Y-m-d', strtotime('-2 day'));
            $lastDate = date('Y-m-d', strtotime('-1 day'));
            $typeList = Ad::getAdListKeys();
            $param = array(
                'firstDate' => array('first' => $firstDate, 'last' => $firstDate),
                'lastDate' => array('first' => $lastDate, 'last' => $lastDate),
                'type' => $typeList[0]
            );
        }
        $id = Yii::app()->user->manage_id;
        $data = StatsModel::getDropDataList($param,$id);
        $excel = new Excel();
        $excel->download($excel->createExcel(
            Ad::getAdNameById(Ad::TYPE_SGTS) . date('Y-m-d'),
            array('用户名', '开始收入', '结束收入', '差', '百分比'),
            array('username', 'firstSum', 'lastSum', 'difference', 'percent'),
            $data
        ));
    }

    /**
     * ZLB
     * 安装降量分析
     * @return [type] [description]
     */
    public function actionThink(){
        set_time_limit(0);
        $firstDate = date('Y-m-d', strtotime('-2 day'));
        $lastDate = date('Y-m-d', strtotime('-1 day'));
        // print_r($_GET['Param']);exit;
        if(isset($_GET['Param'])){
            $data=$_GET['Param'];
            //用户类型
            $know=array('type'=>$data['type'],'firstDate' => array('first' => strtotime($data['firstDate']['first']), 'last' => strtotime($data['firstDate']['last'])),
                'lastDate' => array('first' => strtotime($data['lastDate']['first']), 'last' => strtotime($data['lastDate']['last'])));
            $model=Member::model();
            $data1=$model->reresearch($know);
            $param = array(
                'dataProvider'=>$data1,
                'firstDate' => array('first' => $data['firstDate']['first'], 'last' => $data['firstDate']['last']),
                'lastDate' => array('first' => $data['lastDate']['first'], 'last' => $data['lastDate']['last']),
                'type' => array('0'=>'rom','8'=>'新门店'),
                'value'=>$data['type']
            );

        }
        else{
            $know=array('type'=>0,'firstDate' => array('first' => strtotime($firstDate), 'last' => strtotime($firstDate)),
                'lastDate' => array('first' => strtotime($lastDate), 'last' => strtotime($lastDate)));
            $model=Member::model();
            $data=$model->reresearch($know);
            // print_r($data);exit;
            $param = array(
                'dataProvider'=>$data,
                'firstDate' => array('first' => $firstDate, 'last' => $firstDate),
                'lastDate' => array('first' => $lastDate, 'last' => $lastDate),
                'type' => array('0'=>'rom','8'=>'新门店'),
                'value'=>0
            );  
        }

        
        $this->render('think',array('param'=>$param));

    }
    /**
     *2017-11-23
     *业务数据查询
     *业务流id=3264
     *zlb
     */
    public function actionSelect(){
        header("Content-type:text/html;charset=utf-8");
        $username=isset($_GET['username']) ? $_GET['username'] : '';
        $begin1=isset($_GET['begin']) ? $_GET['begin'] : '';
        $end1=isset($_GET['end']) ? $_GET['end'] : '';
        $data1=array();
        $alert=array();
        if(!empty($username) && !empty($begin1) && !empty($end1) ){
            $sql="select id from `app_member` where username='{$username}'";
            $result=yii::app()->db->createCommand($sql)->queryAll();
            //数据库中有没有该用户
            if(empty($result)){
                echo "<script>alert('系统不存在该用户');location.href='/dhadmin/stats/select';</script>";exit;
            }
            $begin=strtotime($begin1);
            $end=strtotime($end1);
            //安装总数
            $sql="SELECT count(distinct imeicode) as total,type,id FROM `app_rom_appresource` WHERE uid={$result[0]['id']} and createstamp>={$begin} and createstamp<={$end} group by type  order by createstamp desc";
            $data1=yii::app()->db->createCommand($sql)->queryAll();
            // print_r($data1);exit;
            if(!empty($data1)){
                //无卡总数
                $sql="SELECT count(distinct imeicode) as noka,type,id FROM `app_rom_appresource` WHERE uid={$result[0]['id']} and createstamp>={$begin} and createstamp<={$end} and simcode='' group by type";
                $data2=yii::app()->db->createCommand($sql)->queryAll();
                $type=array();
                foreach ($data2 as $key => $value) {
                    $type[]=$value['type'];
                }
                //有卡总数
                 $sql="SELECT count(distinct imeicode) as yeska,type,id FROM `app_rom_appresource` WHERE uid={$result[0]['id']} and createstamp>={$begin} and createstamp<={$end} and simcode!='' group by type";
                $data3=yii::app()->db->createCommand($sql)->queryAll();
                $type2=array();
                foreach ($data3 as $key => $value) {
                    $type2[]=$value['type'];
                }


                foreach ($data1 as $key => $value) {
                    $data1[$key]['uid']=$result[0]['id'];
                    $data1[$key]['begin']=$begin;
                    $data1[$key]['end']=$end;
                    if(in_array($value['type'],$type)){
                        foreach ($data2 as $k => $v) {
                            if($value['type']==$v['type']){
                                $data1[$key]['noka']=$v['noka'];
                            }
                        }
                    }else{
                        $data1[$key]['noka']=0;
                    }
                    
                }

                foreach ($data1 as $key => $value) {
                    if(in_array($value['type'],$type2)){
                        foreach ($data3 as $k => $v) {
                            if($value['type']==$v['type']){
                                $data1[$key]['yeska']=$v['yeska'];
                            }
                        }
                    }else{
                        $data1[$key]['yeska']=0;
                    }
                    
                }





            }

            //安装手机台数
            $sql="select count(distinct imeicode) as num from `app_rom_appresource` where uid={$result[0]['id']} and createstamp>={$begin} and createstamp<={$end}";
            $arr=yii::app()->db->createCommand($sql)->queryAll();
            //无卡台数
            $sql="select count(distinct imeicode) as num from `app_rom_appresource` where uid={$result[0]['id']} and createstamp>={$begin} and createstamp<={$end} and simcode=''";
            $att=yii::app()->db->createCommand($sql)->queryAll();
            //有卡台数
            $sql="select count(distinct imeicode) as num from `app_rom_appresource` where uid={$result[0]['id']} and createstamp>={$begin} and createstamp<={$end} and simcode!=''";
            $aww=yii::app()->db->createCommand($sql)->queryAll();
            //激活手机台数
            $sql="select count(distinct imeicode) as num from `app_rom_appresource` where uid={$result[0]['id']} and createstamp>={$begin} and createstamp<={$end} and status=0 and finishstatus=1 and finishdate!='0000-00-00'";
            $add=yii::app()->db->createCommand($sql)->queryAll();
            $alert['sum']=!empty($arr) ? $arr[0]['num'] : 0;
            $alert['no']=!empty($att) ? $att[0]['num'] : 0;
            $alert['yes']=!empty($aww) ? $aww[0]['num'] : 0;
            $alert['jihuo']=!empty($add) ? $add[0]['num'] : 0;   
        }
        // print_r($data1);exit;
        $model = new CArrayDataProvider($data1, array(
                            'id' => 'incomeSum',
                            'sort' => array(
                                'attributes' => array(
                                    'id', 'type','total', 'yeska','noka'
                                ),
                                // 'defaultOrder' => 'percent'
                            ),
                            'pagination' => array(
                                'pageSize' => 50,
                            ),
                        ));


        
        $this->render('select',array('data'=>$model,'params'=>array('username'=>$username,'begin'=>$begin1,'end'=>$end1),'alert'=>$alert));
    }
    
}