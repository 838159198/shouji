<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/31
 * Time: 13:25
 */
class ReportApiController extends Controller
{
    private static $secret_key = array(
        "7"=>"b9c80a22a043116b25f891fbc5c1bb6f",
        "8"=>"b9c80a22a043112b25f891fjc5c1bb6f",
        "9"=>"bfcu0a22a94311cb25ff91fbc5c1bb6f",
        "10"=>"bfc80k22a0k31125f891fkbc5c1bb6f",
        "11"=>"bfc80a22a074311cb625f891fbc5b6f",
        "12"=>"bfc80a22a04d1cb25jd91fbc5c1bb6f",
        "13"=>"bfc80a22a04d1cb25fsoo1fbc5c3b6f",
        "14"=>"bfc80a22a04d1cb25fsdfbc5pc1bb6f",
        "15"=>"bfc80a22a04d1cb25fs91ffgdc1bb6f",
        "16"=>"bfc80a22a04d1cb25fs91fbc5c1bb6f",
        "17"=>"bfc80a22a04d1cb25fs91sdc5c1bb6f",
        "18"=>"bfc80a22a04d1cb25fs91fbc4c1bb6f",
        "19"=>"bfc80a22a04d1cb25fsfddfsdc1bb6f",
        "20"=>"bfcda22a04d1csfs91e3sfbc5c1bb6f"
    );
    /**
     * 新统计上报监控条件接口
     * http://www.sutuiapp.com/reportApi/getReportCondition?id=&imei=
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
                $uid=$romSoftpak->uid;
                $member=Member::model()->getById($uid);
                $groupid=$member["agent"];//分组
                $data=array();
                $data['service_time']= 60;//监听业务列表间隔时间
                $data['scan_time']= 20;//扫描间隔
                $data['activate_time']= 1200;//激活上报时间间隔
                $data['is_check']= 0;//监听其他业务安装上报
                $data['is_survival']=21600;//统计软件自身是否存活上报
                $models=ControlPackage::model()->findAll("status=1 and type=:type",array(":type"=>$groupid));
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
     * 新统计安装上报到临时表  app_rom_appresource_temp
     * http://www.sutuiapp.com/reportApi/getInstall?appNo=700006&sign=91e1df4dee97b4cc08bf500f6ed79ae1&sys=6.0&brand=HUAWEI&times=1509341989&simcode=&phcode=861010033685899&applist=jrtt,gddt,llq&from=6&tjcode=9&model=HUAWEIMLA-AL00
     */
    public function actionGetInstall(){
        $request = Yii::app()->request;
        if(!$request->isPostRequest) {
            $this->ReturnError(400,"请求不存在");
        }
        $tongji = $request->getPost('appNo','');
        // $sign = $request->getPost('sign','');
        $sys = $request->getPost('sys','');
        $brand = $request->getPost('brand','');
        //$times = $request->getPost('times','');
        $simcode = $request->getPost('simcode','');
        $phcode = $request->getPost('phcode','');
        $applist = $request->getPost('watch','');
        $from = $request->getPost('from','');
        $tjcode = $request->getPost('tjcode','');
        $models = trim($request->getPost('model',''));
        $pcIp = Common::getIp();

        //加密验证
        /*if(!empty($times))
        {
            if(!array_key_exists($tjcode,self::$secret_key)){
                $data['msg'] = "A1005";
               $this->ReturnError("请求秘钥错误");
            }
            $cusign = strtolower(md5($tongji."_".$phcode."_".$tjcode."_".$times."_".self::$secret_key[$tjcode]));
        }
        if ($sign!=$cusign){
            $this->ReturnError("请求秘钥错误");
        }*/
        $applistnull=json_decode($applist,true);//安装业务数据转化为数组

        $phcodeArr = explode(",", $phcode);//imeicode码数组
        //sort($phcodeArr);
        $phcode=isset($phcodeArr[0])? $phcodeArr[0]:'';
        $phcode2=isset($phcodeArr[1])? $phcodeArr[1]:'';
        $type='';
        $rstmodel = RomSoftpak::model()->find('serial_number=:serial_number',array(':serial_number'=>$tongji));
        if (is_null($rstmodel)) {
            $uid='';
        }else {
            $uid = $rstmodel->uid;
        }
        foreach($applistnull as $value){
            $md5=strtoupper($value['appmd5']);
            $pkgname=$value['pkgname'];
            $version=$value['versioncode'];
            $appname=$value['appname'];

            $runtime=$value["runtime"];//手机系统时间

            $sql="INSERT INTO `app_rom_appresource_temp` (`id`,`uid`,`type`,`imeicode`,`simcode`,`tjcode`,`model`,`brand`,`sys`,`finishstatus`,`createtime`,`createstamp`,`installtime`,`installcount`,`from`,`ip`,`md5`,`pkgname`,`appname`,`version`,`phcode`,`runtime`)VALUES
('','".$uid."','".$type."','".$phcode."','".$simcode."','".$tjcode."','".$models."','".$brand."','".$sys."','0','".date("Y-m-d H:i:s")."','".strtotime(date("Y-m-d"))."','".date("Y-m-d H:i:s")."','','".$from."','".$pcIp."','".$md5."','".$pkgname."','".$appname."','".$version."','".$phcode2."','".$runtime."');";
            Yii::app()->db->createCommand($sql)->execute();
        }

    }

