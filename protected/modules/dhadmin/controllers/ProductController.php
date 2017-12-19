<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/8
 * Time: 14:36
 * 业务产品
 */
class ProductController extends DhadminController
{
    public function actions()
    {
        return array(
            //在actions下的return array添加下面两句，没有actions的话自己添加
           // 'upload'=>array('class'=>'application.extensions.KEditor.KEditorUpload'),
            //'manageJson'=>array('class'=>'application.extensions.KEditor.KEditorManage'),
        );
    }
    /*
     * name: 产品列表
     * */
    public function actionIndex()
    {
        $product_model = new Product();
        $criteria = new CDbCriteria();
        //$criteria -> condition = "status=1 and cid=".$category_data['id'];
        //条件查询
        //$criteria->addCondition('status=1');      //根据条件查询
        //$criteria->addCondition('cid=0'.$category_data['id']);
        $criteria -> order = "`order` DESC";
        $count = $product_model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=30;
        $pager->applyLimit($criteria);
        $article_data = $product_model -> findAll($criteria);
        foreach($article_data as $key=>$val)
        {
            $memres=MemberResource::model()->findAll("type='{$val['pathname']}' and openstatus=1");
            $article_data[$key]["enrollment"]=count($memres);
        }
        //print_r($article_data);
        $this->render("index",array('data'=>$article_data,'pages'=>$pager));
    }
    /*
     * name: 产品添加
     * */
    public function actionCreate()
    {
        $product_model = new Product();
        if(isset($_POST['Product'])){
            $detail='';//日志内容
            foreach($_POST['Product'] as $_k => $_v){
                if ($_k == 'sign'){
                    $product_model ->$_k = json_encode($_v);
                    $detail.=' ['.$_k.'] '.json_encode($_v);
                }else{
                    $product_model -> $_k = $_v;
                    $detail.=' ['.$_k.'] '.$_v;
                }
            }
            $product_model -> createtime = time();
            $product_model -> updatetime = time();
            $product_model -> auth = 1;
            if($product_model -> save())
            {
                //日志记录
                $mid = Yii::app()->user->manage_id;
                NoteLog::addLog($detail,$mid,$uid='',$tag='添加产品',$update='');
                //自动创建表、model、
                Common::createByType($product_model->pathname);
                $this->redirect(array("product/index"));
            }
        }
        $arr = $this->getCategoryData();
        $this->render("create",array('model'=>$product_model,'arr'=>$arr));
    }

    /*
     * name:增加分类
     * */
    public function actionAddCategroy(){
        $this->render("add_categroy",array());
    }
    /*
     * name:溫馨提示
     * */
    public function actionPrompt(){
            $title = "溫馨提示";
        if (Yii::app()->request->isAjaxRequest) {
            $content = Yii::app()->request->getParam('content');
            $data = Page::model()->findAll('id=:id',array(':id'=>20));;
            if (empty($data)){
                $admin = new Page();
                $admin->title = $title;
                $admin->content = $content;
                $admin->createtime = time();
                $admin->lasttime = time();
                $admin->status = 1;
                $admin->uid = 0;
                $admin->pathname = 'prompt';
                $t = $admin->save();
            }else{
               $t = Page::model()->updateAll(array('content'=>$content),'title=:title',array(':title'=>$title));
            }
            if ($t>0){
                echo CJSON::encode(array('val' =>'1'));
            }
        }else{
            $data1 = Page::model()->findAll('id=:id',array(':id'=>20));
            $this->render("prompt",array('data'=>$data1));
        }


    }
    
    /*
     * name:分类名字存入
     * */
    public function actionAjax(){
        if (Yii::app()->request->isAjaxRequest) {
            $name = Yii::app()->request->getParam('name');

            $data = ProductCategoryname::model()->findAll('category_name = :category_name',array(':category_name'=>$name));
           if (!empty($data)){
               echo CJSON::encode(array('val' => '0'));
               return;
           }
            $model = new ProductCategoryname();
            $model->category_name = $name;
            $model->createtime = time();
            $model->save();
            echo CJSON::encode(array('val' => '1'));
        }
    }

