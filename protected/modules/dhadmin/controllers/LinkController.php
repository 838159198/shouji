<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/4/21
 * Time: 13:24
 * Name: 友情链接
 */
class LinkController extends DhadminController
{
    //友情链接内容列表
    public function actionIndex()
    {
        $model = new Link();
        $this->render("link_index",array("model"=>$model));
    }
    /*
     * 添加链接
     * */
    public function actionCreate()
    {
        $category_model = new LinkCategory();
        $category_data = $category_model -> findAll();
        $model = new Link();
        if(isset($_POST['Link'])){
            foreach($_POST['Link'] as $_k => $_v){
                $model -> $_k = $_v;
            }
            $model -> createtime = time();
            $model -> lasttime = time();
            $model -> uid = Yii::app()->user->manage_id;
            if($model->save()){
                Yii::app()->user->setFlash("link_status","添加友情链接成功！");
                $this->redirect(array("index"));
            }
        }
        $this->render("link_create",array("model"=>$model,'category'=>$category_data));
    }
    /*
     * 修改链接
     * */
    public function actionUpdate($id)
    {
        $category_model = new LinkCategory();
        $category_data = $category_model -> findAll();
        $model = new Link();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            if(isset($_POST['Link'])){
                foreach($_POST['Link'] as $_k => $_v){
                    $data -> $_k =$_v;
                }
                $data -> lasttime = time();
                if($data->save()){
                    Yii::app()->user->setFlash("link_status","修改友情链接成功！");
                    $this->redirect(array("index"));
                }
            }
            $this->render("link_update",array("model"=>$data,"category"=>$category_data));
        }
    }
    /*
     * 删除链接
     * */
    public function actionDel($id)
    {
        $model = new Link();
        $data = $model -> findbypk($id);
        if(empty($data)){
            echo "不存在的id";
        }else{
            if($data->delete()){
                echo "删除成功";
            }else{
                echo "删除失败，请联系管理员";
            }
        }
    }

}