    /**
     * 将临时安装上报数据导入总表
     * http://www.sutuiapp.com/ceshiReportApi/ImportInstall
     */
    public function actionImportInstall(){
        /*$request = Yii::app()->request;
        $date=$request->getQuery('date', '');
        $date=empty($date)? strtotime('yesterday'):strtotime(date('Y-m-d',strtotime($date)));*/
        sleep(15);//延迟避免安装激活同时

        //查询昨日或特定日期的安装数据
        $sql="SELECT * FROM app_rom_appresource_temp WHERE  flag=0 limit 2000";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        if($data){
            foreach($data as $value){
                $md5=strtoupper($value['md5']);
                $pkgname=$value['pkgname'];
                $imeicode=$value['imeicode'];
                $uid=$value['uid'];
                $phcode=$value['phcode'];

                //判断 手机imeicode、用户、包名、MD5
                if($imeicode=='' || $uid==''|| $md5==''|| $pkgname==''){
                    //改变本条数据状态
                    $this->updateInstallFlag($value['id']);
                    //判断第二个手机imeicode是否为空
                    continue;
                }else{
                    //判断是否为平台的业务包
                    $appinfo=ProductList::model()->find('pakname=:pakname and sign=:sign and status=1',array(':pakname'=>$pkgname,':sign'=>$md5));
                    if($appinfo){
                        $type=$appinfo->type;

                        //补位处理
                        if(substr($imeicode,-1,1)==1){
                            //根据长的查询长短对照表
                            $sql="select short_imei from `app_short_imei` where long_imei='{$imeicode}'";
                            $result=yii::app()->db->createCommand($sql)->queryAll();
                            if($result){
                                /********* 看是否需要判断品牌、型号以确定是正常还是补位手机********/
                                //存在替换短的
                                $imeicode=$result[0]['short_imei'];
                            }
                        }
                        $romstatus=RomAppresource::model()->find('type=:type and imeicode=:imeicode',array(':type'=>$type,':imeicode'=>$imeicode));
                        $data=$this->cover($romstatus,$imeicode, $value['tjcode'],$type);
                        $imeicode=$data['imei']; //补位处理

                        //判断用户是否开启
                        $useresource=MemberResource::model()->find('uid=:uid and type=:type',array('uid'=>$uid,":type"=>$type));
                        if($useresource){
                            /*2017-11-06 黑名单里直接封号 start*/
                            $sql="select id from `app_blacklist` where imeicode='{$imeicode}' OR  imeicode='{$phcode}'";
                            $res=yii::app()->db->createCommand($sql)->queryAll();

                            //判断用户该手机是否已安装
                            //$romstatus=RomAppresource::model()->find('type=:type and imeicode=:imeicode',array(':type'=>$type,':imeicode'=>$imeicode));

                            $before_uid= '';
                            if($romstatus){

                                /*在数据更新之前存入数据库*/
                                $before_uid=$romstatus->uid;
                                if($before_uid!=$uid){
                                    //查看表repeatinstall_uid中有没有存当前uid第一次安装插入的数据
                                    $sql="select id from `app_rom_repeatinstall_uid` where uid={$before_uid} and type='".$type."' and imeicode='".$imeicode."' and before_uid=0";
                                    $result=yii::app()->db->createCommand($sql)->queryAll();
                                    $firettime=$romstatus->createtime;
                                    if(empty($result)){
                                        //第一次安装的数据
                                        $romstatus->installtime=$firettime;
                                        $romstatus->createtime=$value['createtime'];
                                        Common::repeatinstall_uid($romstatus, $value['from'],0);
                                        $romstatus->uid=$uid;
                                        $romstatus->installtime=$value['installtime'];
                                        Common::repeatinstall_uid($romstatus, $value['from'],$before_uid);
                                    }else{
                                        $sql="select id from `app_rom_repeatinstall_uid` where uid={$uid} and imeicode='{$imeicode}'";
                                        $add=yii::app()->db->createCommand($sql)->queryAll();
                                        if(empty($add)){
                                            $romstatus->installtime=$value['createtime'];
                                            $romstatus->createtime=$value['createtime'];
                                            $romstatus->uid=$uid;
                                            Common::repeatinstall_uid($romstatus,$value['from'],$before_uid);
                                        }
                                    }
                                }

                                //第二次安装上报
                                $romstatus->installtime= $value['installtime'];
                                $romstatus->tjcode = $value['tjcode'];
                                $romstatus->from = $value['from'];
                                $romstatus->uid= $uid;

                                $romstatus->installcount=$romstatus->installcount+1+$data['installcount'];
                                $romstatus->ip=$value['ip'];
                                $romstatus->md5=$md5;
                                $romstatus->pkgname=$pkgname;
                                $romstatus->version=$value['version'];
                                $romstatus->appname=$value['appname'];
                                $romstatus->phcode=$phcode;
                                $romstatus->runtime=$value['runtime'];//手机系统时间

                                if($useresource->status==0 ||!empty($res)){
                                    $romstatus->status=0;
                                    $romstatus->closeend=$value['createtime'];
                                }
                                //补位处理
                                if($data['flag']){
                                    $romstatus->status=0;
                                    $romstatus->closeend=$value['createtime'];
                                }
                                $romstatus->update();
                                //改变本条数据状态
                                $this->updateInstallFlag($value['id']);
                                Common::repeatInstall($romstatus,$value['from']);
                            }else{
                                //第二个imeicode为空 首次安装上报
                                if(empty($phcode)){
                                    $rmodel=new RomAppresource();
                                    $rmodel->uid=$uid;
                                    $rmodel->type=$type;
                                    $rmodel->imeicode=$imeicode;
                                    $rmodel->simcode= $value['simcode'];
                                    $rmodel->tjcode= $value['tjcode'];
                                    // 黑名单、业务不可用的直接封号
                                    if($useresource->status==0 || !empty($res))
                                    {
                                        $rmodel->status=0;
                                        $rmodel->closeend=$value['createtime'];
                                    }
                                    else
                                    {
                                        $rmodel->status=1;
                                    }
                                    if($type=='weixin')
                                    {
                                        $rmodel->status=0;
                                        $rmodel->closeend=$value['createtime'];
                                    }
                                    //补位处理
                                    if($data['flag']){
                                        $rmodel->status=0;
                                        $rmodel->closeend=$value['createtime'];
                                    }
                                    $rmodel->model=$value['model'];
                                    $rmodel->brand=$value['brand'];
                                    $rmodel->sys=$value['sys'];
                                    $rmodel->finishstatus=0;
                                    $rmodel->createtime=$value['createtime'];
                                    $rmodel->createstamp=$value['createstamp'];
                                    $rmodel->installtime=$value['installtime'];
                                    $rmodel->installcount=1+$data['installcount'];
                                    $rmodel->from=$value['from'];
                                    $rmodel->ip=$value['ip'];
                                    $rmodel->md5=$md5;
                                    $rmodel->pkgname=$pkgname;
                                    $rmodel->version=$value['version'];
                                    $rmodel->appname=$value['appname'];
                                    $rmodel->phcode=$phcode;
                                    $rmodel->runtime=$value['runtime'];//手机系统时间

                                    if(!is_null($data['romstatus_bu'])){
                                        //补位封号，合并历史记录
                                        $rmodel->installcount=1+$data['installcount'];

                                        $rmodel->simcode=$data['romstatus_bu']->simcode;
                                        $rmodel->createtime=$data['romstatus_bu']->createtime;
                                        $rmodel->createstamp=$data['romstatus_bu']->createstamp;

                                    }
                                    $rmodel->insert();
                                    //改变本条数据状态
                                    $this->updateInstallFlag($value['id']);
                                }else{
                                    //判断第二个手机imeicode是否有安装数据
                                    $romstatus=RomAppresource::model()->find('type=:type and imeicode=:imeicode',array(':type'=>$type,':imeicode'=>$phcode));
                                    $data=$this->cover($romstatus,$phcode, $value['tjcode'],$type);
                                    $imeicode=$data['imei']; //补位处理
                                    if($romstatus){
                                        //第二个手机imeicode有安装数据 则为第二次安装上报
                                        $before_uid=$romstatus->uid;
                                        if($before_uid!=$uid){
                                            //查看表repeatinstall_uid中有没有存当前uid第一次安装插入的数据
                                            $sql="select id from `app_rom_repeatinstall_uid` where uid={$before_uid} and type='".$type."' and imeicode='".$phcode."' and before_uid=0";
                                            $result=yii::app()->db->createCommand($sql)->queryAll();
                                            if(empty($result)){
                                                //第一次安装的数据
                                                $romstatus->installtime=$romstatus->createtime;
                                                Common::repeatinstall_uid($romstatus,$value['from'],0);
                                            }
                                        }

                                        $romstatus->installtime= $value['installtime'];
                                        $romstatus->tjcode = $value['tjcode'];
                                        $romstatus->from = $value['from'];
                                        $romstatus->uid= $uid;
                                        $romstatus->installcount=$romstatus->installcount+1;
                                        $romstatus->ip=$value['ip'];
                                        $romstatus->md5=$md5;
                                        $romstatus->pkgname=$pkgname;
                                        $romstatus->version=$value['version'];
                                        $romstatus->appname=$value['appname'];
                                        $romstatus->phcode=$imeicode;
                                        $romstatus->runtime=$value['runtime'];
                                        //黑名单有值 疯掉
                                        if(!empty($res) || $useresource->status==0){
                                            $romstatus->status=0;
                                            $romstatus->closeend=$value['createtime'];
                                        }
                                        $romstatus->update();
                                        //改变本条数据状态
                                        $this->updateInstallFlag($value['id']);
                                        Common::repeatInstall($romstatus,$value['from']);
                                    }else{
                                        //第二个手机imeicode无安装数据  则为第一个imeicode首次安装上报
                                        $rmodel=new RomAppresource();
                                        $rmodel->uid=$uid;
                                        $rmodel->type=$type;
                                        $rmodel->imeicode=$imeicode;
                                        $rmodel->simcode= $value['simcode'];
                                        $rmodel->tjcode= $value['tjcode'];
                                        if($useresource->status==0 || !empty($res))
                                        {
                                            // 黑名单、业务不可用的直接封号
                                            $rmodel->status=0;
                                            $rmodel->closeend=$value['createtime'];
                                        }
                                        else
                                        {
                                            $rmodel->status=1;
                                        }
                                        if($type=='weixin')
                                        {
                                            $rmodel->status=0;
                                            $rmodel->closeend=$value['createtime'];
                                        }
                                        //补位处理
                                        if($data['flag']){
                                            $rmodel->status=0;
                                            $rmodel->closeend=$value['createtime'];
                                        }
                                        $rmodel->model=$value['model'];
                                        $rmodel->brand=$value['brand'];
                                        $rmodel->sys=$value['sys'];
                                        $rmodel->finishstatus=0;
                                        $rmodel->createtime=$value['createtime'];
                                        $rmodel->createstamp=$value['createstamp'];
                                        $rmodel->installtime=$value['installtime'];
                                        $rmodel->installcount=1;
                                        $rmodel->from=$value['from'];
                                        $rmodel->ip=$value['ip'];
                                        $rmodel->md5=$md5;
                                        $rmodel->pkgname=$pkgname;
                                        $rmodel->version=$value['version'];
                                        $rmodel->appname=$value['appname'];
                                        $rmodel->phcode=$phcode;
                                        $rmodel->runtime=$value['runtime'];//手机系统时间

                                        if(!is_null($data['romstatus_bu'])){
                                            //补位封号，合并历史记录
                                            $rmodel->installcount=1+$data['installcount'];

                                            $rmodel->simcode=$data['romstatus_bu']->simcode;
                                            $rmodel->createtime=$data['romstatus_bu']->createtime;
                                            $rmodel->createstamp=$data['romstatus_bu']->createstamp;

                                        }
                                        $rmodel->insert();
                                        //改变本条数据状态
                                        $this->updateInstallFlag($value['id']);
                                        //第一次重复安装
                                        Common::repeatInstall($rmodel,$value['from']);
                                    }
                                }
                            }
                        }else{
                            //改变本条数据状态
                            $this->updateInstallFlag($value['id']);
                            //未开启过 上报新表
                            $sql="INSERT INTO `app_rom_appresource_free` (`id`,`uid`,`type`,`imeicode`,`simcode`,`tjcode`,`model`,`brand`,`sys`,`finishstatus`,`createtime`,`createstamp`,`installtime`,`installcount`,`from`,`ip`,`md5`,`pkgname`,`appname`,`version`,`phcode`,`runtime`)VALUES
('','".$uid."','".$type."','".$imeicode."','".$value['simcode']."','".$value['tjcode']."','".$value['model']."','".$value['brand']."','".$value['sys']."','0','".$value['createtime']."','".$value['createstamp']."','','','".$value['from']."','".$value['ip']."','".$md5."','".$pkgname."','".$value['appname']."','".$value['version']."','".$phcode."','".$value['runtime']."');";
                            Yii::app()->db->createCommand($sql)->execute();
                        }
                    }else{
                        //改变本条数据状态
                        $this->updateInstallFlag($value['id']);
                        //不是平台包 上报新表
                        $sql="INSERT INTO `app_rom_appresource_free` (`id`,`uid`,`imeicode`,`simcode`,`tjcode`,`model`,`brand`,`sys`,`finishstatus`,`createtime`,`createstamp`,`installtime`,`installcount`,`from`,`ip`,`md5`,`pkgname`,`appname`,`version`,`phcode`,`runtime`)VALUES
('','".$uid."','".$imeicode."','".$value['simcode']."','".$value['tjcode']."','".$value['model']."','".$value['brand']."','".$value['sys']."','0','".$value['createtime']."','".$value['createstamp']."','','','".$value['from']."','".$value['ip']."','".$md5."','".$pkgname."','".$value['appname']."','".$value['version']."','".$phcode."','".$value['runtime']."');";
                        Yii::app()->db->createCommand($sql)->execute();
                    }
                }
                //改变本条数据状态
                $this->updateInstallFlag($value['id']);

            }
        }
        exit;
    }
    // 测试：http://sutui.me/reportApi/come?imei=1234567890&tjcode=9&brand=HUAWEI&model=HUAWEI MT7-TL00&type=llq
    public function actionCome(){
        $imei=Yii::app()->request->getParam('imei');
        $tjcode=Yii::app()->request->getParam('tjcode');//tongjiapp序列号
        $brand=Yii::app()->request->getParam('brand','');
        $model=Yii::app()->request->getParam('model','');//手机型号
        $type=Yii::app()->request->getParam('type');
        $romstatus=RomAppresource::model()->find('type=:type and imeicode=:imeicode',array(':type'=>$type,':imeicode'=>$imei));
        $data=$this->cover($romstatus,$imei,$tjcode,$type,$brand,$model);
        var_dump($data);
    }
    /*
     * @name 补位imei码处理方法
     */
    protected function cover($romstatus,$imei,$tjcode,$type){
        //1.imei码长度判断
        $flag=false;
        $installcount=0;
        $romstatus_bu=null;
        if(strlen($imei)<15){
            $bu_imei=Common::getStrLength($imei);//获取补位imei码

            //根据短的查询长短对照表
            $sql="select id from `app_short_imei` where short_imei='{$imei}'";
            $result=yii::app()->db->createCommand($sql)->queryAll();
            if($result){
                a:
                //对照表存在
                //判断短imei码是否有安装数据
                //$romstatus=RomAppresource::model()->find('type=:type and imeicode=:imeicode',array(':type'=>$type,':imeicode'=>$imei));
                if($romstatus){
                    //存在短
                    $romstatus_bu=RomAppresource::model()->find('type=:type and imeicode=:imeicode',array(':type'=>$type,':imeicode'=>$bu_imei));
                    if($romstatus_bu){
                        //存在补位imei码数据 封号判断
                        if($romstatus_bu->status==0 && $romstatus_bu->closeend !='0000-00-00 00:00:00'){
                            //补位封号：短的imei码并封号判断
                            /* if($romstatus->status==0 && $romstatus_bu->closeend !='0000-00-00 00:00:00'){
                                //短的imei码封号
                                //短的封号安装上报

                            }else{
                                //短的imei码未封号
                                //短的安装上报

                            }*/
                            $romstatus_bu=null;
                        }else{
                            //补位未封号：短的imei码并封号判断
                            if($romstatus->status==0 && $romstatus->closeend !='0000-00-00 00:00:00'){
                                //短的封号：1补位imei码封号
                                $romstatus_bu->status=0;
                                $romstatus_bu->closeend=date('Y-m-d H:i:s');
                                $romstatus_bu->update();
                                //短的封号：2.短的封号安装上报
                                $romstatus_bu=null;
                            }else{
                                //短的正常：1补位imei码封号
                                $installcount=$romstatus_bu->installcount;//补位安装次数
                                $romstatus_bu->status=0;
                                $romstatus_bu->closeend=date('Y-m-d H:i:s');
                                $romstatus_bu->update();
                                //短的正常：2.补位安装上报合并到短的上 $installcount
                            }
                        }

                    }else{
                        //不存在补位imei码和短imei码安装数据
                        //短的首次安装上报

                    }
                    $data=array("imei"=>$imei,'flag'=>$flag,'installcount'=>$installcount,'romstatus_bu'=>$romstatus_bu);
                }else{
                    //不存在短 查询补位安装数据
                    $romstatus_bu=RomAppresource::model()->find('type=:type and imeicode=:imeicode',array(':type'=>$type,':imeicode'=>$bu_imei));
                    if($romstatus_bu){
                        //存在补位imei码数据 封号判断
                        if($romstatus_bu->status==0 && $romstatus_bu->closeend !='0000-00-00 00:00:00'){
                            //封号：新建短的并封号
                            $flag=true;
                            $romstatus_bu=null;
                        }else{
                            //未封号：1.补位封号
                            $installcount=$romstatus_bu->installcount;//补位安装次数
                            $romstatus_bu->status=0;
                            $romstatus_bu->closeend=date('Y-m-d H:i:s');
                            $romstatus_bu->update();
                            //2.新增短的（导入补位的历史记录） $romstatus_bu

                        }

                    }else{
                        //不存在补位imei码数据
                        //短的首次安装上报
                    }
                    $data=array("imei"=>$imei,'flag'=>$flag,'installcount'=>$installcount,'romstatus_bu'=>$romstatus_bu);
                }
            }else{
                //不存在 保存
                $sql="INSERT INTO `app_short_imei`(`id`,`short_imei`,`long_imei`,`createtime`)VALUES
                        ('','".$imei."','".$bu_imei."','".date('Y-m-d H:i:s')."')";
                Yii::app()->db->createCommand($sql)->execute();
                goto a;
            }
        }else{
            //判断版本、末尾1
            //$str=substr($imei,-1,1);
            if(substr($imei,-1,1)==1 && $tjcode<9){
                //根据长的查询长短对照表
                $sql="select short_imei from `app_short_imei` where long_imei='{$imei}'";
                $result=yii::app()->db->createCommand($sql)->queryAll();
                if($result){
                    /********* 看是否需要判断品牌、型号以确定是正常还是补位手机********/
                    //存在替换短的
                    $imei=$result[0]['short_imei'];
                }
            }
           $data=array("imei"=>$imei,'flag'=>$flag,'installcount'=>$installcount,'romstatus_bu'=>$romstatus_bu);
        }
        return $data;
    }

