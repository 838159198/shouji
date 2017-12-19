<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2017/5/17
 * Time: 10:25
 * @name 路由控制器
 */
class SoftrouteController extends DhadminController
{
    /**
     * @name 首页
     */
    public function actionIndex()
    {
        $model = new Softroute('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Softroute'])) {
            $model->attributes = $_GET['Softroute'];
        }
        $this->render("index",array('model'=>$model));
    }
    /**
     * @para 给用户添加路由
     */
    public function actionRouteAdd()
    {
        $model = new Softroute();
        $member= Member::model()->findAll('status=:status',array(':status'=>1));
        if(isset($_POST['Softroute'])){
            foreach($_POST['Softroute'] as $_k => $_v){
                $model -> $_k = $_v;
            }
            $model -> createtime = date('Y-m-d H:i:s',time());
            $model -> mid = Yii::app()->user->manage_id;

            //判断该用户是否有统计id
            $uid=$_POST['Softroute']['uid'];
            $userapp = RomSoftpak::model()->find('uid=:uid and closed=:closed',array(':uid'=>$uid,':closed'=>0));
            //分配对应appid
            if(empty($userapp)){
                throw new CHttpException(500, '该用户还没统计，请先分配');
            }else{
                if($model -> save()){
                    Yii::app()->user->setFlash('status','添加路由成功');
                    $this->redirect(array("index"));
                }
            }

        }
        $this->render("route_add",array("model"=>$model,'member'=>$member));
    }
    /**
     * @para 编辑路由信息
     */
    public function actionRouteUpdate($id)
    {
        $model = Softroute::model();
        $data = $model -> findByPk($id);
        $member= Member::model()->findAll('status=:status',array(':status'=>1));
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            if(isset($_POST['Softroute'])){
                foreach($_POST['Softroute'] as $_k => $_v){
                    $data -> $_k = $_v;
                }
                $data -> updatetime = date('Y-m-d H:i:s',time());
                $data -> mid =  Yii::app()->user->manage_id;
                if($data -> save()){
                    //解绑套餐
                    RomBoxPackage::deleteData($data->route_number);
                    $this->redirect(array("index"));
                }
            }
            $this->render("route_update",array("model"=>$data,'member'=>$member));
        }
    }

    public function actionTest2()
    {
        $softroutes= Softroute::model()->findAll('status=:status',array(':status'=>1));
        $routeUid=array();
        foreach($softroutes as $softroute){
            $data = Member::model() -> findByPk($softroute->uid);
            $routeUid[$softroute->route_number]=$data->username;
        }
        $this->render("test2",array("routeUid"=>json_encode($routeUid)));
    }

    public function actionInfo($to)
    {
        $this->render("info",array("uid"=>$to));
    }


}