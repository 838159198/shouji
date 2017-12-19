<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/14
 * Time: 16:19
 * 产品业务
 */

class ProductController extends MemberController
{
    //压缩包文件写入内容
    public function actionTongji($uid)
    {
        $zip=new ZipArchive;
        $res=$zip->open('uploads/tongji/test.zip',ZipArchive::CREATE);
        if($res===TRUE){
            $zip->addFromString('sys.txt',$uid);
            $zip->close();
            echo 'ok';
        }else{
            echo 'failed';
        }
    }

    // 优化http://www.sutuiapp.com/member/product/index?uidt=929
    public function actionStat(){
        $Url = Yii::app()->request->url;
        $Url = str_replace('/stat','',$Url);
        Yii::app()->request->redirect($Url."?uidt=".$this->uid);
    }

    // 获取用户ID69的用户数据
    private function getAgent69($index){
            $arr = array();
            $sql = "select distinct(pid) from app_product_list where agent=69 and status=1 and isshow=1 order by `pid` DESC";
            $dataListAgent69 = ProductList::model()->findAllBySql($sql);
            foreach ($dataListAgent69 as  $vt) {
                $data = Product::model()->findAll('id=:id', array(":id" => $vt['pid']));
                if ((int)$index != 0){

                    if ((int)$index==$data[0]['category']){
                        $arr[] = $data[0];
                    }
                }else{
                    $arr[] = $data[0];
                }

            }
        return $arr;
    }



    public function actionTextAjax(){
        if (Yii::app()->request->isAjaxRequest) {
            $index = Yii::app()->request->getParam('index');
            $mm=Member::model()->findByPk($this->uid);
            if ((int)$index == 0){
                if (isset($mm['agent']) && $mm['agent']==69){
                    $data = $this::getAgent69($index);
                }else {
                    $data = Product::model()->findAll('ptype=0 and status=1 and auth=1 order by `order` DESC');
                }

            }else if ((int)$index==-1){
                $dataResource = MemberResource::model()->findAllByAttributes(array('uid'=>$this->uid,'status'=>1,'openstatus'=>1));
                $status = array('status' => false, 'keyword' => '', 'value' => '', 'closed' => '', 'typekey' => '11111');
                $dataStatus = array();
                $data =array();
                foreach ($dataResource as $vt){
                    $status["keyword"]= $vt->type;
                    $status["status"]= true;
                    $status["value"]= $vt->key;
                    $status["typekey"]= $vt->key;
                    $data1 = Product::model()->findAllByAttributes(array('ptype'=>0, 'status'=>1, 'auth'=>1, 'pathname'=>$vt->type));
                    if(empty($data1)) continue;
                    $dataStatus[$data1[0]['id']]=$status;
                    $data[]=$data1[0];
                }
            }else{

                if (isset($mm['agent']) && $mm['agent']==69){
                    $data = $this::getAgent69($index);
                }else {
                    $data = Product::model()->findAll('ptype=0 and status=1 and auth=1 and category='.$index.' order by `order` DESC');
                }

            }
                foreach ($data as  $kk=>$vt){
                    $vt['sign']=json_decode($vt['sign']);
                    if (isset($mm['agent']) && $mm['agent']==0){
                        $pinfo=ProductList::model()->find('isshow=1 and status=1 and agent=0 and pid='.$vt["id"].' order by id desc');
//                        $ppinfo = ProductList::model()->find('agent=0 and pid='.$vt["id"].' order by id desc');
                        if(empty($pinfo))
                        {
                            unset($data[$kk]);
                        }
                }
            }
            // 数组下标重新排序
            $data =array_merge($data);

            if ($index!=-1){
                $member = $this->loadModel($this->uid);
                $dataStatus = MemberResource::model()->getAdBindStatus($member, $data);
            }
            echo CJSON::encode(array('val' => $data,'val1'=>$dataStatus));
        }
    }
    // 触摸标题,弹出框
    public function actionTitlePopover(){
        if (Yii::app()->request->isAjaxRequest) {
            $num = Yii::app()->request->getParam('num');
            $dataProduct = Product::model()->findAll('id=:id', array(':id' => $num));
            $dataList = $this::getProductListData($num);
            echo CJSON::encode(array('val' => $dataProduct[0], 'valList' => $dataList));
        }
    }