    /**
     *  ROM：-激活上报数据-
     *  string $phcode imei码
     *  string $simcode sim卡
     *  string $com：运营商
     *  string $runlength：运行时长（单位分，不足一分按一分算）
     *  string $runcount：运行次数
     *  string $runtime：运行时间点
     *  string $date：数据当天日期
     *  string $sys：系统版本
     *  string $type：激活安装0
     *  string $tjcode：统计内码
     *  string $appname：业务app名称
     * https://www.sutuiapp.com/reportApi/UploadData?com=46002&sign=5c84311c84bce83f731e950ba14e1e0b&mac=a4:ca:a0:2c:d9:50&from=5&tjcode=8&watch=[{"appname":"com.sutui","pkgname":"com.sutui","appmd5":"FBF1E5ED72A07BF447D5381866160F0F","date":"2017-10-23","runcount":2,"runlength":6,"runtime":"2017-10-23 11:19:26"}]&appNo=700093&sys=6.0&brand=HUAWEI&uid=1221&simcode=898600040617A5062079&phcode=861010033685899&model=HUAWEI MLA-AL00
    */
    public function actionUploadData()
    {
        $j="J3";
        $request = Yii::app()->request;
        if(!$request->isPostRequest) {
            $this->ReturnError(400,"请求不存在");
        }
        $appNo = $request->getPost('appNo','');
        //$sign = $request->getPost('sign','');
        $from = $request->getPost('from','');
        $phcode = $request->getPost('phcode','');
        $simcode = $request->getPost('simcode','');
        $tjcode = $request->getPost('tjcode','');
        $sys = $request->getPost('sys','');
        $mac = $request->getPost('mac','');
        $model =trim($request->getPost('model',''));
        $brand = $request->getPost('brand','');
        $com = $request->getPost('com','');
        $watch=$request->getPost('watch','');
        //$times = $request->getPost('times',''); `createstamp`  strtotime(date("Y-m-d"))
        $pcIp = Common::getIp();
        $type=0;//激活上报0 卸载1
        $phcodeArr = explode(",", $phcode);//imeicode码数组
        //sort($phcodeArr);
        $phcode=$phcodeArr[0];
        $phcode2=isset($phcodeArr[1])? $phcodeArr[1]:'';
        $rmodel = RomSoftpak::model()->find('serial_number=:serial_number',array(':serial_number'=>$appNo));
        if($rmodel){
            $uid=$rmodel->uid;
            $watch = json_decode($watch,true);
            foreach($watch as $wval){
                $appname='';
                $typename=$wval["appname"];
                $runlength=$wval["runlength"];//首次 扫描间隔时间 向下取整
                $runcount=$wval["runcount"];//首次1
                $runtime=$wval["runtime"];//手机时间
                $date=$wval["date"];//手机日期
                $appmd5=strtoupper($wval['appmd5']);
                $pkgname=$wval["pkgname"];
                $sql="INSERT INTO `app_rom_appupdata_temp` (`id`,`uid`,`type`,`imeicode`,`simcode`,`tjcode`,`model`,`brand`,`sys`,`createtime`,`mac`,`com`,`from`,`ip`,`appmd5`,`appname`,`runlength`,`runcount`,`runtime`,`date`,`finshstatustime`,`finshstatus`,`typename`,`pkgname`,`createstamp`,`phcode` )VALUES
('','".$uid."','".$type."','".$phcode."','".$simcode."','".$tjcode."','".$model."','".$brand."','".$sys."','".date("Y-m-d H:i:s")."','".$mac."','".$com."','".$from."','".$pcIp."','".$appmd5."','".$appname."','".$runlength."','".$runcount."','".$runtime."','".$date."','','','".$typename."','".$pkgname."','".strtotime(date("Y-m-d"))."','".$phcode2."');";
                Yii::app()->db->createCommand($sql)->execute();
            }
        }
    }
    /**
     * 将临时激活数据导入总表
     * http://www.sutuiapp.com/reportApi/ImportUploadData
     */
    public function actionImportUploadData(){
       /* $request = Yii::app()->request;
        $date=$request->getQuery('date', '');
        $date=empty($date)?strtotime('yesterday'):strtotime(date('Y-m-d',strtotime($date)));*/
        //查询昨日或特定日期的安装数据
        $sql="SELECT * FROM app_rom_appupdata_temp WHERE flag=0 limit 1000";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        //var_dump($data);exit;
        if($data){
            foreach($data as $value){
                $md5=$value['appmd5'];
                $pkgname=$value['pkgname'];
                $imeicode=$value['imeicode'];
                $uid=$value['uid'];
                $phcode=$value['phcode'];
                $runlength=ceil($value['runlength']/60);
                //补位处理
                if(substr($imeicode,-1,1)==1){
                    //根据长的查询长短对照表
                    $sql="select short_imei from `app_short_imei` where long_imei='{$imeicode}'";
                    $result=yii::app()->db->createCommand($sql)->queryAll();
                    if($result){
                        /********* 看是否需要判断品牌、型号以确定是正常还是补位手机********/
                        //存在替换短的
                        $imeicode=$result[0]['short_imei'];
                    }
                }
                //判断是否为平台的业务包
                $prolist=ProductList::model()->find('sign=:sign and pakname=:pakname',array(':sign'=>$md5,':pakname'=>$pkgname));
                if(empty($prolist)){
                    //改变本条数据状态
                    $this->updateFlag($value['id']);
                    //不是平台包 上报新表
                    $sql="INSERT INTO `app_rom_appupdata_free` (`id`, `uid`, `simcode`, `sys`, `mac`, `imeicode`, `tjcode`, `model`, `brand`, `com`, `appname`, `runlength`, `runcount`, `runtime`, `type`, `date`, `appmd5`, `createtime`, `finshstatus`, `finshstatustime`, `ip`, `from`, `phcode`) VALUES
('','".$uid."','".$value['simcode']."',	'".$value['sys']."',	'".$value['mac']."',	'".$value['imeicode']."',	'".$value['tjcode']."','".$value['model']."','".$value['brand']."','".$value['com']."','".$value['appname']."','".$runlength."','".$value['runcount']."','".$value['runtime']."','".$value['type']."','".$value['date']."',	'".$value['appmd5']."','".$value['createtime']."','".$value['finshstatus']."','".$value['finshstatustime']."','".$value['ip']."','".$value['from']."','".$value['phcode']."');";
                    Yii::app()->db->createCommand($sql)->execute();
                    continue;
                }else{
                    if($value['simcode']==''){
                        //改变本条数据状态
                        $this->updateFlag($value['id']);
                        //无sim卡不上报激活数据，
                        continue;
                    }
                    $typename=$prolist->type; //平台的业务
                    //业务封号不上报
                    if($prolist->status==0){
                        //改变本条数据状态
                        $this->updateFlag($value['id']);
                        $status=1;
                        $applog=$this->actionAppupdataLog($uid,$value['simcode'],$value['sys'],$value['mac'],$imeicode, $value['tjcode'],$value['model'],$typename,$runlength,$value['runcount'],$value['type'],$md5,$status,$value['from'],$value['createtime']);
                        continue;
                    }

                    //判断用户是否开启
                    $useresource=MemberResource::model()->find('uid=:uid and type=:type',array('uid'=>$uid,":type"=>$typename));
                   if(!isset($useresource)){
                       //改变本条数据状态
                       $this->updateFlag($value['id']);
                        //未开启业务
                       continue;
                   }
                    if($useresource->status==0){
                        //改变本条数据状态
                        $this->updateFlag($value['id']);
                        //业务封号
                        $status=1;
                        $applog=$this->actionAppupdataLog($uid,$value['simcode'],$value['sys'],$value['mac'],$imeicode, $value['tjcode'],$value['model'],$typename,$runlength,$value['runcount'],$value['type'],$md5,$status,$value['from'],$value['createtime']);
                        continue;
                    }
                    //如果安装表中不存在此imeicode，则判断作弊
                    $remodel=RomAppresource::model()->find('type=:type and imeicode=:imeicode',array(':type'=>$typename,':imeicode'=>$imeicode));
                    if(!isset($remodel) && !empty($phcode)){
                        //$remodel=RomAppresource::model()->find('pkgname=:pkgname and imeicode=:imeicode and md5=:md5',array(':pkgname'=>$pkgname,':imeicode'=>$phcode,":md5"=>$md5));
                        $remodel=RomAppresource::model()->find('type=:type and imeicode=:imeicode',array(':type'=>$typename,':imeicode'=>$phcode));
                    }
                    //无安装数据
                    if(!isset($remodel)){
                        //改变本条数据状态
                        $this->updateFlag($value['id']);
                        continue;//不做处理temp
                    }
                    //完成激活或封号不上报
                    if($remodel["finishstatus"]==1){
                        //改变本条数据状态
                        $this->updateFlag($value['id']);
                        $status=1;
                        $applog=$this->actionAppupdataLog($uid,$value['simcode'],$value['sys'],$value['mac'],$imeicode, $value['tjcode'],$value['model'],$typename,$runlength,$value['runcount'],$value['type'],$md5,$status,$value['from'],$value['createtime']);
                        continue;
                    }
                    //判断是作弊用户--封号状态（此手机业务封号，并非用户业务封号）
                    if($remodel["closeend"]!="0000-00-00 00:00:00"){
                        //改变本条数据状态
                        $this->updateFlag($value['id']);
                        $status=1;
                        $applog=$this->actionAppupdataLog($uid,$value['simcode'],$value['sys'],$value['mac'],$imeicode, $value['tjcode'],$value['model'],$typename,$runlength,$value['runcount'],$value['type'],$md5,$status,$value['from'],$value['createtime']);
                        continue;
                    }else{
                        //未完成激活--需上报
                        $upmodel = new RomAppupdata();
                        $upmodel->uid = $uid;
                        $upmodel->simcode = $value['simcode'];
                        $upmodel->sys = $value['sys'];
                        $upmodel->imeicode = $imeicode;
                        $upmodel->tjcode = $value['tjcode'];
                        $upmodel->mac = $value['mac'];
                        $upmodel->com = $value['com'];
                        $upmodel->model = $value['model'];
                        $upmodel->brand = $value['brand'];
                        $upmodel->appname = $typename;
                        $upmodel->runlength = $runlength;
                        $upmodel->runcount = $value['runcount'];
                        $upmodel->runtime = $value['runtime'];
                        $upmodel->type = $value['type'];
                        $upmodel->date = $value['date'];
                        $upmodel->appmd5 = $md5;
                        $upmodel->from = $value['from'];
                        $upmodel->createtime = $value['createtime'];
                        $upmodel->ip=$value['ip'];
                        $upmodel->phcode=$phcode;
                        $upmodel->insert();

                        //改变本条数据状态
                        $this->updateFlag($value['id']);
                        $status=0;
                        $applog=$this->actionAppupdataLog($uid,$value['simcode'],$value['sys'],$value['mac'],$imeicode, $value['tjcode'],$value['model'],$typename,$runlength,$value['runcount'],$value['type'],$md5,$status,$value['from'],$value['createtime']);

                        //补充安装时没有sim记录
                        if(empty($remodel["simcode"]))
                        {
                            $remodel->simcode= $value['simcode'];
                            $remodel->update();
                        }
                    }
                }
                //改变本条数据状态
                $this->updateFlag($value['id']);
            }
        }
        exit;
    }

