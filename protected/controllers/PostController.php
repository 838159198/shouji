<?php
/*
 * 内容：公告、常见问题、帮助中心
 * */
class PostController extends Controller
{
    /*
     * 默认首页列表
     * */
    public function actionIndex()
    {
        $this->title = "内容";
    }
    public function actionCategory($pathname)
    {
        $category = $this->loadCategory($pathname);
        $this->pageTitle = $category['seotitle'];
        if($pathname=="question"){
            $this->render("question_index");
        }else{
            $model = new Posts();
            $criteria = new CDbCriteria();
            $criteria -> condition = "cid={$category['id']} and status=1";
            $criteria -> order = "id DESC";
            $count = $model->count($criteria);
            $pager = new CPagination($count);
            $pager->pageSize=12;
            $pager->applyLimit($criteria);
            $data = $model -> findAll($criteria);
            $this->render("list",array("data"=>$data,"pages"=>$pager,'category'=>$category));
        }

    }
    /*
     * 内容
     * */
    public function actionDetail($catepathname,$id)
    {
        //$category = $this->loadCategory($catepathname);
        $model = new Posts();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"您访问的页面不存在");
        }else{
            if($data->category->pathname != $catepathname){
                throw new CHttpException(404,"您访问的页面不存在");
            }else{
                $this->pageTitle = $data['title'];
                $data->getAddHits();
				$datan = $model -> findAll('id<'.$id.' and status=1 and cid=1 order by id desc limit 1');
                $datap = $model -> findAll('id>'.$id.' and status=1 and cid=1 order by id asc limit 1');
                $this->render("detail",array("data"=>$data,"datap"=>$datap,"datan"=>$datan));
            }
        }
    }
    /*
     * 分类信息
     * */
    private function loadCategory($pathname)
    {
        $model = new PostsCategory();
        $data = $model->find("pathname=:pathname",array(":pathname"=>$pathname));
        if(empty($data)){
            throw new CHttpException(404,"您访问的信息不存在");
        }else{
            return $data;
        }
    }
}