    /*
     * name:分类数据处理
     * */
    private function getCategoryData(){
        $arr = array();
        $data =ProductCategoryname::model()->findAll();
        foreach ($data as $vt){
            $arr[$vt['id']] = $vt['category_name'];
        }
        return $arr;
    }


    /*
     * 更新，修改
     * */
    public function actionEdit($id)
    {
        $product_model = Product::model();
        $product_data = $product_model->findByPk($id);
        if(empty($product_data)){
            //不存在
            throw new CHttpException(404,"不存在的id");
        }else{
            $detail='';//日志内容
            $update=json_encode($product_data->attributes);
            if(isset($_POST['Product'])){
                foreach($_POST['Product'] as $_k => $_v){
                    if ($_k == 'sign'){
                        $product_data ->$_k = json_encode($_v);
                        $detail.=' ['.$_k.'] '.json_encode($_v);
                    }else{
                        $product_data -> $_k = $_v;
                        $detail.=' ['.$_k.'] '.$_v;
                    }
                }
                $product_data -> updatetime = time();
                if($product_data->save()){
                    //日志记录
                    $mid = Yii::app()->user->manage_id;
                    NoteLog::addLog($detail,$mid,$uid='',$tag='产品信息修改',$update);

                    $this->redirect(array("product/index"));
                }
            }
            $product_data['sign'] = json_decode($product_data['sign']);
            $arr = $this->getCategoryData();
            $this->render("edit",array("model"=>$product_data,'arr'=>$arr));
        }
    }

    /*
     * name: 活动管理
     * */
    public function actionHuodong(){
        $campaign = new Campaign();
        $criteria = new CDbCriteria();
        $criteria->addCondition("status=1");
        $criteria -> order = "periods DESC";
        $count = $campaign->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=4;
        $pager->applyLimit($criteria);
        $campaigns = $campaign -> findAll($criteria);
        $this->render("huodong",array('data'=>$campaigns,'pages'=>$pager,'count'=>$count));
    }
    /*
     * name: 修改每期活动
     * */
    public function actionEditCampaign(){

        $campaign=Campaign::model()->find("periods='{$_POST["periods"]}'");
        if(!$campaign){
            throw new CHttpException(404,"业务开启失败");
        }
        $campaign->title=$_POST['title'];
        $campaign->instruction=$_POST['instruction'];
        $campaign->userstarttime=$_POST['userstarttime'];
        $campaign->userendtime=$_POST['userendtime'];
        $campaign->starttime=$_POST['starttime'];
        $campaign->endtime=$_POST['endtime'];
        if($campaign->save())
        {
            exit(CJSON::encode(array("status"=>200,"message"=>"修改成功！")));
            //$this->redirect(array("product/huodong"));
        }
        else
        {
            exit(CJSON::encode(array("status"=>403,"message"=>"修改失败！")));
        }
        //var_dump($campaign);exit;
    }

