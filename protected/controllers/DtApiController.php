<?php
/**
 * 新地推api接口文件
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/20
 * Time: 11:22
 */
class DtApiController extends Controller
{
    const KEY = "53E766A1A124F5D7390C211E092986EP";

    /**
     * pc助手一程序接口
     *  $statid 统计id
     * @datetime:2017-2-6 10:32:11
     * */
    public function actionPcHelper(){
        $request = Yii::app()->request;
        $statid=$request->getQuery('statid', '');
        if($statid==""){
            $this->ReturnError('400',"参数不得为空");
        }
        $romSoftpak=RomSoftpak::model()->find("serial_number=:serial_number",array(":serial_number"=>$statid));
        if($romSoftpak){
            $uid=$romSoftpak->uid;//存在用户
            $memberR=MemberResource::model()->findAll("uid=:uid and status=:status and openstatus=:openstatus and is_put=:is_put",array(":uid"=>$uid,":status"=>1,":openstatus"=>1,"is_put"=>1));
            if($memberR){
                foreach($memberR as $v){
                    $product=Product::model()->getByKeyword($v->type);
                    $a[]=$product->id;
                }
            }
            $default_app=Product::model()->findAll("default_app=:default_app",array(":default_app"=>1));
            if($default_app){
                foreach($default_app as $v){
                    $b[]=$v->id;
                }
            }
            $data['appid']= array_flip($a)+array_flip($b);
            $data['appid'] = array_flip( $data['appid']);
            $boxdesk=Boxdesk::model()->find("tid=:tid and status=:status",array(":tid"=>$statid,":status"=>0));//0-分配
            $data['launcher']= array("name"=>$boxdesk->filename,'md5'=>strtolower($boxdesk->md5),'downPath'=>"http://df.sutuiapp.com". $boxdesk->downloadurl);

            //判断用户是否有单独定做的autorunner.jar包
            $box=Boxdata::model()->find("uid=:uid and classify=:classify",array(":uid"=>$uid,":classify"=>"StFlashTool2"));
            if($box){
                 //用户单独定制
                $data['autorunner']= array("name"=>$box->name,'md5'=>strtolower($box->md5),'downPath'=>"http://df.sutuiapp.com". $box->downPath);
            }else{
                $boxdata=Boxdata::model()->find("uid=:uid and name=:name",array(":uid"=>0,":name"=>'autorunner.jar'));//获取默认的autorunner.jar
                $data['autorunner']= array("name"=>$boxdata->name,'md5'=>strtolower($boxdata->md5),'downPath'=>"http://df.sutuiapp.com". $boxdata->downPath);
            }
            $php_json = json_encode($data); //把php数组格式转换成 json 格式的数据
            echo $php_json;
        }else{
            $this->ReturnError('400',"统计id错误");
        }

    }
    /**
     * 安卓盒子桌面统一程序接口  ------目前没用
     * @method: GET
     * @param box_no 设备码
     * @datetime:2017-03-16 10:32:11
     * */
    public function actionBoxDesk(){
        $request = Yii::app()->request;
        $box_no=$request->getQuery('id', '');
        if($box_no==""){
            $this->ReturnError('400',"参数不得为空");
        }
        $data['time']=date("Y-m-d H:i:s",time());
        $softbox=Softbox::model()->find("box_number=:box_no",array(":box_no"=>$box_no));
        if($softbox){
            $uid=$softbox->uid;//存在用户
            $apps=$this->DeskappUpdate($box_no);//获取更新的app

            $desk=$this->deskApp($box_no);//获取盒子桌面
            //判断用户是否有单独定做的autorunner.jar包
            $box=Boxdata::model()->find("uid=:uid and classify=:classify",array(":uid"=>$uid,":classify"=>"StFlashTool2"));
            if($box){
                $criteria = new CDbCriteria();
                $criteria->addInCondition('id',array(3,$box->id));
                $boxdata = Boxdata::model()->findAll($criteria);                    //用户单独定制
            }else{
                $criteria =new CDbCriteria;
                $criteria->addCondition("uid=0"); //查询条件，即where id =1
                $criteria->addNotInCondition('id',array(1,2));//与上面正好相法，是NOT IN
                $boxdata = Boxdata::model()->findAll($criteria); //获取默认的StFlashTool2，StFlashTool，autorunner.jar
            }
            foreach($boxdata as $v){
                if($v->name=='apktools.apk'){
                    $data['mainPro'][] = array("name"=>$v->name,'md5'=>strtolower($v->md5),'version'=>$v->version,'downPath'=>"http://df.sutuiapp.com". $v->downPath);
                }else{
                    $data['mainPro'][] = array("name"=>$v->name,'md5'=>strtolower($v->md5),'downPath'=>"http://df.sutuiapp.com". $v->downPath);
                }

            }

            $arr=array_merge_recursive($data,$desk);//将两个数组合并
            $data=array_merge_recursive($arr,$apps);

            $php_json = json_encode($data); //把php数组格式转换成 json 格式的数据
            echo $php_json;
        }else{
            $this->ReturnError('400',"设备码绑定错误");
        }

    }

    /**
     * 获取盒子桌面
     * @param string $box_no 设备码
     * @datetime:2017-03-16 10:32:11
     * @return
     * */
    private function deskApp($box_no){
        $softbox=Softbox::model()->find("box_number=:box_no",array(":box_no"=>$box_no));
        if($softbox){
            $uid=$softbox->uid;//存在用户
            $boxdesk=Boxdesk::model()->find("uid=:uid and status=:status",array(":uid"=>$uid,":status"=>0));//0-分配
            if($boxdesk){
                $data = array();
                $data['mainPro'][] = array("name"=>$boxdesk->filename,'md5'=>strtolower($boxdesk->md5),'version'=>$boxdesk->version,'downPath'=>"http://df.sutuiapp.com". $boxdesk->downloadurl);
                //print_r($data);
            }else{
                $this->ReturnError('400',"该用户不存在桌面应用");
            }
            $data['available']=array("status"=>$softbox['status']);
        }else{
            $this->ReturnError('400',"设备码绑定错误");
        }

        return $data;
    }

