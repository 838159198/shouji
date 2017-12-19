<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/14
 * Time: 16:19
 * 产品业务
 */

class ProductController extends NewdtController
{
    public $enableCsrfValidation = false;
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
                $product_list=ProductList::model()->findAll('type=:type and status=:status and agent=:agent and isshow=1 order by id desc limit 1',array(':type'=>$rv['pathname'],':agent'=>77,':status'=>1));
                if(!empty($product_list))
                {
                    $rv['appurl']=$product_list[0]['appurl'];
                    //代替：updatetime--version，enrollment--createtime，actrule--sign
                    $rv['updatetime']=$product_list[0]['version'];
                    $rv['enrollment']=$product_list[0]['createtime'];
                    $rv['actrule']=$product_list[0]['sign'];
                }

            }

        }

        //查询是否有用户--统计APP记录--只操作了RomSoftpak
        $appurlmsg='获取专属APP';
        $appurl='';
        $codeurl='';
        $uidt=Yii::app()->request->getParam('uidt');
        if(isset($uidt)){
            $userapp = RomSoftpak::model()->find('status=0 and uid=:uid and type=:type',array(':uid'=>$uidt,":type"=>'newdt'));
            $boxdesk=Boxdesk::model()->find('uid=:uid and status=:status',array(':uid'=>$uidt,':status'=>0));
            if(empty($userapp) || empty($boxdesk))
            {
                $userapp = RomSoftpak::model()->find('status=0 and uid=:uid and type=:type',array(':uid'=>$uidt,":type"=>'newdt'));
                //分配对应appid
                if(empty($userapp))
                {
                    $type="newdt";
                    $allot=0;
                    //获得最新一条软件序列号
                    $softpak=RomSoftpak::model()->getListGroupByType($type,$allot);
                    $boxdesk=Boxdesk::model()->find("uid=:uid and status=:status ",array(":uid"=>0,":status"=>1));
                    //$mark = MarketSoftpak::model()->find('uid=:uid and type=:type and status=:status',array(':uid'=>0,':type'=>'newdt',':status'=>1));
                    if(empty($softpak) || empty($boxdesk))
                    {
                        throw new CHttpException(500, '无可用软件供分配，请联系客服');
                    }
                    else
                    {
                        //统一判断代理商和普通用户统计分配
                        $spak=Common::getTongji($mm,$type);
                        $spak->status = 0;
                        $spak->uid = $uidt;
                        $spak->update();

                        //分配桌面
                        $this->addTid($uidt,$spak->serial_number);
                        // 生产二维码
                        //$this::qrcode($uidt);
                        //分配后跳转
                        $this->redirect(array('index'));
                        exit;
                    }
                }
                //封号
                elseif($userapp['closed']==1)
                {
                    $appurl='';
                    $codeurl='';
                    throw new CHttpException(500, '此统计软件id已被封号，请联系客服');
                }
                else
                {
                    //$markapp = MarketSoftpak::model()->find('uid=:uid',array(':uid'=>$uidt));
                    $userapp = RomSoftpak::model()->find('status=0 and uid=:uid and type=:type',array(':uid'=>$uidt,":type"=>'newdt'));
                    $appurl=$userapp['serial_number'];
                    //$codeurl=$markapp['codeurl'];
                }
            }
        } else {
            $userapp = RomSoftpak::model()->find('uid=:uid and type=:type',array(':uid'=>$this->uid,":type"=>'newdt'));
            //$markapp = MarketSoftpak::model()->find('uid=:uid and type=:type',array(':uid'=>$this->uid,':type'=>'newdt'));

            //没有对应appid
            if(empty($userapp))
            {
                $appurl='';
                $codeurl='';
            }
            //被封号
            else if($userapp['closed']==1)
            {
                $appurl='';
                $codeurl='';
                $appurlmsg='此统计软件id已被封号，请联系客服';
            }
            else
            {
                $appurl=$userapp['serial_number'];
                 //$codeurl=$markapp['codeurl'];
                $appurlmsg='已有专属APP';
            }
        }

        //查询广告启用状态信息
        $member = $this->loadModel($this->uid);
        $resourceStatus = MemberResource::model()->getAdBindStatus($member, $resourceList);
        $mem_data = "SELECT id FROM `app_member` where agent=99 AND status=1";
        $mem_model = Yii::app()->db->createCommand($mem_data)->queryAll();

        foreach($mem_model as $v){
            $model[]=$v['id'];
        }
        if($member->agent==99){
            //$romPackage = RomPackage::model()->findAllByAttributes(array('uid'=>1324,'sign'=>1));
            $romPackage = Yii::app()->db->createCommand()
                ->select('*')
                ->from('app_rom_package')
                ->where(array('in', 'uid',$model ))
                ->queryAll();
        }else{
            $romPackage = RomPackage::model()->findAllByAttributes(array('gid'=>707,'sign'=>1));
        }

        // 软件列表
        $member_agent=$member['agent'];
        $romPackage = RomPackage::model()->findAllByAttributes(array('gid'=>$member_agent,'sign'=>1));
        if($this->uid == 707){
            $member_agent=707;
        }
        $sqlP = "(SELECT x.pid ,x.id,x.filesize,x.version FROM `app_product_list` x  where id in (select max(id) from `app_product_list` where agent={$member_agent} and status=1 group by pid)  order by x.pid asc)";
        $dataProductlist = Yii::app()->db->createCommand($sqlP)->queryAll();
        $str = "";
        $versionArr = array();// 版本号和pid一一对应
        foreach ($dataProductlist as $vk=> $vt){
            if ($vk == 0){
                $str = $vt['pid'];
            }else{
                $str = $str.",".$vt['pid'];
            }
            $versionArr[$vt['pid']] = $vt['version'];
        }
        $sqlP = "(SELECT * FROM `app_product` where id in({$str}) order by `order` DESC)";
        $dataP = Yii::app()->db->createCommand($sqlP)->queryAll();
        $this->render('index', array(
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
            'codeurl' => $codeurl,
            'appurlmsg' => $appurlmsg,
            'member' => $member,
            'romPackage'=>$romPackage,
            'dataP'=>$dataP,
            'arr'=>$versionArr
        ));
    }

    // ajax得到套餐中包含的业务
    public function actionGetProductData(){
        $mm=Member::model()->findByPk($this->uid);
        $member_agent=$mm['agent'];
        if (Yii::app()->request->isAjaxRequest) {
            $index = Yii::app()->request->getParam('index');

            $data = RomPackage::model()->findAll('id=:id',array(':id'=>$index));

            $idd = implode(',',json_decode($data[0]['pid']));
            $sql = "(SELECT id,name,pic FROM `app_product` WHERE id in({$idd}) order by id)";
            $dataProduct = Yii::app()->db->createCommand($sql)->queryAll();

            $array = array();
            foreach ($dataProduct as $vk=> $vt){
                $sqllist = ProductList::model()->findAll('pid ='.$vt['id'].' and agent='.$member_agent.' and status=1 order by `id` DESC limit 1');
                $arr = array();
                $arr['pid'] = $vt['id'];
                $arr['pic'] = $vt['pic'];
                $arr['name'] = $vt['name'];
                if (!empty($sqllist)){
                    $arr['filesize'] = $sqllist[0]['filesize'];
                }else{
                    $arr['filesize'] = 0;
                }
                $array[] = $arr;
            }
            
            echo CJSON::encode(array('val' => $array));
        }
    }

    //桌面分配
    private function addTid($uid,$tid){
        $boxdesk=Boxdesk::model()->find('uid=:uid and status=:status',array(':uid'=>$uid,':status'=>0));

        if(empty($boxdesk)){
            $box_desk=Boxdesk::model()->findAll('tid=:tid and status=:status',array(':tid'=>$tid,':status'=>1));
            if(empty($box_desk))
            {
                throw new CHttpException(500, '无可用桌面供分配，请联系客服');
            }else{
                foreach ($box_desk as $v){
                    $v->status = 0;
                    $v->uid = $uid;
                    $v->save();
                    break;
                }
            }
        }
    }
    // 生产二维码
    private function qrcode($uidt){
        $markapp = MarketSoftpak::model()->find('uid=:uid',array(':uid'=>$uidt));
        if(empty($markapp))
        {
            $userappt = RomSoftpak::model()->find('uid=:uid',array(':uid'=>$uidt));
            $markapp = MarketSoftpak::model()->find('serial_number=:serial_number and type=:type',array(':serial_number'=>$userappt["serial_number"],':type'=>'newdt'));
            $markapp->status = 0;
            $markapp->uid = $uidt;
            $appurl=$markapp->serial_number;
            if(empty($markapp->codeurl))
            {
                //如果没有二维码则生成
                $codeurls=Common::phpQrcode($markapp->serial_number);
                $markapp->codeurl = $codeurls;
                $codeurl=$codeurls;
            }
            $markapp->update();
        }
    }

    /**
     * pc的桌面和统计分配
     */
    public function actionAjaxDistribute(){
        if (Yii::app()->request->isAjaxRequest) {
            $uid = Yii::app()->request->getParam('uid');
            $member=$this->loadModel($uid);
            $type=$member->type;
            if($type==8){
                $type="newdt";
                $romSoftpak = RomSoftpak::model()->find('uid=:uid and type=:type and status=0',array(':uid'=>$uid,':type'=>$type));
                //判断用户有没统计 没有分配
                if(empty($romSoftpak)){
                    //统一判断代理商和普通用户统计分配
                    $rompak=Common::getTongji($member,$type);
                    if($rompak){
                        //分配统计
                        $rompak->uid=$uid;
                        $rompak->status=0;
                        $rompak->update();
                        //分配桌面
                        $box_desk=Boxdesk::model()->find('tid=:tid',array(':tid'=>$rompak->serial_number));
                        if($box_desk){
                            if($box_desk->status==1){
                                $box_desk->uid=$uid;
                                $box_desk->status=0;
                                $box_desk->update();
                                echo CJSON::encode(array("status"=>200,"msg"=>"分配完成！"));
                            }else{
                                echo CJSON::encode(array("status"=>500,"msg"=>"此统计已被分配！"));
                            }
                        }else{
                            echo CJSON::encode(array("status"=>500,"msg"=>"无可用软件供分配，请联系客服！"));
                        }

                    }else{
                        echo CJSON::encode(array("status"=>500,"msg"=>"无可用软件供分配，请联系客服！"));
                    }

                }
            }else{
                echo CJSON::encode(array("status"=>500,"msg"=>"用户分组错误！"));
            }
        }
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

                    if ($MemberResource->bindMemberByType($this->uid, $type, BindSample::ALLOT_AUTO)) {
                        $bindResource = $MemberResource->getBidValue($this->uid, $resource->pathname);
                    }

                }
                $typev=Product::model()->find('pathname=:pathname',array(':pathname'=>$type));

                //
                if($typev["auth"]==0 && $typev["status"]==1)
                {
                    //在MemberResource表创建默认pc版的app
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
        $this->redirect(array('index'));
    }

    /**
     * 修改用户安全桌面状态
     * @throws CHttpException
     */
    public function actionAjaxOnoff()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $launcher_install = Yii::app()->request->getPost('install');

            if (!is_numeric($launcher_install) || !in_array($launcher_install, array('0', '1'))) {
                echo CJSON::encode(array("status"=>500,"msg"=>"数据错误！"));
            }else{
                if($launcher_install==1){
                    $install=0;
                }else{
                    $install=1;
                }
                $count=Member::updateByUser_launcher($this->uid,$install);
                if($count>0){
                    echo CJSON::encode(array("status"=>200,"msg"=>"修改成功！"));
                }else{
                    echo CJSON::encode(array("status"=>500,"msg"=>"修改失败！"));
                }
            }
        }

    }
    public function actionTest(){
        $mm=Member::model()->findByPk($this->uid);
        echo CJSON::encode(array('val' => $mm->agent));
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