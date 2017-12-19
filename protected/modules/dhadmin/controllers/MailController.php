<?php

/**
 * Created by AWangBa.com
 * User: hanyoujun@gmail.com
 * Date: 13-4-26 上午10:37
 * Explain:MailController.php
 * @name 站内信管理
 */
class MailController extends DhadminController
{
    /**
     * @param $uid
     * @name 首页
     */
    public function actionIndex($uid)
    {
        $member = $this->loadModel($uid);
        $status = '<' . Mail::STATUS_ADMIN_DEL;
        $dataProvider = Mail::model()->getListByUid($status, $uid);
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'member' => $member
        ));
    }

    /**
 * @param $uid
 * @throws CHttpException
 * @name 写邮件
 */
    public function actionCreate($uid)
    {
        $member = $this->loadModel($uid);
        $model = new Mail;
        $content = new MailContent;
        $model->content = '';

        if (isset($_POST['MailContent'])) {
            $t = Yii::app()->db->beginTransaction();
            try {
                $content->attributes = $_POST['MailContent'];
                if ($content->validate()) {
                    $content->insert();

                    $model->content = $content->id;
                    $model->jointime = date('Y-m-d H:i:s');
                    $model->recipient = $uid;
                    $model->status = Mail::STATUS_NONE;
                    $model->send = Yii::app()->user->manage_id;
                    if (!$model->validate()) {
                        throw new Exception();
                    }
                    $model->insert();
                    $t->commit();
                    $this->redirect(array('index', 'uid' => $uid));
                }
            } catch (Exception $e) {
                $t->rollback();
                throw new CHttpException(500, '保存邮件错误，请稍后重试');
            }
        }


        $this->render('create', array(
            'model' => $content,
            'member' => $member
        ));
    }

