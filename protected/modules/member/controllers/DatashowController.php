<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/14
 * Time: 16:19
 * 产品业务
 */

class DatashowController extends MemberController
{
    /**
     * 安装查询
     * 客户最近一次安装测试手机型号时间
     * 从app_rom_appresource,app_rom_repeatinstall,app_rom_repeatinstall_uid中获取installtime最大的那一列值
     * 思路：从这三个表中求出各个最大的时间，最后这三个时间作对比，最大的时间显示这列数据
     */
    public function actionExtmodel($imeicode = '')
    {
        $uid=$this->uid;

        //
        $sql="select id,installtime,model from `app_rom_appresource` where uid={$uid} and imeicode='{$imeicode}' order by installtime desc limit 1";
        $data=yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($data)){
            $time1=$data[0]['installtime'];
        }else{
            $time1='1999-11-11 00:00:00';
        }

        $sql="select id,installtime,model from `app_rom_repeatinstall` where uid={$uid} and imeicode='{$imeicode}' order by installtime desc limit 1";
        $arr=yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($arr)){
            $time2=$arr[0]['installtime'];
        }else{
            $time2='1990-11-11 00:00:00';
        }

        $sql="select id,installtime,model from `app_rom_repeatinstall_uid` where uid={$uid} and imeicode='{$imeicode}' order by installtime desc limit 1";
        $att=yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($att)){
            $time3=$att[0]['installtime'];
        }else{
            $time3='1999-11-11 00:00:00';
        }

        if(!empty($data) || !empty($arr) || !empty($att)){
            if(strtotime($time1) >= strtotime($time2) && strtotime($time1) >= strtotime($time3)){
                $list=$data;
            }
            else if(strtotime($time2) >= strtotime($time1) && strtotime($time2) >= strtotime($time3) && $time2!='1990-11-11 00:00:00'){
                $list=$arr;

            }else if(strtotime($time3) >= strtotime($time1) && strtotime($time3) >= strtotime($time2) && $time3!='1990-11-11 00:00:00'){
                $list=$att;
            }
        }else{
            $list=array();
        }
// print_r($list);exit;
        $this->render('extmodel', array(
            'dataProvider' => new CArrayDataProvider($list, array(
                'pagination' => array(
                    'pageSize' => Common::PAGE_SIZE,
                ),
            )),
        'iemi'=>$imeicode));
        

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
        $curdate = strtotime(date('Y-m-d'));// 获取当前日期
        //日查询--判断安装量：未完成+当天日期（其它条件查询无效）
        $arr = $this::judgeStampAndType($datestr,$date2str,2);
        if (in_array($curdate,$arr)){
            $key=array_search($curdate ,$arr);
            array_splice($arr,$key,1);
            $sql ="select id,uid,createstamp,model,count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and  createstamp = {$curdate}  group by createstamp,model order by counts desc";
            $curmodel = Yii::app()->db->createCommand($sql)->queryAll();
            //(当天)查找app_rom_repeatinstall_uid中的数据
            $sql_month2="select distinct(imeicode) as imeicode from app_rom_repeatinstall_uid where uid={$uid} and createstamp = {$datestr} ";
            $result=yii::app()->db->createCommand($sql_month2)->queryAll();
            $imei=array();
            if(!empty($result)){
                    foreach ($result as $key => $value) {
                        $sql="select id from `app_rom_appresource` where uid={$uid} and imeicode='{$value['imeicode']}' and createstamp = {$datestr}";

                        $resu=yii::app()->db->createCommand($sql)->queryAll();
                        if(empty($resu)){
                           $curmodel[0]['counts']+=1;
                        }
                }
            
            } 

        }
