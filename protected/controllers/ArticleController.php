<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/20
 * Time: 11:20
 * 文章系统
 */
class ArticleController extends Controller
{
    public function actionIndex()
    {
        $this->pageTitle = "文章中心";
        $model = new Article();
        $criteria = new CDbCriteria();
        $criteria -> condition = "status=1";
        $criteria -> order = "id DESC";
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=4;
        $pager->applyLimit($criteria);
        $data = $model -> findAll($criteria);
        $this->render("index",array("data"=>$data,"pages"=>$pager));
    }
    /*
     * 栏目
     * */
    public function actionList($pathname)
    {
        $category = $this->loadCategory($pathname);
        $this->pageTitle = $category['seotitle'];
        $model = new Article();
        $criteria = new CDbCriteria();
        $criteria -> condition = "cid={$category['id']} and status=1";
        $criteria -> order = "id DESC";
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=4;
        $pager->applyLimit($criteria);
        $data = $model -> findAll($criteria);
        $this->render("list",array("data"=>$data,"pages"=>$pager,'category'=>$category));
    }
    /*
     * 详情
     * */
    public function actionDetail($id)
    {
        $model = Article::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"您查看的页面不存在");
        }else{
            if($data['status']==1){
                $this->pageTitle = $data['title'];
				$datap = $model -> findAll('id<'.$id." and status=1 and cid={$data['cid']} order by id desc limit 1");
                $datan = $model -> findAll('id>'.$id." and status=1 and cid={$data['cid']} order by id asc limit 1");
                $data->updateCounters(array("hits" => 1),"id=".$id);
                $this->render("detail",array("data"=>$data,"datap"=>$datap,"datan"=>$datan));
            }else{
                throw new CHttpException(403,"您没有权限查看当前页面");
            }
        }
    }
    /*
     * 控制器获取分类信息
     * */
    private function loadCategory($pathname)
    {
        $model = new ArticleCategory();
        $data = $model->find("pathname=:pathname",array(":pathname"=>$pathname));
        if(empty($data)){
            throw new CHttpException(404,"您查看的页面不存在");
        }else{
            return $data;
        }

    }
}