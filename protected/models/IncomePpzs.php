<?php

/**
 * Created by liyashan
 * Date: 15-5-13 下午15:36
 * Explain:手机百度
 */
class IncomePpzs extends Income
{
    private $deductComputeList;

    /**
     * @param string $className
     * @return IncomeUcllq
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string
     */
    public function tableName()
    {
        return '{{income_ppzs}}';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return Ad::TYPE_PPZS;
    }

    /**
     * 保留，接口对接
     */
    public function compute(MemberInfo $member, $num)
    {
        return ;
    }
    /**
     * 获取单独计算用户
     * @return array
     */
    public static function getDeductUsers()
    {
        //单独用户
        $uList=BindSample::model()->findAll('type= "ppzs" and status = 0 and closed=0 and utype=0');
        foreach ($uList as $k=>$v) {
            $user=Member::model()->getById($v["uid"])->username;
            //用户名无法取出，使用status代替用户名字段
            $uList[$k]["status"]=$user;
        }
        $uList=array($uList);
        //散量分组
        $count=count($uList);
        $sql="SELECT * FROM (SELECT * FROM app_product_list ORDER BY id DESC) as a where type='ppzs' and status=1 and agent!=707 group by agent";
        $prolist =  Yii::app()->db->createCommand($sql)->queryAll();
        $prolist=array($prolist);
        if(!empty($prolist))
        {
            foreach ($prolist[0] as $ke=>$va)
            {
                $uList[$count+$ke][0]["uid"]="999999".$va["agent"];
                $uList[$count+$ke][0]["val"]=$va["pakid"];
                $uList[$count+$ke][0]["status"]=$va["agent"];
            }
        }

        //array('uid'=>0,'val'=>'1013208d','status'=>'散量'),
        return $uList;

    }
    /**
     * 自己计算添加数据
     * @param string $key
     * @return int 导入数据数量
     * @throws CHttpException
     */
    public function createBySelf($key = '')
    {
        $type = $this->getType();

        $dataList = array();
        if (!empty($key)) {
            return 0;
        }
        $dcList = $this->getDeductComputeList();
        foreach ($dcList as $k=>$v) {
            $uList=$this->getDeductUsers();
            //业务单价
            $prodata=Product::model()->getByKeyword($type);
            $price=$prodata["price"];
            //单独id用户
            if(!empty($dcList[$k]) && substr($k,0,6)!="999999")
            {
                foreach($uList[0] as $a=>$b)
                {
                    if($b["uid"]==$k)
                    {
                        $mrid=MemberResource::model()->getBidValue2($k,$type);
                        if(empty($mrid)) continue;
                        $dataList[] = array(
                            'uid' => $k,
                            'mrid' => $mrid["key"],
                            'data' => $dcList[$k]*$price,
                            'createtime' => $this->getImportDate(),
                            'status' => 1
                        );
                    }
                }
            }
            //分组散量用户
            if(!empty($dcList[$k]) && substr($k,0,6)=="999999")
            {
                //散量总数--$sum
                $sum=$v;
                //分组用户
                $groupid=str_replace("999999","",$k);
                $priceX= AgentPrice::findPrice($prodata['id'],$groupid);
                if($priceX==99999){$price=$price;} else{$price=$priceX;}
                $groupusers=Member::model()->findAll('agent=:agent  and status=:status',array(':agent'=>$groupid,':status'=>1));
                $uids=array();
                if(empty($groupusers))continue;
                foreach ($groupusers as $kry=>$vrl)
                {
                    $uids[]= $vrl["id"];
                }
                $uids=implode(",",$uids);

                $day=$this->getImportDate();
                //分组用户散量计数
                $sql = "SELECT uid,count(uid) as num FROM app_rom_appresource
                WHERE type= '$type' and uid in ($uids) and finishdate='$day' and status= 0 and finishstatus= 1 group by uid";
                $romresAll =  Yii::app()->db->createCommand($sql)->queryAll();

                if(!empty($romresAll))
                {
                    //用户实际完成总数
                    $numall=0;

                    foreach ($romresAll as $kry=>$vrl)
                    {
                        $numall += $vrl["num"];
                    }
                    if($numall!=0)
                    {
                        //输入数量和平台统计数量对比，按妖娇要求以手动录入总数为准分配给用户
                        /*if($sum>$numall)
                         {
                             $sum=$numall;
                         }*/

                        //判断是否为整数，计数
                        $isint=0;
                        $isintuser=array();

                        foreach ($romresAll as $kr=>$vr)
                        {
                            $alone="";
                            $uid=$vr["uid"];
                            //作弊用户不计算
                            //if ($uid==855570) continue;
                            //用户资源表没有值不计算--可不开启业务，但不能被封号
                            $memberResource = MemberResource::model()->getBidValue2($uid, $type);//$status=1
                            if (is_null($memberResource)) continue;

                            if (!is_null($memberResource) && !is_null($memberResource->u))
                            {
                                //计算用户收入
                                $usernum=$vr["num"];
                                //判断为整数，计数
                                $thisunum=($usernum/$numall)*$sum;
                                $thisnum=intval($thisunum);
                                if($thisnum!=0){$isint=$isint+$thisnum;};
                                if($thisunum!=0 && $thisunum<1)
                                {
                                    $isintuser[$kr]["uid"]=$uid;
                                    $isintuser[$kr]["udata"]=$thisunum;
                                    $isintuser[$kr]["mrid"]=$memberResource->id;
                                }

                                $data= $thisnum*$price;
                                //判断是否存在单独id，如有则data相加
                                foreach($dataList as $dl=>$dlv)
                                {
                                    if($dlv["uid"]==$uid)
                                    {
                                        $dataList[$dl]["mrid"]=$memberResource->id;
                                        $dataList[$dl]["data"]=$dlv["data"]+$data;
                                        $alone=1;
                                        continue;
                                    }
                                }
                                if($alone!=1)
                                {
                                    $dataList[] = array(
                                        'uid' => $uid,
                                        'mrid' => $memberResource->id,
                                        'data' => $data,
                                        'createtime' => $this->getImportDate(),
                                        'status' => $memberResource->status
                                    );
                                }



                            }
                        }

                        //激活量小于1未分配激活数据部分计算方法
                        if(!empty($isintuser))
                        {
                            //将数组按照udata大小排序
                            foreach ($isintuser as $keyd => $rowd)
                            {
                                $udata[$keyd]  = $rowd['udata'];
                            }
                            @array_multisort($udata,SORT_DESC,$isintuser);

                            //剩余总量：导入数据-实际分给用户总数据
                            $surplus=$sum-$isint;
                            if($surplus>0)
                            {
                                //小于1：未分配激活量用户
                                $iscount=count($isintuser);
                                if($iscount!=0)
                                {
                                    //激活量/总用户数
                                    $per=$surplus/$iscount;
                                    $perv=0;
                                    $pers=1;

                                    if($per>=1)
                                    {
                                        //整除数值=应分配给全部用户的整数部分激活量
                                        $perv=intval($per);
                                        //去除上面整除数值后剩余激活量
                                        $pers=$surplus%$iscount;
                                        //去除整除部分后：剩余激活量/总用户数（小于1）
                                        $per=$pers/$iscount;
                                    }
                                    if($per<1 && $per>=0)
                                    {
                                        //存在整除量计算，无余数
                                        if($perv>0 && $pers==0)
                                        {
                                            foreach($isintuser as $ki=>$vi)
                                            {
                                                //用户应分得激活数
                                                $tisnum=0;

                                                $tisnum=$perv;
                                                $data= $tisnum*$price;
                                                $dataList[] = array(
                                                    'uid' => $vi["uid"],
                                                    'mrid' => $vi["mrid"],
                                                    'data' => $data,
                                                    'createtime' => $this->getImportDate(),
                                                    'status' => 1
                                                );

                                            }
                                        }
                                        //存在整除量计算，有余数
                                        elseif($perv>0)
                                        {
                                            $i=0;
                                            $countis=$iscount*$perv;
                                            $intsur=$surplus-$countis;
                                            foreach($isintuser as $ki=>$vi)
                                            {
                                                //用户应分得激活数
                                                $tisnum=0;

                                                $i=$i+1;

                                                if($i-$intsur<=0)
                                                {
                                                    $tisnum=$perv+1;
                                                }
                                                else
                                                {
                                                    $tisnum=$perv;
                                                }

                                                $data= $tisnum*$price;
                                                $dataList[] = array(
                                                    'uid' => $vi["uid"],
                                                    'mrid' => $vi["mrid"],
                                                    'data' => $data,
                                                    'createtime' => $this->getImportDate(),
                                                    'status' => 1
                                                );

                                            }
                                        }
                                        //无整除量计算
                                        else
                                        {
                                            $i=0;
                                            foreach($isintuser as $ki=>$vi)
                                            {
                                                //用户应分得激活数
                                                $tisnum=0;
                                                $i=$i+1;

                                                if($i-$surplus<=0)
                                                {
                                                    $tisnum=1;
                                                }
                                                else
                                                {
                                                    continue;
                                                }

                                                $data= $tisnum*$price;
                                                $dataList[] = array(
                                                    'uid' => $vi["uid"],
                                                    'mrid' => $vi["mrid"],
                                                    'data' => $data,
                                                    'createtime' => $this->getImportDate(),
                                                    'status' => 1
                                                );
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        //分组77的代理商提成及子用户收益扣量计算
                        //代理商760，zhoukun
                        if($groupid==77)
                        {
                            //总激活量
                            $msum=0;
                            $subactive=array();
                            foreach($dataList as $dlj=>$dlvj)
                            {
                                //760子用户
                                $m=Member::model()->find('subagent=760 and id='.$dlvj["uid"]);
                                if(empty($m))
                                {
                                    continue;
                                }
                                else
                                {
                                    //根据收益计算单激活量和总
                                    $acount=$dataList[$dlj]["data"]/$price;
                                    $subactive[$dlj]["uid"]=$dlvj["uid"];
                                    $subactive[$dlj]["count"]=$acount;
                                    $msum=$msum+$acount;
                                }

                            }

                            //760提成10%：具体提成与扣量
                            if($msum>0)
                            {
                                //代理商应得总扣量
                                $agentcount=floor($msum*0.1);
                                //子用户最终量，直接导入
                                $subfinal=array();
                                //未扣量部分子用户
                                $subnone=array();
                                //代理商最终提成量
                                $agentcs=0;
                                //子用户未扣总量
                                $subkl=0;
                                //未扣量部分子用户第二次扣量集合
                                $subnone_res=array();
                                //子用户扣量
                                foreach($subactive as $s=>$sv)
                                {
                                    $klcount=0;
                                    //子用户扣量值
                                    $klcount=round(($sv["count"]/$msum)*$agentcount);//四舍五入
                                    if($klcount>=1)
                                    {
                                        //子用户最终应分配的量
                                        $subfinal[$s]["uid"]=$sv["uid"];
                                        $subfinal[$s]["data"]=($sv["count"]-$klcount);
                                        $agentcs=$agentcs+$klcount;
                                    }
                                    else
                                    {
                                        //未扣量子用户
                                        $subnone[$s]["uid"]=$sv["uid"];
                                        $subnone[$s]["data"]=$sv["count"];
                                    }
                                }
                                //四舍五入法扣量总和与代理商总扣量对比
                                if($agentcs>=$agentcount)
                                {
                                    //代理商最终提成量
                                    $agentcount=$agentcs;
                                }
                                else
                                {
                                    //四舍五入未扣量用户继续扣量
                                    //应扣总量
                                    $subkl=$agentcount-$agentcs;
                                    if($subkl>0 && !empty($subnone))
                                    {
                                        //将数组按照udata大小排序
                                        foreach ($subnone as $kleyd => $rlowd)
                                        {
                                            $udata[$kleyd]  = $rlowd['data'];
                                        }
                                        @array_multisort($udata,SORT_DESC,$subnone);
                                        //具体扣量
                                        $subnone_res= $this->getUserKl($subkl,$subnone);
                                    }
                                    elseif($subkl>0 && empty($subnone))//官方输入量大于平台实际激活数据导致的，多出的不扣
                                    {
                                        //代理商最终提成量
                                        $agentcount=$agentcs;
                                    }
                                }
                                //合并四舍五入扣量+二次扣量子用户
                                if(!empty($subnone_res))
                                {
                                    $sublist = array_merge($subfinal, $subnone_res);
                                }
                                else
                                {
                                    $sublist = $subfinal;
                                }

                                //修改$dataList数据
                                foreach($dataList as $dlak=>$dlavk)
                                {
                                    foreach($sublist as $dl=>$dlv)
                                    {
                                        if($dlavk["uid"]==$dlv["uid"])
                                        {
                                            $dataList[$dlak]["data"]=$dlv["data"]*$price;
                                            continue;
                                        }
                                    }
                                }
                                //增加代理商提成
                                if(!empty($agentcount))
                                {
                                    $dataList[] = array(
                                        'uid' => 760,
                                        'mrid' => "760",
                                        'data' => $agentcount*$price,
                                        'createtime' => $this->getImportDate(),
                                        'status' => 1
                                    );
                                }



                            }



                        }
                    }
                }
            }
        }

        $this->insertByArray($type, $dataList);
        return count($dcList);
    }
    /**
     * 具体扣量
     * @return int|void
     */
    public function getUserKl($subkl,$subnone)
    {
        if($subkl>0)
        {
            foreach ($subnone as $suk => $vuk)
            {
                if($subkl<=0)
                {
                    continue;
                }
                if($vuk["data"]>=1)
                {
                    $subnone[$suk]["uid"]=$vuk["uid"];
                    $subnone[$suk]["data"]=$vuk["data"]-1;
                    $subkl=$subkl-1;
                }

            }
        }
        if($subkl>0)
        {
            $this->getUserKl($subkl,$subnone);
        }
        else
        {
            return $subnone;
        }

    }
    /**
     * @param $array
     * @return int|void
     * @throws CHttpException
     */
    public function createByArray($array)
    {
        throw new CHttpException(500, '该项目不适用此方法');
    }

    /**
     * @param string $txt
     * @param string $key
     * @return int|void
     * @throws CHttpException
     */
    public function createByText($txt, $key = '')
    {
        throw new CHttpException(500, '该项目不适用此方法');
    }

    /**
     * @param DOMDocument $dom
     * @param string $key
     * @return int|void
     * @throws CHttpException
     */
    public function createByXml(DOMDocument $dom, $key = '')
    {
        throw new CHttpException(500, '该项目不适用此方法');
    }

    /**
     * @param array $deductComputeList
     */
    public function setDeductComputeList($deductComputeList)
    {
        $this->deductComputeList = $deductComputeList;
    }

    /**
     * @return array
     */
    public function getDeductComputeList()
    {
        return $this->deductComputeList;
    }
}