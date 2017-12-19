<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/13
 * Time: 16:46
 * Name: 管理员
 */
class ManageController extends DhadminController
{
    /*
     * 列表
     * */
    public function actionIndex()
    {
        $model = new Manage();
        $model->unsetAttributes();
        if (isset($_GET['Manage'])) {
            $model->attributes = $_GET['Manage'];
        }
        $this->render("index",array('model'=>$model));
    }
    /*
     * 创建
     * */
    public function actionCreate()
    {
        $model = new Manage("newadd");
        $group = ManageGroup::model()->findAll();
        if(isset($_POST['Manage'])){
            foreach($_POST['Manage'] as $_k => $_v){
                $model -> $_k = $_v;
            }
            $model -> jointime = time();
            $model -> overtime = time();
            $model -> joinip = Yii::app()->request->userHostAddress;
            $model -> overip = Yii::app()->request->userHostAddress;
            //默认密码sutui888
            $model -> password = md5(strrev(md5(strrev(trim("sutui888")))));
            if($model -> save()){
                Yii::app()->user->setFlash("status","恭喜你，创建成功！");
                $this->redirect(array("manage/index"));
            }
        }
        $this->render("create",array("model"=>$model,'group'=>$group));
    }
    /*
     * 编辑
     * */
    public function actionEdit($id)
    {
        $model = Manage::model();
        $data = $model -> findByPk($id);
        $data -> scenario = "editadd";
        $group = ManageGroup::model()->findAll();
        if(empty($data)){
            throw new CHttpException(404,"不存在");
        }else{
            if(isset($_POST['Manage'])){
                foreach($_POST['Manage'] as $_k => $_v){
                    $data -> $_k = $_v;
                }
                if($data -> save()){
                    Yii::app()->user->setFlash("status","恭喜你，资料修改成功！");
                    $this->redirect(array("manage/index"));
                }
            }
            $this->render("edit",array("model"=>$data,'group'=>$group));
        }
    }
    /*
     * 资料查看
     * */
    public function actionDetail($id)
    {
        $model = new Manage();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在");
        }else{
            $this->render("detail",array("data"=>$data));
        }
    }
    /*
     * 锁定
     * */
    public function actionLock($id)
    {
        $model = new Manage();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的用户id");
        }else{
            $data->status = 0;
            $data -> update();
            Yii::app()->user->setFlash("status","恭喜你，账号已成功锁定！");
            $this->redirect(array("manage/detail?id=".$id));
        }
    }
    /*
     * 解锁
     * */
    public function actionUnlock($id)
    {
        $model = Manage::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            $data -> status = 1;
            if($data -> update()){
                Yii::app()->user->setFlash("status","恭喜你，解锁成功！");
                $this->redirect(array("manage/detail?id=".$id));
            }else{
                throw new CHttpException(404,"解锁失败");
            }
        }
    }
    /*
     * 重置密码
     * */
    public function actionPassword($id)
    {
        $model = Manage::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            $data -> password = md5(strrev(md5(strrev(trim("sutui888")))));
            if($data -> update()){
                Yii::app()->user->setFlash("status","恭喜你，密码重置成功！默认密码为：123456");
                $this->redirect(array("manage/detail?id=".$id));
            }else{
                throw new CHttpException(404,"密码重置失败");
            }
        }
    }
    /*
     * 权限
     * */
    public function actionAuth($id)
    {
        $model = Manage::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            if(isset($_POST['Manage'])){
                $data -> auth = implode(",",$_POST['Manage']['auth']);
                if($data -> save()){
                    Yii::app()->user->setFlash("status","恭喜你，权限设置成功！");
                    $this->redirect(array("manage/detail?id=".$id));
                }else{
                    throw new CHttpException(404,"权限失败");
                }
            }

            $this->render("auth",array("model"=>$data));
        }
    }
    /*
     * 修改自己的资料
     * */
    public function actionMyInfo()
    {
        $id = Yii::app()->user->manage_id;
        $model = Manage::model();
        $data = $model -> findByPk($id);
        $data -> scenario  = "myinfo";
        if(empty($data)){
            throw new CHttpException(404,"发生错误，请联系管理员");
        }else{
            if(isset($_POST['Manage'])){
                $data -> name = $_POST['Manage']['name'];
                $data -> sex = $_POST['Manage']['sex'];
                $data -> idcard = $_POST['Manage']['idcard'];
                $data -> phone = $_POST['Manage']['phone'];
                $data -> mail = $_POST['Manage']['mail'];
                $data -> qq = $_POST['Manage']['qq'];
                if($data->save()){
                    Yii::app()->user->setFlash("status","恭喜你，您的资料修改成功！");
                    $this->redirect(array("manage/myInfo"));
                }
            }
            $this->render("my_info",array("data"=>$data));
        }
    }
    /*
     * 修改自己的密码
     * */
    public function actionMyPassword()
    {
        $id = Yii::app()->user->manage_id;
        $model = Manage::model();
        $data = $model -> findByPk($id);
        $data -> scenario  = "mypassword";
        if(empty($data)){
            throw new CHttpException(404,"发生错误，请联系管理员");
        }else{
            $data -> password = "";
            if(isset($_POST['Manage'])){
                $data -> password = $_POST['Manage']['password'];
                $data -> password2 = $_POST['Manage']['password2'];
                if ($data->validate()) {
                    //$model -> password = md5(strrev(md5(strrev(trim(123456)))));
                    $data -> password = md5(strrev(md5(strrev(trim($_POST['Manage']['password'])))));;
                    if($data->update()){
                        Yii::app()->user->setFlash("status","恭喜你，您的密码修改成功！");
                        $this->redirect(array("manage/myPassword"));
                    }
                }
            }
            $this->render("my_password",array("data"=>$data));
        }
    }
}