    public function actionHuodong2()
    {
       // var_dump($_GET['periods']);exit;
        $pcamlog_model = new CampaignLog();
        $criteria = new CDbCriteria();

        if(!empty($_GET['periods']))
        {
            $criteria->addCondition("cid=".$_GET['periods']);
        }
        $criteria -> order = "id DESC";
        $count = $pcamlog_model->count($criteria);
        $pager = new CPagination($count);
        $pager->pageSize=20;
        $pager->applyLimit($criteria);
        $pcamlog_d = $pcamlog_model -> findAll($criteria);
        $pcamlog_data = array();
        foreach($pcamlog_d as $t)
        {
            $pcamlog_data[$t->id] = $t->attributes;
        }
        foreach($pcamlog_data as $key=>$val)
        {
            $proinfo=Product::model()->findByPk($val["pid"]);
            $pcamlog_data[$key]["pname"]=$proinfo["name"];

            $memres=Member::model()->findByPk($val["uid"]);
            $pcamlog_data[$key]["username"]=$memres["username"];
            $pcamlog_data[$key]["tel"]=$memres["tel"];
            $pcamlog_data[$key]["qq"]=$memres["qq"];
            if($val["status"]==0)
            {
                $pcamlog_data[$key]["statusname"]="未审核";
            }
            elseif($val["status"]==1)
            {
                $pcamlog_data[$key]["statusname"]="审核通过";
            }
            elseif($val["status"]==2)
            {
                $pcamlog_data[$key]["statusname"]="拒绝";
            }

        }

        $this->render("huodong2",array('data'=>$pcamlog_data,'pages'=>$pager,'count'=>$count,'periods'=>$_GET['periods']));
    }

    /*
     * 活动审核
     * */
    public function actionHuodongedit($id)
    {
        $camlog_model = CampaignLog::model();
        $camlog_data = $camlog_model->findByPk($id);
        $memres=Member::model()->findByPk($camlog_data["uid"]);
        $username=$memres["username"];

        if(empty($camlog_data))
        {
            throw new CHttpException(404,"不存在的id");
        }
        else
        {
            if(isset($_POST['CampaignLog']))
            {
                foreach($_POST['CampaignLog'] as $_k => $_v)
                {
                    $camlog_data -> $_k = $_v;
                }
                if($camlog_data->save()){
                    //开启业务
                    if($_POST['CampaignLog']['status']==1)
                    {
                        $status=1;
                        $res=$this->actionProedit($camlog_data["pid"], $status, $camlog_data["uid"]);
                        if($res)
                        {
                            $this->redirect(array("product/huodong"));
                        }
                        else
                        {
                            throw new CHttpException(404,"业务开启失败");
                        }
                    }
                    $this->redirect(array("product/huodong"));

                }
            }
            $this->render("huodongedit",array("model"=>$camlog_data,"username"=>$username));
        }
    }
    /*
    * 活动数据
    * */
    public function actionHddata()
    {
       $model=new CampaignIncome('search');
       // $campaignIncome=CampaignIncome::model()->findAll("periods='{$_GET['periods']}'");
        $model->unsetAttributes();
        if(isset($_GET['CampaignIncome']))
            $model->attributes=$_GET['CampaignIncome'];
        $this->render('hddata',array(
            'model'=>$model,'periods'=>$_GET['periods']
        ));
    }
    /*
     * 活动排名
     * */
    public function actionHdorder()
    {
        $model=new CampaignSort('search');
        $model->unsetAttributes();
        if(isset($_GET['CampaignSort']))
            $model->attributes=$_GET['CampaignSort'];
        $this->render('hdorder',array(
            'model'=>$model,'periods'=>$_GET['periods']
        ));
    }
    /*
    * 弹出用户名连接字符串页面
    * */
    public function actionUsernames($usernames)
    {
        $this->renderPartial("usernames",array('usernames'=>$usernames));
    }
    //弹出添加排名页面
    public function actionAdd($periods)
    {
        $this->renderPartial("add",array('periods'=>$periods));
    }
    //弹出每期活动修改页面
    public function actionShowCampaign($periods)
    {
        $caminfo=Campaign::model()->findAll(array(
            'condition' => 'periods=:periods',
            'params' => array(':periods'=>$periods),
        ));
        $this->renderPartial("showCampaign",array('periods'=>$periods,'caminfo'=>$caminfo));
    }
    //手动添加排名
    public function actionSortAdd()
    {
        if(isset($_POST['username']) && !empty($_POST['username'])){
            $usernameArr=explode(',',$_POST['username']);

        }else{
            $num=$_POST['num'];//添加人数
            $sort_model = CampaignSort::model()->findAll("periods={$_POST['periods']} and del=1");
            $criteria = new CDbCriteria() ;
            $criteria -> select = array('username,id');
            $criteria -> condition = 'status =1';
            if($sort_model){
                foreach($sort_model as  $v){
                    $nameArr[]= $v->username;
                }
                if($nameArr){
                    $criteria->addNotInCondition('username', $nameArr);
                }
            }

            $criteria -> limit = $num;
            $result = Member::model()->findAll($criteria);
            foreach($result as  $v){
                $usernameArr[]= $v->username;
            }
        }

        $arr=array();
        //print_r($resnum);exit;
        foreach($usernameArr as $v){
            $campaignSort = new CampaignSort();
            $model_campa= CampaignSort::model()->findAll("periods={$_POST['periods']} and del=1 and username='{$v}'");
           // var_dump($model_campa);exit;
            if(!empty($model_campa)){
                continue;
            }
            $campaignSort->username=$v;
            $campaignSort->num=0;
            $campaignSort->sort=rand($_POST['sort_start'],$_POST['sort_end']);
            $campaignSort->type=$_POST['type'];
            $campaignSort->periods=$_POST['periods'];
            $campaignSort->truth=0;
            $campaignSort->createtime=time();
            $arr=$campaignSort->save();

        }

        if(count($arr)>=1){
            exit(CJSON::encode(array("status"=>200,"message"=>"添加成功！")));
        }else{
            exit(CJSON::encode(array("status"=>403,"message"=>"添加失败！")));
        }
    }
    //发布排名
    public function actionSortPublish()
    {
        $periods=$_POST['periods'];
        $campaignSorts=CampaignSort::model()->findAll("periods='{$periods}' and del=1");
        if(!$campaignSorts)
            exit(CJSON::encode(array("status"=>403,"message"=>"暂时没有数据！")));
        $campaign=Campaign::model()->findAll("periods='{$periods}' and status=1");
        foreach($campaign as $v){
            $v->publish_status=1;
            $v->publishtime=time();
            $num=$v->update();
            $num+=$num;
        }
        if($num>=1){
            exit(CJSON::encode(array("status"=>200,"message"=>"发布成功！")));
        }else{
            exit(CJSON::encode(array("status"=>403,"message"=>"发布失败！")));
        }
    }