    /**
     * 产品首页
     */
    public function actionIndex()
    {
        $memberResourceList = MemberResource::model()->getByUid($this->uid);
        $resourceList = Product::model()->findAll('ptype=0 and status=1 and auth=1 order by `order` DESC');
        foreach($resourceList as $rk=>$rv)
        {
            $mm=Member::model()->findByPk($this->uid);
            $member_agent=$mm['agent'];
            //$member_agent=Yii::app()->user->getState('member_agent');
            //存在代理商分组
            if(isset($member_agent))
            {
                $product_list=ProductList::model()->findAll('type=:type and status=:status and agent=:agent and isshow=1 order by id desc limit 1',array(':type'=>$rv['pathname'],':agent'=>$member_agent,':status'=>1));
                if(!empty($product_list))
                {
                    $rv['appurl']=$product_list[0]['appurl'];
                    //代替：updatetime--version，enrollment--createtime，actrule--sign
                    $rv['updatetime']=$product_list[0]['version'];
                    $rv['enrollment']=$product_list[0]['createtime'];
                    $rv['actrule']=$product_list[0]['sign'];

                }
                //分组中没有的业务类型
                else
                {
                    $rv['appurl']="";
                }
            }
            else
            {
                $product_list=ProductList::model()->findAll('type=:type and status=:status and agent=:agent and isshow=1 order by id desc limit 1',array(':type'=>$rv['pathname'],':agent'=>0,':status'=>1));
                $rv['appurl']=$product_list[0]['appurl'];
                //代替：updatetime--version，enrollment--createtime，actrule--sign
                $rv['updatetime']=$product_list[0]['version'];
                $rv['enrollment']=$product_list[0]['createtime'];
                $rv['actrule']=$product_list[0]['sign'];
            }

        }

        //查询是否有用户--统计APP记录--只操作了RomSoftpak
        $appurlmsg='点击下载';
        $uidt=Yii::app()->request->getParam('uidt');
        $appurl='';
        if(!empty($uidt))
        {
            $userapp = RomSoftpak::model()->find('uid=:uid',array(':uid'=>$uidt));
            //分配对应appid
            if(empty($userapp))
            {
                $type="tongji";
                $allot=0;
                //获得最新一条软件序列号
                $softpak=RomSoftpak::model()->getListGroupByType($type,$allot);
                if(empty($softpak))
                {
                    throw new CHttpException(500, '无可用软件供分配，请联系客服');
                }
                else
                {
                    foreach ($softpak as $spak)
                    {
                        if ($type == $spak->type)
                        {
                            $spak->status = 0;
                            $spak->uid = $uidt;
                            $spak->update();
                            $appurl=$spak->url;
                            break;
                        }
                    }
                    $this->RecordSave();
                    //分配后下载app
                    $this->redirect($appurl);
                }
            }
            //封号
            elseif($userapp['closed']==1)
            {
                $appurl='';
                throw new CHttpException(500, '此统计软件id已被封号，请联系客服');
            }
            else
            {
                $this->RecordSave();
                $appurl=$userapp['url'];
                $this->redirect($appurl);
            }
        }
        else
        {
            $userapp = RomSoftpak::model()->find('uid=:uid',array(':uid'=>$this->uid));
            //没有对应appid
            if(empty($userapp))
            {
                $appurl='';
            }
            //被封号
            elseif($userapp['closed']==1)
            {
                $appurl='';
                $appurlmsg='此统计软件id已被封号，请联系客服';
            }
            else
            {
                $appurl=$userapp['url'];
            }
        }

        $arr = $this::getCategoryName();
        //查询广告启用状态信息
        $member = $this->loadModel($this->uid);
        $resourceStatus = MemberResource::model()->getAdBindStatus($member, $resourceList);
        $prompt = Page::model()->findAll('id=:id',array(':id'=>20));
        $this->render('index', array(
//            'data' => new CArrayDataProvider($resourceList, array(
//                    'pagination' => array(
//                        'pageSize' => Common::PAGE_INFINITY,
//                    ),
//
//                )
//            ),
            'resourceList'=>$resourceList,
            'memberResourceList' => $memberResourceList,
            'resourceStatus' => $resourceStatus,
            'arr'=>$arr,
            'appurl' => $appurl,
            'appurlmsg' => $appurlmsg,
            'member' => $member,
            'prompt' => $prompt,
        ));
    }

