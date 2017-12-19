<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2016/12/15
 * Time: 10:25
 * @name 盒子信息
 */
class SoftboxController extends DhadminController
{
    /**
     * @name 首页
     */
    public function actionIndex()
    {
        $model = new Softbox();
//        $model->unsetAttributes(); // clear any default values
        // 只显示box_number!=MDAZRJ的
        $criteria = new CDbCriteria;
        $criteria->addCondition("box_number!='MDAZRJ' and mid!= 0");
        $model->dbCriteria = $criteria;
        if (isset($_GET['Softbox'])) {
            $model->attributes = $_GET['Softbox'];
        }
        $this->render("index",array('model'=>$model));
    }
    /**
     * @para 添加盒子信息
     */
    public function actionBoxAdd()
    {
        $model = new Softbox();
        $member= Member::model()->findAll('status=:status',array(':status'=>1));
        if(isset($_POST['Softbox'])){

            $trans = Yii::app()->db->beginTransaction();
            try {

                foreach($_POST['Softbox'] as $_k => $_v){
                    $model -> $_k = $_v;
                }
                $model -> createtime = time();
                $model -> mid = Yii::app()->user->manage_id;

                //判断该用户是否有统计id
                $uid=$_POST['Softbox']['uid'];
                $userapp = RomSoftpak::model()->find('uid=:uid and closed=:closed',array(':uid'=>$uid,':closed'=>0));
                //分配对应appid
                if(empty($userapp)){
                    //判断用户类型
                    $member=Member::model()->findByPk($uid);
                    $agent=$member->type;
                    $types=Common::getUserType($agent);
                    $allot=0;
                    //获得该类型最新一条软件序列号
                    $softpak=RomSoftpak::model()->getListGroupByType($types,$allot);
                    if(empty($softpak))
                    {
                        throw new CHttpException(500, '无可用软件供分配，请联系客服');
                    }else{
                        foreach ($softpak as $spak){
                            if ($types == $spak->type){
                                //分配统计id
                                $this->addTid($uid,$spak->serial_number);
                                $spak->status = 0;
                                $spak->uid = $uid;
                                $spak->save();
                                break;
                            }
                        }
                    }
                    //如果有统计id 则已分配不需要再分配了
                }else{
                    //$this->addTid($uid,$userapp->serial_number);
                }
                if($model -> save()){
                    $trans->commit();
                    Yii::app()->user->setFlash('status','添加盒子成功');
                    $this->redirect(array("index"));
                }
            } catch (Exception $e) {
                $trans->rollback();
                throw new CHttpException(500,$e->getMessage());
            }

        }
        $this->render("box_add",array("model"=>$model,'member'=>$member));
    }
    private function addTid($uid,$tid){
        $boxdesk=Boxdesk::model()->find('uid=:uid and status=:status',array(':uid'=>$uid,':status'=>0));

        if(empty($boxdesk)){
            $box_desk=Boxdesk::model()->findAll('tid=:tid and status=:status',array(':tid'=>$tid,':status'=>1));
            if(empty($box_desk))
            {
                throw new CHttpException(500, '无可用桌面供分配，请联系客服');
            }else{
                foreach ($box_desk as $v){
                    $v->status = 0;
                    $v->uid = $uid;
                    $v->save();
                    break;
                }
            }
            //如果有统计id 则已分配不需要再分配了
        }
    }
    /**
     * @para 编辑盒子信息
     */
    public function actionBoxUpdate($id)
    {
        $model = Softbox::model();
        $data = $model -> findByPk($id);
        $member= Member::model()->findAll('status=:status',array(':status'=>1));
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            if(isset($_POST['Softbox'])){
                foreach($_POST['Softbox'] as $_k => $_v){
                    $data -> $_k = $_v;
                }
                $data -> updatetime = time();
                $data -> mid =  Yii::app()->user->manage_id;
                if($data -> save()){
                    $this->redirect(array("index"));
                }
            }
            $this->render("box_update",array("model"=>$data,'member'=>$member));
        }
    }

}