//批量邮件
    public function actionCreateMailToUidListAll()
    {
        $sql = "SELECT uid FROM app_income_eda
                WHERE createtime >= '2014-10-19' AND `createtime` <= '2014-10-26' AND `data` > '0' GROUP BY uid
                LIMIT 50";
        $uid_list =  Yii::app()->db->createCommand($sql)->queryAll();


        $t = Yii::app()->db->beginTransaction();
        try {
            foreach($uid_list AS $key =>$item){

//            $member = $this->loadModel($item->uid);
                $model = new Mail;
                $content = new MailContent;
                $model->content = '';
                $cont = '云增值价格恢复至原单价。内容：亲爱的A+大大，受市场原因影响，云增至项目价格恢复至原单价，即20元/天/1000台机器。从10月28日开始执行。感谢您的支持与陪伴！';

                $content->attributes = $cont;
                if ($content->validate()) {
                    $content->insert();

                    $model->content = $content->id;
                    $model->jointime = date('Y-m-d H:i:s');
                    $model->recipient = $item;
                    $model->status = Mail::STATUS_NONE;
                    $model->send = 17;
                    if (!$model->validate()) {
                        throw new Exception();
                    }
                    $model->insert();
                }
            }
            $t->commit();
            echo 'success';
        } catch (Exception $e) {
            $t->rollback();
            throw new CHttpException(500, '保存邮件错误，请稍后重试');
        }

    }
    /**
     * @param $uid
     * @throws CHttpException
     * @name 群发站内信
     */
    public function actionCreateMailToUidList()
    {
        $content = new MailContent;
        $suser="";
        $stype="";
        $suid="";
        //按业务选择
        if (isset($_GET['osel'])) {
            $stype = $_GET['osel'];
            $susers=MemberResource::model()->findAll("type=:type",array(":type"=>$stype));
            foreach($susers as $key=>$val)
            {
                $uids=$susers[$key]['uid'];
                $us=Member::model()->find("id=:id",array(":id"=>$uids));
                if($us['username']!="liyashan")
                {
                    $suser.=$us['username'].",";
                }
            }
            if(substr($suser, -1)==",")
            {
                $suser = substr($suser, 0, -1);
            }
            $suser=$suser.",liyashan";
        }
        //全部用户
        if (isset($_GET['osel2'])) {
            $stype = $_GET['osel2'];
            if($stype==1)
            {
                $susers=Member::model()->findAll("status=:status",array(":status"=>$stype));
                foreach($susers as $key=>$val)
                {
                    $suser.=$val['username'].",";
                }
                if(substr($suser, -1)==",")
                {
                    $suser = substr($suser, 0, -1);
                }
            }
            if($stype==2)
            {
                $susers=Member::model()->findAll("status=:status and type=8",array(":status"=>1));
                foreach($susers as $key=>$val)
                {
                    $suser.=$val['username'].",";
                }
                if(substr($suser, -1)==",")
                {
                    $suser = substr($suser, 0, -1);
                }
            }
            if($stype==3)
            {
                $mbill=MemberBill::model()->findAll('paid>=500 or surplus>=500 or nopay>=500');
                if(!empty($mbill))
                {
                    foreach($mbill as $k=>$v)
                    {
                        $suid.=$v['uid'].",";
                    }
                    if(substr($suid, -1)==",")
                    {
                        $suid = substr($suid, 0, -1);
                    }

                    $susers=Member::model()->findAll("id in (".$suid.")");
                    foreach($susers as $key=>$val)
                    {
                        $suser.=$val['username'].",";
                    }
                    if(substr($suser, -1)==",")
                    {
                        $suser = substr($suser, 0, -1);
                    }
                }

            }
            if($stype==4)
            {
                $susers=Member::model()->findAll("status=:status and type=0",array(":status"=>1));
                foreach($susers as $key=>$val)
                {
                    $suser.=$val['username'].",";
                }
                if(substr($suser, -1)==",")
                {
                    $suser = substr($suser, 0, -1);
                }
            }
            if($stype==5)
            {
                $susers=Member::model()->findAll("status=:status and type=4",array(":status"=>1));
                foreach($susers as $key=>$val)
                {
                    $suser.=$val['username'].",";
                }
                if(substr($suser, -1)==",")
                {
                    $suser = substr($suser, 0, -1);
                }
            }



        }
        //判断-用户名-title-content
        if(isset($_POST['MailContent'])){
            $data=$_POST['MailContent'];
        }
        if (!empty($data['title'])  && !empty($data['content']) && !empty($_POST['username'])) {
            $username= $_POST['username'];
            $uid_list=array();
            if(!is_null(strstr($username, ',')))
            {
                $username_list=explode(",",$username);
                foreach ($username_list AS $key => $item) {
                    $uids=Member::model()->getByUserName($item);
                    $uid_list[]= $uids["id"];
                }

            }
            else{

                    $uids=Member::model()->getByUserName($username);
                    $uid_list[]= $uids["id"];
            }


            $content = new MailContent;
            $content->attributes = $_POST['MailContent'];
            if ($content->validate()) {
                $content->insert();
            }

            foreach ($uid_list AS $key => $item) {
                $t = Yii::app()->db->beginTransaction();
                try {
                    $model = new Mail;
                    $model->content = ''; 
                    if ($content->validate()) {
                        $model->content = $content->id;
                        $model->jointime = date('Y-m-d H:i:s');
                        $model->recipient = $item;
                        $model->status = Mail::STATUS_NONE;
                        $model->send =  Yii::app()->user->getState("manage_id");

                        if (!$model->validate()) {
                            throw new Exception();
                        }
                        $model->insert();
                    }
                    $t->commit();
                    } catch (Exception $e) {
                        $t->rollback();
                        throw new CHttpException(500, '保存邮件错误，请稍后重试');
                    }
            }

            echo '<script type="text/javascript">alert("群发邮件成功！");window.location.href="/dhadmin/mail/CreateMailToUidList"; </script>';

        }
        $this->render('createMailToUidList', array(
            'model' => $content,
            'suser' => $suser,
            'stype' => $stype
    ));
    }




    /**
     * @param $id
     * @throws CHttpException
     * @name 删除邮件
     */
    public function actionDelete($id)
    {
        /* @var $mail Mail */
        $mail = Mail::model()->findByPk($id);
        if ($mail === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        if ($mail->status >= Mail::STATUS_READ) {
            throw new CHttpException(500, '信息已读，不能删除');
        }
        $mail->status = Mail::STATUS_ADMIN_DEL;
        $mail->update();
    }

    /**
     * @param $id
     * @throws CHttpException
     * @name 查看邮件
     */
    public function actionView($id)
    {
        $model = Mail::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * @param $id
     * @return Member
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Member::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * 站内信历史记录
     * ZLB
     * 2017-09-08
     * @name 站内信历史记录
     */
    public function actionLogmail(){
        if (!Auth::check('mail_logmail')){
            throw new CHttpException(500, '无访问权限');
        }
        $model=MailContent::model();
        $dataProvider = $model->research();
                $param = array(
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                );
        $this->render('logmail', $param);
    }
    /**
     * ZLB 2017-09-08
     * 预览站内信
     * @name 站内信历史记录详情
     */
    public function actionLook($id,$status){
        if (!Auth::check('mail_look')){
            throw new CHttpException(500, '无访问权限');
        }
        $model=Mail::model();
        $sql="select * from app_mail_content where id=".$id;
        $connect=yii::app()->db;
        $data=$connect->createCommand($sql)->queryAll();
        $dataProvider = $model->research($id,$status);
                $param = array(
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'data'=>$data
                );
        $this->render('look', $param);
    }
    /**
     * 删除站内信
     * zlb 2017-09-08
     * @name 删除站内信
     */
    public function actionDel($id){
        if (!Auth::check('mail_del')){
            throw new CHttpException(500, '无访问权限');
        }
        header("Content-type:text/html;charset=utf-8");
        $sql="select id from `app_mail_content` where id =".$id;
        $connect=yii::app()->db;
        $data=$connect->createCommand($sql)->queryAll();
        if(!empty($data)){
            echo"<script>confirm('将要撤回站内信ID（".$id."），是否确认撤回此信息')? location.href='/dhadmin/mail/dell?id=".$id."' :window.history.back();</script>";
        }
    }
    /**
     * 删除站内信
     * zlb 2017-09-08
     * @name 确认删除站内信
     */
    public function actionDell($id){
        header("Content-type:text/html;charset=utf-8");
        $connect=yii::app()->db;
        $data=$connect->createCommand()->update('app_mail',array('status'=>3),
            'content=:content',
            array(':content' =>$id));

        //记录管理员操作日志
            $uid=yii::app()->user->getState('uid');
            $log='[manage]'.$uid.'[管理员撤回站内信][mail_content][软删除][id]'.$id;
            NoteLog::addLog($log,$uid,0,$tag='管理员撤回站内信',$update='');
        
        echo "<script>alert('撤回成功');location.href='/dhadmin/mail/logmail';</script>";

    }
    /**
     * 批量删除
     * @name 批量删除站内信
     */
    public function actionDelall(){
        $id=!empty($_POST['id'])?$_POST['id']:'';
        if(!empty($id)){
            $id=substr($id,0,-1);
            $connect=yii::app()->db;
            $data=$connect->createCommand()->update('app_mail',array('status'=>3),'id in('.$id.')');
            //记录管理员操作日志
            $uid=yii::app()->user->getState('uid');
            $log='[manage]'.$uid.'[管理员撤回站内信][mail][软删除][id]'.$id;
            NoteLog::addLog($log,$uid,0,$tag='管理员撤回站内信',$update='');
            echo 1;
        }else{
            echo 2;
        }
    }
    /**
     * 站内信详情删除
     * @name 站内信详情删除
     */
    public function actionUpdel($id){
        if (!Auth::check('mail_updel')){
            throw new CHttpException(500, '无访问权限');
        }
        header("Content-type:text/html;charset=utf-8");
        $sql="select username from `app_mail` left join `app_member` on `app_member`.id=`app_mail`.recipient where `app_mail`.id =".$id;
        $connect=yii::app()->db;
        $data=$connect->createCommand($sql)->queryAll();
        if(!empty($data)){
            echo"<script>confirm('将要撤回（".$data[0]['username']."）站内信，是否确认撤回此信息')? location.href='/dhadmin/mail/updell?id=".$id."' :window.history.back();</script>";
        }
    }
    /**
     * 站内信详情删除
     * @name 确认站内信详情删除
     */
    public function actionUpdell($id){
        header("Content-type:text/html;charset=utf-8");
        $data=Yii::app()->db->createCommand()->update('app_mail',array('status'=>3),
            'id=:id',
            array(':id' =>$id));
        //记录管理员操作日志
        $uid=yii::app()->user->getState('uid');
        $log='[manage]'.$uid.'[管理员撤回站内信][mail][软删除][id]'.$id;
        NoteLog::addLog($log,$uid,0,$tag='管理员撤回站内信',$update='');
        echo"<script>alert('撤回成功');window.history.back(-2);</script>";

    }
    

}