    // 统计软件下载ajax返回
    public function actionDownload(){
        if (Yii::app()->request->isAjaxRequest) {
            $downLogo = Yii::app()->request->getParam('downLogo');
            if ((int)$downLogo == 1){
                $this->RecordSave();
            }
            echo CJSON::encode(array('val' => 'sucess'));
        }
    }
    // 软件大小
    public function actionSoftwareSize(){
        if (Yii::app()->request->isAjaxRequest) {
            $array = Yii::app()->request->getParam('array');
            $size = 0;
            foreach ($array as $vt){
                $data = $this::getProductListData($vt);
                $data['filesize'];
                $size = $size+$data['filesize'];
            }
            echo CJSON::encode(array('val' => $size));
        }
    }

    // 统计软件存入
    public function RecordSave(){
        $data = RomSoftpak::model()->findAll('uid=:uid',array(':uid'=>$this->uid));
        $admin = new RowSoftdownloadRecord();
        $admin->uid = $this->uid;
        $admin->tj_version = $data[0]['version'];
        $admin->tj_url = $data[0]['url'];
        $admin->download_time = time();
        $dataMember=Member::model()->findAll('id=:id',array(':id'=>$this->uid));
        $admin->type = $dataMember[0]['type'];
        $admin->user_ip = Yii::app()->request->userHostAddress;
        $admin->save();
    }

    // 产品下载ajax返回
    public function actionGoodDownload(){
        if (Yii::app()->request->isAjaxRequest) {
            $arr = Yii::app()->request->getParam('arr');
            $items = array();
            foreach ($arr as $vt){
                $dataP = Product::model()->findAll('id=:id',array(':id'=>$vt));
                $dataP[0]['name'] = iconv('UTF-8','GBK',$dataP[0]['name']);
                $dataList = $this::getProductListData($vt);
                $v = explode('/',$dataList['appurl']);
                $vv = explode('_',$v[3]);
                $str = str_replace($vv[0],$dataP[0]['name'],$v[3]);
                $arr1 = array();
                $arr1[0] = $dataList['appurl'];
                $arr1[1]=$str;
                $items[]=$arr1;
            }
            $zipurl = $this::getZip($items);
            if ($zipurl && $zipurl!='error'){
                foreach ($arr as $vt){
                    $data = $this::getProductListData($vt);
                    $model =  new RowSoftdownloadRecord();
                    $model->pid = $data['pid'];
                    $model->uid = $this->uid;
                    $model->sign = $data['sign'];
                    $model->download_time = time();
                    $dataMember=Member::model()->findAll('id=:id',array(':id'=>$this->uid));
                    $model->type = $dataMember[0]['type'];
                    $model->user_ip = Yii::app()->request->userHostAddress;
                    $model->save();
                }
            }
            echo CJSON::encode(array('val' => $zipurl));
        }
    }

    // 产品列表单条数据
    private function getProductListData($vt){
        $model = new ProductList();
        $criteria = new CDbCriteria();
        $criteria -> condition = "status=1";
        $criteria -> condition = "isshow=1";
        $criteria -> order = "id DESC";
        $mm=Member::model()->findByPk($this->uid);
        if (isset($mm['agent']) && $mm['agent']==69){
            $criteria ->addCondition('agent=69 and pid='.$vt.'');
        }else {
            if (($this->uid == 213) && ($vt == 38)){
                $criteria ->addCondition('agent=0 and pid='.$vt.' and sign="24489A1ACA808763534A6D1934ADEEEB"');
            }
            else if (($this->uid == 213) && ($vt == 36)){
                $criteria ->addCondition('agent=0 and pid='.$vt.' and sign="F93C1DABC9BEB853C120A701E31EA07D"');
            }
            else if (($this->uid == 1906) && ($vt == 52)){
                $criteria ->addCondition('agent=0 and pid='.$vt.' and sign="DE64041A4ABF70EA170C62A6E9A85251"');
            }
            else{
                $criteria ->addCondition('agent=0 and pid='.$vt.'');
            }
        }
        $data = $model -> find($criteria);
        return $data;
    }


