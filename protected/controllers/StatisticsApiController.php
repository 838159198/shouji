<?php
/**
 * Created by PhpStorm.
 * User: Peng
 * Date: 2017/2/22
 * Time: 16:01
 */
class StatisticsApiController extends Controller
{
    const KEY = "5T4632F5CA29833F76E09158252472DD";

    /**
     * 接口:http://www.sutuiapp.com/statisticsApi/getData
     */
    public function actionGetData(){
        $date = date('Y-m-d',strtotime("-1 day"));
        $date1 = date('Y-m-d 00:00:00',strtotime("-1 day"));
        $date2= date('Y-m-d 00:00:00');

        $sqlincomefinal = "select * from `app_income_final` WHERE `date` = '{$date}' LIMIT 1";
        $dataincomefinal = Yii::app()->db->createCommand($sqlincomefinal)->queryAll();
        $sqlarrival = "select * from `app_rom_arrival_count` WHERE `date` = '{$date}' LIMIT 1";
        $dataarrival = Yii::app()->db->createCommand($sqlarrival)->queryAll();

        if (!empty($dataincomefinal) || !empty($dataarrival)){
            return;
        }

        // 安装收益
        $sqlinstall = "select COUNT(DISTINCT imeicode) `count`,uid from `app_rom_appresource` WHERE `createtime` >= '{$date1}' AND `createtime` <= '{$date2}' and `tc`=1 AND `from` = 5 GROUP BY uid";
        $datainstall = Yii::app()->db->createCommand($sqlinstall)->queryAll();

        // 总到达数据查询
        $sqlnoincome = "select COUNT(DISTINCT imeicode) AS `count`,uid,id ,tcid from `app_rom_appresource` WHERE `tcfirsttime` >= '{$date1}' AND `tcfirsttime` <= '{$date2}' and `tc`=1 AND `from` = 5 AND  `tcid`<1000 AND `noincome` IS NULL GROUP BY uid,id";
        $datanoincome = Yii::app()->db->createCommand($sqlnoincome)->queryAll();
        $this::dataAZ($datainstall,$datanoincome,$date1,$date2);
    }


    // 安装数据
    private function dataAZ($datainstall,$datanoincome,$date1,$date2){

        if (!empty($datainstall) &&!empty($datanoincome)){
            // uid对应的安装收益
            $a = array();
            foreach ($datainstall as $vt){
                $a[$vt['uid']]=$vt['count'];
            }
            // 扣量数据操作
            $noin_tcid = $this::operateKL($datanoincome);
            $nt = array();
            foreach ($noin_tcid as $k=>$vt){
                $nt[$k]=array_count_values($vt);
            }
            // 到达收益
            $s = $this::arrivalyield($nt,$date1,$date2);
            //安装收益和到达收益数据合并处理,并存入数据库
            $this::andao($a,$s);
        }else if (!empty($datainstall)&&empty($datanoincome)){

            // uid对应的安装收益
            $a = array();
            foreach ($datainstall as $vt){
                $a[$vt['uid']]=$vt['count'];
            }
            // 到达收益
            $s = array();
            //安装收益和到达收益数据合并处理,并存入数据库
            $this::andao($a,$s);
        }else if (empty($datainstall)&&!empty($datanoincome)){
            $a = array();
            // 扣量数据操作
            $noin_tcid = $this::operateKL($datanoincome);
            $nt = array();
            foreach ($noin_tcid as $k=>$vt){
                $nt[$k]=array_count_values($vt);
            }
            // 到达收益
            $s = $this::arrivalyield($nt,$date1,$date2);
            //安装收益和到达收益数据合并处理,并存入数据库
            $this::andao($a,$s);

        }


    }



    // 扣量数据操作,随机扣量
    private function operateKL($datanoincome){
        //      扣量数据操作
//        $sqlnoincome = "select COUNT(DISTINCT imeicode) AS `count`,uid,id ,tcid from `app_rom_appresource` WHERE `tcfirsttime` LIKE '%{$date}%' and `tc`=1 AND `from` = 5 AND  `tcid`<1000 GROUP BY uid,id";
//        $datanoincome = Yii::app()->db->createCommand($sqlnoincome)->queryAll();
        $noin = array();
        foreach ($datanoincome as $vt){
            $m = array();
            $m['id'] = $vt['id'];
            $m['tcid'] = $vt['tcid'];
            $noin[$vt['uid']][] = $m;
        }
        // 通过uid查询用户对应的扣量比,并添加扣量标示
        foreach ($noin as $k=>$vt){
            $dataM = Member::model()->findAll('id=:id',array(":id"=>$k));
            $datadown = 0;
            if ((int)$dataM[0]['datadown']==0){
                $datadown = 1-100*0.01;
            }else{
                $datadown = 1-$dataM[0]['datadown']*0.01;
            }
            $num =  floor(count($vt)*$datadown);// 舍弃小数取整,得到扣量个数
            $noin_tcid = array();
            if($num !=0){
                if ($num == 1){
                    $rand_num =  array_rand($vt,$num);
                    RomAppresource::model()->updateAll(array('noincome'=>0),'id=:id',array(':id'=>$vt[$rand_num]['id']));
                    $noin_tcid[$k][]=$vt[$rand_num]['tcid'];
                }else{
                    $rand_keys =  array_rand($vt,$num);
                    foreach ($rand_keys as $vm){
                        RomAppresource::model()->updateAll(array('noincome'=>0),'id=:id',array(':id'=>$vt[$vm]['id']));
                        $noin_tcid[$k][]=$vt[$vm]['tcid'];
                    }
                }
            }
        }
        return $noin_tcid;
    }