    /**
     * 一键导入活动排名
     */
    public function actionSort()
    {
        $usernames=$_POST['usernames'];
        $usernamesArr=explode(',',$usernames);
        //var_dump($usernamesArr);exit;
        $countArr=array();
        foreach($usernamesArr as $v){
            $usernameArr=explode('|',$v);
            $campaignSorts=CampaignSort::model()->findAll("username='{$usernameArr[0]}' and del=1 and periods='{$usernameArr[2]}'");
            if($campaignSorts){
               continue;
            }else{
                $campaignSort = new CampaignSort();
                $campaignSort->username=$usernameArr[0];
                $campaignSort->type=$usernameArr[3];
                $campaignSort->periods=$usernameArr[2];
                $product=Product::model()->find("pathname='{$usernameArr[3]}'");
                $num=0;
                if($product){
                    $num=ceil($usernameArr[4]/$product->price);
                }
                $campaignSort->sort=$num;
                $campaignSort->num=$num;
                $campaignSort->createtime=time();
                $countArr=$campaignSort->save();
            }

        }
        if(count($countArr)>=1){
            exit(CJSON::encode(array("status"=>200,"message"=>"导入成功！")));

        }else{
            exit(CJSON::encode(array("status"=>403,"message"=>"导入失败！")));
        }
    }
    /*
   *  一键删除用户排名
   * */
    public function actionDelAll($periods)
    {
        $campaignSorts=CampaignSort::model()->findAll("periods='{$periods}' and del=1");
        $name=Product::model()->getByKeyword($campaignSorts[0]->type);
        if(empty($campaignSorts)){
            echo "不存在的数据";
        }else{
            $arrDel=array();
            foreach($campaignSorts as $v){
                $v->del=0;
                $arrDel=$v->save();
            }
            if(count($arrDel)>=1){
                echo "删除成功";
                $this->redirect(array("product/hdorder?periods=".$periods."&name=".$name->name));
            }else{
                echo "删除失败，请联系管理员";
            }
        }
    }
    /*
  * 删除用户排名
  * */
    public function actionDel($id)
    {
        $campaignSort = new CampaignSort();
        $data = $campaignSort -> findbypk($id);
        if(empty($data)){
            echo "不存在的id";
        }else{
            $data->del=0;
            if($data->save()){
                echo "删除成功";
            }else{
                echo "删除失败，请联系管理员";
            }
        }
    }
    /*
   * 用户排名重新更新
   * */
    public function actionSortUpdate()
    {
        $sort=$_POST['sort'];
        $id=$_POST['id'];
        $campaignSort = new CampaignSort();
        $data = $campaignSort -> findbypk($id);
        if(empty($data)){
            exit(CJSON::encode(array("status"=>200,"message"=>"不存在的id！")));
        }else{
            $data->sort=$sort;
            if($data->save()){
                exit(CJSON::encode(array("status"=>200,"message"=>"更新成功！")));
            }else{
                exit(CJSON::encode(array("status"=>200,"message"=>"更新失败，请重新操作！")));
            }
        }
    }