    // 下载压缩文件
    private function getZip($items)
    {
        set_time_limit(0);
        $zip = new ZipArchive;
        $zipname =date('YmdHis',time());
        // 删除前天下载的压缩包文件
        $prefilename = date("Ymd",strtotime("-2 day"));
/*        $prefile = getcwd().'/uploads/zip/'.$prefilename;
        $prefile = str_replace('\\','/',$prefile);*/

        $prefileall = getcwd().'/uploads/zip/';
        $filenamearr=scandir($prefileall);
        foreach ($filenamearr as $filename){
            if( $filename!='.' && $filename!='..' && (int)$filename<(int)$prefilename){
                $filename = getcwd().'/uploads/zip/'.$filename;
                $filename = str_replace('\\','/',$filename);
                $this::delezipfile($filename);
            }
        }

        // 创建今天的压缩包文件
        $filename = date('Ymd',time());
        $filepath = getcwd().'/uploads/zip/'.$filename;
        $filepath = str_replace('\\','/',$filepath);
        if (!file_exists($filepath)){
            mkdir ($filepath, 0777);
        }
        $path =getcwd().'/uploads/zip/'.$filename.'/'.$zipname.'.zip';
        $path = str_replace('\\','/',$path);
        if (!file_exists($path)) {
            $bool= $zip->open($path,ZipArchive::CREATE);//创建一个空的zip文件
            if ($bool){
                foreach ($items as $k=>$vt) {
                    $zip->addFile( getcwd().$vt[0],$vt[1]);
                }
            }else{
                $path = 'error';
            }
            $zip->close();
            return $path;
        }else{
            $path = 'error';
            return $path;
        }
    }
    /*
     * name:删除压缩包
     * */
    private function delezipfile($prefile){
        // 判断文件是否存在
        if (file_exists($prefile)){
            // 判断是不是文件夹
            if(is_dir($prefile)){
                $file_list= scandir($prefile);
                foreach ($file_list as $file)
                {
                    if( $file!='.' && $file!='..')
                    {
                        @unlink($prefile.'/'.$file);
                    }
                }
                @rmdir($prefile);  //这种方法不用判
            }
        }
    }
    /*
     * name:类别名数组处理
     * */
    private function getCategoryName(){
        $dataCateName = ProductCategoryname::model()->findAll();
        $arr = array();
        $arr[0]="全部";
        foreach ($dataCateName as $vt){
            $arr[$vt['id']] = $vt['category_name'];
        }
        $arr[-1]="我已开启业务";
        ksort($arr);
        return $arr;
    }

