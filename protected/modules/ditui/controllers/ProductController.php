<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/14
 * Time: 16:19
 * 产品业务
 */

class ProductController extends DituiController
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
        $uidt=Yii::app()->request->getParam('uidt');
        $appurl='';
        $codeurl='';
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
                            break;
                        }
                    }
                    //分配应用市场id
                    $markapp = MarketSoftpak::model()->find('uid=:uid',array(':uid'=>$uidt));
                    if(empty($markapp))
                    {
                        $userappt = RomSoftpak::model()->find('uid=:uid',array(':uid'=>$uidt));
                        $markapp = MarketSoftpak::model()->find('serial_number=:serial_number',array(':serial_number'=>$userappt["serial_number"]));
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
                $markapp = MarketSoftpak::model()->find('uid=:uid',array(':uid'=>$uidt));
                $appurl=$markapp['serial_number'];
                $codeurl=$markapp['codeurl'];
            }
        }
        else
        {
            $userapp = RomSoftpak::model()->find('uid=:uid',array(':uid'=>$this->uid));
            $markapp = MarketSoftpak::model()->find('uid=:uid',array(':uid'=>$this->uid));

            //没有对应appid
            if(empty($markapp))
            {
                $appurl='';
                $codeurl='';
            }
            //被封号
            elseif($userapp['closed']==1)
            {
                $appurl='';
                $codeurl='';
                $appurlmsg='此统计软件id已被封号，请联系客服';
            }
            else
            {
                $appurl=$markapp['serial_number'];
                $codeurl=$markapp['codeurl'];
                $appurlmsg='已有专属APP';
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
        $this->redirect(array('index'));
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