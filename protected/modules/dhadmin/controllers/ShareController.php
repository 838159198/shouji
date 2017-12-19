<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2016/12/15
 * Time: 10:25
 * @name 推广来源信息
 */
class ShareController extends DhadminController
{
    /**
     * @name 首页
     */
    public function actionIndex()
    {
        $model = new Share('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Share'])) {
            $model->attributes = $_GET['Share'];
        }
        $this->render("index",array('model'=>$model));
    }
    /**
     * @name 添加盒子信息
     */
    public function actionSourceAdd()
    {
        $model = new SpreadSource();
        if(isset($_POST['SpreadSource'])){
            foreach($_POST['SpreadSource'] as $_k => $_v){
                $model -> $_k = $_v;
            }
            $model -> createtime = time();
            $model -> mid = Yii::app()->user->manage_id;
            if($model -> save()){
                Yii::app()->user->setFlash('source_status','添加渠道成功');
                $this->redirect(array("index"));
            }
        }
        $this->render("source_add",array("model"=>$model));
    }
    /**
     * @name 编辑盒子信息
     */
    public function actionSourceUpdate($id)
    {
        $model = SpreadSource::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            if(isset($_POST['SpreadSource'])){
                foreach($_POST['SpreadSource'] as $_k => $_v){
                    $data -> $_k = $_v;
                }
                $data -> mid =  Yii::app()->user->manage_id;
                if($data -> save()){
                    Yii::app()->user->setFlash('source_status','修改渠道成功');
                    $this->redirect(array("index"));
                }
            }
            $this->render("source_update",array("model"=>$data));
        }
    }

}