    /**
     * 获取盒子应用程序更新
     * @param $box_no设备码
     * @datetime:2016-12-16 11:32:11
     * */
    private function DeskappUpdate($box_no){
        $softbox=Softbox::model()->find("box_number=:box_no",array(":box_no"=>$box_no));
        if($softbox){
            $uid=$softbox->uid;//存在用户
            $member=Member::model()->findByPk($uid);
            $agent=$member->agent;
            $memberR=MemberResource::model()->findAll("uid=:uid and status=:status and openstatus=:openstatus and is_put=:is_put",array(":uid"=>$uid,":status"=>1,":openstatus"=>1,"is_put"=>1));
            if($memberR){
                $data = array();
                foreach($memberR as $k=> $v){
                    $productList = ProductList::model()->find(array(
                        'select'=>array('pid','appurl','sign','type'),
                        'order' => 'id DESC',
                        'condition' => 'status=:status AND agent=:agent AND type=:type',
                        'params' => array(':status'=>1,':agent' => $agent,':type'=>$v->type),
                        'limit'=>1
                    ));
                    if($productList){
                        //echo $productList["pid"]."|".$productList["sign"]."|http://df.sutuiapp.com".$productList["appurl"]."<br>";
                        $data['apps'][]=array("name"=>$productList->pid,"md5"=>strtolower($productList->sign),"downPath"=>"http://df.sutuiapp.com".$productList->appurl);
                    }

                }
                //客户自己的app应用不属于咱平台
                $boxdatas=Boxdata::model()->findAll("uid=:uid and classify=:classify ",array(":uid"=>$uid,":classify"=>"otherapk"));
                if($boxdatas){
                    foreach($boxdatas as $boxdata){
                        $data['apps'][]=array("name"=>$boxdata->tid,"md5"=>strtolower($boxdata->md5),"downPath"=>"http://df.sutuiapp.com".$boxdata->downPath);
                    }
                }


            }else{
                $this->ReturnError('400',"没有投放的产品");
            }
        }else{
            $this->ReturnError('400',"设备码绑定错误");
        }
        return $data;
    }
    /**************************************************以下代码为新接口盒子套餐，PC版 以及数据上报*****************************************************************/
    /**
     * 盒子套餐一程序接口
     * @method: GET
     * @param box_no 设备码
     * @datetime:2016-12-16 10:32:11
     * */
    public function actionBoxPackage(){
        $request = Yii::app()->request;
        $box_no=$request->getQuery('id', '');
        $time=$request->getQuery('time', '');
        $token=$request->getQuery('token', '');
        if($token=="" || $time=="" ||$box_no==""){
            $this->ReturnError('400',"参数不得为空");
        }
        //加密判断
        $token1=$this->createToken($time);
        if($token !=$token1){
            $this->ReturnError(400,"token不匹配");
        }
        $softbox=Softbox::model()->find("box_number=:box_no",array(":box_no"=>$box_no));
        if($softbox){
            $uid=$softbox['uid'];
            $member=Member::model()->findByPk($uid);
            $agent=$member->agent;
            $type=$member->type;
            $launcher_install=$member->launcher_install;
            $data=$this->deskApp($box_no);//获取盒子桌面
            //判断用户是否有单独定做的autorunner.jar包
            $box=Boxdata::model()->find("uid=:uid and classify=:classify",array(":uid"=>$uid,":classify"=>"StFlashTool2"));
            if($box){
                $criteria = new CDbCriteria();
                $criteria->addInCondition('id',array(1,2,3,$box->id));
                $boxdata = Boxdata::model()->findAll($criteria);                    //用户单独定制
            }else{
                $boxdata=Boxdata::model()->findAll("uid=:uid",array(":uid"=>0));//获取默认的StFlashTool2，StFlashTool，autorunner.jar
            }
            foreach($boxdata as $v){
                $data['mainPro'][] = array("name"=>$v->name,'md5'=>strtolower($v->md5),'downPath'=>"http://df.sutuiapp.com". $v->downPath);
            }

            $tongji_type=Common::getUserType($type);
            //单独统计
            $userapp = RomSoftpak::model()->find('uid=:uid and type=:type',array(':uid'=>$uid,":type"=>$tongji_type));
            if(isset($userapp)){
                $data['mainPro'][]= array("name"=>'stat.apk','md5'=>strtolower($userapp->md5),'downPath'=>"http://df.sutuiapp.com". $userapp->url);
            }

            $pids=$this->getPackageByBoxno($box_no);//获取套餐列表
            $data['sign'] = $pids[1];
            $data['tc_id'] = $pids[2];
            //判断用户安装桌面还是只有统计
            $data['launcher_install'] =$launcher_install;
            $pids=json_decode($pids[0]);
            foreach($pids as $v){
                $productList = ProductList::model()->find(array(
                    'select'=>array('appurl','sign','type','pakname'),
                    'order' => 'id DESC',
                    'condition' => 'status=:status AND agent=:agent AND pid=:pid',
                    'params' => array(':status'=>1,':agent' => $agent,':pid'=>$v),
                    'limit'=>1
                ));
                $product=Product::model()->findByPk($v);
                if($productList){
                    $data['apps'][]=array("name"=>$v,"md5"=>strtolower($productList->sign),"downPath"=>"http://df.sutuiapp.com".$productList->appurl,
                        "app_name"=>$product->name,"pkg_name"=>$productList->pakname
                    );
                }
            }
            //96分组独有
            $boxs=Boxdata::model()->find("uid=:uid and classify=:classify",array(":uid"=>$uid,":classify"=>"apklist"));
            if($agent==96 && isset($boxs)){
                $data['apps'][]=array("name"=>'apk_list',"md5"=>strtolower($boxs->md5),"downPath"=>"http://df.sutuiapp.com".$boxs->downPath);
            }
        }else{
            $this->ReturnError('400',"该设备码错误");
        }

        $php_json = json_encode($data); //把php数组格式转换成 json 格式的数据
        echo $php_json;
    }


