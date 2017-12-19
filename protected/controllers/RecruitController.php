<?php
class RecruitController extends Controller
{

    public function actionIndex()
    {
        
        $dataClass = RecruitClass::model()->findAll('is_show=:is_show',array(':is_show'=>1));
        foreach ($dataClass as $row){
            $dataJob = RecruitJob::model()->findAll('jobid=:jobid',array(':jobid'=>$row['classid']));
            $arr[$row['classname']] = $dataJob;
        }
        $this->render('index', array(
            'data'=>$dataClass,
            'data1'=>$arr
        ));
    }

    /**
     * 获取职位信息
     */
    public function actionJob()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $classid = Yii::app()->request->getParam('classid');
            $dataJob = RecruitJob::model()->findAllByAttributes(array('jobid'=>$classid,'is_show'=>1));
                echo CJSON::encode(array('val'=>$dataJob));
        }
    }

    /**
     * 模板数据
     */
    public function actionResume(){
        if (Yii::app()->request->isAjaxRequest) {
            $id = Yii::app()->request->getParam('id');
            $data = RecruitJob::model()->findAll('id=:id',array(':id'=>$id));
            $dataResume = RecruitResume::model()->findAllByAttributes(array('jobname'=>$data[0]['jobname'],'is_show'=>1));
            echo CJSON::encode(array('val'=>$dataResume));
        }
    }
}