    /*
     * 更新安装上报临时表状态
     */
    protected function updateInstallFlag($id){
        $time=time();
        //改变本条数据状态
        $sqlupdate = "update `app_rom_appresource_temp` set `flag` =1,`splittime` ={$time} where id = {$id}";
        Yii::app()->db->createCommand($sqlupdate)->execute();
    }
    /*
     * 更新激活上报临时表状态
     */
    protected function updateFlag($id){
        $time=time();
        //改变本条数据状态
        $sqlupdate = "update `app_rom_appupdata_temp` set `flag` =1,`splittime` ={$time} where id = {$id}";
        Yii::app()->db->createCommand($sqlupdate)->execute();
    }

    /**
     * @name 卸载上报
     *  string $phcode imei码
     *  string $simcode sim卡
     *  string $com：运营商
     *  string $runlength：运行时长（单位秒，不足一分按一分算）
     *  string $runcount：运行次数
     *  string $runtime：运行时间点
     *  string $date：数据当天日期
     *  string $sys：系统版本
     *  string $type：激活安装0
     *  string $tjcode：统计内码
     *  string $appname：业务app名称
     * https://www.sutuiapp.com/reportApi/UninstallData?com=46002&sign=5c84311c84bce83f731e950ba14e1e0b&mac=a4:ca:a0:2c:d9:50&from=5&tjcode=8&watch=[{"appname":"com.sutui","pkgname":"com.sutui","appmd5":"FBF1E5ED72A07BF447D5381866160F0F","date":"2017-10-23","runcount":2,"runlength":6,"runtime":"2017-10-23 11:19:26"}]&appNo=700093&sys=6.0&brand=HUAWEI&uid=1221&simcode=898600040617A5062079&phcode=861010033685899&model=HUAWEI MLA-AL00
     */
    public function actionUninstallData()
    {
        $j="J3";
        $request = Yii::app()->request;
        if(!$request->isPostRequest) {
            $this->ReturnError(400,"请求不存在");
        }
        $appNo = $request->getPost('appNo','');
        //$sign = $request->getPost('sign','');
        $from = $request->getPost('from','');
        $phcode = $request->getPost('phcode','');
        $simcode = $request->getPost('simcode','');
        $tjcode = $request->getPost('tjcode','');
        $sys = $request->getPost('sys','');
        $mac = $request->getPost('mac','');
        $model =trim($request->getPost('model',''));
        $brand = $request->getPost('brand','');
        $com = $request->getPost('com','');
        $watch=$request->getPost('watch','');
        //$times = $request->getPost('times','');
        $pcIp = Common::getIp();
        $type=1;//激活上报0 卸载1
        $phcodeArr = explode(",", $phcode);//imeicode码数组
        //sort($phcodeArr);
        $phcode=$phcodeArr[0];
        $phcode2=isset($phcodeArr[1])? $phcodeArr[1]:'';
        $rmodel = RomSoftpak::model()->find('serial_number=:serial_number',array(':serial_number'=>$appNo));
        if($rmodel){
            $uid=$rmodel->uid;
            $watch = json_decode($watch,true);
            foreach($watch as $wval){
                $appname='';
                $typename=$wval["appname"];
                $runlength=$wval["runlength"];//首次 扫描间隔时间 向下取整
                $runcount=$wval["runcount"];
                $runtime=$wval["runtime"];
                $date=$wval["date"];
                $appmd5=strtoupper($wval['appmd5']);
                $pkgname=$wval["pkgname"];
                $sql="INSERT INTO `app_rom_appupdata_temp` (`id`,`uid`,`type`,`imeicode`,`simcode`,`tjcode`,`model`,`brand`,`sys`,`createtime`,`mac`,`com`,`from`,`ip`,`appmd5`,`appname`,`runlength`,`runcount`,`runtime`,`date`,`finshstatustime`,`finshstatus`,`typename`,`pkgname`,`createstamp`,`phcode` )VALUES
('','".$uid."','".$type."','".$phcode."','".$simcode."','".$tjcode."','".$model."','".$brand."','".$sys."','".date("Y-m-d H:i:s")."','".$mac."','".$com."','".$from."','".$pcIp."','".$appmd5."','".$appname."','".$runlength."','".$runcount."','".$runtime."','".$date."','','','".$typename."','".$pkgname."','".strtotime(date("Y-m-d"))."','".$phcode2."');";
                Yii::app()->db->createCommand($sql)->execute();
            }
        }
    }