    /**
     * 修改业务状态
     * @param string $type 业务类型
     * @param string $status 状态
     * @param string $uid fs
     * @throws CHttpException
     */
    public function actionProedit($type, $status, $uid)
    {
        if (!is_numeric($status) || !in_array($status, array('0', '1')) || empty($type)) {
            throw new CHttpException(500, '数据错误');
        }
        $transaction = Yii::app()->db->beginTransaction();

        try {
            $resource = Product::model()->findByPk($type);

            if (is_null($resource)) {
                throw new CHttpException(500, '没有此广告类型或此类型广告不允许修改');
            }
            if ($resource->auth == Product::AUTH_CLOSED) {
                throw new CHttpException(500, '此项目已关闭');
            }

            $MemberResource = MemberResource::model();
            $bindResource = $MemberResource->getBidValue($uid, $resource->pathname);

            //如果没有绑定表中没有值
            if (is_null($bindResource))
            {
                if ($MemberResource->bindMemberByType($uid, $resource->pathname, BindSample::ALLOT_AUTO))
                {
                    $bindResource = $MemberResource->getBidValue($uid, $resource->pathname);
                }
            }

            //添加LOG,更改开启状态
            MemberResourceLog::model()->add($bindResource, $status);

            $logStr = '[gainadvert] ';
            $logStr .= $status == 0 ? '[关闭]' : '[开启]';
            $logStr .= ' [type]' . $resource->pathname . ' [key]' . $bindResource->key;
            Log::memberEvent($logStr);
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollback();
            $msg = $e->getMessage();
            throw new CHttpException(500, '开启状态出现错误。' . $msg);
        }

    }