    /**
     * 获取盒子应用套餐
     * @param $box_no设备码
     * @datetime:2016-2-20 13:01:11
     * */
    private function getPackageByBoxno($box_no){
        $softbox=Softbox::model()->find("box_number=:box_no",array(":box_no"=>$box_no));
        if($softbox){
            if($softbox['status']==0)  $this->ReturnError('400',"该盒子已被禁用");
            $boxPackage=RomBoxPackage::model()->find("box_number=:box_no",array(":box_no"=>$box_no));
            if($boxPackage){
                $pack_id=$boxPackage['pack_id'];
                $package=RomPackage::model()->findByPk($pack_id);
                $pids[0]=$package['pid'];
                $pids[1]=$package['sign'];
                $pids[2]=$pack_id;
            }else{
                $this->ReturnError('400',"该盒子未绑定套餐");
            }
        }else{
            $this->ReturnError('400',"该设备码错误");
        }
        return $pids;
    }
    /**
     * PC助手：套餐接口 type=3
     * @return string 所有套餐
     * */
    public  function actionPcPackage(){
        $request = Yii::app()->request;
        $uid=$request->getQuery('uid', '');
        if($uid==""){
            $this->ReturnError('400',"参数不得为空");
        }
        $member=Member::model()->findByPk($uid);

        if(!$member){
            $this->ReturnError('400',"不存在该用户");
        }

  //      $time=$request->getQuery('time', '');
 //       $token=$request->getQuery('token', '');
//        if($token=="" || $time==""){
//            $this->ReturnError('400',"参数不得为空");
//        }
//        //加密判断
//        $token1=$this->createToken($time);
//        if($token !=$token1){
//            $this->ReturnError(400,"token不匹配");
//        }
        $subagent=$member->subagent;

        $db = new CDbCriteria();
        if($subagent ==707){
            //给707单独分配套餐
            $db->addInCondition('uid', array(707));
        }else{
            $db->addInCondition('uid', array(0,$uid));
        }
       
        $package=RomPackage::model()->findAll($db);
        if($package){
            foreach($package as $pack){
                if($pack->type==3){
                    $arr=json_decode($pack->pid);
                    $str=implode(',',$arr);
                    $data['package'][]=array("name"=>$pack->package_name,"filesize"=>$pack->filesize,"num"=>$pack->num,"pid"=>$str,"sign"=>$pack->sign,"tc_id"=>$pack->id);
                }

            }

        }else{
            $this->ReturnError('400',"无可用套餐");
        }
        $php_json = json_encode($data); //把php数组格式转换成 json 格式的数据
        echo $php_json;
    }
    /**
     * 上报手机客户端数据
     * @datetime: 2016-4-12 13:20:34
     * @method: POST
     * @param uid 用户id
     * @param token 固定字符串
     * @param appIds 业务id
     * @param imeicode 手机imei码
     * @param simcode sim卡
     * @param model 手机型号
     * @param brand 品牌
     * @param sys andriod系统
     * @param ip 手机ip
     * @param tc 套餐标记tc，0自定义1平台
     * @param tc_id 套餐id
     * */
    public function actionAdd(){
        $request = Yii::app()->request;
        if(!$request->isPostRequest){
            $data=array("status_code"=>400,"status_message"=>"请求不存在");
            $time=time();
            $content = "[Log Time] ".date("Y-m-d H:i:s",$time)."\r";
            $content .= "[错误信息] ";
            foreach ($data AS $k => $v){
                $content .= "[".$k."] ".$v." ";
            }
            $content .= "\n\r";
            $file  = "log/pc_clientdata_".date("Y-m-d",$time).'_log.txt';//要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
            if(file_put_contents($file, $content,FILE_APPEND)){// 这个函数支持版本(PHP 5)
                //echo "写入成功。<br />";
            }
            $this->ReturnError(400,"请求不存在");
        }
        $hid = $request->getPost('hid','');
        $uid = $request->getPost('uid','');
        $token = $request->getPost('token','');
        $times = $request->getPost('time','');
        $appIds = $request->getPost('type','');
        $imeicode = $request->getPost('imeicode','');
        $simcode = $request->getPost('simcode','');
        $model = trim($request->getPost('model',''));
        $brand = $request->getPost('brand','');
        $sys = $request->getPost('sys','');
        $tc = $request->getPost('tc','');
        $tc_id = $request->getPost('tc_id','');
        $pcIp = Common::getIp();
        $from=9;//c++上报
        $token1=$this->createToken($times);

        //通过盒子id获取uid
        if(empty($uid) && !empty($hid)){
            $softbox=Softbox::model()->find("box_number=:box_no",array(":box_no"=>$hid));
            if($softbox){
                $uid=$softbox['uid'];
            }else{
                $data=array("status_code"=>400,"status_message"=>"盒子编码错误");
                $this->saveUserAddLog($uid,$appIds,$imeicode,$simcode,$model,$sys,$tc,$brand,$hid,$pcIp,$tc_id,$times,$token,$data);
                $this->ReturnError(400,"盒子编码错误");
            }
        }
        if($uid==""|| $token=="" || $appIds=="" || $imeicode=="" || $brand=="" || $sys=="" || $tc==""){
            $data=array("status_code"=>400,"status_message"=>"请求数据出现空字符串");
            $this->saveUserAddLog($uid,$appIds,$imeicode,$simcode,$model,$sys,$tc,$brand,$hid,$pcIp,$tc_id,$times,$token,$data);
            $this->ReturnError(400,"请求数据出现空字符串");
        }
        //获取用户数据
        $userData = $this->getUserData($uid);
        if(!isset($userData)){
            $data=array("status_code"=>400,"status_message"=>"用户信息错误");
            $this->saveUserAddLog($uid,$appIds,$imeicode,$simcode,$model,$sys,$tc,$brand,$hid,$pcIp,$tc_id,$times,$token,$data);
            $this->ReturnError(400,"用户信息错误");
        }
        //获取用户套餐数据
        if($tc ==1){
            $packageData =RomPackage::model()->findByPk($tc_id);
            if(!isset($packageData)){
                $data=array("status_code"=>400,"status_message"=>"用户套餐信息错误");
                $this->saveUserAddLog($uid,$appIds,$imeicode,$simcode,$model,$sys,$tc,$brand,$hid,$pcIp,$tc_id,$times,$token,$data);
                $this->ReturnError(400,"用户套餐信息错误");
            }
        }
        if($token !=$token1){
            $data=array("status_code"=>400,"status_message"=>"token不匹配");
            $this->saveUserAddLog($uid,$appIds,$imeicode,$simcode,$model,$sys,$tc,$brand,$hid,$pcIp,$tc_id,$times,$token,$data);
            $this->ReturnError(400,"token不匹配");
        }

        $appArr=explode('|',$appIds);
        //var_dump($appArr);exit;
        //循环每个业务pid
        $count=0;
        $m=0;
        foreach($appArr as $appId){
            //查询业务是否可用
            $model_product = Product::model()->findByPk($appId);
            if(!$model_product || $model_product['status']!=1){
                $data=array("status_code"=>400,"status_message"=>"此业务已禁用或不存在");
                $this->saveUserAddLog($uid,$appIds,$imeicode,$simcode,$model,$sys,$tc,$brand,$hid,$pcIp,$tc_id,$times,$token,$data);
                $m++;
               continue;
            }
            //开启业务 ；openstatus开启状态
            Common::resourceOpen($uid,$model_product['pathname']);
            //上报到rom_appresource
            $useresource=MemberResource::model()->find('uid=:uid AND type=:type AND status=1',array('uid'=>$uid,":type"=>$model_product['pathname']));
            //开启业务
            if(!empty($useresource))
            {
                //黑名单判断
                $sql="select id from `app_blacklist` where imeicode='{$imeicode}'";
                $result=yii::app()->db->createCommand($sql)->queryAll();
                $romstatus=RomAppresource::model()->find('type=:type and imeicode=:imeicode',array(':type'=>$model_product['pathname'],':imeicode'=>$imeicode));
                $before_uid=!empty($romstatus) ? $romstatus->uid : '';
                //判断已安装
                if(!empty($romstatus))
                {
                    /*//已激活或是已封号
                    if($romstatus->status==0)
                    {
                        $data=array("status_code"=>400,"status_message"=>"此业务已封号");
                        $this->saveUserAddLog($uid,$appId,$imeicode,$simcode,$model,$sys,$tc,$brand,$hid,$pcIp,$tc_id,$times,$token,$data);
                    }
                    else
                    {
                        //第二次安装上报
                        $romstatus->installtime=date("Y-m-d H:i:s");
                        $romstatus->from = $from;
                        $romstatus->uid= $uid;
                        $romstatus->installcount=$romstatus->installcount+1;
                        $romstatus->ip=$pcIp;
                        $romstatus->update();
                    }*/
                    //$romstatus更新后的数据，第二个参数是uptype pc 上报
                    //包括已激活或是已封号de 第二次安装上报
                    /*2017-11-06 end*/
                    $firettime=$romstatus->createtime;
                    /*在数据更新之前存入数据库*/
                    if( $romstatus->uid=='23'){
                        if($before_uid!=$uid){
                            //查看表repeatinstall_uid中有没有存当前uid第一次安装插入的数据
                            $sql="select id from `app_rom_repeatinstall_uid` where imeicode='".$imeicode."' and before_uid=0";
                            $result=yii::app()->db->createCommand($sql)->queryAll();

                            if(empty($result)){
                                //第一次安装的数据
                                $romstatus->installtime=$firettime;
                                $romstatus->createtime=date('Y-m-d H:i:s');
                                Common::repeatinstall_uid($romstatus,$from,0);
                                $romstatus->uid=$uid;
                                $romstatus->installtime=date('Y-m-d H:i:s');
                                Common::repeatinstall_uid($romstatus,$from,$before_uid);
                            }else{
                                $sql="select id from `app_rom_repeatinstall_uid` where uid={$uid} and imeicode='{$imeicode}'";
                                $add=yii::app()->db->createCommand($sql)->queryAll();
                                if(empty($add)){
                                    $romstatus->installtime=date('Y-m-d H:i:s');
                                    $romstatus->createtime=date('Y-m-d H:i:s');
                                    $romstatus->uid=$uid;
                                    Common::repeatinstall_uid($romstatus,$from,$before_uid);
                                }
                            }
                        }
                    }


                    if(!empty($result)){
                        $romstatus->status=0;
                        $romstatus->closeend=date('Y-m-d H:i:s');
                    }
                    $romstatus->installtime=date("Y-m-d H:i:s");
                    $romstatus->from = $from;
                    $romstatus->uid= $uid;
                    $romstatus->installcount=$romstatus->installcount+1;
                    $romstatus->ip=$pcIp;
                    $romstatus->update();
                    Common::repeatInstall($romstatus,$from);
                    $count++;//上报个数
                }else{
                    //首次安装上报
                    $rmodel=new RomAppresource();
                    $rmodel->uid=$uid;
                    $rmodel->type=$model_product['pathname'];
                    $rmodel->imeicode=$imeicode;
                    $rmodel->simcode=$simcode;
                    $rmodel->tjcode=8;
                    $rmodel->status=1;
                    $rmodel->model=$model;
                    $rmodel->brand=$brand;
                    $rmodel->finishstatus=0;
                    $rmodel->createtime=date("Y-m-d H:i:s");
                    $rmodel->createstamp=strtotime(date("Y-m-d"));
                    $rmodel->installtime=date("Y-m-d H:i:s");
                    $rmodel->installcount=1;
                    $rmodel->from=$from;
                    $rmodel->sys=$sys;
                    $rmodel->tc=$tc;
                    $rmodel->tcid=$tc_id;
                    $rmodel->ip=$pcIp;
                    if($model_product['pathname']=='weixin' || !empty($result))
                    {
                        $rmodel->status=0;
                        $rmodel->closeend=date("Y-m-d H:i:s");
                    }

                    if($rmodel->save()){
                        $count++;
                        Common::repeatInstall($rmodel,$from);
                        //Common::repeatInstall($romstatus,$from);//第一次上报重复表
                    }else{
                        $errors = $rmodel->getErrors();
                        $content='';
                        foreach($errors as $key=>$error){
                            $content .= "[".$key."] ".$error[0]." ";
                        }
                        $data=array("status_code"=>400,"status_message"=>"首次上报错误","error"=>$content);
                        $this->saveUserAddLog($uid,$appIds,$imeicode,$simcode,$model,$sys,$tc,$brand,$hid,$pcIp,$tc_id,$times,$token,$data);
                    }

                }
            }
        }

        if(count($appArr)==$count){
            $data = array();
            $data['status_code'] = 200;
            $data['status_message'] = "成功";
            $this->ReturnJson($data);
        }else{
            //$data = $rmodel->getErrors();
            $n=count($appArr)-$count;//上报错误个数
            $data=array("status_code"=>400,"status_message"=>"用户上报信息失败".$n."个");
            $this->saveUserAddLog($uid,$appIds,$imeicode,$simcode,$model,$sys,$tc,$brand,$hid,$pcIp,$tc_id,$times,$token,$data);
        }


    }



