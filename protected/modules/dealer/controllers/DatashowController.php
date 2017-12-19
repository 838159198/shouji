<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/14
 * Time: 16:19
 * 产品业务
 */

class DatashowController extends DealerController
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
        $date = empty($date) ? date('Y-m-d', strtotime('-1 day')) : $date;
        $date2 = empty($date2) ? date('Y-m-d', strtotime('-1 day')) : $date2;
        $uid=$this->uid;
        //总数据统计
        $sql_month ="select count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and DATE_FORMAT(createtime,'%Y%m%d') >= DATE_FORMAT('{$date}','%Y%m%d') and DATE_FORMAT(createtime,'%Y%m%d') <= DATE_FORMAT('{$date2}','%Y%m%d')";
        $monthdata = Yii::app()->db->createCommand($sql_month)->queryAll();

        //日查询--判断安装量：未完成+当天日期（其它条件查询无效）
        $sql ="select id,uid,createtime,model,count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and  DATE_FORMAT(createtime,'%Y%m%d') >= DATE_FORMAT('{$date}','%Y%m%d') and DATE_FORMAT(createtime,'%Y%m%d') <= DATE_FORMAT('{$date2}','%Y%m%d')  group by  model order by counts desc";
        $modelList = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('instalcheck', array(
            'dataProvider' => new CArrayDataProvider($modelList, array(
                'pagination' => array(
                    'pageSize' => Common::PAGE_SIZE,
                ),
            )),
            'date' => $date,
            'date2' => $date2,
            'monthdata' => $monthdata
        ));
    }
    /**
     * 应用激活排行
     */
    public function actionActivatop()
    {
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

        $this->render('activatop', array(
            'dataProvider' => new CArrayDataProvider($activadata, array(
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

        $activadata = Yii::app()->db->createCommand($sql_all)->queryAll();
        $this->render('modeltop', array(
            'dataProvider' => new CArrayDataProvider($activadata, array(
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
                $sql_all ="SELECT a.id,a.version,a.status,a.createtime,b.name FROM app_product_list as a LEFT JOIN app_product as b ON a.pid= b.id WHERE agent=0 and isshow=1 and  type='{$type}' ORDER BY createtime DESC";
            }else{
                $sql_all ="SELECT a.id,a.version,a.status,a.createtime,b.name FROM app_product_list as a LEFT JOIN app_product as b ON a.pid= b.id WHERE isshow=1 and agent=0  ORDER BY createtime DESC";
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


    /**
     * 数据上报
     */
    public function actionDatalist()
    {
        $m=Yii::app()->request->getPost('m', '');
        //当前查询月
        $date = empty($m) ? date('Y-m') : $m;
        if (DateUtil::dateDiff(date('Y-m-d'), $date) < -186) {
            throw new CHttpException(500, '只统计六个月内的数据');
        }
        $uid=$this->uid;

        //查找月份内数据
        $begin=date('Y-m-01', strtotime($date));
        $end=date('Y-m-d', strtotime("$begin +1 month -1 day"));
        $begintime = strtotime($begin);
        $endtime = strtotime($end);
        $data=array();
        $i=0;
        $sql_all ="SELECT id,imeicode,count(id) as ancounts,date_format(createtime,'%Y-%m-%d') as datedd FROM `app_rom_appresource` WHERE `uid` = '{$uid}' and date_format(createtime,'%Y-%m') = '{$date}' group by datedd,imeicode";
        $dataarr = Yii::app()->db->createCommand($sql_all)->queryAll();

        //分组
        $items = array();
        foreach($dataarr as $item) {
            $order_id = $item['datedd'];
            unset($item['datedd']);
            if(!isset($items[$order_id])) {
                $items[$order_id] = array('datedd'=>$order_id, 'items'=>array());
            }
            $items[$order_id]['items'][] = $item;
        }
        for ($start = $begintime; $start <= $endtime; $start += 24 * 3600)
        {
            $daychar=date("Y-m-d", $start);
            $sumcount=0;
            $sumancount=0;
            $accessdata=0;
            $sumimeicode="";
            $id=1;

            if(isset($items[$daychar]))
            {
                if(!empty($items[$daychar]['items']))
                {
                    foreach($items[$daychar]['items'] as $akey=>$aval)
                    {
                        $sumcount=count($items[$daychar]['items']);
                        $sumancount=$sumancount+$aval["ancounts"];
                        $sumimeicode="'".$aval["imeicode"]."',".$sumimeicode;
                    }
                    $sumimeicode=substr($sumimeicode, 0, -1);
                    $sql_alls ="select sum(t.sumcss) as sumcsss from (SELECT count(distinct appname) as sumcss FROM `app_rom_appupdata` WHERE type!=1 and  `uid` ='{$uid}' and imeicode in({$sumimeicode}) group by imeicode) as t";
                    $accessdata = Yii::app()->db->createCommand($sql_alls)->queryAll();

                    $data[$i]["id"]=$id;
                    $data[$i]["date"]=$daychar;
                    $data[$i]["counts"]=$sumcount;
                    $data[$i]["ancounts"]=$sumancount;
                    $data[$i]["accessdata"]=$accessdata[0]["sumcsss"];
                    $i++;
                }
            }
            else
            {
                $data[$i]["id"]=$id;
                $data[$i]["date"]=$daychar;
                $data[$i]["counts"]=$sumcount;
                $data[$i]["ancounts"]=$sumancount;
                $data[$i]["accessdata"]=$accessdata;
                $i++;
                continue;
            }
        }

        $this->render('datalist', array(
            'date' => $date,
            'dataProvider' => new CArrayDataProvider($data, array(
                'pagination' => array(
                    'pageSize' => 31,
                ),
            )),
        ));
    }


    /**
     * 数据上报--天详情
     */
    public function actionDatalistday()
    {
        $day=Yii::app()->request->getParam('date');
        $dates=Yii::app()->request->getParam('dates');
        $imei=Yii::app()->request->getParam('imei');
        $uid=$this->uid;

        //查找数据
        $data=array();
        //print_r($day);exit;
        if(!empty($day) || !empty($imei) || !empty($dates))
        {
            if (!empty($day) && (DateUtil::dateDiff(date('Y-m-d'), $day) < -186)) {
                throw new CHttpException(500, '只统计六个月内的数据');
            }
            elseif (!empty($day))
            {
                $daychar=$day;
            }
            if(!empty($dates))
            {
                $daychar=$dates;
            }


            if(Yii::app()->request->isPostRequest && !empty($imei))
            {
                $sql_all ="SELECT id,imeicode,model,count(id) as ancounts FROM `app_rom_appresource` WHERE `uid` = '{$uid}' and createtime like '%{$daychar}%' and `imeicode` = '{$imei}' group by imeicode";
                $activadata = Yii::app()->db->createCommand($sql_all)->queryAll();
            }
            elseif(Yii::app()->request->isPostRequest && empty($imei))
            {
                $sql_all ="SELECT id,imeicode,model,count(id) as ancounts FROM `app_rom_appresource` WHERE `uid` = '{$uid}' and createtime like '%{$daychar}%' group by imeicode";
                $activadata = Yii::app()->db->createCommand($sql_all)->queryAll();
            }
            else
            {
                $sql_all ="SELECT id,imeicode,model,count(id) as ancounts FROM `app_rom_appresource` WHERE `uid` = '{$uid}' and createtime like '%{$daychar}%' group by imeicode";
                $activadata = Yii::app()->db->createCommand($sql_all)->queryAll();
            }


            if(!empty($activadata))
            {
                foreach($activadata as $akey=>$aval)
                {
                    $sql_alls ="SELECT count(distinct appname) as sumcss FROM `app_rom_appupdata` WHERE type!=1 and `uid` ='{$uid}' and imeicode ='{$aval["imeicode"]}' group by imeicode";
                    $accessdata = Yii::app()->db->createCommand($sql_alls)->queryAll();

                    $data[$akey]["id"]=$akey+1;
                    $data[$akey]["day"]=$daychar;
                    $data[$akey]["ancounts"]=$aval["ancounts"];
                    $data[$akey]["model"]=$aval["model"];
                    $data[$akey]["imeicode"]=$aval["imeicode"];
                    if(!empty($accessdata))
                    {
                        $data[$akey]["accessdata"]=$accessdata[0]["sumcss"];
                    }
                    else
                    {
                        $data[$akey]["accessdata"]=0;
                    }
                }
                foreach ($data as $key => $row) {
                    if(empty($row['id']))
                    {
                        $row['id']=0;
                    }
                    $volume[$key]  = $row['id'];
                }
                array_multisort($volume, SORT_DESC, $data);
            }


        }

        if(empty($day)){
            $day=$dates;
        }
        $this->render('datalistday', array(
            'day'=>$day,
            'dataProvider' => new CArrayDataProvider($data, array(
                'pagination' => array(
                    'pageSize' => 30,
                ),
            )),
        ));
    }

    /**
     * 数据上报--imei详情
     */
    public function actionDatalistonly()
    {
        $day=Yii::app()->request->getParam('date');
        $imei=Yii::app()->request->getParam('imei');
        $uid=$this->uid;

        //查找数据
        $data=array();
        $models="";
        //print_r($day);exit;
        if(!empty($imei) && !empty($day))
        {
            if (DateUtil::dateDiff(date('Y-m-d'), $day) < -186) {
                throw new CHttpException(500, '只统计六个月内的数据');
            }
            $daychar=$day;

            $sql_all ="SELECT id,imeicode,`type`,`model` FROM `app_rom_appresource` WHERE `uid` = '{$uid}' and createtime like '%{$daychar}%' and `imeicode` = '{$imei}' group by type";
            $activadata = Yii::app()->db->createCommand($sql_all)->queryAll();

            if(!empty($activadata))
            {
                foreach($activadata as $akey=>$aval)
                {
                    $data[$akey]["id"]=$akey+1;
                    $models=$aval["model"];
                    $pro=Product::model()->find('pathname=:pathname',array(':pathname'=>$aval["type"]));
                    $data[$akey]["pic"]=$pro["pic"];
                    $data[$akey]["name"]=$pro["name"];
                }
                foreach ($data as $key => $row) {
                    if(empty($row['id']))
                    {
                        $row['id']=0;
                    }
                    $volume[$key]  = $row['id'];
                }
                array_multisort($volume, SORT_DESC, $data);
            }
        }

        $this->render('datalistonly', array(
            'day'=>$day,
            'models'=>$models,
            'dataProvider' => new CArrayDataProvider($data, array(
                'pagination' => array(
                    'pageSize' => 30,
                ),
            )),
        ));
    }


}