    /**
     * 产品首页--VIP产品区
     */
    public function actionNewIndex()
    {
        $memberResourceList = MemberResource::model()->getByUid($this->uid);
        $resourceList = Product::model()->findAll('ptype=1 and status=1 and auth=1 order by `order` DESC');
        foreach($resourceList as $rk=>$rv)
        {
            $mm=Member::model()->findByPk($this->uid);
            $member_agent=$mm['agent'];
            //$member_agent=Yii::app()->user->getState('member_agent');
            //存在代理商分组
            if(isset($member_agent))
            {
                $product_list=ProductList::model()->findAll('type=:type and status=:status and agent=:agent and isshow=1 order by id desc limit 1',array(':type'=>$rv['pathname'],':agent'=>$member_agent,':status'=>1));
                if(!empty($product_list))
                {
                    $rv['appurl']=$product_list[0]['appurl'];
                    //代替：updatetime--version，enrollment--createtime，actrule--sign
                    $rv['updatetime']=$product_list[0]['version'];
                    $rv['enrollment']=$product_list[0]['createtime'];
                    $rv['actrule']=$product_list[0]['sign'];
                }
                //分组中没有的业务类型
                else
                {
                    $rv['appurl']="";
                }
            }
            else
            {
                $product_list=ProductList::model()->findAll('type=:type and status=:status and agent=:agent and isshow=1 order by id desc limit 1',array(':type'=>$rv['pathname'],':agent'=>0,':status'=>1));
                $rv['appurl']=$product_list[0]['appurl'];
                //代替：updatetime--version，enrollment--createtime，actrule--sign
                $rv['updatetime']=$product_list[0]['version'];
                $rv['enrollment']=$product_list[0]['createtime'];
                $rv['actrule']=$product_list[0]['sign'];
            }

        }

        //查询是否有用户--统计APP记录--只操作了RomSoftpak
        $appurlmsg='点击下载';
        $uidt=Yii::app()->request->getParam('uidt');
        $appurl='';
        if(isset($uidt))
        {
            $userapp = RomSoftpak::model()->find('uid=:uid',array(':uid'=>$uidt));
            //分配对应appid
            if(empty($userapp))
            {
                $type="tongji";
                $allot=0;
                //获得最新一条软件序列号
                $softpak=RomSoftpak::model()->getListGroupByType($type,$allot);
                if(empty($softpak))
                {
                    throw new CHttpException(500, '无可用软件供分配，请联系客服');
                }
                else
                {
                    foreach ($softpak as $spak)
                    {
                        if ($type == $spak->type)
                        {
                            $spak->status = 0;
                            $spak->uid = $uidt;
                            $spak->update();
                            $appurl=$spak->url;
                            break;
                        }
                    }
                    //分配后下载app
                    $this->redirect($appurl);
                    exit;
                }
            }
            //封号
            elseif($userapp['closed']==1)
            {
                $appurl='';
                throw new CHttpException(500, '此统计软件id已被封号，请联系客服');
            }
            else
            {
                $appurl=$userapp['url'];
            }
        }
        else
        {
            $userapp = RomSoftpak::model()->find('uid=:uid',array(':uid'=>$this->uid));
            //没有对应appid
            if(empty($userapp))
            {
                $appurl='';
            }
            //被封号
            elseif($userapp['closed']==1)
            {
                $appurl='';
                $appurlmsg='此统计软件id已被封号，请联系客服';
            }
            else
            {
                $appurl=$userapp['url'];
            }
        }



        //查询广告启用状态信息
        $member = $this->loadModel($this->uid);
        $resourceStatus = MemberResource::model()->getAdBindStatus($member, $resourceList);

        $this->render('newindex', array(
            'data' => new CArrayDataProvider($resourceList, array(
                    'pagination' => array(
                        'pageSize' => Common::PAGE_INFINITY,
                    ),
                    'sort'=>array(
                        'defaultOrder'=>'id DESC',
                    ),
                )
            ),
            'memberResourceList' => $memberResourceList,
            'resourceStatus' => $resourceStatus,
            'appurl' => $appurl,
            'appurlmsg' => $appurlmsg,
            'member' => $member
        ));
    }
    /**
     * 产品首页--活动区
     */
    public function actionCampaignIndex()
    {
        $memberResourceList = MemberResource::model()->getByUid($this->uid);

        //查询是否有用户--统计APP记录--只操作了RomSoftpak
        $appurlmsg='点击下载';
        $uidt=Yii::app()->request->getParam('uidt');
        $appurl='';
        if(!empty($uidt))
        {
            $userapp = RomSoftpak::model()->find('uid=:uid',array(':uid'=>$uidt));
            //分配对应appid
            if(empty($userapp))
            {
                $type="tongji";
                $allot=0;
                //获得最新一条软件序列号
                $softpak=RomSoftpak::model()->getListGroupByType($type,$allot);
                if(empty($softpak))
                {
                    throw new CHttpException(500, '无可用软件供分配，请联系客服');
                }
                else
                {
                    foreach ($softpak as $spak)
                    {
                        if ($type == $spak->type)
                        {
                            $spak->status = 0;
                            $spak->uid = $uidt;
                            $spak->update();
                            $appurl=$spak->url;
                            break;
                        }
                    }
                    //分配后下载app
                    $this->redirect($appurl);
                    exit;
                }
            }
            //封号
            elseif($userapp['closed']==1)
            {
                $appurl='';
                throw new CHttpException(500, '此统计软件id已被封号，请联系客服');
            }
            else
            {
                $appurl=$userapp['url'];
            }
        }
        else
        {
            $userapp = RomSoftpak::model()->find('uid=:uid',array(':uid'=>$this->uid));
            //没有对应appid
            if(empty($userapp))
            {
                $appurl='';
            }
            //被封号
            elseif($userapp['closed']==1)
            {
                $appurl='';
                $appurlmsg='此统计软件id已被封号，请联系客服';
            }
            else
            {
                $appurl=$userapp['url'];
            }
        }

        //活动
        $campaigntt=Campaign::model()->findAll(array('condition'=>'status=1','order' => 'id DESC'));
        $campaign = array();
        foreach($campaigntt as $t)
        {
            $campaign[$t->id] = $t->attributes;
        }

        $resourceLists=array();
        foreach($campaign as $ckey=>$cval)
        {
            $pro=Product::model()->findByPk($cval["pid"]);
            $campaign[$ckey]["createtime"]=$pro["name"];

            $resourceList = Product::model()->findAll('id='.$cval["pid"]);
            foreach($resourceList as $rk=>$rv)
            {
                $campaign[$ckey]["pname"]=$rv["name"];
                $campaign[$ckey]["pauth"]=$rv["auth"];
                $campaign[$ckey]["ppic"]=$rv["pic"];
                $campaign[$ckey]["pprice"]=$rv["price"];
                $campaign[$ckey]["punder_instructions"]=$rv["under_instructions"];
                $campaign[$ckey]["pcontent"]=$rv["content"];
                $campaign[$ckey]["pinstall_instructions"]=$rv["install_instructions"];

                $campaign[$ckey]["pid"]=$rv["id"];
                $campaign[$ckey]["ppathname"]=$rv["pathname"];




                $mm=Member::model()->findByPk($this->uid);
                $member_agent=$mm['agent'];
                //$member_agent=Yii::app()->user->getState('member_agent');
                //存在代理商分组
                if(!empty($member_agent))
                {
                    $product_list=ProductList::model()->findAll('type=:type and status=:status and agent=:agent and isshow=1 order by id desc limit 1',array(':type'=>$rv['pathname'],':agent'=>$member_agent,':status'=>1));
                    if(!empty($product_list))
                    {
                        $rv['appurl']=$product_list[0]['appurl'];
                        //代替：updatetime--version，enrollment--createtime，actrule--sign
                        $rv['updatetime']=$product_list[0]['version'];
                        $rv['enrollment']=$product_list[0]['createtime'];
                        $rv['actrule']=$product_list[0]['sign'];

                        $campaign[$ckey]["pappurl"]=$product_list[0]["appurl"];
                        $campaign[$ckey]["pversion"]=$product_list[0]["version"];
                        $campaign[$ckey]["penrollment"]=$product_list[0]["createtime"];
                        $campaign[$ckey]["pactrule"]=$product_list[0]["sign"];
                    }
                    //分组中没有的业务类型
                    else
                    {
                        $rv['appurl']="";
                    }
                }
                else
                {
                    $product_list=ProductList::model()->findAll('type=:type and status=:status and agent=:agent and isshow=1 order by id desc limit 1',array(':type'=>$rv['pathname'],':agent'=>0,':status'=>1));
                    $rv['appurl']=$product_list[0]['appurl'];
                    //代替：updatetime--version，enrollment--createtime，actrule--sign
                    $rv['updatetime']=$product_list[0]['version'];
                    $rv['enrollment']=$product_list[0]['createtime'];
                    $rv['actrule']=$product_list[0]['sign'];

                    $campaign[$ckey]["pappurl"]=$product_list[0]["appurl"];
                    $campaign[$ckey]["pversion"]=$product_list[0]["version"];
                    $campaign[$ckey]["penrollment"]=$product_list[0]["createtime"];
                    $campaign[$ckey]["pactrule"]=$product_list[0]["sign"];
                }

            }

            $resourceLists[$ckey]=$resourceList;



            $user = Yii::app()->user;
            $uids=$user->getState('member_uid');
            $result=CampaignLog::model()->find('cid ='. $cval["periods"].' and uid = '. $uids);
            if(!empty($result))
            {
                if($result["status"]==0)
                {
                    $campaign[$ckey]["temp"]="未审核";
                }
                elseif($result["status"]==1)
                {
                    $campaign[$ckey]["temp"]="审核通过";
                }
                elseif($result["status"]==2)
                {
                    $campaign[$ckey]["temp"]="拒绝:".$result["bak"];
                }

            }
            else
            {
                $campaign[$ckey]["temp"]='<a class="btn btn-danger" href="/member/campaignLog/commit?id='.$cval["periods"].'">马上报名</a>';
            }
        }
        $resourceLists=array_values($resourceLists);
        $resourceListtemp=array();
        foreach($resourceLists as $rek=>$rev)
        {
            $resourceListtemp[]=$rev[0];
        }
        $resourceList=$resourceListtemp;
        //print_r($resourceListtemp);exit;
        $campaign=array_values($campaign);

        //print_r($campaign);exit;

        //查询广告启用状态信息
        $member = $this->loadModel($this->uid);
        $resourceStatus = MemberResource::model()->getAdBindStatus($member, $resourceList);
        $data=$campaign;
        $this->render('campaignindex', array(
            'data' => new CArrayDataProvider($data, array(
                    'pagination' => array(
                        'pageSize' => Common::PAGE_INFINITY,
                    ),
                    'sort'=>array(
                        'defaultOrder'=>'id DESC',
                    ),
                )
            ),
            'memberResourceList' => $memberResourceList,
            'resourceStatus' => $resourceStatus,
            'appurl' => $appurl,
            'appurlmsg' => $appurlmsg,
            'member' => $member
        ));
    }
    /**
     * 修改广告状态
     * @param string $type 广告类型
     * @param string $status 状态
     * @throws CHttpException
     */
    public function actionEdit()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $type = Yii::app()->request->getParam('type');
            $status = Yii::app()->request->getParam('status');
            $campaignType = $type;
            if (strstr($type,'campaign')){
                $type = str_replace('campaign','',$type);
            }
            if (!is_numeric($status) || !in_array($status, array('0', '1')) || empty($type)) {
                echo CJSON::encode(array('val' => '0'));
                return;
                //throw new CHttpException(500, '数据错误');
            }
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $resource = Product::model()->getByKeyword($type);
                if (is_null($resource)) {
                    echo CJSON::encode(array('val' => '1'));
                    return;
                    //throw new CHttpException(500, '没有此广告类型或此类型广告不允许修改');
                }
                if ($resource->auth == Product::AUTH_CLOSED) {
                    echo CJSON::encode(array('val' => '2'));
                    return;
//                throw new CHttpException(500, '此项目已关闭');
                }

