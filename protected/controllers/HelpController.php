<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/7/1
 * Time: 11:18
 */
class HelpController extends Controller
{
    /*
     * 首页
     * */
    public function actionIndex()
    {
        $model = new Article();
        $criteria = new CDbCriteria();
        $category = ArticleCategory::model()->categoryByPathname("help");
        $criteria -> condition = "`status`=1 and `cid`={$category['id']}";
        $criteria -> order = "id DESC";
        $count = $model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=3;
        $pager->applyLimit($criteria);
        $data = $model -> findAll($criteria);
        $this->render("index",array("data"=>$data,'pages'=>$pager));
    }
    /*
     * 详情
     * */
    public function actionDetail($id)
    {
        $model = Posts::model();
        $data = $model -> findByPk($id);
        if (empty($data)) {
            throw new CHttpException(404, "没有找到您想访问的页面!");
        } else {
            $category_model = PostsCategory::model()->categoryByPathname("help");
            if ($data['status'] == 1) {
                if($data['cid'] != $category['id']){
                    throw new CHttpException(404, "没有找到您想访问的页面!");
                }else{
                    $this->pageTitle = $data['title'];
                    $model->updateCounters(array("hits" => 1),"id={$id}");
                    $this->render("detail", array('data' => $data));
                }
            } else {
                throw new CHttpException(404, "没有找到您想访问的页面!");
            }
        }
    }

    /**
     *2017-11-16
     *接口
     *调用该接口实现自动封号功能
     *供后台黑名单定时拉黑使用
     */
    public function actionImeikill(){
        set_time_limit(0);
        echo '<meta charset="utf-8">';
        $sql="select update_detail from `app_note_log` where tag='imei定时封号' limit 1";
        $result=yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($result)){
            //设定的最大安装次数
            $num=$result[0]['update_detail'];
            //找出大于安装数的全部手机imei 
            $sql="select imeicode,max(`app_rom_appresource`.id) as id from `app_rom_appresource` left join `app_member` on `app_rom_appresource`.uid=`app_member`.id where agent in(0,69) and closeend='0000-00-00 00:00:00' and installcount > ".$num." group by imeicode";
            $daa=yii::app()->db->createCommand($sql)->queryAll();
            $data=array();
            if(!empty($daa)){
                $id='';
                foreach ($daa as $key => $value) {
                    $id.=$value['id'].',';
                }
                $id=!empty($id) ? substr($id,0,-1) : '';
                $sql="select uid,model,brand,imeicode from `app_rom_appresource` where id in({$id})";
                $data=yii::app()->db->createCommand($sql)->queryAll();
            }
            if(!empty($data)){
                $cishu=0;
                $imei='';
                foreach ($data as $key => $value) {
                    $imei.='"'.$value["imeicode"].'"';
                    $imei.=',';
                    // $mid=yii::app()->user->manage_id;
                    //imei是否已经拉黑
                    $sql="select id from `app_blacklist` where imeicode='{$value['imeicode']}'";
                    $arr=yii::app()->db->createCommand($sql)->queryAll();
                    if(!empty($arr)){
                        continue;
                    }
                    yii::app()->db->createCommand()->insert('app_blacklist',
                        array(
                            'imeicode'=>$value['imeicode'],
                            'mid'=>0,
                            'createtime'=>date('Y-m-d H:i:s'),
                            'brand'=>$value['brand'],
                            'model'=>$value['model'],
                            'uid'=>$value['uid']
                        )
                    );
                    $cishu+=1;

                }
                //update
                $imei=substr($imei,0,-1);
                // echo $imei;exit;
                $a=yii::app()->db->createCommand()->update('app_rom_appresource',array('finishstatus'=>0,'closeend'=>date('Y-m-d H:i:s'),'finishdate'=>'0000-00-00','finishtime'=>'0000-00-00 00:00:00','status'=>0),'imeicode in ('.$imei.')'
                    );
                if($a){
                    echo '封杀手机'.$cishu.'个';exit;//共处理了多少个手机
                }
                // echo $cishu;//共处理了多少条数据
                
            }else{
               echo '封杀手机0个';exit;
            }
            
        }
        echo '封杀手机0个';exit;
    }
}