    // 到达收益
    private function arrivalyield($nt,$date1,$date2){
        // 到达收益
        $sqlarrive = "select COUNT(DISTINCT imeicode) `count`,uid,tcid from `app_rom_appresource` WHERE `tcfirsttime` >= '{$date1}' AND `tcfirsttime` <= '{$date2}' and `tc`=1 AND  `tcid`<1000 AND `noincome` IS NULL AND `from` =5  GROUP BY uid,tcid ";
        $dataarrive = Yii::app()->db->createCommand($sqlarrive)->queryAll();
        // 系统套餐
        $sqlpackage = "select id,install_bill,arrive_bill from `app_rom_package` WHERE id<1000 AND uid=0 AND sign = 1";
        $datapackage = Yii::app()->db->createCommand($sqlpackage)->queryAll();


        // 对于套餐数据处理,id作为index
        $arrpackage = array();
        foreach ($datapackage as $vt){
            $arrpackage[$vt['id']] =array('install_bill'=>$vt['install_bill'],'arrive_bill'=>$vt['arrive_bill']);
        }

        // 处理数据获取到达收益
        //每组数据到达总收益
        $b = array();
        foreach ($dataarrive as $vt){
            $d = array();
            $c=$arrpackage[$vt['tcid']]['arrive_bill'];
            $d['uid'] = $vt['uid'];
            $d['count'] = $vt['count'];
            $d['bill'] = $vt['count']*$c;
            $d['tcid'] = $vt['tcid'];
            $d['backcount']=$vt['count'];
            $b[] = $d;
        }
        // 按uid分组,相同uid的在同一个数组
        $r = array();
        foreach ($b as $vt){
            $r[$vt['uid']][$vt['tcid']] = $vt;
        }


        // 到达计数存数据库
        $this::arrivalJS($nt,$r,$date1,$date2);

        // 算出每个uid不同套餐收入的总和
        $s = array();
        foreach ($r as $k=>$vt){
            $t=0;
            foreach ($vt as $v){
                $t +=$v['bill'];
            }
            $s[$k]=$t;
        }

        return $s;
    }

    // 安装收益和到达收益数据合并处理,并存入数据库
    private function andao($a,$s){
        $date = date('Y-m-d',strtotime("-1 day"));
        // 获取两个数组键值,并去重合并成一个数组
        $a_key = array_keys($a);
        $s_key = array_keys($s);
        $key_arr = array_keys(array_flip($a_key)+array_flip($s_key));

        // 安装收益和到达收益数据合并处理,并存入数据库
        $z = array();
        foreach ($key_arr as $key=>$vt){
            $f = array();
            if (array_key_exists($vt,$s) && array_key_exists($vt,$a)){
                $f['uid'] = $vt;
                $f['install_income']=$a[$vt];
                $f['arrive_income'] = $s[$vt];
            }else if (!array_key_exists($vt,$s) && array_key_exists($vt,$a)){
                $f['uid'] = $vt;
                $f['install_income']=$a[$vt];
                $f['arrive_income'] = 0;
            }else if (array_key_exists($vt,$s) && !array_key_exists($vt,$a)){
                $f['uid'] = $vt;
                $f['install_income']=0;
                $f['arrive_income'] = $s[$vt];
            }
            $z[] = $f;
        }
        $val = "";
        foreach ($z as $k => $vt){
            if ($k == 0){
                $val = '('."''".','.$vt['uid'].','.$vt['install_income'].','.$vt['arrive_income'].','.'0'.','.'0'.','.'0'.','."'$date'".','.'1)';
            }else{
                $val.=',('."''".','.$vt['uid'].','.$vt['install_income'].','.$vt['arrive_income'].','.'0'.','.'0'.','.'0'.','."'$date'".','.'1)';
            }
        }
        $sqlfinal="INSERT INTO `app_income_final`(`id`,`uid`, `install_income`, `arrive_income`, `activate_income`, `income_type`, `all_income`, `date`, `status`) VALUES {$val}";
        Yii::app()->db->createCommand($sqlfinal)->execute();
    }


    // 到达计数存数据库
    private function arrivalJS($nt,$r){
        $date = date('Y-m-d',strtotime("-1 day"));
        foreach ($nt as $key=>$val){
            foreach ($val as $k =>$v){
                if (array_key_exists($k, $r[$key])){
                    $r[$key][$k]['backcount'] = $r[$key][$k]['count']+$v;// 获取扣量前对应tcid的个数
                }else{
                    $r[$key][$k]['uid']=$key;
                    $r[$key][$k]['count']=0;
                    $r[$key][$k]['backcount'] = $v;
                    $r[$key][$k]['bill'] = 0;
                    $r[$key][$k]['tcid'] = $k;
                }
            }
        }

        $p = array();
        foreach ($r as $k=>$vv){
            foreach ($vv as $v){
                $p[]=$v;
            }
        }
        $vald = "";
        foreach ($p as $k=>$v){
            if ($k==0){
                $vald = '('."''".','.$v['uid'].','.$v['tcid'].','.$v['count'].','.$v['backcount'].','."'$date'".')';
            }else {
                $vald .= ',('."''".','.$v['uid'].','.$v['tcid'].','.$v['count'].','.$v['backcount'].','."'$date'".')';
            }
        }
        $sqldaoda = "INSERT INTO `app_rom_arrival_count`(`id`, `uid`, `arrive_tc`, `arrive_count`, `arrive_backcount`, `date`) VALUES {$vald}";
        Yii::app()->db->createCommand($sqldaoda)->execute();

    }



}