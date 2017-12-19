<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/14
 * Time: 16:19
 * 产品业务
 */

class ProductController extends DealerController
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

            if(!empty($member_agent))
            {
                $product_list=ProductList::model()->findAll('type=:type and status=:status and agent=:agent and isshow=1 order by id desc limit 1',array(':type'=>$rv['pathname'],':agent'=>$member_agent,':status'=>1));
                //print_r($product_list);exit;
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
                $type="dealer";
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
                    //分配盒子桌面id
                   $boxDesk = Boxdesk::model()->find('uid=:uid',array(':uid'=>$uidt));
                    if(empty($boxDesk))
                    {
                        $userappt = RomSoftpak::model()->find('uid=:uid',array(':uid'=>$uidt));
                        $boxDesk_model = Boxdesk::model()->find('tid=:serial_number',array(':serial_number'=>$userappt["serial_number"]));
                        $boxDesk_model->status = 0;//0-分配
                        $boxDesk_model->uid = $uidt;
                        $boxDesk_model->update();
                    }
                    //分配后下载app
                    $this->redirect("http://www.sutuiapp.com".$appurl);
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
        $this->render('index', array(
            'data' => new CArrayDataProvider($resourceList, array(
                    'pagination' => array(
                        'pageSize' => Common::PAGE_INFINITY,
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
    //业务投放
    public function actionOpenIsPut($type,$uid){
        $memberResource = MemberResource::model()->find('type=:type and uid=:uid',array(':type'=>$type,':uid'=>$uid));
        $memberResource->is_put=1;

        if($memberResource->update()){
            $this->redirect(array('index'));
        }else{
            throw new CHttpException(500, '开启业务投放失败');
        }
    }
    //关闭投放
    public function actionCloseIsPut($type,$uid){
        $memberResource = MemberResource::model()->find('type=:type and uid=:uid',array(':type'=>$type,':uid'=>$uid));
        $memberResource->is_put=0;

        if($memberResource->update()){
            $this->redirect(array('index'));
        }else{
            throw new CHttpException(500, '关闭业务投放失败');
        }
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
            if(!empty($member_agent))
            {
                $product_list=ProductList::model()->findAll('type=:type and status=:status and agent=:agent order by id desc limit 1',array(':type'=>$rv['pathname'],':agent'=>$member_agent,':status'=>1));
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
                $product_list=ProductList::model()->findAll('type=:type and status=:status and agent=:agent order by id desc limit 1',array(':type'=>$rv['pathname'],':agent'=>0,':status'=>1));
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
                $type="dealer";
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
                $type="dealer";
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
                    $product_list=ProductList::model()->findAll('type=:type and status=:status and agent=:agent order by id desc limit 1',array(':type'=>$rv['pathname'],':agent'=>$member_agent,':status'=>1));
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
                    $product_list=ProductList::model()->findAll('type=:type and status=:status and agent=:agent order by id desc limit 1',array(':type'=>$rv['pathname'],':agent'=>0,':status'=>1));
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
    public function actionEdit($type, $status)
    {
        if (!is_numeric($status) || !in_array($status, array('0', '1')) || empty($type)) {
            throw new CHttpException(500, '数据错误');
        }
        $transaction = Yii::app()->db->beginTransaction();
        $controller_id = Yii::app()->request->urlReferrer;
        try {
            $resource = Product::model()->getByKeyword($type);

            if (is_null($resource)) {
                throw new CHttpException(500, '没有此广告类型或此类型广告不允许修改');
            }
            if ($resource->auth == Product::AUTH_CLOSED) {
                throw new CHttpException(500, '此项目已关闭');
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
                        throw new CHttpException(500, '此项目只有客服可以开启');
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
                throw new Exception('该业务已没有可使用的ID，请联系客服');
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
            throw new CHttpException(500, '修改状态出现错误。' . $msg);
        }
        $user = Yii::app()->user->getState("member_manage");

        if($user && strstr($controller_id,"campaignindex"))
        {
           $minfo= Member::model()->findByPk($this->uid);
            //echo "<script language=\"javascript\">windows.open('http://wpa.qq.com/msgrd?v=3&uin=2139896304&site=qq&menu=yes')</script>";
            //echo "<script language='javascript' type='text/javascript'>window.open('http://wpa.qq.com/msgrd?v=3&uin=".$minfo["tel"]."&site=qq&menu=yes');</script>";
           $this->redirect(array('campaignindex'));
            //$this->redirect('http://wpa.qq.com/msgrd?v=3&uin=2139896304&site=qq&menu=yes');
        }
        elseif(strstr($controller_id,"campaignindex"))
        {
            $this->redirect(array('campaignindex'));
        }
        else
        {
            $this->redirect(array('index'));
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