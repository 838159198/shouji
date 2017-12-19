<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/4/20
 * Time: 10:08
 * Name: 帮助中心、常见问题、公告
 */
class PostsController extends DhadminController
{
    /*
      * name: 内容列表
      * Date: 2015-4-20 10:12:04
      * */
    public function actionIndex()
    {
        $model = new Posts();
        $this->render("posts_index",array("model"=>$model));
    }
    /*
     * name: 分类列表
     * */
    public function actionList($cateid)
    {
        $category_model = new PostsCategory();
        $category_data = $category_model -> findByPk($cateid);
        if(empty($category_data)){
            throw new CHttpException(404,"不存在");
        }else{
            $model = new Posts();
            $criteria = new CDbCriteria;
            $criteria -> condition = "cid={$cateid}";
            //$criteria->compare('id', $this->id, true);
            /*$criteria->compare('title', $this->title, true);
            $criteria->compare('createtime', $this->createtime, true);
            $criteria->compare('lasttime', $this->lasttime, true);*/
            //$criteria->compare('status', $this->status);


            $data =  new CActiveDataProvider($model, array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => 25,
                ),
                'sort'=>array(
                    'defaultOrder'=>'id DESC', //设置默认排序
                    //       'defaultOrder'=>'t.id DESC', //当有join连接查询时，需要添加表别名
                ),
            ));
            $this->render("posts_list",array("model"=>$model,'data'=>$data,'category'=>$category_data));
        }

    }
    /*
     * name: 添加文章
     * */
    public function actionCreate()
    {
        //Yii::app()->cache->flush();
        $model = new Posts();
        $category_model = new PostsCategory();
        $category_data = $category_model -> findAll("status=1");
        if(isset($_POST['Posts'])){
            foreach($_POST['Posts'] as $_k => $_v){
                $model -> $_k = $_v;
            }
            $model -> createtime = time();
            $model -> lasttime = time();
            $model -> uid = Yii::app()->user->manage_id;//$this->uid;
            if($model->save()){
                Yii::app()->user->setFlash("article_status","添加内容成功！");
                $this->redirect(array("index"));
            }
        }
        $this->render("posts_create",array("model"=>$model,'category'=>$category_data));
    }
    /*
     * Name: 编辑文章
     * */
    public function actionUpdate($id)
    {
        $model = Posts::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            //$data -> scenario  = "admin";
            $category_model = new PostsCategory();
            $category_data = $category_model -> findAll("status=1");
            if(isset($_POST['Posts'])){
                foreach($_POST['Posts'] as $_k => $_v){
                    $data -> $_k = $_v;
                }
                $data -> lasttime = time();
                if($data -> save()){
                    Yii::app()->user->setFlash("article_status","修改成功！");
                    $this->redirect(array("index"));
                }
            }

            $this->render("posts_update",array("model"=>$data,'category'=>$category_data));
        }
    }
    public function actionDel($id)
    {
        $model = new Posts();
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