    /**
     * 业务激活量相关
     */
    public function actionActivation($date='',$date2='',$username='',$type=''){
                $start = empty($date) ? '' : $date;
                $end = empty($date2) ? '' : $date2;;
                $username = empty($username) ? '' : $username;
                $type = empty($type) ? '' : $type;

            $t = 0;
            $sql = 'SELECT b.uid,b.imeicode,b.simcode,b.mac,b.model,b.createtime FROM `app_rom_appupdata` b,(select min(id) as id from  `app_rom_appupdata` WHERE finshstatus=1 ';
            $sql1 = ' group by imeicode) a where a.id=b.id';
            if (!empty($start) && !empty($end)){
                $sql .=' and finshstatustime >= \''.$start.'\' and finshstatustime <=\''.$end.'\'';
            }

            if (!empty($username)){
                $mem = Member::model()->find('username=:username',array(':username'=>$username));
                if (empty($mem)){
                    $t = 1;
                }else{
                    $sql .= ' and uid = '.$mem['id'];
                }
            }

            if (!empty($type)){
                $sql .= ' and appname = \''.$type.'\'';
            }
            if (empty($start) || empty($end) || $t==1){
                $data = array();
            }else{
                $sq = $sql.$sql1;
                $data = Yii::app()->db->createCommand($sq)->queryAll();
            }


            if (!empty($data)){
                $array_str = serialize($data);// 序列化数组
                // 数组存缓存中
                Yii::app()->cache->set('activation_interval', $array_str,10800);
            }


        $dataProvider=new CArrayDataProvider($data, array(
            'keyField'=>'uid',
            'sort'=>array(
                'attributes'=>array(
                    'createtime',
                ),
                'defaultOrder'=>'createtime ASC',
            ),
            'pagination'=>array(
                'pageSize'=>25,
            ),
        ));
        $this->render('activation',array('dataProvider'=>$dataProvider,'start'=>$start,'end'=>$end,'username'=>$username,'type'=>$type));
    }


    /**
     * 导出Excel
     *
     */
//    public function actionExcel()
//    {
//        $cache = Yii::app()->cache->get('activation_interval');
//        // 反序列化
//        $array = unserialize($cache);
//
//        foreach ($array as $mpl) {
//            /* @var $mpl MemberPaylog */
//            $data[] = array(
//                'uid' => $mpl['uid'],
//                'username' => Member::getUsernameByMid($mpl['uid']),
//                'imeicode' => $mpl['imeicode'],
//                'simcode' => $mpl['simcode'],
//                'mac' => $mpl['mac'],
//                'model' => $mpl['model'],
//                'createtime' => $mpl['createtime'],
//            );
//        }
//        $excel = new Excel();
//        $excel->download($excel->createExcel(
//            Ad::getAdNameById(Ad::TYPE_SGTS) . date('Y-m-d'),
//            array('用户ID', '用户名', 'Imeicode', 'Sim码', 'Mac', '手机型号', '上报时间'),
//            array('uid', 'username', 'imeicode', 'simcode', 'mac', 'model', 'createtime'),
//            $data
//        ));
//    }

    /**
     * 导出成csv文件,可用excel打开
     */
    public function actionExcel()
    {
        set_time_limit(0);
        ini_set('memory_limit','1024M');
        $cache = Yii::app()->cache->get('activation_interval');
        // 反序列化
        $array = unserialize($cache);
        $str = "用户ID,用户名,Imeicode,Sim码,Mac,手机型号,上报时间\n";
        $str = iconv('utf-8','gb2312//TRANSLIT//IGNORE',$str);

        foreach ($array as $mpl) {
            /* @var $mpl MemberPaylog */
                $uid =  iconv('utf-8','gb2312//TRANSLIT//IGNORE',$mpl['uid']);
                $username = iconv('utf-8','gb2312//TRANSLIT//IGNORE',Member::getUsernameByMid($mpl['uid']));
                $imeicode = $mpl['imeicode'];
                $simcode = iconv('utf-8','gb2312//TRANSLIT//IGNORE',$mpl['simcode']);
                $mac = iconv('utf-8','gb2312//TRANSLIT//IGNORE',$mpl['mac']);
                $model = iconv('utf-8','gb2312//TRANSLIT//IGNORE',$mpl['model']);
                $createtime = iconv('utf-8','gb2312//TRANSLIT//IGNORE',$mpl['createtime']);
            $str .= $uid.",".$username.",".$imeicode.",".$simcode.",".$mac.",".$model.",".$createtime."\n";
        }
        $filename = date('Y-m-d').'.csv'; //设置文件名
        $this->export_csv($filename,$str); //导出

    }


    function export_csv($filename,$data)
    {
        header("Content-type:application/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
    }





}
