<?php
/**
 * Created by AWangBa.com
 * User: hanyoujun@gmail.com
 * Date: 13-5-27 下午1:11
 * Explain:
 * @name 用户角色管理
 */
class RoleController extends DhadminController
{
    /**
     * @name 首页
     */
    public function actionIndex()
    {
        $data = Role::model()->getDataProvider();
        $this->render('index', array('data' => $data));
    }

    /**
     * @name 添加
     */
    public function actionCreate()
    {
        $model = new Role('insert');
        if (isset($_POST['Role'])) {
            $model->attributes = $_POST['Role'];
            if ($model->validate()) {
                if ($model->insert()) {
                    $this->redirect(array('index'));
                }
            }
        }
        $this->render('create', array('model' => $model));
    }

    /**
     * @param $id
     * @name 修改
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        if (isset($_POST['Role'])) {
            $model->attributes = $_POST['Role'];
            if ($model->validate()) {
                if ($model->update()) {
                    $this->redirect(array('index'));
                }
            }
        }
        $this->render('update', array('model' => $model));
    }

    /**
     * @param $id
     * @throws CHttpException
     * @name 删除
     */
    public function actionDelete($id)
    {
        if ($id == 1) {
            throw new CHttpException(500, '该角色是基础角色，不能删除');
        }
        $model = $this->loadModel($id);
        $childList = Role::model()->getChildRoleList($id, Role::model()->getList());
        if (count($childList) > 0) {
            throw new CHttpException(500, '不能删除，该角色还有子项');
        } else {
            $model->delete();
        }
    }
    /**
     * @name 客服晋升
     */
    public function actionUpdateManageRoleByWeekTaskCallBack(){
        if (Yii::app()->request->isAjaxRequest) {
            $status = Yii::app()->request->getParam('status'); //atid int
            $id = Yii::app()->user->manage_id;
            $role = Role::ADVANCED_STAFF;   //高级客服权限
            $time = time();
            //拒绝晋升
            if($status==0){
                $count =Manage::model()->updateByPk($id,array('promotion'=>0));
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
                exit (); //成功
            }elseif($status==1){
                //等待晋升状态，当前时间
                $count =Manage::model()->updateByPk($id,array('promotion'=> 2,'pro_time'=> $time));
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS));
                exit (); //成功
            }
        }
    }
    /**
     * @param $id
     * @return Role
     * @throws CHttpException
     */
    private function loadModel($id)
    {
        $model = Role::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}