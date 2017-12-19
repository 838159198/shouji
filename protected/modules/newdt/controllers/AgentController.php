<?php

/**
 * Created by AWangBa.com
 * User: hanyoujun@gmail.com
 * Date: 13-8-2 上午11:06
 * Explain: 推广用户及提成管理
 */
class AgentController extends NewdtController
{
    public $incomeList = array();

    /*
    * name: 子用户管理
    * */
    public function actionIndex($startdate='',$enddate='',$type=''){
        if(empty($startdate)){
            $startdate=date("Y-m-01");
        }
        $startdate=strtotime($startdate);
        $data=array();
        if(empty($type)){
            $arr=self::findMemberData($page=15);
            if(isset($arr)){
                $members= $arr['members'];
                $pager= $arr['pages'];
                $count= $arr['count'];
                $data=self::findByPage($members,$startdate,$enddate);
            }

            $arr=self::findMemberData($page=100000);
            if(isset($arr)){
                $members= $arr['members'];
                $dataa=self::findByPage($members,$startdate,$enddate);
            }

        }else{
            $count =1;
            $pager = new CPagination($count);
            $pager->pageSize=15;
            $member=Member::model()->findByPk($type);
            if(empty($enddate)){

                $sql ="select uid,createstamp,count(distinct(imeicode)) as counts from app_rom_appresource where uid={$type} and createstamp >= {$startdate}  order by counts desc";
            }else{
                $end=strtotime($enddate);
                $sql ="select uid,createstamp,count(distinct(imeicode)) as counts from app_rom_appresource where uid={$type} and createstamp >= {$startdate} and createstamp <= {$end} order by counts desc";
            }
            $curmodel = Yii::app()->db->createCommand($sql)->queryAll();
            if(isset($curmodel)){
                $data[]=array("username"=>$member->username,"count"=>$curmodel[0]['counts'],"uid"=>$type,"subagent"=>$member->sign);
            }
            $dataa=$data;
        }
        $countArr=array();
        $count1=0; $count2=0; $count3=0;
        //var_dump($data);exit;
        foreach($dataa as $v){
            $arr=RomSubagentdata::getActivationNum( $v['uid'],$startdate,strtotime($enddate),$v['subagent']);
            $count1 +=$v['count'];
            $count2 +=$arr[0]['activation'];
            $count3 +=$arr[0]['income'];
        }
        $countArr[0]=$count1;
        $countArr[1]=$count2;
        $countArr[2]=$count3;
        /*代理商实际收入2017-11-28*/
        $arrr=self::findMemberData($page=15);
        $earning=array();
        if(isset($arrr)){
                $members= $arrr['members'];
                $pager= $arrr['pages'];
                $count= $arrr['count'];
                $earning=self::findByPage($members,$startdate,$enddate);
        }

        $this->render("index",array('data'=>$data,'pages'=>$pager,'count'=>$count,"startdate"=>$startdate,"enddate"=>$enddate,"type"=>$type,"countArr"=>$countArr,'earn'=>$earning));
    }
    //代理商分页
    private function findMemberData($page='')
    {
        $uid = $this->uid;
        $model_member=Member::model()->findByPk($uid);
        $member = new Member();
        $criteria = new CDbCriteria();
        $criteria->addCondition("subagent=:subagent and agent=:agent");
        $criteria->params[':subagent']=$uid;
        $criteria->params[':agent']=$model_member->agent;
        //$criteria -> order = "periods DESC";
        $count = $member->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=$page;
        $pager->applyLimit($criteria);
        $members = $member -> findAll($criteria);
        $arr[]=array();
        $arr['members']=$members;
        $arr['pages']=$pager;
        $arr['count']=$count;
        return $arr;
    }
    private function findByPage($members,$startdate,$enddate){
        $data=array();
        foreach($members as $member){
            $id=$member->id;
            $sign=$member->sign;
            if($sign==2){
                //2级代理
                $members = Member::model()->findAll(array(
                    'select'=>array('id'),
                    'condition' => 'status=:status AND agent=:agent AND subagent=:subagent',
                    'params' => array(':status'=>1,':agent' => $member->agent,':subagent'=>$id)
                ));
                $uids='';
                if($members){
                    foreach($members as $v){
                        $uids.=$v->id.',';
                    }
                    $uids=substr($uids,0,-1);
                }
                $uids=empty($uids)?"('')":'('.$uids.')';
                if(empty($enddate)){
                    $sql ="select uid,count(distinct(imeicode)) as counts from app_rom_appresource where uid in {$uids} and createstamp >= {$startdate}  order by counts desc";
                }else{
                    $end=strtotime($enddate);
                    $sql ="select createstamp,count(distinct(imeicode)) as counts from app_rom_appresource where uid IN {$uids} and createstamp >= {$startdate} and createstamp <= {$end} order by counts desc";
                }
                $curmodel = Yii::app()->db->createCommand($sql)->queryAll();
                if(isset($curmodel)){
                    $data[]=array("username"=>$member->username,"count"=>$curmodel[0]['counts'],"uid"=>$id,"subagent"=>$sign);
                }
            }else{
                if(empty($enddate)){
                    $sql ="select count(distinct(imeicode)) as counts from app_rom_appresource where uid={$id} and createstamp >= {$startdate}  order by counts desc";
                }else{
                    $end=strtotime($enddate);
                    $sql ="select uid,createstamp,count(distinct(imeicode)) as counts from app_rom_appresource where uid={$id} and createstamp >= {$startdate} and createstamp <= {$end} order by counts desc";
                }
                $curmodel = Yii::app()->db->createCommand($sql)->queryAll();
                if(isset($curmodel)){
                    $data[]=array("username"=>$member->username,"count"=>$curmodel[0]['counts'],"uid"=>$id,"subagent"=>$sign);
                }
            }

        }
        return $data;
    }





