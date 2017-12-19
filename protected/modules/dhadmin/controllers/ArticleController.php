<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/4/20
 * Time: 10:08
 * Name: 文章相关模块
 */
class ArticleController extends DhadminController
{
    /*
      * name: 文章列表
      * Date: 2015-4-20 10:12:04
      * */
    public function actionIndex()
    {
        $model = new Article();
        $this->render("article_index",array("model"=>$model));
    }
    /*
     * name: 添加文章
     * */
    public function actionCreate()
    {
        $model = new Article();
        $category_model = new ArticleCategory();
        $category_data = $category_model -> findAll("status=1");
        if(isset($_POST['Article'])){
            foreach($_POST['Article'] as $_k => $_v){
                $model -> $_k = $_v;
            }
            $model -> createtime = time();
            $model -> lasttime = time();
            $model -> uid = Yii::app()->user->manage_id;//$this->uid;
            if($model->save()){
                Yii::app()->user->setFlash("article_status","添加文章成功！");
                $this->redirect(array("index"));
            }
        }
        $this->render("article_create",array("model"=>$model,'category'=>$category_data));
    }
    /*
     * Name: 编辑文章
     * */
    public function actionUpdate($id)
    {
        $model = Article::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            //$data -> scenario  = "admin";
            $category_model = new ArticleCategory();
            $category_data = $category_model -> findAll("status=1");
            if(isset($_POST['Article'])){
                foreach($_POST['Article'] as $_k => $_v){
                    $data -> $_k = $_v;
                }
                $data -> lasttime = time();
                if($data -> save()){
                    Yii::app()->user->setFlash("article_status","修改成功！");
                    $this->redirect(array("index"));
                }
            }

            $this->render("article_update",array("model"=>$data,'category'=>$category_data));
        }
    }
    public function actionDel($id)
    {
        $model = new Article();
        $data = $model -> findByPk($id);
        if(empty($data)){
            echo "不存在的id";
            //throw new CHttpException(404,"不存在的id");
        }else{
            if($data->delete()){
                echo "删除成功";
            }else{
                echo "删除失败";
            }
        }
    }
    /*
     * @Name: 文章分类
     * @Date: 2015-4-20 10:16:58
     * */
    public function actionCategory()
    {
        $model = new ArticleCategory();
        $model->unsetAttributes(); // clear any default values
        $this->render('category_index', array(
            'model' => $model,
        ));
    }
    /*
     * @name: 栏目创建
     * */
    public function actionCategoryCreate()
    {
        $model = new ArticleCategory();
        if(isset($_POST['ArticleCategory'])){
            foreach($_POST["ArticleCategory"] as $_k => $_v){
                $model -> $_k = $_v;
            }
            $model -> createtime = time();
            $model -> lasttime = time();
            if($model ->save()){
                $this->redirect(array('category'));
                //成功 刷新当前表单
                //$this->refresh();
            }
        }
        $this->render("category_create",array("model"=>$model));
    }
    /*
     * @name: 栏目更新
     * */
    public function actionCategoryUpdate($id)
    {
        $model = ArticleCategory::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在");
        }else{
            if(isset($_POST["ArticleCategory"])){
                foreach($_POST["ArticleCategory"] as $_k => $_v){
                    $data -> $_k = $_v;
                }
                $data -> lasttime = time();
                if($data -> save()){
                    $this->redirect(array('category'));
                }
            }
            $this->render("category_update",array("model"=>$data));
        }
    }
    /*
     * 栏目删除
     * */
    public function actionCategoryDel($id)
    {
        exit("禁止删除");
        //
        /*$model = new ArticleCategory();
        $data = $model -> findByPk($id);
        if(empty($data)){
            echo "不存在的id";
        }else{
            echo "ok";
        }*/
    }
}