    /**
     * 门店app--数据上报接口
     * Name : 门店app
     * http://192.168.0.106/dtApi/mdapp?tj_id=10000&imei=15415145415&iccid=4154541545454&systemVersionCode=bgfmnbklmn&simOperatorName=bfgjbgjebjgivbnheg&mac=bgvihnegi41541&models=nvgfkdnfk&brand=nmvlbdnbk&ip=45845445&timestamp=14235678945&md5=40d648ece509e3822928c1b41efef264
     */
    public function actionmdapp(){
        $request = Yii::app()->request;
        if(!$request->isPostRequest) {
            $this->ReturnError(400,"请求不存在");
        }
        $tj_id = $request->getPost('tj_id','');
        $imei = $request->getPost('imei','');
        $iccid = $request->getPost('iccid','');
        $systemVersionCode = $request->getPost('systemVersionCode','');
        $simOperatorName = $request->getPost('simOperatorName','');
        $mac = $request->getPost('mac','');
        $models = $request->getPost('models','');
        $brand = $request->getPost('brand','');
        $ip = Common::getIp();
        $timestamp = $request->getPost('timestamp','');
        $md5 = $request->getPost('md5','');
//        $tj_id= $request->getGet('tj_id','');
        /*
        $tj_id=trim(Yii::app()->request->getParam('tj_id'));
        $imei=trim(Yii::app()->request->getParam('imei'));
        $iccid=trim(Yii::app()->request->getParam('iccid'));
        $systemVersionCode=trim(Yii::app()->request->getParam('systemVersionCode'));
        $simOperatorName=trim(Yii::app()->request->getParam('simOperatorName'));
        $mac=trim(Yii::app()->request->getParam('mac'));
        $models=trim(Yii::app()->request->getParam('models'));
        $brand=trim(Yii::app()->request->getParam('brand'));
        $ip=trim(Yii::app()->request->getParam('ip'));
        $timestamp=trim(Yii::app()->request->getParam('timestamp'));
        $md5=trim(Yii::app()->request->getParam('md5'));
*/
        $cumd5 = md5($imei.$timestamp.self::KEY);
        $stat = 1;
        $uid='';
        $pack_id='';
        $datasql = '';
        $agent='';
        $softPak='';
        $md5status=1;

        if ($tj_id==""){
            $uid='';
            $this->ReturnError(400);
        }else{
            $softPak = RomSoftpak::model()->find('serial_number=:serial_number and type=:type', array(':serial_number'=>$tj_id,':type'=>'newdt'));
            if ($softPak['uid']==0){
                $uid='';
                $stat = 0;
            }else {
                $uid = $softPak['uid'];
                // 通过uid获取用户对应套餐
                $package = RomBoxPackage::model()->find('uid=:uid and box_number=:box_number',array(':uid'=>$uid,':box_number'=>'MDAZRJ'));
                if (empty($package)){
                    $pack_id='';
                    $stat = 0;
                }else {
                    $pack_id = $package['pack_id'];
                    if ($imei==""  || $timestamp==""||$md5==""){
                        $stat = 0;
                    }else{
                        if ($tj_id<=700000 || $tj_id>=800000){
                            $stat = 0;
                        }else{
                            if ($md5!=$cumd5){
                                $stat=0;
                                $md5status=0;
                            }else{
                                // 根据套餐id获取对应业务id
                                $rom_package = RomPackage::model()->find('id=:id',array(':id'=>$pack_id));
                                if (empty($rom_package)){
                                    $stat = 0;
                                }else{
                                    // 获取用户agent
                                    $agent = $this::actionGetAgentByUid($uid);
                                    if ((int)$agent!=96 && (int)$agent !=99 && (int)$agent !=707){
                                        $stat = 0;
                                    }else{
                                        $arr = json_decode($rom_package['pid']);
                                        $pid_str = implode(',',$arr);
                                        // 根据套餐中业务id获取业务信息
                                        $sql = "SELECT id,name,pathname FROM `app_product` WHERE `id` in ($pid_str) and status=1 ORDER by id";
                                        $datasql = Yii::app()->db->createCommand($sql)->queryAll();
                                        foreach ($datasql as $vt){
                                            // 开启业务
                                            Common::resourceOpen($uid,$vt['pathname']);
                                        }
                                        $stat = 1;
                                        $md5status=1;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }






        /*
        if($tj_id=="" || $imei==""  || $timestamp==""){
            if ($tj_id==""){
                $uid='';
            }
            $stat = 0;
        }else {//以上三个参数不为空则走else
            if ($md5!=$cumd5){
                $stat=0;
                $md5status=0;
            }else{
                if ($tj_id<=700000 || $tj_id>=800000){
                    $uid='';
                    $stat = 0;
                }else{
                    $softPak = RomSoftpak::model()->find('serial_number=:serial_number and type=:type', array(':serial_number'=>$tj_id,':type'=>'newdt'));
                    if ($softPak['uid']==0){
                        $uid='';
                        $stat = 0;
                    }else{
                        $uid =$softPak['uid'];

                        // 通过uid获取用户对应套餐
                        $package = RomBoxPackage::model()->find('uid=:uid and box_number=:box_number',array(':uid'=>$uid,':box_number'=>'MDAZRJ'));
                        if (empty($package)){
                            $stat = 0;
                        }else {
                            $pack_id = $package['pack_id'];

                            // 根据套餐id获取对应业务id
                            $rom_package = RomPackage::model()->find('id=:id',array(':id'=>$pack_id));
                            if (empty($rom_package)){
                                $stat = 0;
                            }else{
                                // 获取用户agent
                                $agent = $this::actionGetAgentByUid($uid);
                                if ((int)$agent!=96 && (int)$agent !=99){
                                    $stat = 0;
                                }else{
                                    $arr = json_decode($rom_package['pid']);
                                    $pid_str = implode(',',$arr);
                                    // 根据套餐中业务id获取业务信息
                                    $sql = "SELECT id,name,pathname FROM `app_product` WHERE `id` in ($pid_str) and status=1 ORDER by id";
                                    $datasql = Yii::app()->db->createCommand($sql)->queryAll();
                                    foreach ($datasql as $vt){
                                        // 开启业务
                                        Common::resourceOpen($uid,$vt['pathname']);
                                    }
                                    $stat = 1;
                                }
                            }
                        }
                    }
                }
            }
        }
*/


        $model = new ClientMdappData();
        $model->tj_id = $tj_id;
        $model->imei = $imei;
        $model->iccid = $iccid;
        $model->systemVersionCode = $systemVersionCode;
        $model->simOperatorName = $simOperatorName;
        $model->mac = $mac;
        $model->models = $models;
        $model->brand = $brand;
        $model->ip = $ip;
        $model->timestamp = $timestamp;
        $model->uid = $uid;
        $model->pack_id = $pack_id;
        $model->createtime = time();
        if($stat==0){
            $model->status = 0;
            if ($md5status==0||$md5==""){
                $model->md5=0;
            }else{
                $model->md5=1;
            }
        }else{

            $model->md5 = 1;
            $model->status = 1;
        }

        if ($model->save()){
            if ($stat ==0){
                $this->ReturnError(400);
            }else{
                $array = array();
                $tj_url = "http://df.sutuiapp.com".$softPak['url'];
                $arr =$this::actionProductData($agent,$datasql);
                $array['tj_url']=$tj_url;
                $array['app_info']=$arr;
                $data = array();
                $data['status_code'] = 200;
                $data['status_message'] = $array;
                $this->ReturnJson($data);
            }
        }
    }

    /**
     * 路由器接口---返回一键安装路由器套餐一致
     * http://192.168.0.106/dtApi/dog?route=route1
     */
    public function actionDog(){
        $route_number=trim(Yii::app()->request->getParam('route'));
        $data = Softroute::model()->findByAttributes(array('route_number'=>$route_number,'status'=>1));
        if (empty($data)){
            $this->ReturnError(400,"找不对应的路由器");
        }
        $uid = $data['uid'];
        // 通过用户uid获取用户对应的套餐
        $package = RomBoxPackage::model()->find('uid=:uid and box_number=:box_number',array(':uid'=>$uid,':box_number'=>$route_number));
        //$pack_id = 1; // 用户对应的套餐id
        if (!empty($package) && !empty($package['pack_id'])){
            $pack_id = $package['pack_id'];
        }else{
            $this->ReturnError(400,"路由器没有绑定套餐");
        }
        // 根据套餐id获取对应业务id
        $rom_package = RomPackage::model()->find('id=:id',array(':id'=>$pack_id));
        if (empty($rom_package)){
            $this->ReturnError(400,"套餐内无业务");
        }
        $agent = $this::actionGetAgentByUid($uid);
        if ((int)$agent!=96 && (int)$agent !=99 && (int)$agent !=707){
            $this->ReturnError(400,"用户类型错误");
        }
        $arr = json_decode($rom_package['pid']);
        $pid_str = implode(',',$arr);
        // 根据套餐中业务id获取业务信息
        $sql = "SELECT id,name,pathname FROM `app_product` WHERE `id` in ($pid_str) and status=1 ORDER by id";
        $datasql = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($datasql as $vt){
            // 开启业务
            Common::resourceOpen($uid,$vt['pathname']);
        }

        $softPak = RomSoftpak::model()->find('uid=:uid and type=:type', array(':uid'=>$uid,':type'=>'newdt'));
        if (empty($softPak)){
            $this->ReturnError(400,"暂无分配统计软件");
        }

        $array = array();
        $tj_url = "http://df.sutuiapp.com".$softPak['url'];
        $arr =$this::actionProductData($agent,$datasql);
        $array['tj_url']=$tj_url;
        $array['app_info']=$arr;
        $data = array();
        $data['status_code'] = 200;
        $data['status_message'] = $array;
        $this->ReturnJson($data);
    }




    /**
     * 统计上报相关
     * http://www.sutuiapp.com/dtApi/countreport?uid=&simcode=12454454&imeicode=15415145415&sys=4154541545454&mac=45:78:98:d1&tjcode=10007&models=bgvihnegi41541&brand=nvgfkdnfk&com=nmvlbdnbk&type=1&ip=45845445
     */
    public function actionCountReport(){

        $request = Yii::app()->request;
        if(!$request->isPostRequest) {
            $this->ReturnError(400,"请求不存在");
        }
        $uid = $request->getPost('uid','');
        if($uid==''){
            //新版统计上传 统计号
            $tid = $request->getPost('appNo','');
            $romSoftpak=RomSoftpak::model()->find("serial_number=:serial_number",array(":serial_number"=>$tid));
            $uid=isset($romSoftpak)?$romSoftpak->uid:'';
        }
        $simcode = trim($request->getPost('simcode',''));
        $imeicode = $request->getPost('imeicode','');
        $sys = $request->getPost('sys','');
        $mac = $request->getPost('mac','');
        $tjcode = $request->getPost('tjcode','');
        $models = $request->getPost('models','');
        $brand = $request->getPost('brand','');
        $com = $request->getPost('com','');
        $type= $request->getPost('type','');
        $ip = Common::getIp();
        $createtime = date('Y-m-d H:i:s');
        $model = new RomAppupdataDay();
        $model->uid = $uid;
        $model->simcode = $simcode;
        $model->imeicode = $imeicode;
        $model->sys = $sys;
        $model->mac = $mac;
        $model->tjcode = $tjcode;
        $model->models = $models;
        $model->brand = $brand;
        $model->com = $com;
        $model->type = $type;
        $model->ip = $ip;
        $model->createtime = $createtime;
        $model->reportime = $createtime;
        $model->reportimestamp = strtotime(date("Y-m-d"));

        if (empty($imeicode)){
            return;
        }

        $date = strtotime(date("Y-m-d"));
        $sql = "SELECT id,type FROM `app_rom_appupdata_day` WHERE `imeicode`='$imeicode' limit 1";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        if (empty($data)){
            $model->count = 1;
            $model->save();
        }else{
            $sql1 = "SELECT id,uid FROM `app_rom_appupdata_day` WHERE `imeicode`='{$imeicode}' and `reportimestamp` = '{$date}' limit 1";
            $data1 = Yii::app()->db->createCommand($sql1)->queryAll();

            if (empty($data1)){
                $sqly = "SELECT id,uid FROM `app_rom_appupdata_day` WHERE `imeicode`='{$imeicode}' limit 1";
                $datay = Yii::app()->db->createCommand($sqly)->queryAll();
                if ($datay[0]['uid']==0 && !empty($uid)){
                    $sqlupdate = "update `app_rom_appupdata_day` set `count` = count + 1,uid='$uid',`reportimestamp` = '$date',reportime='".date("Y-m-d H:i:s")."' where imeicode = '{$imeicode}'";
                    Yii::app()->db->createCommand($sqlupdate)->execute();
                }else{
                    $sqlupdate = "update `app_rom_appupdata_day` set `count` = count + 1,`reportimestamp` = '$date',reportime='".date("Y-m-d H:i:s")."' where imeicode = '{$imeicode}'";
                    Yii::app()->db->createCommand($sqlupdate)->execute();
                }
            }else{
                if ($data1[0]['uid']==0 && !empty($uid)){
                    RomAppupdataDay::model()->updateAll(array('reportime'=>date("Y-m-d H:i:s"),'uid'=>$uid),'imeicode=:imeicode',array(':imeicode'=>$imeicode));
                }else{
                    RomAppupdataDay::model()->updateAll(array('reportime'=>date("Y-m-d H:i:s")),'imeicode=:imeicode',array(':imeicode'=>$imeicode));
                }

            }
        }
//        if (empty($data)){
//            $sql_resource = "SELECT * FROM `app_rom_appresource` WHERE `imeicode` = '$imeicode' AND `status` = 1 limit 1";
//            $data_resource = Yii::app()->db->createCommand($sql_resource)->queryAll();
//           if (empty($data_resource)){
//               $model->type = 2;
//           }else{
//               $model->type = $type;
//           }
//            $model->count = 1;
//            $model->save();
//        }elseif (!empty($data) && $data[0]['type']==2){
//            return;
//        }else{
//            $sql_resource = "SELECT * FROM `app_rom_appresource` WHERE `imeicode` = '$imeicode' AND `status` = 1 limit 1";
//            $data_resource = Yii::app()->db->createCommand($sql_resource)->queryAll();
//
//            $sql1 = "SELECT id FROM `app_rom_appupdata_day` WHERE `imeicode`='{$imeicode}' and `reportimestamp` = '{$date}' limit 1";
//            $data1 = Yii::app()->db->createCommand($sql1)->queryAll();
//            if (empty($data_resource)){
//                if (empty($data1)){
//                    $sqlupdate = "update `app_rom_appupdata_day` set `count` = count + 1,`type` = 2,`reportimestamp` = '$date',reportime='".date("Y-m-d H:i:s")."' where imeicode = '{$imeicode}'";
//                    Yii::app()->db->createCommand($sqlupdate)->execute();
//                }else{
//                    RomAppupdataDay::model()->updateAll(array('reportime'=>date("Y-m-d H:i:s"),'type'=>2),'imeicode=:imeicode',array(':imeicode'=>$imeicode));
//                }
//            }else{
//                if (empty($data1)){
//                    $sqlupdate = "update `app_rom_appupdata_day` set `count` = count + 1,`type` = 1,`reportimestamp` = '$date',reportime='".date("Y-m-d H:i:s")."' where imeicode = '{$imeicode}'";
//                    Yii::app()->db->createCommand($sqlupdate)->execute();
//                }else{
//                    RomAppupdataDay::model()->updateAll(array('reportime'=>date("Y-m-d H:i:s")),'imeicode=:imeicode',array(':imeicode'=>$imeicode));
//                }
//            }
//
//        }

    }




    /**
     * 获取PC所有计费：业务接口
     * @return string 该用户所有的计费业务，桌面,autorunner
     * */
    public  function actionGetChargeApp(){
        $request = Yii::app()->request;
        $uid=$request->getQuery('uid', '');
        if($uid==""){
            $this->ReturnError('400',"参数不得为空");
        }
        $member=Member::model()->findByPk($uid);
        if(!$member){
            $this->ReturnError('400',"不存在该用户");
        }
        $types=$member->type;
        $type=Common::getUserType($types);
        $launcher_install=$member->launcher_install;
        $uninstall=$member->uninstall;
        if(!in_array($types,array(3,4,8))){
            $this->ReturnError('400',"该用户类型有误");
        }
        $boxdesk=Boxdesk::model()->find("uid=:uid and status=:status",array(":uid"=>$uid,":status"=>0));//0-分配
        if($boxdesk){
            $data = array();
            $data['state_Code'] =200;
            //判断用户安装桌面还是只有统计
            $data['launcher_install'] =$launcher_install;

            //卸载应用
            $data['uninstall'] =$uninstall;
            //pc助手 版本号，下载地址
            $data['update']= array("ver"=>"1.16.1025.20272",'downPath'=>"http://df.sutuiapp.com/uploads/pczs/StInstallTool.exe");
            $data['launcher']= array("name"=>$boxdesk->filename,'md5'=>strtolower($boxdesk->md5),'downPath'=>"http://df.sutuiapp.com". $boxdesk->downloadurl);
        }else{
            //不存在桌面 是否需要分配
            $userapp = RomSoftpak::model()->find('uid=:uid and type=:type',array(':uid'=>$uid,":type"=>$type));
            //存在统计id 直接分配桌面
            if($userapp){
                $tid=$userapp['serial_number'];
                $box_desk=Boxdesk::model()->find('tid=:tid and status=:status',array(':tid'=>$tid,':status'=>1));
                if($box_desk){
                    $box_desk->status = 0;
                    $box_desk->uid = $uid;
                    if($box_desk->save()){
                        $data = array();
                        $data['status_code'] =200;
                        //判断用户安装桌面还是只有统计
                        $data['launcher_install'] =$launcher_install;
                        //卸载应用
                        $data['uninstall'] =$uninstall;
                        //pc助手 版本号，下载地址
                        $data['update']= array("ver"=>"1.16.1025.20272",'downPath'=>"http://df.sutuiapp.com/uploads/pczs/StInstallTool.exe");
                        $data['launcher']= array("name"=>$box_desk->filename,'md5'=>strtolower($box_desk->md5),'downPath'=>"http://df.sutuiapp.com". $box_desk->downloadurl);
                    }
                }else{
                    $this->ReturnError('400',"该统计id没有对应的桌面");
                }

            //不存在统计id 先分配统计再桌面
            }else{
                //统一判断代理商和普通用户统计分配
                $softpak=Common::getTongji($member,$type);
                if(empty($softpak)) {
                    $this->ReturnError('400',"无可用软件供分配，请联系客服");
                }else{
                    //分配统计id
                    $softpak->uid=$uid;
                    $softpak->status=0;
                    if( $softpak->save()){
                        $tid=$softpak['serial_number'];
                        //只有特定分组用户才有桌面
                        if(in_array($types,array(3,4,8))){
                            $box_desk=Boxdesk::model()->find('tid=:tid and status=:status',array(':tid'=>$tid,':status'=>1));
                            if($box_desk){
                                $box_desk->status = 0;
                                $box_desk->uid = $uid;
                                if($box_desk->save()){
                                    $data = array();
                                    $data['stateCode'] =200;
                                    //判断用户安装桌面还是只有统计
                                    $data['launcher_install'] =$launcher_install;
                                    //卸载应用
                                    $data['uninstall'] =$uninstall;
                                    //pc助手 版本号，下载地址
                                    $data['update']= array("ver"=>"1.16.1025.20272",'downPath'=>"http://df.sutuiapp.com/uploads/pczs/StInstallTool.exe");
                                    $data['launcher']= array("name"=>$box_desk->filename,'md5'=>strtolower($box_desk->md5),'downPath'=>"http://df.sutuiapp.com". $box_desk->downloadurl);
                                }
                            }else{
                                $this->ReturnError('400',"该统计id没有对应的桌面");
                            }

                        }

                    }

                }
            }

        }
        //判断用户是否有单独定做的autorunner.jar包
        $box=BrandScript::model()->findAll("uid=:uid",array(":uid"=>$uid));
        if($box){//用户单独定制
            foreach($box as $k=>$v){
            $data['autorunnerList'][]= array('brand'=>$v->brand,"name"=>$v->name,'md5'=>strtolower($v->md5),'downPath'=>"http://df.sutuiapp.com". $v->downPath);
            }
        }else{
            $box=BrandScript::model()->findAll("uid=:uid",array(":uid"=>0));
            foreach($box as $k=>$v){
            $data['autorunnerList'][]= array('brand'=>$v->brand,"name"=>$v->name,'md5'=>strtolower($v->md5),'downPath'=>"http://df.sutuiapp.com". $v->downPath);
            }
            //默认autorunner包
            $box=Boxdata::model()->find("uid=:uid and name=:name",array(":uid"=>0,":name"=>"autorunner.jar"));
            $data['autorunnerList'][]= array('brand'=>"default","name"=>$box->name,'md5'=>strtolower($box->md5),'downPath'=>"http://df.sutuiapp.com". $box->downPath);

        }
        //判断用户是否有单独定做的autorunner.jar包
        $box=Boxdata::model()->find("uid=:uid and classify=:classify",array(":uid"=>$uid,":classify"=>"StFlashTool2"));
        if($box){
            $data['autorunner']= array("name"=>$box->name,'md5'=>strtolower($box->md5),'downPath'=>"http://df.sutuiapp.com". $box->downPath);              //用户单独定制
        }else{
            $box=Boxdata::model()->find("uid=:uid and name=:name",array(":uid"=>0,":name"=>"autorunner.jar"));
            $data['autorunner']= array("name"=>$box->name,'md5'=>strtolower($box->md5),'downPath'=>"http://df.sutuiapp.com". $box->downPath);
        }
        
        //单独统计
        $userapp = RomSoftpak::model()->find('uid=:uid and type=:type',array(':uid'=>$uid,":type"=>$type));
        if(isset($userapp)){
            $data['stat']= array("name"=>'stat.apk','md5'=>strtolower($userapp->md5),'downPath'=>"http://df.sutuiapp.com". $userapp->url);
        }
        $agent=$member->agent;
        $product_list=ProductList::model()->findAllBySql('select * from (select * from app_product_list WHERE agent='.$agent. '  and status=1 order by createtime desc) as a group by a.type');
        foreach($product_list as $v){
            $product=Product::model()->findByPk($v->pid);
            $pic=$product->pic;
            $app_name=$product->name;
            $data['appList'][]= array("app_id"=>$v->pid,"app_name"=>$app_name,"app_size"=>$v->filesize,"package_name"=>$v->pakname,'app_md5'=>$v->sign,'app_icon'=>"http://df.sutuiapp.com".$pic,'app_download_url'=>"http://df.sutuiapp.com". $v->appurl);
        }
        $php_json = json_encode($data); //把php数组格式转换成 json 格式的数据
        echo $php_json;

    }

    /**
     * 安全桌面、应用卸载开关接口
     * */
    public function actionChangeLauncher_install(){
        $request = Yii::app()->request;
        $uid=$request->getQuery('uid', '');
        $install=$request->getQuery('install', '');
        $uninstall=$request->getQuery('uninstall', '');
        if($uid=="" || $install=="" || $uninstall==""){
            $this->ReturnError('400',"参数不得为空");
        }else{
            $member=Member::model()->findByPk($uid);
            if($member){
                if(in_array($install,array(0,1)) && in_array($uninstall,array(0,1))){
                    Member::updateByUser_launcher2($uid,$install,$uninstall);
                }else{
                    $this->ReturnError('400',"参数错误");
                }

            }else{
                $this->ReturnError('400',"不存在该用户");
            }

        }
    }

    /**
     * 路由器码业务接口
     * @return string 该用户所有的计费业务，统计,一键下载
     * */
    public  function actionRoute(){
        $request = Yii::app()->request;
        $routerID=$request->getQuery('routerID', '');
        $time=$request->getQuery('time', '');
        $token1=$request->getQuery('token', '');
        if($routerID=="" || $time=="" || $token1=="" ){
            $this->ReturnError('400',"参数不得为空");
        }else{
            $token2=$this->getToken($routerID,$time);
            if($token1 !=$token2)   $this->ReturnError(400,"验证错误");
            //获取路由
            $softroute=Softroute::model()->find("route_number=:routerID",array(":routerID"=>$routerID));
            if($softroute){
                $uid=$softroute['uid'];
                $member=Member::model()->findByPk($uid);
                if($member){
                    $data=array();
                    //路由本身更新
                    $data['routerUpdate']= array("name"=>'routerUpdate','md5'=>strtolower('6B718055C3BC8DB7B76F04FEA6D328B6'),'downPath'=>"http://sutuiapp.com". '/uploads/route/routerUpdate');

                    //
                    if(is_file('./uploads/sys/update.sh')){
                        $data['sys_update_sh']= array("name"=>'update.sh','md5'=>strtolower('807294ad7ac40e2d2895e82eb96926df'),'downPath'=>"http://sutuiapp.com". '/uploads/sys/update.sh');
                        $data['sys_update_gz']= array("name"=>'update.tar.gz','md5'=>strtolower('0885d9e0f46be19660432e1afe64bdda'),'downPath'=>"http://sutuiapp.com". '/uploads/sys/update.tar.gz');
                    }

                    $types=Common::getUserType($member->type);
                    //统计
                    $userapp = RomSoftpak::model()->find('uid=:uid and type=:type',array(':uid'=>$uid,":type"=>$types));
                    if(isset($userapp)){
                        $data['stat']= array("name"=>'stat.apk','md5'=>strtolower($userapp->md5),'downPath'=>"http://df.sutuiapp.com". $userapp->url);

                        //桌面
                        $boxDesk = Boxdesk::model()->find('uid=:uid and tid=:tid',array(':uid'=>$uid,":tid"=>$userapp->serial_number));
                        if(isset($boxDesk)){
                            $data['launcher']= array("name"=>'launcher.apk','md5'=>strtolower($boxDesk->md5),'downPath'=>"http://df.sutuiapp.com". $boxDesk->downloadurl);
                        }else{
                            $this->ReturnError('400',"不存在桌面");
                        }
                    }


                    //一键下载
                    $data['oneClickSetup']= array("name"=>'oneClickSetup.apk','md5'=>strtolower('127650C7F5D140DC34429E134C0A20B8'),'downPath'=>"http://df.sutuiapp.com". '/uploads/route/oneClickSetup.apk');


                    //获取套餐列表
                    $boxPackage=RomBoxPackage::model()->find("box_number=:box_no",array(":box_no"=>$routerID));
                    if($boxPackage){
                        $pack_id=$boxPackage['pack_id'];
                        $package=RomPackage::model()->findByPk($pack_id);
                        $pids[0]=$package['pid'];
                        $pids[1]=$package['sign'];
                        $pids[2]=$pack_id;
                    }else{
                        $this->ReturnError('400',"该路由器未绑定套餐");
                    }
                    $pids=json_decode($pids[0]);
                    foreach($pids as $v){
                        $productList = ProductList::model()->find(array(
                            'select'=>array('appurl','sign','type','pakname'),
                            'order' => 'id DESC',
                            'condition' => 'status=:status AND agent=:agent AND pid=:pid',
                            'params' => array(':status'=>1,':agent' => $member['agent'],':pid'=>$v),
                            'limit'=>1
                        ));
                        if($productList){
                            $data['apps'][]=array("name"=>str_replace('/uploads/apk/','',$productList->appurl),"md5"=>strtolower($productList->sign),"downPath"=>"http://df.sutuiapp.com".$productList->appurl);
                        }
                    }
                }else{
                    $this->ReturnError(400,"用户错误");
                }
            }else{
                $this->ReturnError(400,"路由编码错误");
            }
        }

        $php_json = json_encode($data); //把php数组格式转换成 json 格式的数据
        echo $php_json;

    }

    /**
     * 保存用户上报错误日志信息
     * */
    public function actionCheckPhoneLog(){
        $request = Yii::app()->request;
        if(!$request->isPostRequest) {
            $this->ReturnError(400,"请求不存在");
        }
        $uid = $request->getPost('uid','');
        $model = $request->getPost('model','');
        $brand = $request->getPost('brand','');
        $status = $request->getPost('status','');
        $duration = $request->getPost('duration','');
        $member=Member::model()->findByPk($uid);
        if($member){
            $sql="INSERT INTO `app_check_phone_log` (`id`,  `uid`, `createtime`, `model`,`brand`, `status`, `duration`)
                          VALUES('','".$uid."','".date('Y-m-d H:i:s',time())."','".$model."','".$brand."','".$status."','".$duration."');";
            Yii::app()->db->createCommand($sql)->execute();
        }

    }

    /**
     * 路由器上报手机业务安装数据
     * http://www.sutuiapp.com/dtApi/routeReport?routerID=c8eea6325871&simcode=12454454&imeicode=15415145415&sys=4154541545454&appname=com.market.chenxiang|com.browser_llqhz&models=bgvihnegi41541&brand=nvgfkdnfk&installTime=Oct 16 11:08:40 2017
     */
    public function actionRouteReport(){
        $request = Yii::app()->request;
        if(!$request->isPostRequest) {
            $this->ReturnError(400,"请求不存在");
        }
        $routerID = $request->getPost('routerID','');
        $simcode = trim($request->getPost('simcode',''));
        $imeicode = $request->getPost('imeicode','');
        $sys = $request->getPost('sys','');
        $models = $request->getPost('models','');
        $brand = $request->getPost('brand','');
        $appname = $request->getPost('appname','');
        $installTime = $request->getPost('installTime','');
        $installTime=strtotime($installTime);
        $model = new RouteReportdata();
        $model->appname = $appname;
        $model->routerID = $routerID;
        $model->simcode = $simcode;
        $model->imeicode = $imeicode;
        $model->sys = $sys;
        $model->models = $models;
        $model->brand = $brand;
        $model->installTime =$installTime;
        $model->installdate = strtotime(date("Y-m-d",$installTime));
        $model->createtime=date("Y-m-d H:i:s");
        $model->save();

    }

    /**
     * 门店手机桌面上报数据
     * http://www.sutuiapp.com/dtApi/deskReport
     */
    public function actionDeskReport(){
        $request = Yii::app()->request;
        if(!$request->isPostRequest) {
            $this->ReturnError(400,"请求不存在");
        }
        $data = $request->getPost('data','');
        if($data==''){
            $this->ReturnError(400,"数据错误");
        }
        $data=json_decode($data,true);
        $sql="INSERT INTO `app_desk_reportdata` (`id`,`package_name`,`simcode`,`imeicode`,`soft_name`, `models`,`brand`,`md5`,`size`,`class_name`,`status`,`serial_number`,`is_sys`,`createtime`,`createstamp`)VALUES";
        foreach($data as $v){
            $sql.="('','".$v['package_name']."','". $v['simcode']."','". $v['imeicode']."','". $v['soft_name']."','". $v['models']."','".$v['brand']."','". $v['md5']."',
        '". $v['size']."','".$v['soft_name']."','". $v['form']."','". $v['serial_number']."','".$v['is_sys']."','". date('Y-m-d H:i:s')."','".strtotime(date("Y-m-d"))."'),";
        }
        $sql=substr($sql,0,-1).';';
        Yii::app()->db->createCommand( $sql)->execute();

    }
    /**
     * 新统计上报监控条件接口
     * http://www.sutuiapp.com/dtApi/getReportCondition?id=&imei=
     */
    public function actionGetReportCondition(){
        $request = Yii::app()->request;
        if(!$request->isPostRequest) {
            $this->ReturnError(400,"请求不存在");
        }
        $id = $request->getPost('id','');
        $imei = $request->getPost('imei','');
        if($id==''){
            $this->ReturnError(400,"数据错误");
        }else{
            $romSoftpak=RomSoftpak::model()->find("serial_number=:serial_number",array(":serial_number"=>$id));
            if($romSoftpak){
                $data=array();
                $data['service_time']= 60;//监听业务列表间隔时间
                $data['scan_time']= 20;//扫描间隔
                $data['activate_time']= 3600;//扫描间隔
                $data['is_check']= 0;//监听其他业务安装上报
                $data['is_survival']= 20;//统计软件自身是否存活上报
                $models=ControlPackage::model()->findAll("status=1");
                if($models){
                    foreach($models as $value){
                        $data['packages'][]=array('pkgname'=>$value->package_name,"reportswitch"=>$value->is_check);
                    }
                    $this->ReturnJson($data);
                }else{
                    $this->ReturnError(400,"监控包数据错误");
                }
            }else{
                $this->ReturnError(400,"统计数据错误");
            }
        }

    }


    /**
     * 保存用户上报错误日志信息
     * */
    protected function saveUserAddLog($uid='',$appIds='',$imeicode='',$simcode='',$model='',$sys='',$tc='',$brand='',$hid='',$pcIp='',$tc_id='',$times='',$token='',$data){
        $time=time();
        $content = "[Log Time] ".date("Y-m-d H:i:s",$time)."\r";
        $content .= "[uid]".$uid."[hid]".$hid."[times]".$times."[token]".$token."[pid]".$appIds."[imeicode]".$imeicode." [simcode] ".$simcode." [model] ".$model." [sys] ".$sys." [tc] ".$tc." [tc_id] ".$tc_id."[PCIP] ".$pcIp." [brand] ".$brand."\r";
        $content .= "[错误信息] ";
        foreach ($data AS $k => $v){
            $content .= "[".$k."] ".$v." ";
        }
        //$content .= "\n\r";
        $file  = "log/pc_clientdata_".date("Y-m-d",$time).'_log.txt';//要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
        if(file_put_contents($file, $content.PHP_EOL,FILE_APPEND)){// 这个函数支持版本(PHP 5)
            //echo "写入成功。<br />";
        }
    }




    /**
     * 通过用户id获取用户信息
     * @param int $id
     * @return object
     * */
    protected function getUserData($id){
        $data = Member::model()->findByPk($id);
        if($data){
            return $data;
        }else{
            $this->ReturnError(400,'用户数据发生错误');
        }
    }
    /**
     * 查询业务是否开启
     * @param int $id
     * @return object
     * */
    protected function getProduct($id){
        $model = new Product();
        $data = $model->findByPk($id);
        if(!$data || $data['status']!=1){
            $this->ReturnError(400,'业务不存在或未开启');
        }else{
            return $data;
        }
    }
    /**
     * 生成路由器token
     * @param string $routerID
     *  @param string $time
     * @return string
     * */
    protected function getToken($routerID,$time){
        return md5($routerID."_".$time."_lyq");
    }

    /**
     * 生成token
     * @param string $username
     * @return string
     * */
    protected function createToken($username=""){
        return md5($username."_comsutuiapp");
    }

    /**
     * Name : 返回参数不正确消息
     * @param int $code
     * */
    protected function ReturnError($code=400,$error=""){
        $data = array();
        $data['status_code'] = $code;
        $data['status_message'] = ($error=="")?"参数错误":$error;
        $this->ReturnJson($data);
    }

    /**
     * Name : 数据转换成json格式
     * @return json
     * */
    protected function ReturnJson($data){
        header('Content-type: application/json');
        exit(CJSON::encode($data));
    }
    /*
     * curl输出json格式
     * */
    protected function curlJson($data){
        header('Content-type: application/json');
        exit($data);
    }




    /**
     * 根据用户id获取agent
     */
    protected function actionGetAgentByUid($uid){
        $data = MemberInfo::model()->find('id=:id',array(':id'=>$uid));
        return $data['agent'];

    }

    /**
     * 返回业务数据;
     */
    protected function actionProductData($agent,$data){
        $agent = (int)$agent;
        $array = array();
        foreach ($data as $vt){
            $arr = array();
            $list = ProductList::model()->findAllBySql("select appurl,filesize,sign,pakname from  app_product_list WHERE agent={$agent} AND status=1 AND type ='{$vt['pathname']}' order by id desc limit 1");

            if (!empty($list)){
                $arr['app_id']=$vt['id'];
                $arr['app_name']=$vt['name'];
                $b = explode('/',$list[0]['appurl']);
                $arr['app_type'] = str_replace('.apk','',$b[3]);
                $arr['app_url']="http://df.sutuiapp.com".$list[0]['appurl'];
                $arr['filesize']= $this::filesizeToByte($list[0]['filesize']);
                $arr['md5'] = $list[0]['sign'];
                $arr['pakname'] = $list[0]['pakname'];
                $array[]=$arr;
            }
        }

        $a = array();
        foreach ($array as $vt){
            if ($vt['app_id'] ==38 || $vt['app_id'] == 36){
                $a[] = $vt;
            }
        }
        foreach ($array as $vt){
            if ($vt['app_id'] !=38 && $vt['app_id'] != 36){
                $a[] = $vt;
            }
        }
        return $a;
    }


    /**
     * 文件大小转换  gb ==> mb ==> kb ==> byte
     */
    private function filesizeToByte($filesize){
        $num = preg_replace('/[a-zA-Z]/s','',$filesize);
        $str = strtolower(str_replace($num,'',$filesize));
        $size = '';
        switch ($str){
            case 'gb':
                $size = $num*1024*1024*1024;
                break;
            case 'mb':
                $size = $num*1024*1024;
                break;
            case 'kb':
                $size = $num*1024;
                break;
            case 'bytes':
                if ($num-0<1){
                    $size = 1;
                }else{
                    $size = $num;
                }

                break;
        }
        return round($size);
    }

    /**
     * 获取年月日
     */
    public function actionGetDateTime(){
        echo date('Y-m-d');
        // Yii::app()->cache->delete('101010600');
    }

    /**
     * 获取天气
     */
    public function actionGetWeather(){
        $request = Yii::app()->request;
        $cityid=$request->getQuery('cityid', '');
        if($cityid=="" ){
            $this->ReturnError('400',"参数不得为空");
        }else{
            $value=Yii::app()->cache->get($cityid);
            if($value===false){
                $url = "http://www.weather.com.cn/weather/".$cityid.".shtml";
                $page_content = file_get_contents($url);
                $regex4="/<div id=\"7d\" class=\"c7d\".*?>.*?<\/div>/ism";
                $regex="/<ul class=\"t clearfix\">(.*?)<\/ul>/is";
                if(preg_match_all($regex4, $page_content, $matches)){
                    preg_match_all($regex, $page_content, $matchess);
                    $html=str_replace("<li class='on'>", '<li class="on">', $matchess[0][0]);
                    $regex="/<li class=\"sky skyid lv(.*) on\">(.*?)<\/li>/is";
                    if(preg_match_all($regex, $html, $matchesss)){
                        $str=$matchesss[0][0];
                        $str=strip_tags($str);
                        $str=( str_replace(array("\r\n", "\r", "\n"), " ", $str));
                        $arr = explode(' ',$str);
                        $data=array("tp"=>$arr[4],"temp"=>$arr[6].$arr[7]);
                        Yii::app()->cache->set($cityid,$data,3*60*60);
                    }else{
                        $this->getWeather2($cityid);
                        // $this->ReturnError('400',"发生错误，请重新请求");
                    }
                }else{
                    $this->getWeather2($cityid);
                    // $this->ReturnError('400',"发生错误，请重新请求！");
                }
            }else{
                $data=$value;
            }
            $this->ReturnJson($data);

        }
    }

    /**
     * 获取天气2
     * 
     */
    public function getWeather2($citynum=''){
        if(isset($citynum) && !empty($citynum)){
            $url = "http://wthrcdn.etouch.cn/WeatherApi?citykey=".$citynum;
            $con=file_get_contents("compress.zlib://".$url);
            //XML标签配置
            $xmlTag = array(
                'date',
                'high',
                'low',
                'type'
            );
             
            $arr = array();
            foreach($xmlTag as $x) {
                preg_match_all("/<".$x.">.*<\/".$x.">/", $con, $temp);
                $arr[] = $temp[0];
            }
            //去除XML标签并组装数据
            $data = array();
            foreach($arr as $key => $value) {
                foreach($value as $k => $v) {
                    $a = explode($xmlTag[$key].'>', $v);
                    $v = substr($a[1], 0, strlen($a[1])-2);
                    $data[$k][$xmlTag[$key]] = $v;
                }
            }
            $att=array();
            foreach ($data as $key => $value) {
                $att['tp']=$value['type'];
                $att['temp']=str_replace(' ','',mb_substr($value['high'],2,5,'utf-8').'/'.mb_substr($value['low'],2,5,'utf-8'));
            }

            if(empty($att)){
                $this->ReturnError('400',"获取不到该城市天气，请重新请求！");
            }
            $this->ReturnJson($att);
        }else{
            $this->ReturnError('400',"发生错误，请重新请求！");
        }
            
    }
    public function actionCeshi(){
        $this->getWeather2($_GET['cityid']);
    }
    
    /*
     * 获取统计回收数据
     * http://www.sutuiapp.com/dtApi/GetRecycleData
     * */
    public function actionGetRecycleData()
    {
        $num=RecycleSoftpak::getRecycleData();
        if($num>0){
            $this->ReturnJson('更新成功');
        }else{
            $this->ReturnError('400',"发生错误，请重新请求");
        }
    }
}