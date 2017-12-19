<?php

class DefaultController extends DhadminController
{
	public function actionIndex()
	{
        /*
         * 注册用户数
         * */
        $member_model = new Member();
        //所有人数
        $member_count = $member_model ->count();
        //正常状态
        $member_ok_count = $member_model->count("status=1");
        //锁定状态
        $member_fail_count = $member_model->count("status=0");
        /*
         * 业务
         * */
        $product_model = new Product();
        $product_count = $product_model -> count();
        //已开启
        $product_ok_count = $product_model->count("status=1");
        //关闭
        $product_fail_count = $product_model->count("status=0");

        //$member_count = count($member_data);
        //用户数据

        //echo "123";
		$this->render('index',array('member_count'=>$member_count,
        'member_ok_count'=>$member_ok_count,
        'member_fail_count'=>$member_fail_count,
        'product_count'=>$product_count,
        'product_ok_count'=>$product_ok_count,
        'product_fail_count'=>$product_fail_count));
	}
    //测试数据管理
    public function actionTestData()
    {
        //删除表测试数据
        if (Yii::app()->request->isPostRequest) {
            $request = Yii::app()->request;
            $table = $request->getPost('table');
            $uid = $request->getPost('uid');
            $imeicode = $request->getPost('imeicode');
            $closedate = $request->getPost('closedate');

            if (empty($table)) {
                Common::redirect(Yii::app()->request->urlReferrer, '请选择删除表');
            }
            if (!empty($uid) && !in_array($uid,array(23,304,510,722,1128))) {
                Common::redirect(Yii::app()->request->urlReferrer, '非法操作');
            }
            if (!empty($imeicode) && !in_array($imeicode,Common::getExceptList())) {
                Common::redirect(Yii::app()->request->urlReferrer, '非法操作');
            }
            if (empty($uid) && empty($imeicode)) {
                Common::redirect(Yii::app()->request->urlReferrer, '非法操作');
            }

            if(empty($imeicode))
            {
                if(empty($uid))
                {
                    Common::redirect(Yii::app()->request->urlReferrer, '非法操作');
                }
                else
                {
                    if(empty($closedate))
                    {
                        if($table=="app_client_data")
                        {
                            $sql = 'DELETE FROM '.$table.' where `user_id`=\''.$uid.'\'';
                        }
                        else
                        {
                            if($table=="app_rom_appupdata" || $table=="app_rom_appupdata_dtmark")
                            {
                                $sql = 'DELETE FROM '.$table.' where `uid`=\''.$uid.'\' and `finshstatus`=0';
                            }
                            elseif($table=="app_rom_appupdatalog")
                            {
                                $sql = 'DELETE FROM '.$table.' where `uid`=\''.$uid.'\'';
                            }
                            else
                            {
                                $sql = 'DELETE FROM '.$table.' where `uid`=\''.$uid.'\' and `finishstatus`=0';
                            }

                        }
                    }
                    else
                    {
                        if($table=="app_client_data")
                        {
                            $sql = 'DELETE FROM '.$table.' where `user_id`=\''.$uid.'\' and FROM_UNIXTIME(createtime,"%Y-%m-%d") like \'%'.$closedate.'%\'';
                        }
                        elseif($table=="app_rom_appupdatalog")
                        {
                            $sql = 'DELETE FROM '.$table.' where `user_id`=\''.$uid.'\' and FROM_UNIXTIME(createtime,"%Y-%m-%d") like \'%'.$closedate.'%\'';
                        }
                        else
                        {
                            $sql = 'DELETE FROM '.$table.' where `uid`=\''.$uid.'\' and `createtime` like \'%'.$closedate.'%\'';
                        }
                    }

                }

            }
            else
            {
                if(empty($uid))
                {
                    if(empty($closedate))
                    {
                        if($table=="app_client_data")
                        {
                            $sql = 'DELETE FROM '.$table.' where `imei`=\''.$imeicode.'\'';
                        }
                        else
                        {
                            if($table=="app_rom_appupdata" || $table=="app_rom_appupdata_dtmark")
                            {
                                $sql = 'DELETE FROM '.$table.' where `imeicode`=\''.$imeicode.'\' and `finshstatus`=0';
                            }
                            elseif($table=="app_rom_appupdatalog")
                            {
                                $sql = 'DELETE FROM '.$table.' where `imeicode`=\''.$imeicode.'\'';
                            }
                            else
                            {
                                $sql = 'DELETE FROM '.$table.' where `imeicode`=\''.$imeicode.'\' and `finishstatus`=0';
                            }

                        }
                    }
                    else
                    {
                        if($table=="app_client_data")
                        {
                            $sql = 'DELETE FROM '.$table.' where `imei`=\''.$imeicode.'\' and FROM_UNIXTIME(createtime,"%Y-%m-%d") like \'%'.$closedate.'%\'';
                        }
                        elseif($table=="app_rom_appupdatalog")
                        {
                            $sql = 'DELETE FROM '.$table.' where `imei`=\''.$imeicode.'\' and FROM_UNIXTIME(createtime,"%Y-%m-%d") like \'%'.$closedate.'%\'';
                        }
                        else
                        {
                            $sql = 'DELETE FROM '.$table.' where `imeicode`=\''.$imeicode.'\' and `createtime` like \'%'.$closedate.'%\'';
                        }
                    }

                }
                else
                {
                    if(empty($closedate))
                    {
                        if($table=="app_client_data")
                        {
                            $sql = 'DELETE FROM '.$table.' where `user_id`=\''.$uid.'\' and `imei`=\''.$imeicode.'\'';
                        }
                        else
                        {
                            if($table=="app_rom_appupdata" || $table=="app_rom_appupdata_dtmark")
                            {
                                $sql = 'DELETE FROM '.$table.' where `uid`=\''.$uid.'\' and `imeicode`=\''.$imeicode.'\' and `finshstatus`=0';
                            }
                            elseif($table=="app_rom_appupdatalog")
                            {
                                $sql = 'DELETE FROM '.$table.' where `uid`=\''.$uid.'\' and `imeicode`=\''.$imeicode.'\'';
                            }
                            else
                            {
                                $sql = 'DELETE FROM '.$table.' where `uid`=\''.$uid.'\' and `imeicode`=\''.$imeicode.'\' and `finishstatus`=0';
                            }

                        }
                    }
                    else
                    {
                        if($table=="app_client_data")
                        {
                            $sql = 'DELETE FROM '.$table.' where `user_id`=\''.$uid.'\' and `imei`=\''.$imeicode.'\' and FROM_UNIXTIME(createtime,"%Y-%m-%d") like \'%'.$closedate.'%\'';
                        }
                        elseif($table=="app_rom_appupdatalog")
                        {
                            $sql = 'DELETE FROM '.$table.' where `user_id`=\''.$uid.'\' and `imei`=\''.$imeicode.'\' and FROM_UNIXTIME(createtime,"%Y-%m-%d") like \'%'.$closedate.'%\'';
                        }
                        else
                        {
                            $sql = 'DELETE FROM '.$table.' where `uid`=\''.$uid.'\' and `imeicode`=\''.$imeicode.'\' and `createtime` like \'%'.$closedate.'%\'';
                        }
                    }

                }


            }
            $command=Yii::app()->db->createCommand($sql)->execute();
            Common::redirect(Yii::app()->request->urlReferrer, $table.'表--清除'.$command.'条数据');

        }

        $this->render('testdata',array());
    }
    public function actionCache()
    {
        Yii::app()->cache->flush();
    }
/**
 *  清理缓存
 */
public function actionFlush()
        {
        Yii::trace('Flushing cache','system.caching.'.get_class($this));
        Common::redirect($this->createUrl('/dhadmin'), '清理完毕');
        }
}