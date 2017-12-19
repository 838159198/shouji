<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/20
 * Time: 13:46
 * 独立页面
 */
class PageController extends Controller
{
    public function actionDetail($pathname)
    {
        $model = new Page();
        $data = $model->find("pathname=:pathname",array(":pathname"=>$pathname));
        if(empty($data)){
            throw new CHttpException(404,"您查看的页面不存在");
        }else{
            if($data['status']!=1){
                throw new CHttpException(403,"您无权查看该页面");
            }else{
                $this->pageTitle = $data['title'];
                if($pathname == "aboutus"){
                    $this->render("aboutus",array("data"=>$data));
                }
                //$this->render("detail",array("data"=>$data));
            }
        }
    }
}