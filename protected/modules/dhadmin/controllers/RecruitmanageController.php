<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2016/10/13
 * Time: 10:43
 * 招聘模块
 */
class RecruitmanageController extends DhadminController
{

    /*****************************************************************分类管理***********************************************************************/
    /**
     * 分类
     */
    public function actionCategory()
    {
        $cate = RecruitClass::model()->findAll();
        $this->render('category',array('data'=>$cate));
    }


    /**
     * 编辑
     */
    public function actionEdit()
    {
        $this->render('edit');
    }

    /**
     * 删除
     */
    public function actionDel()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $classid = Yii::app()->request->getParam('classid');
            RecruitClass::model()->deleteAll('classid=:id',array(':id'=>$classid));
            RecruitJob::model()->deleteAll('jobid=:jobid',array(':jobid'=>$classid));
            RecruitResume::model()->deleteAll('link_id=:link_id',array(':link_id'=>$classid));
        }
    }

    /**
     * 编辑页面
     */
    public function actionEditajax()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $classid = Yii::app()->request->getParam('classid');
            $dataclass = RecruitClass::model()->findAll('classid=:classid',array(':classid'=>$classid));
            $datajob = RecruitJob::model()->findAll('jobid=:jobid',array(':jobid'=>$classid));
            echo CJSON::encode(array('val' => $dataclass,'valjob'=>$datajob));
        }
    }
    
    /**
     * 确认提交
     */
    public function actionSubmit(){
        if (Yii::app()->request->isAjaxRequest) {
            $catearr = Yii::app()->request->getParam('catearr');
            $jobarr = Yii::app()->request->getParam('jobarr');
            $timeid = Yii::app()->request->getParam('submittime');
            $currentTime = strtotime(date('Y-m-d H:i:s'));// 当前时间
            /**
             * 已有模板
             */
            if ($catearr['id'] != -1) {
                $cate = RecruitClass::model()->findAll('id=:id', array(":id" => $catearr['id']));
                if ($catearr['name'] == $cate[0]['classname']) {
                } else {
                    $alldata = RecruitClass::model()->findAll();
                    $n = 0;
                    foreach ($alldata as $vt) {
                        if ($catearr['name'] == $vt['classname']) {
                            $n = 1;
                            echo CJSON::encode(array('val' => 'error'));
                        }
                    }
                    if ($n == 0) {
                        RecruitClass::model()->updateAll(array('classname' => $catearr['name']), 'id=:id', array(':id' => $catearr['id']));
                    }
                }
                RecruitJob::model()->deleteAll('jobid=:jobid', array(':jobid' => $timeid));
                foreach ($jobarr as $vt) {
                    $admin = new RecruitJob();
                    if ($vt['id'] != -1) {
                        $admin->id = $vt['id'];
                    }
                    $admin->jobname = $vt['name'];
                    $admin->jobid = $timeid;
                    $admin->save();
                }
            }else{
                /**
                 * 添加空模板
                 */
                $alldata = RecruitClass::model()->findAll();
                $n = 0;
                foreach ($alldata as $vt) {
                    if ($catearr['name'] == $vt['classname']) {
                        $n = 1;
                        echo CJSON::encode(array('val' => 'error'));
                    }
                }
                if ($n == 0) {
                    $classadmin = new RecruitClass();
                    $classadmin->classname = $catearr['name'];
                    $classadmin->classid = $currentTime;
                    $classadmin->createtime = $currentTime;
                    $classadmin->save();

                    foreach ($jobarr as $vt) {
                        $admin = new RecruitJob();
                        $admin->jobname = $vt['name'];
                        $admin->jobid = $currentTime;
                        $admin->save();
                    }
                }
            }
        }
    }


    /*****************************************************************简历管理***********************************************************************/

    /**
     * 简历
     */
    public function actionResume()
    {
        // 查询每个名称的简历的数量
        $sql = "select id,jobname,count(jobname) as counts from app_recruit_resume group by jobname order by counts";
//        $sql = "select jobname from app_recruit_resume group by jobname  having(count(jobname)>0)";
        $cate = Yii::app()->db->createCommand($sql)->queryAll();
        $arr = array();
        foreach ($cate as $vt){
            $data = RecruitResume::model()->findAll('jobname=:jobname',array(':jobname'=>$vt['jobname']));
            foreach ($data as $vo){
                    $arr[] = $vo;
                }
        }
        $this->render('resume',array('data'=>$arr));
    }


    /**
     * 简历管理职位数据
     */
    public function actionResumeAjax(){
        if (Yii::app()->request->isAjaxRequest) {
            $classid = Yii::app()->request->getParam('classid');
            $datajob = RecruitJob::model()->findAll('jobid=:jobid',array(':jobid'=>$classid));
            echo CJSON::encode(array('val' => $datajob));
        }
    }

    /**
     * 是否前台显示
     */

    public function actionShow(){
        if (Yii::app()->request->isAjaxRequest) {
            $id = Yii::app()->request->getParam('id');
            $show = Yii::app()->request->getParam('show');
            $data = RecruitResume::model()->findAll('id=:id',array(':id'=>$id));

            // 修改RecruitJob中数据前台显示
            RecruitJob::model()->updateAll(array('is_show'=>$show),'jobname=:jobname',array(':jobname'=>$data[0]['jobname']));

            // 修改RecruitJob中数据前台显示
//            RecruitClass::model()->updateAll(array('is_show'=>$show),'classname=:classname',array(':classname'=>$data[0]['classname']));
            $showdata = RecruitJob::model()->findAllByAttributes(array('jobid'=>$data[0]['link_id'],'is_show'=>1));
            if ($showdata){
                RecruitClass::model()->updateAll(array('is_show'=>1),'classname=:classname',array(':classname'=>$data[0]['classname']));
            }else{
                RecruitClass::model()->updateAll(array('is_show'=>0),'classname=:classname',array(':classname'=>$data[0]['classname']));
            }



            // 修改RecruitResume中数据
            $dataAll = RecruitResume::model()->findAll('jobname=:jobname',array(':jobname'=>$data[0]['jobname']));
            foreach ($dataAll as $vt){
                RecruitResume::model()->updateAll(array('is_show'=>0),'id = :id',array(':id'=>$vt['id']));
            }
            RecruitResume::model()->updateAll(array('is_show'=>$show), 'id=:id',array(':id'=>$id));
            $dataAll1 =  RecruitResume::model()->findAll('jobname=:jobname',array(':jobname'=>$data[0]['jobname']));
            echo CJSON::encode(array('val' => $dataAll1));
        }

    }

    /**
     * 接收模板ajax传递的数据,并存入数据库
     */
    public function actionResumeSubmit(){
        if (Yii::app()->request->isAjaxRequest) {
            $arr = Yii::app()->request->getParam('arr');
            $id = Yii::app()->request->getParam('id');
            $admin = new RecruitResume();
            //修改编辑后的前台显示
            if ($id) {
                $dataEdit = RecruitResume::model()->findAll('id=:id', array(':id' => $id));
                $dataAll = RecruitResume::model()->findAllByAttributes(array('link_id' => $dataEdit[0]['link_id'], 'is_show' => 1));
                if (count($dataAll) == 1) {
                    if ($dataEdit[0]['is_show'] == 1) {
                        RecruitJob::model()->updateAll(array('is_show' => 0), 'jobname=:jobname', array(':jobname' => $dataEdit[0]['jobname']));
                        RecruitClass::model()->updateAll(array('is_show' => 0), 'classname=:classname', array(':classname' => $dataEdit[0]['classname']));
                    }
                } elseif (count($dataAll) > 1) {
                    if ($dataEdit[0]['is_show'] == 1) {
                        RecruitJob::model()->updateAll(array('is_show' => 0), 'jobname=:jobname', array(':jobname' => $dataEdit[0]['jobname']));
                    }
                } elseif (count($dataAll) == 0) {

                }
                RecruitResume::model()->deleteAll('id = :id',array(':id'=>$id));
                $admin->id = $id;
            }

            $data = RecruitClass::model()->findAll('classid=:classid',array(':classid'=>$arr['link_id']));
            $admin->classname = $data[0]['classname'];
            $admin->jobname = $arr['jobname'];
            $admin->link_id = $arr['link_id'];
            $admin->age = $arr['age'];
            $admin->sex = $arr['sex'];
            $admin->need_num = $arr['num'];
            $admin->education = $arr['education'];
            $admin->experience = $arr['experience'];
            $admin->salary = $arr['salary'];
            $admin->pay = json_encode($arr['pay']);
            $admin->duty = json_encode($arr['job_duty']);
            $admin->job_require = json_encode($arr['require']);
            $admin->createtime = strtotime(date('Y-m-d H:i:s'));
            $admin->tel_num = $arr['tel_num'];
            $admin->mail = $arr['mail'];
            $admin->working_place = json_encode($arr['working_place']);
            $admin->save();
        }
    }

    /**
     * 加载简历模板
     */

    public function actionAddresume(){
        $data = RecruitClass::model()->findAll();
        $this->render('addresume', array('data'=>$data));
    }
    /**
     * 编辑简历
     */
    public function actionEditResume(){
        if (Yii::app()->request->isAjaxRequest) {
            $linkid = Yii::app()->request->getParam('linkid');
            $id = Yii::app()->request->getParam('id');
            $data = RecruitResume::model()->findAll("id=:id",array(':id'=>$id));
            $datajob = RecruitJob::model()->findAll('jobid =:linkid',array(':linkid'=>$linkid));
            echo CJSON::encode(array('val' => $data,'val1'=>$datajob));
        }
    }
    /**
     * 删除简历模板
     */
    public function actionDelResume(){
        if (Yii::app()->request->isAjaxRequest) {
            $id = Yii::app()->request->getParam('id');
            $is_select = Yii::app()->request->getParam('is_select');
            $dataresume = RecruitResume::model()->findAll("id=:id",array(':id'=>$id));
            $dataAll = RecruitResume::model()->findAll('link_id=:link_id',array(':link_id'=>$dataresume[0]['link_id']));
            $int = 0;
            foreach ($dataAll as $vt){
                if ($vt['is_show'] == 1){
                    $int = $int+1;
                }
            }
            if ($is_select == 1){
                $data =  RecruitResume::model()->findAll('id=:id',array(':id'=>$id));
                RecruitJob::model()->updateAll(array('is_show'=>0),'jobname=:jobname',array(':jobname'=>$data[0]['jobname']));
            }
            if ($is_select == 1 && $int<=1){
              $data =  RecruitResume::model()->findAll('id=:id',array(':id'=>$id));
                RecruitClass::model()->updateAll(array('is_show'=>0),'classname=:classname',array(':classname'=>$data[0]['classname']));
            }
            RecruitResume::model()->deleteAll("id=:id",array(':id'=>$id));

        }
    }
}