                $MemberResource = MemberResource::model();
                $bindResource = $MemberResource->getBidValue($this->uid, $resource->pathname);

                //如果没有绑定表中没有值
                if (is_null($bindResource)) {

                    if ($resource->auth == Product::AUTH_MANAGE) {

                        //管理员登录可开启默认为管理员/客服权限的业务
                        if(Yii::app()->user->getState("member_manage")==true)
                        {

                            if ($MemberResource->bindMemberByType($this->uid, $type, BindSample::ALLOT_AUTO)) {
                                $bindResource = $MemberResource->getBidValue($this->uid, $resource->pathname);
                            }
                        }
                        else
                        {
                            echo CJSON::encode(array('val' => '3'));
                            return;
//                        throw new CHttpException(500, '此项目只有客服可以开启');
                        }

                    }
                    $typev=Product::model()->find('pathname=:pathname',array(':pathname'=>$type));

                    //
                    if($typev["auth"]==0 && $typev["status"]==1)
                    {
                        if ($MemberResource->bindMemberByType($this->uid, $type, BindSample::ALLOT_AUTO)) {
                            $bindResource = $MemberResource->getBidValue($this->uid, $resource->pathname);
                        }
                    }
                }

                if ($bindResource == null) {
                    echo CJSON::encode(array('val' => '4'));
                    return;
//                throw new Exception('该业务已没有可使用的ID，请联系客服');
                }
                //添加LOG,更改开启状态
                MemberResourceLog::model()->add($bindResource, $status);