// print_r($curmodel);exit;
        /*2017-11-06 加上app_rom_repeatinstall_uid 里面的数据*/
        if (!empty($arr)){
            $arrstr = implode(',',$arr);
            $mysql="select id,uid,createstamp,model,count(distinct(imeicode)) as counts from app_rom_repeatinstall_uid where uid={$uid} and  createstamp in ($arrstr)  group by createstamp,model order by counts desc ";
    
            $result=yii::app()->db->createCommand($mysql)->queryAll();
            
            if (!empty($result)){
                foreach ($result as $k=> $v){
                    $sql="select distinct(imeicode) as imeicode from `app_rom_repeatinstall_uid` where model='{$v['model']}' and createstamp='{$v['createstamp']}' and uid={$uid}";
                    $result=yii::app()->db->createCommand($sql)->queryAll();
                    if(!empty($result)){
                        foreach ($result as $key => $value) {
                            $sql="select id from `app_rom_appresource` where model='{$v['model']}' and createstamp='{$v['createstamp']}' and uid={$uid} and imeicode='{$value['imeicode']}'";
                            $data=yii::app()->db->createCommand($sql)->queryAll();
                            if(!empty($data)){

                            }else{
                                $model = new RomInstalcheck();
                                $model->uid = $v['uid'];
                                $model->counts = $v['counts'];
                                $model->createstamp = $v['createstamp'];
                                $model->model = $v['model'];
                                $model->type = 2;
                                $model->insert(); 
                            }

                        }
                    }

                    
                }
            }else{
                foreach ($arr as $vt){
                    $model = new RomInstalcheck();
                    $model->uid = $uid;
                    $model->counts = 0;
                    $model->createstamp = $vt;
                    $model->model = '';
                    $model->type = 2;
                    $model->insert();
                }
            }
        }
        /*2017-11-06 end */
        if (!empty($arr)){
            $arrstr = implode(',',$arr);
            $sql ="select id,uid,createstamp,model,count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and  createstamp in ($arrstr)  group by createstamp,model order by counts desc";
            $modelList = Yii::app()->db->createCommand($sql)->queryAll();
            if (!empty($modelList)){
                foreach ($modelList as $k=> $v){
                    $model = new RomInstalcheck();
                    $model->uid = $v['uid'];
                    $model->counts = $v['counts'];
                    $model->createstamp = $v['createstamp'];
                    $model->model = $v['model'];
                    $model->type = 2;
                    $model->insert();
                }
            }else{
                foreach ($arr as $vt){
                    $model = new RomInstalcheck();
                    $model->uid = $uid;
                    $model->counts = 0;
                    $model->createstamp = $vt;
                    $model->model = '';
                    $model->type = 2;
                    $model->insert();
                }
            }
        }

          $sql ="select id,uid,sum(counts) as counts,model from app_rom_instalcheck where uid={$uid} and type=2 and  createstamp >= {$datestr} and createstamp <= {$date2str}  group by  model order by counts desc";
          $modelList1 = Yii::app()->db->createCommand($sql)->queryAll();
          $b = array();
          if (!empty($curmodel)){
            foreach ($modelList1 as $v){
                $b[$v['model']] = $v;
            }
              foreach ($curmodel as $v){
                  if (array_key_exists($v['model'], $b)){
                      $b[$v['model']]['counts'] = $b[$v['model']]['counts']+$v['counts'];
                  }else{
                      $b[$v['model']]['id']='';
                      $b[$v['model']]['uid']=$v['uid'];
                      $b[$v['model']]['counts']=$v['counts'];
                      $b[$v['model']]['model']=$v['model'];
                  }
              }
              $modelList1 =  array_values($b);
         }
          $modelList = array();
        foreach ($modelList1 as $vt){
              if ($vt['counts']!=0){
                    $modelList[] = $vt;
                }
          }

        //单独用户anxiaozhu1005 单独曲线图 $uid=21
        $income = array();
        $array = $this::judgeStampAndType($datestr,$date2str,1);
        if (in_array($curdate,$array)){
            $key=array_search($curdate ,$array);
            array_splice($array,$key,1);
            $sql="select uid, createstamp,count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and createstamp={$curdate} group by createstamp order by createstamp";
            $curListt = Yii::app()->db->createCommand($sql)->queryAll();
            //当天去表app_rom_repeatinstall_uid中查找当天的数据
            $sql="select imeicode from app_rom_repeatinstall_uid where uid={$uid} and createstamp={$curdate}  group by createstamp order by createstamp";
            $curListt2 = Yii::app()->db->createCommand($sql)->queryAll();

            if(!empty($curListt2)){
                // $curListt=array_merge($curListt,$curListt2);
                foreach ($curListt2 as $key => $value) {
                    $sql="select id from app_rom_appresource where uid={$uid} and createstamp={$curdate} and imeicode='{$value['imeicode']}'";
                    $re=yii::app()->db->createCommand($sql)->queryAll();
                    if(empty($re)){
                        $curListt[0]['counts']+=1;
                    }
                }
            }
 
        }
        if (!empty($array)){
            $arraystr = implode(',',$array);
            $sql="select uid, createstamp,count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and createstamp IN ($arraystr) group by createstamp order by createstamp";
            $modelListt = Yii::app()->db->createCommand($sql)->queryAll();
            if (!empty($modelListt)){
                foreach ($modelListt as $k=> $v){
                    $model = new RomInstalcheck();
                    $model->uid = $v['uid'];
                    $model->counts = $v['counts'];
                    $model->createstamp = $v['createstamp'];
                    $model->type = 1;
                    $model->insert();
                }
            }else{
                foreach ($array as $vt){
                    $model = new RomInstalcheck();
                    $model->uid = $uid;
                    $model->counts = 0;
                    $model->createstamp = $vt;
                    $model->type = 1;
                    $model->insert();
                }
            }
        }
        $sql="select uid, sum(counts) as counts,createstamp from app_rom_instalcheck where uid={$uid} and type=2 and createstamp >= {$datestr} and createstamp <= {$date2str} group by createstamp order by createstamp";
        $modelListt = Yii::app()->db->createCommand($sql)->queryAll();
        if (!empty($curListt)){
            foreach ($curListt as $v){
                array_push($modelListt,$v);
            }
        }
        foreach($modelListt as $k=> $v){
            if ($v['counts']==0){
                continue;
            }
            foreach($v as $kk=>$vv){
                $datee = $v['createstamp'];
                $income[$k][$kk]=$v[$kk];
                $income[$k]['y']=intval(date('Y', $datee));
                $income[$k]['m']=intval(date('m', $datee));
                $income[$k]['d']=intval(date('d', $datee));
            }
        }

        //总数据统计
        $sql_month ="select sum(counts) as counts from app_rom_instalcheck where uid={$uid} and type=2 and createstamp >= {$datestr} and createstamp <= {$date2str}";
        $monthdata = Yii::app()->db->createCommand($sql_month)->queryAll();
        if (!empty($curmodel)){
            $sum = 0;
            foreach ($curmodel as $v){
                $sum = $sum +$v['counts'];
            }
            $monthdata[0]['counts'] = $monthdata[0]['counts'] +$sum;
        }

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
     * 获取app_rom_instalcheck表中不存在的时间
     * @param $start
     * @param $end
     * @param $type
     * @return array
     */
    private function judgeStampAndType($start,$end,$type){
       $arr =  $this::getStampZone($start,$end);
        $array = array();
        foreach ($arr as $v){
            $data=RomInstalcheck::model()->findByAttributes(array('uid'=>$this->uid,'createstamp'=>$v,'type'=>$type));
            if (empty($data)){
                $array[] = $v;
            }
        }
        return $array;
    }

    /**
     * 获取两个时间戳中间的所有日期
     * @param $start
     * @param $end
     * @return array
     */
    private function getStampZone($start,$end){
        $arr = array();
        $zone = $start;
        while ($zone <=$end){
            $arr[] = $zone;
            $zone = $zone + 24*3600;
        }
        return $arr;
    }




    /**
     * 应用激活排行
     */
    public function actionActivatop()
    {


        // 增加缓存,提高访问速度
        if (Yii::app()->cache->get('member_datashow_activatop')==false){
            $sql_all ="select id,type,count(type) as counts from app_rom_appresource where finishstatus=1 group by type order by counts desc limit 20";
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
            Yii::app()->cache->set('member_datashow_activatop', $array_str,86400);
        }
        $cache = Yii::app()->cache->get('member_datashow_activatop');
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
        $mang_login = Yii::app()->user->getState("member_manage");

        if($mang_login)
        {
            $sql_all ="select id,model,count(model) as counts from app_rom_appresource group by model order by counts desc limit 200";
        }
        else
        {
            $sql_all ="select id,model,count(model) as counts from app_rom_appresource group by model order by counts desc limit 20";
        }


        // 增加缓存,提高访问速度
        if (Yii::app()->cache->get('member_datashow_modeltop')==false){
            $activadata = Yii::app()->db->createCommand($sql_all)->queryAll();
            $array_str = serialize($activadata);// 序列化数组
            // 数组存缓存中
            Yii::app()->cache->set('member_datashow_modeltop', $array_str,86400);
        }
        $cache = Yii::app()->cache->get('member_datashow_modeltop');
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
     * 产品更新状况
     */
    public function actionProductupdate($type='')
    {
            if(!empty($type)){
                $sql_all ="SELECT a.id,a.version,a.status,a.createtime,b.name FROM app_product_list as a LEFT JOIN app_product as b ON a.pid= b.id WHERE agent=0 and isshow=1 and type='{$type}' ORDER BY createtime DESC";
            }else{
                $sql_all ="SELECT a.id,a.version,a.status,a.createtime,b.name FROM app_product_list as a LEFT JOIN app_product as b ON a.pid= b.id WHERE isshow=1 and  agent=0 ORDER BY createtime DESC";
            }
        $activadata = Yii::app()->db->createCommand($sql_all)->queryAll();
        $this->render('productupdate', array(
            'dataProvider' => new CArrayDataProvider($activadata, array(
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


}