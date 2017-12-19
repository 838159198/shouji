<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2017/5/17
 * Time: 10:25
 * @name 路由控制器
 */
class ControlPackageController extends DhadminController
{
    /**
     * @name 监控包列表
     */
    public function actionIndex()
    {
        $model = new ControlPackage('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['ControlPackage'])) {
            $model->attributes = $_GET['ControlPackage'];
        }
        $this->render("index",array('model'=>$model));
    }
    /**
     * @para 添加监控包
     */
    public function actionPackageAdd()
    {
        $model = new ControlPackage();
        if(isset($_POST['ControlPackage'])){
            foreach($_POST['ControlPackage'] as $_k => $_v){
                $model -> $_k = $_v;
            }
            $model -> createtime = date('Y-m-d H:i:s',time());
            $model -> mid = Yii::app()->user->manage_id;
            if($model -> save()){
                Yii::app()->user->setFlash('status','添加监控包成功');
                $this->redirect(array("index"));
            }
        }
        $this->render("package_add",array("model"=>$model));
    }
    /**
     * @para 编辑路由信息
     */
    public function actionPackageUpdate($id)
    {
        $model = ControlPackage::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            if(isset($_POST['ControlPackage'])){
                foreach($_POST['ControlPackage'] as $_k => $_v){
                    $data -> $_k = $_v;
                }
                $data -> updatetime = date('Y-m-d H:i:s',time());
                $data -> mid =  Yii::app()->user->manage_id;
                if($data -> save()){
                    $this->redirect(array("index"));
                }
            }
            $this->render("package_update",array("model"=>$data));
        }
    }

    /**
     * @para 一键导入
     */
    public function actionPackageAll()
    {
        $mid = Yii::app()->user->manage_id;
        $model = new ControlPackage();
        if(isset($_POST['ControlPackage'])){

            $type=$_POST['ControlPackage']['type'];
            if($type==''){
                $sql="SELECT pakname,name,pathname,agent FROM app_product_list l LEFT JOIN app_product p ON l.pid=p.id";
            }else{
                $sql="SELECT pakname,name,pathname,agent FROM app_product_list l LEFT JOIN app_product p ON l.pid=p.id WHERE l.agent={$type}";
            }
            $data = Yii::app()->db->createCommand($sql)->queryAll();

            if($data){
                $num=0;
                foreach($data as $v){
                    $controlPackage=ControlPackage::model()->find("package_name=:package_name and type=:type",array(":package_name"=>$v['pakname'],":type"=>$v['agent']));
                    if($controlPackage) continue;
                    $model = new ControlPackage();
                    $model ->type = $v['agent'];
                    $model -> createtime = date('Y-m-d H:i:s',time());
                    $model -> mid = $mid;
                    $model -> name = $v['name'];
                    $model -> pathname = $v['pathname'];
                    $model -> package_name = $v['pakname'];
                    $num= $model->save();
                }
                if($num>0){
                    Yii::app()->user->setFlash('status','一键导入监控包成功');
                }else{
                    Yii::app()->user->setFlash('status','已经全部导入，无需再操作！');
                }
                $this->redirect(array("index"));
            }

        }
        $this->render("package_all",array("model"=>$model));
    }




}