                $logStr = '[gainadvert] ';
                $logStr .= $status == 0 ? '[关闭]' : '[开启]';
                $logStr .= ' [type]' . $type . ' [key]' . $bindResource->key;
                Log::memberEvent($logStr);
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
                $msg = $e->getMessage();
                echo CJSON::encode(array('val' => '5'));
                return;
//            throw new CHttpException(500, '修改状态出现错误。' . $msg);
            }
            $user = Yii::app()->user->getState("member_manage");
            if ($user && strstr($campaignType, "campaign")) {
                $minfo = Member::model()->findByPk($this->uid);
                //echo "<script language=\"javascript\">windows.open('http://wpa.qq.com/msgrd?v=3&uin=2139896304&site=qq&menu=yes')</script>";
                //echo "<script language='javascript' type='text/javascript'>window.open('http://wpa.qq.com/msgrd?v=3&uin=".$minfo["tel"]."&site=qq&menu=yes');</script>";
                echo CJSON::encode(array('val' => '6'));
                //$this->redirect('http://wpa.qq.com/msgrd?v=3&uin=2139896304&site=qq&menu=yes');
            } elseif (strstr($campaignType, "campaign")) {
                echo CJSON::encode(array('val' => '6'));
            } else {
                echo CJSON::encode(array('val' => '6'));
            }
        }
    }

    /**
     * @param $id
     * @return Member|null
     */
    private function loadModel($id)
    {
        $member = Member::model()->getById($id);
        if (empty($member->alias)) {
            $member->alias = Common::createTempPassword($member->username);
            $member->update();
        }
        return $member;
    }


}