    /**
     * @param $id
     * @return MemberInfo|null
     * @throws CHttpException
     */
    private function loadModel($id)
    {
        $model = Member::model()->getById($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
	/*
     * 子用户详细数据
     * */
    public function actionSubuser($uid,$date="")
    {
        $member=Member::model()->findByPk($uid);
        if(empty($date)){
            $date=date("Y-m");
        }
        $first=date('Y-m-01', strtotime($date));
        $firstday = strtotime(date('Y-m-01', strtotime($date)));
        $lastday = strtotime(date('Y-m-d', strtotime("$first +1 month -1 day")));

        $nowTime = date('Y-m', time());
        if ($date == $nowTime) {
            $lastDay = date('j', time()) - 1; //月份中的第几天，没有前导零
        } else {
            $lastDay = date('t', strtotime($date)); //给定月份所应有的天数
        }

        $sql ="select uid,createtime,createstamp,count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and createstamp BETWEEN {$firstday} AND {$lastday} GROUP BY createstamp order by counts desc";
        $curmodel = Yii::app()->db->createCommand($sql)->queryAll();
        $data=array();
        foreach($curmodel as $k=>$v){
            $data[$v['createstamp']]=$v;
        }

        // 获取当月所有日期
        $nowDays = array();
        for($i=1;$i<=$lastDay;$i++){
            $_date = $date . '-' . sprintf('%02d', $i);
            $nowDays[strtotime($_date)] = $_date;
        }
        //var_dump($data);exit;
        $this->render("subuser",array("data"=>$data,"date"=>$date,"nowDays"=>$nowDays));
    }

    /*
     * 2级代理商子用户详细数据
     * */
    public function actionSubuser2($uid='',$date="",$sub='')
    {
        $id='';
        if(!empty($sub)){
            $member=Member::model()->findByPk($sub);
            $model_data=Member::model()->find("subagent=:subagent and agent=:agent",array(":subagent"=>$sub,":agent"=>$member->agent));
            if($model_data){
                $id=$model_data->id;
            }
        }
        $uid=empty($uid)?$id:$uid;
        $member=Member::model()->findByPk($uid);
        $nowDays = array();
        $data=array();
        if(empty($date)){
            $date=date("Y-m");
        }
        if($member){
            $first=date('Y-m-01', strtotime($date));
            $firstday = strtotime(date('Y-m-01', strtotime($date)));
            $lastday = strtotime(date('Y-m-d', strtotime("$first +1 month -1 day")));

            $nowTime = date('Y-m', time());
            if ($date == $nowTime) {
                $lastDay = date('j', time()) - 1; //月份中的第几天，没有前导零
            } else {
                $lastDay = date('t', strtotime($date)); //给定月份所应有的天数
            }

            $sql ="select uid,createtime,createstamp,count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and createstamp BETWEEN {$firstday} AND {$lastday} GROUP BY createstamp order by counts desc";
            $curmodel = Yii::app()->db->createCommand($sql)->queryAll();
            $data=array();
            foreach($curmodel as $k=>$v){
                $data[$v['createstamp']]=$v;
            }

            // 获取当月所有日期
            for($i=1;$i<=$lastDay;$i++){
                $_date = $date . '-' . sprintf('%02d', $i);
                $nowDays[strtotime($_date)] = $_date;
            }
        }

        //var_dump($data);exit;
        $this->render("subuser2",array("data"=>$data,"date"=>$date,"nowDays"=>$nowDays,"type"=>$uid));
    }

    /*
     * 子用户详细数据
     * */
    public function actionDetail($date="")
    {
        $uid=$this->uid;
        $member=Member::model()->findByPk($uid);
        if(empty($date)){
            $date=date("Y-m");
        }
        $first=date('Y-m-01', strtotime($date));
        $firstday = strtotime(date('Y-m-01', strtotime($date)));
        $lastday = strtotime(date('Y-m-d', strtotime("$first +1 month -1 day")));

        $nowTime = date('Y-m', time());
        if ($date == $nowTime) {
            $lastDay = date('j', time()) - 1; //月份中的第几天，没有前导零
        } else {
            $lastDay = date('t', strtotime($date)); //给定月份所应有的天数
        }

        $sql ="select uid,createtime,createstamp,count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and createstamp BETWEEN {$firstday} AND {$lastday} GROUP BY createstamp order by counts desc";
        $curmodel = Yii::app()->db->createCommand($sql)->queryAll();
        $data=array();
        foreach($curmodel as $k=>$v){
            $data[$v['createstamp']]=$v;
        }

        // 获取当月所有日期
        $nowDays = array();
        for($i=1;$i<=$lastDay;$i++){
            $_date = $date . '-' . sprintf('%02d', $i);
            $nowDays[strtotime($_date)] = $_date;
        }
        //var_dump($data);exit;
        $this->render("detail",array("data"=>$data,"date"=>$date,"nowDays"=>$nowDays));
    }


    /*
   * 子用户价格设置列表
   * */
    public function actionSubprice(){
        $modell = new SubagentPrice('search');
        $modell->unsetAttributes();
        if (isset($_GET['SubagentPrice'])) {
            $modell->attributes = $_GET['SubagentPrice'];
        }
        if(isset($_POST['SubagentPrice'])){

            foreach($_POST['SubagentPrice'] as $_k => $_v){
                $modell->$_k = $_v;
            }
            $modell -> updatetime = date("Y-m-d H:i:s",time());
            $modell->agent=$this->uid;
            if ($modell->validate()) {
                if($modell->insert()){
                    //日志记录
                    if (Yii::app()->user->getState('member_manage')!=false){
                        $mid = Yii::app()->user->manage_id;
                    }else {
                        $mid = '';
                    }
                    $detail=json_encode($modell->attributes);
                    NoteLog::addLog($detail,$mid,$this->uid,$tag='添加子用户价格',$update='');
                    Yii::app()->user->setFlash("credits_status","子用户价格添加成功");
                    $this->redirect("subprice");
                }
            }
            $modell->unsetAttributes();

        }
        $this->render("subprice",array('model'=>$modell));

    }


    /*
     * 子用户价格修改
     */
    public function actionUpdate($id){
        $uid=$this->uid;
        $model = SubagentPrice::model();
        $data = $model -> findByPk($id);
        $members=Member::model()->findAll("subagent=:subagent and agent=:agent",array(":subagent"=>$uid,":agent"=>$uid)) ;
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            $update=json_encode($data->attributes);
            if(isset($_POST['SubagentPrice'])){
                foreach($_POST['SubagentPrice'] as $_k => $_v){
                    $data -> $_k = $_v;
                }
                $data -> updatetime = date('Y-m-d H:i:s',time());
                if($data -> update()){
                    //日志记录
                    if (Yii::app()->user->getState('member_manage')!=false){
                        $mid = Yii::app()->user->manage_id;
                    }else {
                        $mid = '';
                    }
                    $detail=json_encode($data->attributes);
                    NoteLog::addLog($detail,$mid,$uid,$tag='子用户价格修改',$update);
                    Yii::app()->user->setFlash("credits_status","价格修改成功");
                    $this->redirect(array("subprice"));
                }
            }
            $this->render("update",array('data'=>$data,"members"=>$members));
        }

    }

    /*
     * 子用户价格删除
     */
    public function actionDelete($id){

        $model = SubagentPrice::model();
        $data = $model -> findByPk($id);

        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            $update=json_encode($data->attributes);
            if($data -> delete()){
                //日志记录
                if (Yii::app()->user->getState('member_manage')!=false){
                    $mid = Yii::app()->user->manage_id;
                }else {
                    $mid = '';
                }
                NoteLog::addLog($detail='',$mid,$this->uid,$tag='删除子用户价格',$update);
                Yii::app()->user->setFlash("credits_status","价格删除成功");
                $this->redirect(array("subprice"));
            }
        }

    }



}