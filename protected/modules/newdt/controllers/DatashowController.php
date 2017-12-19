<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/14
 * Time: 16:19
 * 产品业务
 */

class DatashowController extends NewdtController
{
    /**
     * 安装查询
     */
    public function actionExtmodel($imeicode = '')
    {
        $uid=$this->uid;

        //日数据
        $modelList = RomAppresource::model()->findAll("uid={$uid} and imeicode=:imeicode order by installtime desc limit 1",array(":imeicode"=>$imeicode));

        $this->render('extmodel', array(
            'dataProvider' => new CArrayDataProvider($modelList, array(
                'pagination' => array(
                    'pageSize' => Common::PAGE_SIZE,
                ),
            )),
        ));
    }
    /**
     * 安装量分析
     */
    public function actionInstalcheck($date = '',$date2 = '')
    {
        Script::registerCssFile('asyncbox.css');
        Script::registerScriptFile(Script::HIGHSTOCK);
        Script::registerScriptFile('manage/instalcheck.graphs.js');

        $date = empty($date) ? date('Y-m-01', strtotime('-1 day')) : $date;
        $datestr=strtotime($date);
        $date2 = empty($date2) ? date('Y-m-d', strtotime('-1 day')) : $date2;
        $date2str=strtotime($date2);
        $uid=$this->uid;
        //总数据统计
        $sql_month ="select count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and createstamp >= {$datestr} and createstamp <= {$date2str}";
        $monthdata = Yii::app()->db->createCommand($sql_month)->queryAll();

        /* 2017-11-09 去这个表app_rom_repeatinstall_uid 查被替换调的*/
        $sql_month2="select distinct(imeicode) as imeicode from app_rom_repeatinstall_uid where uid={$uid} and createstamp >= {$datestr} and createstamp <= {$date2str}";
        $result=yii::app()->db->createCommand($sql_month2)->queryAll();
        $imei=array();
        if(!empty($result)){
                foreach ($result as $key => $value) {
                    $sql="select id from `app_rom_appresource` where uid={$uid} and imeicode='{$value['imeicode']}'";

                    $resu=yii::app()->db->createCommand($sql)->queryAll();
                    if(empty($resu)){
                        $imei[]['imeicode']=$value['imeicode'];
                        $monthdata[0]['counts']+=1;
                    }
                }
            
        }
        /*2017-11-09 end*/



        //日查询--判断安装量：未完成+当天日期（其它条件查询无效）
        $sql ="select id,uid,createstamp,model,count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and  createstamp >= {$datestr} and createstamp <= {$date2str}  group by  model order by counts desc";
        $modelList = Yii::app()->db->createCommand($sql)->queryAll();

        /*2017-11-09 去这个表app_rom_repeatinstall_uid 查被替换调的*/
        $model=array();
        foreach ($modelList as $key => $value) {
            $model[]=$value['model'];
        }
        $array=array();

        //一个手机对应一个imeicode对应一个手机型号
        if(!empty($imei)){
            foreach ($imei as $k => $value) {
                $sql="select id,uid,model,createstamp from `app_rom_repeatinstall_uid` where imeicode='{$value['imeicode']}' and uid={$uid} limit 1";
                $result=yii::app()->db->createCommand($sql)->queryAll();
                foreach ($result as $key => $value) {
                    if(in_array($value['model'],$model)){
                        foreach ($modelList as $k => $v) {
                            if($value['model']==$v['model']){
                                $modelList[$k]['counts']+=1;
                            }
                        }
                    }else{
                        $array[$k]['id']=$value['id'];
                        $array[$k]['uid']=$value['uid'];
                        $array[$k]['createstamp']=$value['createstamp'];
                        $array[$k]['model']=$value['model'];
                        $array[$k]['counts']=1; 
                    }
                        
                }
                        

            }
        }

        if(!empty($array)){
            $modelList=array_merge($modelList,$array);
        }



        //单独用户anxiaozhu1005 单独曲线图 $uid=21
        $income = array();

        $sql="select createstamp,count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and createstamp >= {$datestr} and createstamp <= {$date2str} group by createstamp order by createstamp";
        $modelListt = Yii::app()->db->createCommand($sql)->queryAll();

        /*2017-11-09 去这个表app_rom_repeatinstall_uid 查被替换调的*/
        $datetime=array();
        foreach ($modelListt as $key => $value) {
            $datetime[]=$value['createstamp'];
        }

        $sql="select createstamp,count(distinct(imeicode)) as counts from app_rom_repeatinstall_uid where uid={$uid} and createstamp >= {$datestr} and createstamp <= {$date2str} group by createstamp order by createstamp";
        $result=yii::app()->db->createCommand($sql)->queryAll();
        $time=array();
        if (!empty($result)){
                foreach ($result as $k=> $v){
                    $sql="select distinct(imeicode) as imeicode from `app_rom_repeatinstall_uid` where  createstamp='{$v['createstamp']}' and uid={$uid}";
                    $result=yii::app()->db->createCommand($sql)->queryAll();
                    if(!empty($result)){
                        foreach ($result as $key => $value) {
                            $sql="select id from `app_rom_appresource` where createstamp='{$v['createstamp']}' and uid={$uid} and imeicode='{$value['imeicode']}'";
                            $data=yii::app()->db->createCommand($sql)->queryAll();
                            if(!empty($data)){

                            }else{
                                if(in_array($v['createstamp'],$datetime)){
                                    foreach ($modelListt as $ke => $va) {
                                        if($v['createstamp']==$va['createstamp']){
                                            $modelListt[$ke]['counts']+=1;
                                        }
                                    }
                                }else{
                                    $time[$k]['createstamp']=$v['createstamp'];
                                    $time[$k]['counts']=$v['counts'];
                                }
                            }

                        }
                    }

                    
                }
        }
        if(!empty($time)){
            $modelListt=array_merge($modelListt,$time);
        }
        

        foreach($modelListt as $k=> $v){
            foreach($v as $kk=>$vv){
                $datee = $v['createstamp'];
                $income[$k][$kk]=$v[$kk];
                $income[$k]['y']=intval(date('Y', $datee));
                $income[$k]['m']=intval(date('m', $datee));
                $income[$k]['d']=intval(date('d', $datee));
            }
        }
        // var_dump($income);


        $this->render('instalcheck', array(
            'dataProvider' => new CArrayDataProvider($modelList, array(
                'pagination' => array(
                    'pageSize' => Common::PAGE_SIZE,
                ),
            )),
            'json' => json_encode($income),
            'uid'=>$uid,
            'date' => $date,
            'date2' => $date2,
            'monthdata' => $monthdata
        ));
    }
    /**
     * 应用安装排行
     */
    public function actionActivatop()
    {

        // 增加缓存,提高访问速度
        if (Yii::app()->cache->get('newdt_datashow_activatop')==false){
            $sql_all ="select id,type,count(type) as counts from app_rom_appresource group by type order by counts desc limit 10";
            $activadata = Yii::app()->db->createCommand($sql_all)->queryAll();

            if(!empty($activadata))
            {
                foreach($activadata as $key=>$val)
                {
                    $proinfo=Product::model()->find('pathname=:pathname',array(":pathname"=>$val["type"]));
                    if($proinfo["status"]==1)
                    {
                        $activadata[$key]["name"]=$proinfo["name"];
                        $activadata[$key]["pic"]=$proinfo["pic"];
                        $activadata[$key]["content"]=$proinfo["content"];
                    }
                    else
                    {
                        unset($activadata[$key]);
                    }

                }
            }
            $array_str = serialize($activadata);// 序列化数组
            // 数组存缓存中
            Yii::app()->cache->set('newdt_datashow_activatop', $array_str,86400);
        }
        $cache = Yii::app()->cache->get('newdt_datashow_activatop');
        // 反序列化
        $arr = unserialize($cache);

        $this->render('activatop', array(
            'dataProvider' => new CArrayDataProvider($arr, array(
                'pagination' => array(
                    'pageSize' => Common::PAGE_SIZE,
                ),
            )),
        ));
    }
    /**
     * 热门机型排行
     */
    public function actionModeltop()
    {
        $sql_all ="select id,model,count(model) as counts from app_rom_appresource group by model order by counts desc limit 20";


        // 增加缓存,提高访问速度
        if (Yii::app()->cache->get('newdt_datashow_modeltop')==false){
            $activadata = Yii::app()->db->createCommand($sql_all)->queryAll();
            $array_str = serialize($activadata);// 序列化数组
            // 数组存缓存中
            Yii::app()->cache->set('newdt_datashow_modeltop', $array_str,86400);
        }
        $cache = Yii::app()->cache->get('newdt_datashow_modeltop');
        // 反序列化
        $arr = unserialize($cache);

        $this->render('modeltop', array(
            'dataProvider' => new CArrayDataProvider($arr, array(
                'pagination' => array(
                    'pageSize' => Common::PAGE_SIZE,
                ),
            )),
        ));
    }


