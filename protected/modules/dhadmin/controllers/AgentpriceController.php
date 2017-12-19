<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2016/12/15
 * Time: 10:25
 * @name 代理商价格
 */
class AgentpriceController extends DhadminController
{
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
                if($_k=='uid'){
                    $member=Member::model()->getByUserName($_v);
                    if($member){
                        $uid=$member->id;
                        $agent=$member->agent;
                    }else{
                        throw new CHttpException(404,"不存在该用户");
                    }

                }
            }
            $modell -> updatetime = date("Y-m-d H:i:s",time());
            $modell->agent=$agent;
            $modell->uid=$uid;
            if ($modell->validate()) {
                if($modell->insert()){
                    //日志记录
                    if (Yii::app()->user->getState('member_manage')!=false){
                        $mid = Yii::app()->user->manage_id;
                    }else {
                        $mid = '';
                    }

                    /*收入操作日志 start 2017-11-15*/
                    $mid=yii::app()->user->getState('uid');
                    $content=' [manage] '.$mid.'[uid] '.$modell->uid.'[单价]'.$modell->price.'[用户类别] 子用户 [添加时间]'.$modell->updatetime.'表[subagent_price]中插入'.json_encode($modell->attributes);
                    $ip=$_SERVER['SERVER_ADDR'];
                    $title='代理商单价管理-添加';
                    $before_content='';
                    Income::addlogincome($mid,$modell->uid,$content,$ip,$before_content,$title);
                    /*收入操作日志 end*/

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

                    /*收入操作日志 start 2017-11-15*/
                    $mid=yii::app()->user->getState('uid');
                    $content=' [manage] '.$mid.'[uid] '.$data->uid.'[单价]'.$data->price.'[用户类别] 子用户 [修改时间]'.$data->updatetime.'表[subagent_price]中数据修改为'.json_encode($data->attributes);
                    $ip=$_SERVER['SERVER_ADDR'];
                    $title='代理商单价管理-修改';
                    $before_content=$update;
                    Income::addlogincome($mid,$data->uid,$content,$ip,$before_content,$title);
                    /*收入操作日志 end*/

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

                /*收入操作日志 start 2017-11-15*/
                    $mid=yii::app()->user->getState('uid');
                    $content=' [manage] '.$mid.'[uid] '.$data->uid.'[单价]'.$data->price.'[用户类别] 子用户 [删除时间]'.date('Y-m-d H:i:s').'表[subagent_price]中数据删除'.$update;
                    $ip=$_SERVER['SERVER_ADDR'];
                    $title='代理商单价管理-删除';
                    $before_content='';
                    Income::addlogincome($mid,$data->uid,$content,$ip,$before_content,$title);
                /*收入操作日志 end*/

                NoteLog::addLog($detail='',$mid,$this->uid,$tag='删除子用户价格',$update);
                Yii::app()->user->setFlash("credits_status","价格删除成功");
                $this->redirect(array("subprice"));
            }
        }

    }
}