    /**
     * 上报数据到日志表
     * */
    public function actionAppupdataLog($uid,$simcode,$sys,$mac,$phcode,$tjcode,$model,$appname,$runlength,$runcount,$type,$appmd5,$status,$from,$createtime)
    {
        $day=date("Y-m-d",strtotime($createtime));
        $first_data=RomAppupdatalog::model()->find('imeicode=:imeicode and appname=:appname and first=:first',array(':imeicode'=>$phcode,':appname'=>$appname,':first'=>1));
        if(empty($first_data))
        {
            //记录
            $first=1;
            $this->actionAppLog($uid,$simcode,$sys,$mac,$phcode,$tjcode,$model,$appname,$runlength,$runcount,$type,$appmd5,$first,$status,$from,$createtime);
            return array($appname,"0");
        }
        elseif(!empty($first_data) && $this->diffBetweenTwoDays(date("Y-m-d",strtotime($first_data["createtime"])),date("Y-m-d"))<=60)
        {
            $first=0;
            if($type==1)
            {
                //记录
                $this->actionAppLog($uid,$simcode,$sys,$mac,$phcode,$tjcode,$model,$appname,$runlength,$runcount,$type,$appmd5,$first,$status,$from,$createtime);
                return array($appname,"1");
            }
            else
            {
                $curr_data=RomAppupdatalog::model()->find('imeicode=:imeicode and appname=:appname and first!=:first and createtime like "%'.$day.'%"',array(':imeicode'=>$phcode,':appname'=>$appname,':first'=>1));

                //记录
                if(empty($curr_data))
                {
                    $this->actionAppLog($uid,$simcode,$sys,$mac,$phcode,$tjcode,$model,$appname,$runlength,$runcount,$type,$appmd5,$first,$status,$from,$createtime);
                }
                return array($appname,"0");
            }
        }
        elseif(!empty($first_data) && $this->diffBetweenTwoDays(date("Y-m-d",strtotime($first_data["createtime"])),date("Y-m-d"))>60)
        {
            //日志表超过60天记录返回卸载
            return array($appname,"1");
        }
    }

    /**
     * 上报数据到日志表
     * */
    public function actionAppLog($uid,$simcode,$sys,$mac,$phcode,$tjcode,$model,$appname,$runlength,$runcount,$type,$appmd5,$first,$status,$from,$createtime){
        $upmodel = new RomAppupdatalog();
        $upmodel->uid = $uid;
        $upmodel->simcode = $simcode;
        $upmodel->sys = $sys;$upmodel->mac = $mac;
        $upmodel->imeicode = $phcode;
        $upmodel->tjcode = $tjcode;
        $upmodel->model = $model;
        $upmodel->appname = $appname;
        $upmodel->runlength = $runlength;
        $upmodel->runcount = $runcount;
        $upmodel->type = $type;
        $upmodel->appmd5 = $appmd5;
        $upmodel->createtime =$createtime;
        $upmodel->first = $first;
        $upmodel->status = $status;
        $upmodel->from = $from;
        $upmodel->ip=Common::getIp();
        $upmodel->insert();
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
    //日期相差天数
    protected function diffBetweenTwoDays ($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);

        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ($second1 - $second2) / 86400;
    }
}