    /**
     * 业务说明
     */
    public function actionInstruction()
    {
        $this->render('instruction', array());
    }

    /**
     * 盒子套餐配置
     * @para string $type类型
     */
    public function actionBoxmanage(){
        $uid = $this->uid;
        $model = new Softbox();
        $model->uidsearch($uid);
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Softbox'])) {
            $model->attributes = $_GET['Softbox'];
        }
        $this->render("boxmanage",array("model"=>$model));
    }
    /**
     * 装机助手套餐配置
     * @para string $type类型
     */
    public function actionHelp(){
        $uid = $this->uid;
        $data = Softbox::model()->find('uid=:uid and box_number=:box_number',array(':uid'=>$uid,':box_number'=>'MDAZRJ'));
        $package = RomBoxPackage::model()->find('uid=:uid and box_number=:box_number',array(':uid'=>$uid,':box_number'=>'MDAZRJ'));
        if (empty($data) && empty($package)){
            $this::userSoftbox($uid);
            $this::userBoxPackage($uid);
        }else if (!empty($data)&&empty($package)){
            $this::userBoxPackage($uid);
        }else if (empty($data)&&!empty($package)){
            $this::userSoftbox($uid);
        }
        $data = Softbox::model()->find('uid=:uid and box_number=:box_number',array(':uid'=>$uid,':box_number'=>'MDAZRJ'));
        $this->render("help",array("model"=>$data));
    }

    /**
     * 路由器套餐配置
     * @para string $type类型
     */
    public function actionRoute(){
        $uid = $this->uid;
        $model = new Softroute();
        $model->uidsearch($uid);
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Softroute'])) {
            $model->attributes = $_GET['Softroute'];
        }
        $this->render("route",array("model"=>$model));

    }

    // softbox用户绑定套餐添加
    private function userSoftbox($uid){
        $admin = new Softbox();
        $admin->uid=$uid;
        $admin->box_number='MDAZRJ';
        $admin->status = 1;
        $admin->createtime = time();
        $admin->updatetime = time();
        $admin->mid = 0;
        $admin->type = 0;
        try{
            if ($admin->insert()){

            }else{
                throw new CHttpException(404, '页面出错,请刷新后重试');
            }
        }catch (Exception $e){
            throw new CHttpException(404, $e->getMessage());
        }
    }

    // box_package用户绑定套餐添加
    private function userBoxPackage($uid){
        $model = new RomBoxPackage();
        $model->uid=$uid;
        $model->pack_id = 1;
        $model->box_number="MDAZRJ";
        $model->status = 1;
        try{
            if ($model->insert()){

            }else{
                throw new CHttpException(404, '页面出错,请刷新后重试');
            }
        }catch (Exception $e){
            throw new CHttpException(404, $e->getMessage());
        }
    }

    // ajax获取套餐信息
    public function actionAjaxPackage(){
        $mm=Member::model()->findByPk($this->uid);
        $member_agent=$mm['agent'];
        if (Yii::app()->request->isAjaxRequest) {

            // 判断套餐默认选择项
            $boxcode = Yii::app()->request->getParam('boxcode');
            $cate = Yii::app()->request->getParam('cate');
            $databox = RomBoxPackage::model()->findAll('box_number=:box_number',array(':box_number'=>$boxcode));
            if($member_agent==96){
                $mem_data = "SELECT id FROM `app_member` where agent=96 AND status=1";
                $mem_model = Yii::app()->db->createCommand($mem_data)->queryAll();

                foreach($mem_model as $v){
                    $model[]=$v['id'];
                }
                $data = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('app_rom_package')
                    ->where(array('in', 'uid',$model ))
                    ->queryAll();
            }else{
                //根据agent 判断分组套餐
                if($cate=='route'){
                    $type=2;
                    $sql = "(select id,package_name from app_rom_package where uid = 0 and gid={$member_agent} and sign = 1 and type=2 order by id asc)";
                }elseif($cate=='help'){
                    $type=1;
                    $sql = "(select id,package_name from app_rom_package where uid = 0 and gid={$member_agent} and sign = 1 and type=1 order by id asc)";
                }else{
                    $type=0;
                    $sql = "(select id,package_name from app_rom_package where uid = 0 and gid={$member_agent} and sign = 1 and type=0 order by id asc)";
                }

                $data = Yii::app()->db->createCommand($sql)->queryAll();
            }


            $sqlcustom = "(select id,package_name from app_rom_package where uid={$this->uid} and sign = 0 and type={$type} order by id asc)";
            $datacustom = Yii::app()->db->createCommand($sqlcustom)->queryAll();

            if (empty($databox)){
                $id = 0;
            }else{
                $id = $databox[0]['pack_id'];
            }
            if (empty($datacustom)){
                echo CJSON::encode(array('val' => $data,'val1'=>$id));
            }else{
                $c = array_merge($data,$datacustom);
                echo CJSON::encode(array('val' => $c,'val1'=>$id));
            }
        }
    }


    // 选择套餐后,传值入数据库
    public function actionGetAjax(){
        if (Yii::app()->request->isAjaxRequest) {
            $packageid = Yii::app()->request->getParam('packageid');
            $boxcode = Yii::app()->request->getParam('boxcode');
            $cate = Yii::app()->request->getParam('cate');
            // 判断RomBoxPackage中是否已经存在对应设备码数据
            $databoxPackage = RomBoxPackage::model()->findAll('box_number=:box_number',array(':box_number'=>$boxcode));
            if (empty($databoxPackage)) {
                if($cate=='route')
                    $databox = Softroute::model()->findAll('route_number=:box_number',array(':box_number' => $boxcode));
                else
                    $databox = Softbox::model()->findAll('box_number=:box_number',array(':box_number' => $boxcode));
                $model = new RomBoxPackage();
                $model->uid = $databox[0]['uid'];
                $model->pack_id = $packageid;
                $model->box_number = $boxcode;
                $model->status = 1;

                if ($model->insert()) {
                    echo CJSON::encode(array('val' => 'success'));
                } else {
                    echo CJSON::encode(array('val' => 'fail'));
                }
            }else{
                RomBoxPackage::model()->updateAll(array('pack_id'=>$packageid),'box_number=:box_number',array(':box_number'=>$boxcode));
            }

        }
    }


}