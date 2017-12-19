<?php
/**
 * 统计APP
 */
class TongjiController extends DhadminController
{
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 * 未使用
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * 业务资源创建--未使用
	 */
	public function actionCreate()
	{
        $product_model = new Product();
        $product_data = $product_model -> findAll();
		$model=new BindSample;
		if(isset($_POST['BindSample']))
		{
			$model->attributes=$_POST['BindSample'];
			if($model->save())
				$this->redirect('admin');
	}
		$this->render('create',array(
			'model'=>$model,
            'product'=>$product_data
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 * 业务资源修改--未使用
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if(isset($_POST['BindSample']))
		{
			$model->attributes=$_POST['BindSample'];
			if($model->save())
				$this->redirect('admin');
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 * 业务资源删除--未使用
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * 未使用
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('RomSoftpak');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * APP所属列表
	 */
	public function actionAdmin()
	{
		$model=new RomSoftpak('search');
		$model->unsetAttributes();
		if(isset($_GET['RomSoftpak']))
			$model->attributes=$_GET['RomSoftpak'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	/**
	 * 业务APP激活情况
	 */
	public function actionAppresource()
	{
		$model=new RomAppresource('search');
		$model->unsetAttributes();
		if(isset($_GET['RomAppresource']))
			$model->attributes=$_GET['RomAppresource'];

		$this->render('appresource',array(
			'model'=>$model,
		));
	}
	/**
	 * 上报数据
	 */
	public function actionAppupdata()
	{
        $model=Yii::app()->cache->get('cache');


        if($model===false){
            $model=new RomAppupdata('search');
            $model->unsetAttributes();
            if(isset($_GET['RomAppupdata']))
                $model->attributes=$_GET['RomAppupdata'];
            Yii::app()->cache->set('cache',$model,60);
        }
		$this->render('appupdata',array(
			'model'=>$model,
		));
	}
    /**
     * @param $date
     * @param $type
     * @param $username
     * @param $models
     * 业务APP激活判定
     */
    public function actionConfirm($date,$type,$username,$models)
    {
        if(empty($date))
        {
            $date=date('Y-m-d', strtotime('-1 day'));
        }
        $dates = empty($date) ? date('Y-m-d', strtotime('-1 day'))." 23:59:59" : $date." 23:59:59";
        $type = empty($type) ? "ucllq" : $type;
        $models = empty($models) ? "" : urldecode($models);
        $prorule=Product::model()->find('pathname=:pathname',array('pathname'=>$type));
        $actrule=$prorule["actrule"];

        $data = RomAppupdata::model()->getDataProviderByDate($dates,$type,$username,$actrule,$models);

        //RomAppresource::model()->findAll("uid='$this->uid' and uid='$this->uid' and uid='$this->uid'");
        $this->render('confirm', array(
            'data' => $data,
            'date' => $date,
            'models' => $models,
            'username' => $username,
            'type' => $type,
        ));
    }
    /**
     * 地推判定
     * @author zkn
     * @datetime 2016-6-20 16:10:45
     * */
    public function actionDtConfirm($date,$type,$username)
    {
        if(empty($date))
        {
            $date=date('Y-m-d', strtotime('-1 day'));
        }
        $dates = empty($date) ? date('Y-m-d', strtotime('-1 day'))." 23:59:59" : $date." 23:59:59";
        $type = empty($type) ? "ucllq" : $type;


        $data = RomAppresource::model()->getDtDataProviderByDate($dates,$type,$username);

        $this->render('dt_confirm', array(
            'data' => $data,
            'date' => $date,
            'username' => $username,
            'type' => $type,
        ));
    }

    /**
     * @throws CHttpException
     * 判定激活
     */
    public function actionUpdates()
    {
/*        if (Yii::app()->request->isPostRequest === false) {
            throw new CHttpException(404, '无此页面');
        }*/
        $t = Yii::app()->db->beginTransaction();
        $typename="";
        $title="";
        try {
            $rids = Yii::app()->request->getParam('rid');
            if(Yii::app()->request->getQuery('rid')!="")
            {
                $rids=array($rids);
            }
            if (is_array($rids) === false) throw new Exception();
            $date="";

            foreach ($rids as $k=>$v)
            {
                $subrid=explode("-",$v);
                if (empty($subrid[0]) || empty($subrid[1]) || empty($subrid[2]) || empty($subrid[3])) continue;
                if(empty($subrid[4]))
                {
                    $this->activity($subrid[0],$subrid[1],$subrid[2],$subrid[3]);
                }
                else
                {
                    //地推使用
                    $this->activity($subrid[0],$subrid[1],$subrid[2],$subrid[3],$subrid[4]);
                    $title=$subrid[4];
                }

                $typename=$subrid[1];
                $date=$subrid[3];
            }
            if(Yii::app()->request->getQuery('rid')!="")
            {
                $smodel=new SystemLog();
                if($title=="dt")
                {
                    $smodel->type="DTACTIVITY";
                }
                else
                {
                    $smodel->type="ACTIVITY";
                }
                $smodel->content="判定".$rids[0]."激活";
                $smodel->operate=Yii::app()->user->getState('manage_username');
                $smodel->status=1;
                $smodel->date=$date;
                $smodel->insert();
            }
            else
            {
                $smodel=new SystemLog();
                if($title=="dt")
                {
                    $smodel->type="DTACTIVITY";
                }
                else
                {
                    $smodel->type="ACTIVITY";
                }
                $smodel->content="判定所有激活";
                $smodel->operate=Yii::app()->user->getState('manage_username');
                $smodel->status=1;
                $smodel->target=$typename;
                $smodel->date=$date;
                $smodel->insert();
            }
            $t->commit();
            if(Yii::app()->request->getQuery('rid')!="")
            {
                echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
                echo '<script type="text/javascript" charset="utf-8">alert("完成激活");window.history.go(-1); </script>';
            }
            else
            {
                echo 'success';
            }

        } catch (Exception $e) {
            $t->rollback();
            echo 'error';
        }
    }
    /**
     * 具体激活
     * @param $id
     * @param $type
     * @param $imei
     * @param $date
     * @param $title
     * @throws Exception
     */
    private function activity($id,$type,$imei,$date,$title="")
    {
        try {
            $model = RomAppresource::model()->find('uid=:uid AND status=1 AND finishstatus=0 AND type=:type AND imeicode=:imeicode',array(':uid'=>$id,':type'=>$type,':imeicode'=>$imei));
            if (is_null($model))
            {
                if($title=="dt")
                {
                }
                else
                {
                    $modelup = RomAppupdata::model()->findAll('uid=:uid AND appname=:appname AND imeicode=:imeicode',array(':uid'=>$id,':appname'=>$type,':imeicode'=>$imei));
                    if (is_null($modelup)) { }
                    else
                    {
                        foreach($modelup as $ki=>$vi)
                        {
                            $modelupsub = RomAppupdata::model()->find('id=:id',array(':id'=>$vi["id"]));
                            $modelupsub->finshstatus=1;
                            $modelupsub->finshstatustime=$date;
                            $modelupsub->update();
                        }
                    }
                }


            }
            else
            {
                $model->finishdate = $date;
                $model->finishtime = date('Y-m-d H:i:s');
                $model->finishstatus = 1;
                $model->status = 0;
                $model->update();

                if($title=="dt")
                {
                }
                else
                {
                    $modelup = RomAppupdata::model()->findAll('uid=:uid AND appname=:appname AND imeicode=:imeicode',array(':uid'=>$id,':appname'=>$type,':imeicode'=>$imei));
                    if (is_null($modelup)) { }
                    else
                    {
                        foreach($modelup as $ki=>$vi)
                        {
                            $modelupsub = RomAppupdata::model()->find('id=:id',array(':id'=>$vi["id"]));
                            $modelupsub->finshstatus=1;
                            $modelupsub->finshstatustime=$date;
                            $modelupsub->update();
                        }
                    }
                }


            }





        } catch (Exception $e) {
            throw $e;
        }
    }
    /**
     * @throws CHttpException
     * 业务封号
     */
    public function actionNoupdates()
    {
        $t = Yii::app()->db->beginTransaction();
        try {
            $rids = Yii::app()->request->getParam('rid');
            if(Yii::app()->request->getQuery('rid')!="")
            {
                $rids=array($rids);
            }
            if (is_array($rids) === false) throw new Exception();
            foreach ($rids as $k=>$v)
            {
                $subrid=explode("-",$v);
                if (empty($subrid[0]) || empty($subrid[1]) || empty($subrid[2])) continue;
                $this->noactivity($subrid[0],$subrid[1],$subrid[2]);
            }
            if(Yii::app()->request->getQuery('rid')!="")
            {
                $smodel=new SystemLog();
                $smodel->type="UPDATE";
                $smodel->content="判定".$rids[0]."封号";
                $smodel->operate=Yii::app()->user->getState('manage_username');
                $smodel->status=1;
                $smodel->date=date('Y-m-d H:i:s');
                $smodel->insert();
            }
            else
            {
                $smodel=new SystemLog();
                $smodel->type="UPDATE";
                $smodel->content="判定所有封号";
                $smodel->operate=Yii::app()->user->getState('manage_username');
                $smodel->status=1;
                $smodel->date=date('Y-m-d H:i:s');
                $smodel->insert();
            }
            $t->commit();
            if(Yii::app()->request->getQuery('rid')!="")
            {
                echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
                echo '<script type="text/javascript" charset="utf-8">alert("完成封号");window.history.go(-1); </script>';
            }
            else
            {
                echo 'success';
            }
        } catch (Exception $e) {
            $t->rollback();
            echo 'error';
        }
    }
    /**
     * 具体业务封号
     * @param $id
     * @param $type
     * @param $imei
     * @throws Exception
     */
    private function noactivity($id,$type,$imei)
    {
        try {
            $model = RomAppresource::model()->find('uid=:uid AND type=:type AND imeicode=:imeicode',array(':uid'=>$id,':type'=>$type,':imeicode'=>$imei));
            if (is_null($model)) {throw new CHttpException(500, '错误，无此信息');}

            $model->closeend = date('Y-m-d H:i:s');
            $model->finishstatus = 0;
            $model->status = 0;
            $model->update();

            $modelup = RomAppupdata::model()->findAll('uid=:uid AND appname=:appname AND imeicode=:imeicode',array(':uid'=>$id,':appname'=>$type,':imeicode'=>$imei));
            if (is_null($modelup)) { }
            else
            {
                foreach($modelup as $ki=>$vi)
                {
                    $modelupsub = RomAppupdata::model()->find('id=:id',array(':id'=>$vi["id"]));
                    $modelupsub->finshstatus=1;
                    $modelupsub->update();
                }
            }


        } catch (Exception $e) {
            throw $e;
        }
    }
    /**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return BindSample the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=BindSample::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BindSample $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='bind-sample-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    /**
     * 安装量分析
     */
    public function actionAppresourceSee($date = '',$date2 = '',$username = '')
    {
        $date = empty($date) ? date('Y-m-d', strtotime('-1 day')) : $date;
        $date2 = empty($date2) ? date('Y-m-d', strtotime('-1 day')) : $date2;

        $member=Member::model()->getByUserName($username);
        $uid = empty($member["id"]) ? 2 : $member["id"];

        //总数据统计
        $sql_month ="select count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and DATE_FORMAT(createtime,'%Y%m%d') >= DATE_FORMAT('{$date}','%Y%m%d') and DATE_FORMAT(createtime,'%Y%m%d') <= DATE_FORMAT('{$date2}','%Y%m%d')";
        $monthdata = Yii::app()->db->createCommand($sql_month)->queryAll();

        //日查询--判断安装量：未完成+当天日期（其它条件查询无效）
        $sql ="select id,uid,createtime,model,count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and  DATE_FORMAT(createtime,'%Y%m%d') >= DATE_FORMAT('{$date}','%Y%m%d') and DATE_FORMAT(createtime,'%Y%m%d') <= DATE_FORMAT('{$date2}','%Y%m%d')  group by  model order by counts desc";
        $modelList = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('appresourcesee', array(
            'dataProvider' => new CArrayDataProvider($modelList, array(
                'pagination' => array(
                    'pageSize' => Common::PAGE_SIZE,
                ),
            )),
            'date' => $date,
            'date2' => $date2,
            'username' => $username,
            'monthdata' => $monthdata
        ));
    }
    /**
     * 安装量排行
     */
    public function actionappresourceList($date = '',$date2 = '',$username = '')
    {
        $date = empty($date) ? date('Y-m-d', strtotime('-1 day')) : $date;
        $date2 = empty($date2) ? date('Y-m-d', strtotime('-1 day')) : $date2;

        $member=Member::model()->getByUserName($username);
        $uid = empty($member["id"]) ? 2 : $member["id"];

        //总数据统计
        $sql_month ="select count(distinct(imeicode)) as counts from app_rom_appresource where DATE_FORMAT(createtime,'%Y%m%d') >= DATE_FORMAT('{$date}','%Y%m%d') and DATE_FORMAT(createtime,'%Y%m%d') <= DATE_FORMAT('{$date2}','%Y%m%d')";
        $monthdata = Yii::app()->db->createCommand($sql_month)->queryAll();

        //日查询--判断安装量
        if($uid==2)
        {
            $sql ="select id,uid,createtime,model,count(distinct(imeicode)) as counts from app_rom_appresource where DATE_FORMAT(createtime,'%Y%m%d') >= DATE_FORMAT('{$date}','%Y%m%d') and DATE_FORMAT(createtime,'%Y%m%d') <= DATE_FORMAT('{$date2}','%Y%m%d')  group by  uid order by counts desc";
        }
        else
        {
            $sql ="select id,uid,createtime,model,count(distinct(imeicode)) as counts from app_rom_appresource where uid={$uid} and  DATE_FORMAT(createtime,'%Y%m%d') >= DATE_FORMAT('{$date}','%Y%m%d') and DATE_FORMAT(createtime,'%Y%m%d') <= DATE_FORMAT('{$date2}','%Y%m%d')  group by  uid order by counts desc";
        }
        $modelList = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('appresourcelist', array(
            'dataProvider' => new CArrayDataProvider($modelList, array(
                'pagination' => array(
                    'pageSize' => Common::PAGE_SIZE,
                ),
            )),
            'date' => $date,
            'date2' => $date2,
            'username' => $username,
            'monthdata' => $monthdata
        ));
    }
    /**
     * APP数据分析
     */
    public function actionDataInfo($date = '',$date2 = '',$type = '',$from='')
    {
        $date = empty($date) ? date('Y-m-d', strtotime('-1 day')) : $date;
        $dateall=$date." 00:00:00";
        $date2 = empty($date2) ? date('Y-m-d', strtotime('-1 day')) : $date2;
        $date2all=$date2." 23:59:59";
        $type = empty($type) ? "" : $type;
        $from = empty($from) ? 0 : $from;
        if($from==5)
        {
            $from='5,6,7,8,9';
        }

        $uidss=Member::model()->findAll('subagent=707 or id=1957');
        $uidsstr=array();
        foreach($uidss as $uk=>$uv)
        {
            $uidsstr[]=$uv['id'];
        }
        $uidstr=implode(",",$uidsstr);
        if(empty($type))
        {
            $sql="select *  from (";
            $sqlinstall="(SELECT '1' a,0,type as id,type,count(simcode) as install,`from` FROM `app_rom_appresource` WHERE `createtime` >= '{$dateall}'  AND `createtime` <= '{$date2all}' AND `from` in ({$from}) AND `uid` not in ({$uidstr}) group by type)";
            $sqlactivate="(SELECT '2',finishdate as date,type as id,type,count(simcode) as activate,`from` FROM `app_rom_appresource` WHERE `finishdate` >= '{$date}' AND `finishdate` <= '{$date2}' AND `from` in ({$from}) AND `uid` not in ({$uidstr}) group by type)";
            $sqluninstall="(SELECT '3',0,type as id,appname as type,count(simcode) as uninstall,`from` FROM `app_rom_appupdata` WHERE `type` = '1' AND `date` >= '{$date}'  AND `date` <= '{$date2}' AND `from` in ({$from})  AND `uid` not in ({$uidstr}) group by appname)";
            switch ($from)
            {
                case 0:
                    $sqlimportlog="(SELECT '4',0,type as id,type,sum(data) as importdata,0 FROM `app_import_log` WHERE `date` >= '{$date}'  AND `date` <= '{$date2}' AND `gid` in (0,69)  group by type)";
                    break;
                case 1:
                    $sqlimportlog="(SELECT '4',0,type as id,type,sum(data) as importdata,0 FROM `app_import_log` WHERE `date` >= '{$date}'  AND `date` <= '{$date2}' AND `gid` =77  group by type)";
                    break;
                case 2:
                    $sqlimportlog="(SELECT '4',0,type as id,type,sum(data) as importdata,0 FROM `app_import_log` WHERE `date` >= '{$date}'  AND `date` <= '{$date2}' AND `gid` =77  group by type)";
                    break;
                case 3:
                    $sqlimportlog="(SELECT '4',0,type as id,type,sum(data) as importdata,0 FROM `app_import_log` WHERE `date` >= '{$date}'  AND `date` <= '{$date2}' AND `gid` =88  group by type)";
                    break;
                case 4:
                    $sqlimportlog="(SELECT '4',0,type as id,type,sum(data) as importdata,0 FROM `app_import_log` WHERE `date` >= '{$date}'  AND `date` <= '{$date2}' AND `gid` =88  group by type)";
                    break;
                case 5:
                    $sqlimportlog="(SELECT '4',0,type as id,type,sum(data) as importdata,0 FROM `app_import_log` WHERE `date` >= '{$date}'  AND `date` <= '{$date2}' AND `gid` =99  group by type)";
                    break;
            }

            $sql=$sql.$sqlinstall." UNION ALL ".$sqlactivate." UNION ALL ".$sqluninstall." UNION ALL ".$sqlimportlog;
            $sql=$sql.") as a ORDER BY a.type,a.install DESC";
        }
        else
        {
            $sql="select *  from (";
            $sqlinstall="(SELECT '1' a,0,type as id,type,count(simcode) as install,`from` FROM `app_rom_appresource` WHERE `createtime` >= '{$dateall}'  AND `createtime` <= '{$date2all}' AND `type`='{$type}' AND `from` in ({$from})  AND `uid` not in ({$uidstr}) group by type)";
            $sqlactivate="(SELECT '2',finishdate as date,type as id,type,count(simcode) as activate,`from` FROM `app_rom_appresource` WHERE `finishdate` >= '{$date}' AND `finishdate` <= '{$date2}' AND `type`='{$type}' AND `from` in ({$from})  AND `uid` not in ({$uidstr}) group by type)";
            $sqluninstall="(SELECT '3',0,type as id,appname as type,count(simcode) as uninstall,`from` FROM `app_rom_appupdata` WHERE `type` = '1' AND `date` >= '{$date}'  AND `date` <= '{$date2}' AND `appname`='{$type}' AND `from` in ({$from})  AND `uid` not in ({$uidstr}) group by appname)";
            switch ($from)
            {
                case 0:
                    $sqlimportlog="(SELECT '4',0,type as id,type,sum(data) as importdata,0 FROM `app_import_log` WHERE `date` >= '{$date}'  AND `date` <= '{$date2}' AND `gid` in (0,69) AND `type`='{$type}'  group by type)";
                    break;
                case 1:
                    $sqlimportlog="(SELECT '4',0,type as id,type,sum(data) as importdata,0 FROM `app_import_log` WHERE `date` >= '{$date}'  AND `date` <= '{$date2}' AND `gid` =77 AND `type`='{$type}'  group by type)";
                    break;
                case 2:
                    $sqlimportlog="(SELECT '4',0,type as id,type,sum(data) as importdata,0 FROM `app_import_log` WHERE `date` >= '{$date}'  AND `date` <= '{$date2}' AND `gid` =77  AND `type`='{$type}' group by type)";
                    break;
                case 3:
                    $sqlimportlog="(SELECT '4',0,type as id,type,sum(data) as importdata,0 FROM `app_import_log` WHERE `date` >= '{$date}'  AND `date` <= '{$date2}' AND `gid` =88  AND `type`='{$type}' group by type)";
                    break;
                case 4:
                    $sqlimportlog="(SELECT '4',0,type as id,type,sum(data) as importdata,0 FROM `app_import_log` WHERE `date` >= '{$date}'  AND `date` <= '{$date2}' AND `gid` =88  AND `type`='{$type}' group by type)";
                    break;
                case 5:
                    $sqlimportlog="(SELECT '4',0,type as id,type,sum(data) as importdata,0 FROM `app_import_log` WHERE `date` >= '{$date}'  AND `date` <= '{$date2}' AND `gid` =99  AND `type`='{$type}' group by type)";
                    break;

            }
            $sql=$sql.$sqlinstall." UNION ALL ".$sqlactivate." UNION ALL ".$sqluninstall." UNION ALL ".$sqlimportlog;
            $sql=$sql.") as a ORDER BY a.type,a.install DESC";
        }

        $modelList = Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($modelList))
        {
            $typename="";
            foreach($modelList as $key=>$val)
            {
                $pro=Product::model()->find('pathname=:pathname',array(':pathname'=>$val["type"]));
                $modelList[$key]["date"]=$date;
                //去重复类型
                if($val["type"]==$typename)
                {
                    unset($modelList[$key]);
                    continue;
                }
                else
                {
                    $typename=$val["type"];
                }
                //比较赋值
                foreach($modelList as $k=>$v)
                {
                    if($val["type"]==$v["type"])
                    {
                        if($v["a"]==1){$modelList[$key]["install"]=$v["install"];$modelList[$key]["type"]=$pro["name"];}
                        if($v["a"]==2){$modelList[$key]["activate"]=$v["install"];$modelList[$key]["type"]=$pro["name"];}
                        if($v["a"]==3){$modelList[$key]["uninstall"]=$v["install"];$modelList[$key]["type"]=$pro["name"];}
                        if($v["a"]==4){$modelList[$key]["importactivate"]=$v["install"];$modelList[$key]["type"]=$pro["name"];}
                    }
                    //安装剩余量
                    if(!empty($modelList[$key]["install"]) && !empty($modelList[$key]["uninstall"]))
                    {
                        $modelList[$key]["installother"]=$modelList[$key]["install"]-$modelList[$key]["uninstall"];
                    }
                    else
                    {
                        $modelList[$key]["installother"]="";
                    }
                    //留存率
                    if(!empty($modelList[$key]["installother"]) && !empty($modelList[$key]["install"]))
                    {
                        $modelList[$key]["existrate"]=(round($modelList[$key]["installother"]/$modelList[$key]["install"],2)*100)."%";
                    }
                    else
                    {
                        $modelList[$key]["existrate"]="";
                    }
                    //激活转化
                    if(!empty($modelList[$key]["importactivate"]) && !empty($modelList[$key]["installother"]))
                    {
                        $modelList[$key]["activatechange"]=(round($modelList[$key]["importactivate"]/$modelList[$key]["installother"],2)*100)."%";
                    }
                    else
                    {
                        $modelList[$key]["activatechange"]="";
                    }


                }


            }
        }


        $this->render('datainfo', array(
            'dataProvider' => new CArrayDataProvider($modelList, array(
                'pagination' => array(
                    'pageSize' => 80,
                ),
            )),
            'date' => $date,
            'date2' => $date2,
            'type' => $type,
            'from' => $from,
        ));
    }
    /**
     * APP业务详情
     */
    public function actionAppdetail($date = '',$date2 = '',$type = '',$liu='')
    {
        $date = empty($date) ? date('Y-m-d', strtotime('-1 day')) : $date;
        $dateall=$date." 00:00:00";
        $date2 = empty($date2) ? date('Y-m-d', strtotime('-1 day')) : $date2;
        $date2all=$date2." 23:59:59";
        $type = empty($type) ? "" : $type;
        $modelList=array();
        if(!empty($type))
        {

            $sqlinstall="(SELECT uid,count(distinct imeicode) as install FROM `app_rom_appresource` WHERE `createtime` >= '{$dateall}'  AND `createtime` <= '{$date2all}' AND `type`='{$type}' group by uid  ORDER BY install DESC)";
            $installdata = Yii::app()->db->createCommand($sqlinstall)->queryAll();

            $inuid="";
            if(!empty($installdata))
            {
                foreach($installdata as $ink=>$inv)
                {
                    $inuid=$inv["uid"].",".$inuid;
                }
            }
            if(substr($inuid, -1)==",")
            {
                $inuid = substr($inuid, 0, -1);
            }
            if(!empty($inuid))
            {
                $sqluninstall="(SELECT uid,count(distinct imeicode) as uninstall FROM `app_rom_appupdata` WHERE `type` = '1' AND `date` >= '{$date}'  AND `date` <= '{$date2}' AND `appname`='{$type}' AND `uid`in({$inuid}) group by uid)";
                $uninstalldata = Yii::app()->db->createCommand($sqluninstall)->queryAll();


                if(!empty($installdata))
                {
                    foreach($installdata as $key=>$inv)
                    {
                        if(!empty($uninstalldata))
                        {
                            foreach($uninstalldata as $unk=>$unv)
                            {
                                $tit=0;
                                if($inv["uid"]==$unv["uid"])
                                {
                                    $tit=1;
                                    $modelList[$key]["uninstall"]=$unv["uninstall"];
                                    break;
                                }
                            }
                            $modelList[$key]["id"]=$key+1;
                            $undata=Member::model()->getById($inv["uid"]);
                            $modelList[$key]["username"]=$undata["username"];
                            $modelList[$key]["install"]=$inv["install"];

                            if($tit==1)
                            {
                                //安装剩余量
                                if(!empty($modelList[$key]["install"]) && !empty($modelList[$key]["uninstall"]))
                                {
                                    $modelList[$key]["installother"]=$modelList[$key]["install"]-$modelList[$key]["uninstall"];
                                }
                                //留存率
                                if(!empty($modelList[$key]["installother"]) && !empty($modelList[$key]["install"]))
                                {
                                    if($modelList[$key]["installother"]>0)
                                    {
                                        $modelList[$key]["existrate"]=(round($modelList[$key]["installother"]/$modelList[$key]["install"],2)*100)."%";
                                    }
                                    else
                                    {
                                        $modelList[$key]["existrate"]=0;
                                    }

                                }
                            }
                            else
                            {
                                $modelList[$key]["installother"]="";
                                $modelList[$key]["existrate"]="";
                            }
                        }
                        else
                        {
                            $modelList[$key]["id"]=$key+1;
                            $undata=Member::model()->getById($inv["uid"]);
                            $modelList[$key]["username"]=$undata["username"];
                            $modelList[$key]["install"]=$inv["install"];
                            $modelList[$key]["installother"]="";
                            $modelList[$key]["existrate"]="";
                        }




                    }
                }
            }

        }
        else
        {
            $modelList=array();
        }

        if(!empty($liu) && !empty($modelList))
        {
            foreach ($modelList as $key => $row) {
                if(empty($row['existrate']))
                {
                    $row['existrate']=0;
                }
                $volume[$key]  = $row['existrate'];
            }
            array_multisort($volume, SORT_DESC, $modelList);
        }
        $this->render('appdetail', array(
            'dataProvider' => new CArrayDataProvider($modelList, array(
                'pagination' => array(
                    'pageSize' => 80,
                ),
            )),
            'date' => $date,
            'date2' => $date2,
            'type' => $type,
        ));
    }
    /**
     * APP业务详情--测试使用
     */
    public function actionAppdetailtest($date = '',$date2 = '')
    {
        $date = empty($date) ? date('Y-m-d', strtotime('-1 day')) : $date;
        $dateall=$date." 00:00:00";
        $date2 = empty($date2) ? date('Y-m-d', strtotime('-1 day')) : $date2;
        $date2all=$date2." 23:59:59";

        $modelList=array();
        if(!empty($date2all))
        {

            $typeList = Ad::getAdListKeys();
            $sql="";
            foreach ($typeList as $type) {
                $sql .= 'sum(if(type=\''.$type . '\',a.counts,0)) AS '.$type.',';
            }
            $sql=substr($sql,0,strlen($sql)-1);
            $sqlinstall="select a.uid as uid,
{$sql}
    from
	(SELECT uid,type,count(distinct imeicode) as counts
FROM `app_rom_appresource`
WHERE `from` in(1,2) AND `createtime` >='{$dateall}' and `createtime` <= '{$date2all}'
group by type,uid) as a
    group by a.uid";
            $installdata = Yii::app()->db->createCommand($sqlinstall)->queryAll();
            if(!empty($installdata))
            {
                foreach($installdata as $key=>$inv)
                {
                    $installdata[$key]["id"]=$key+1;
                    $undata=Member::model()->getById($inv["uid"]);
                    $installdata[$key]["username"]=$undata["username"];

                    $sqli="SELECT count(distinct imeicode) as counts
FROM `app_rom_appresource`
WHERE `from` in(1,2) AND `createtime` >='{$dateall}' and `createtime` <= '{$date2all}' AND `uid` ='{$inv["uid"]}'";
                    $inst = Yii::app()->db->createCommand($sqli)->queryAll();
                    $installdata[$key]["sum"]=$inst[0]["counts"];

                }
            }
        }
        else
        {
            $installdata=array();
        }
        if(!empty($installdata))
        {
            foreach ($installdata as $key => $row) {
                if(empty($row['sum']))
                {
                    $row['sum']=0;
                }
                $volume[$key]  = $row['sum'];
            }
            array_multisort($volume, SORT_DESC, $installdata);
        }
        $this->render('appdetailtest', array(
            'dataProvider' => new CArrayDataProvider($installdata, array(
                'pagination' => array(
                    'pageSize' => 80,
                ),
            )),
            'date' => $date,
            'date2' => $date2,
        ));
    }
    /**
     * client data
     * @author zkn
     * 地推安装上报
     * */
    public function actionClientData(){
        $model = new ClientData();
        $model->unsetAttributes();
        if(isset($_GET['ClientData']))
            $model->attributes=$_GET['ClientData'];

        $this->render('client_data',array(
            'model'=>$model,
        ));
    }
    /**
     * 增加安装量分析排行榜
     * http://spoa.58xuntui.com/operation/default/detail?id=1003
     * @author zkn
     * @datetime 2016-6-16 13:51:28
     * */
    public function AppInstallAnalysis($stat_date,$end_date){
        $sql = "SELEC";
    }
    /**
     * 用户安装量数据导入
     * @author zkn
     * @datetime 2016-6-16 13:54:48
     * */
    public function AppInstallAnalysisImport($date){
        
    }




    /**
     * 数据留存
     */
    public function actionAppdataretention(){
        $date=date('Y-m-d', strtotime('-2 day'));
        $this->render('appdataretention', array('date'=>$date));
    }

    /*
     * 业务数据返回-业务对应的ajax
     **/
    public function actionYWajax(){
        if (Yii::app()->request->isAjaxRequest) {
            $pathname = Yii::app()->request->getParam('pathname');
            $fz= Yii::app()->request->getParam('fz');
            $data =$this->getPathnameAgent($pathname,$fz);
            $arr = array();
            if (!empty($data)){
                foreach ($data as $vt){
                    $arr[]=$vt['sign'].'__'.$vt['version'];
                }
                exit(CJSON::encode(array("val"=>$arr)));
            }else{
                exit(CJSON::encode(array("val"=>'empty',"message"=>"查询数据为空,请重新选择")));
            }
        }
    }
    
    /*
     * 业务数据返回-分组对应的ajax
     * */
    
    public function actionFZajax(){
        if (Yii::app()->request->isAjaxRequest) {

            $pathname = Yii::app()->request->getParam('pathname');
            $fz= Yii::app()->request->getParam('fz');
            $data =$this->getPathnameAgent($pathname,$fz);
            $arr = array();
            if (!empty($data)){
                foreach ($data as $vt){
                    $arr[]=$vt['sign'].'__'.$vt['version']."__".substr($vt['createtime'],0,10);
                }
                exit(CJSON::encode(array("val"=>$arr)));
            }else{
                exit(CJSON::encode(array("val"=>'empty',"message"=>"查询数据为空,请重新选择分组")));
            }
        }
        
    }

    // 通过pathname 和 agent查询数据
    private function getPathnameAgent($pathname,$fz){
        $sqlinstall="(SELECT * FROM `app_product_list` WHERE type = '{$pathname}' and agent='{$fz}' order by id desc)";
        $data = Yii::app()->db->createCommand($sqlinstall)->queryAll();
        return$data;

    }

    // 查询之前数据处理
    public function actionDealData(){
        if (Yii::app()->request->isAjaxRequest) {
            $arr = Yii::app()->request->getParam('data');
            $md5_array = $arr['md5_version'];
            $time = strtotime($arr['date']);
            $dateStart = $arr['date'];
            $dateEnd = date("Y-m-d",$time+($arr['zq']+1)*24*60*60);
            $md5_str = '';
            foreach ($md5_array as $k=>$vt){
                if ($k!=0){
                    $md5 = explode('__',$vt);
                    $md5_str=$md5_str."'".$md5[0]."',";
                }
            }
            $md5_str=substr($md5_str,0,-1);
            $mid = Yii::app()->user->manage_id;
            $tablename = "app_rom_appupdatalog".$mid;
            $sql = "CREATE TABLE IF NOT EXISTS {$tablename} LIKE app_rom_appupdatalog_lstable";
            Yii::app()->db->createCommand($sql)->execute();
            $sql ="truncate table {$tablename}";
            Yii::app()->db->createCommand($sql)->execute();
            $sql = "INSERT INTO {$tablename} SELECT * FROM `app_rom_appupdatalog` WHERE `appname` = '{$arr['yw']}' AND `appmd5` IN ({$md5_str}) AND `createtime` >= '{$dateStart}' AND `createtime` <= '{$dateEnd}'";
            $data = Yii::app()->db->createCommand($sql)->execute();
            
            exit(CJSON::encode(array("val"=>$data)));
        }


    }



    // 搜索数据提交ajax

    public function actionCXajax(){
        if (Yii::app()->request->isAjaxRequest) {
            $arr = Yii::app()->request->getParam('data');
            $md5_array = $arr['md5_version'];
            $time = strtotime($arr['date']);
            $day = date("Y-m-d",$time+($arr['zq'])*24*60*60);
            $name = $arr['username'];
            $check=$arr['check'];
            $alluser = $arr['alluser'];
            if ($alluser =='true'){
                exit(CJSON::encode(array("val" => 'fail', 'message' => "查询出错")));
            }
            $md5_arr = array();
            foreach ($md5_array as $k=>$vt){
                if ($k!=0){
                    $md5 = explode('__',$vt);
                    $md5_arr[]=$md5[0];
                }
            }

            // 获取表名--每个用户对应表明不同
            $mid = Yii::app()->user->manage_id;
            $tablename = "app_rom_appupdatalog".$mid;
            $table = Yii::app()->db->createCommand("SHOW TABLES LIKE '".$tablename."'")->queryAll();
            if ($table == null){
                exit(CJSON::encode(array("val" => 'fail', 'message' => "数据查询出错")));
            };

            $arr_den = array();
            $arr_mol = array();
            foreach ($md5_arr as $vt) {
                if ($name == '') {
                    if ($check == 'true') {
                        $sql = "(SELECT DISTINCT imeicode,DATE_FORMAT(createtime,'%Y-%m-%d') as createtime  FROM {$tablename} WHERE `appname` = '{$arr['yw']}' AND `appmd5` ='{$vt}' AND `createtime` LIKE '%{$arr['date']}%' AND `first` = 1 )";
                    } else {
                        $sql = "(SELECT DISTINCT imeicode,DATE_FORMAT(createtime,'%Y-%m-%d') as createtime FROM {$tablename} WHERE `appname` = '{$arr['yw']}' AND `appmd5` ='{$vt}' AND `createtime` >= '{$arr['date']}' AND `createtime` <= '{$day}' AND `first` = 1 )";
                    }
                } else {
                    $dataMember = Member::model()->findAll('username=:username', array(':username' => $name));
                    if ($check == 'true') {
                        if (empty($dataMember)) {
                            exit(CJSON::encode(array("val" => 'fail', 'message' => "用户名不存在,无法查到数据")));
                        } else {
                            $sql = "(SELECT DISTINCT imeicode, DATE_FORMAT(createtime,'%Y-%m-%d') as createtime FROM {$tablename} WHERE `appname` = '{$arr['yw']}' AND `appmd5` ='{$vt}' AND `uid` = {$dataMember[0]['id']} AND `createtime` LIKE '%{$arr['date']}%' AND `first` = 1 )";
                        }
                    } else {
                        if (empty($dataMember)) {
                            exit(CJSON::encode(array("val" => 'fail', 'message' => "用户名不存在,无法查到数据")));
                        } else {
                            $sql = "(SELECT DISTINCT imeicode, DATE_FORMAT(createtime,'%Y-%m-%d') as createtime FROM {$tablename} WHERE `appname` = '{$arr['yw']}' AND `appmd5` ='{$vt}' AND `uid` = {$dataMember[0]['id']} AND `createtime` >= '{$arr['date']}' AND `createtime` <= '{$day}' AND `first` = 1 )";
                        }
                    }

                }
                $data = Yii::app()->db->createCommand($sql)->queryAll();

                $data = $this->dealData($data);
                $array = array();
                foreach ($data as $key => $v) {
                    $date = $key;
                    $imei = $v;
                    $arrI = explode(',', $imei);
                    $str = "'" . implode("','", $arrI) . "'";
                    $array[strtotime($date)] = $str;
                }
                for ($i = 0; $i < $arr['zq']; $i++) {
                    $day = date("Y-m-d", $time + $i * 24 * 60 * 60);
                    if (isset($array[strtotime($day)])) {

                    } else {
                        $array[strtotime($day)] = '';
                    }
                }
                // 按键值升序排序
                ksort($array);
                for ($i = 0; $i < $arr['zq']; $i++) {
                    $time2 = $time + $i * 24 * 60 * 60;
                    // 查询开始日期
                    $startTime = date("Y-m-d", $time2 + 1 * 24 * 60 * 60);
                    $endTime = date("Y-m-d", $time + ($arr['zq'] + 1) * 24 * 60 * 60);

                    $day = date("Y-m-d", $time + ($i + 1) * 24 * 60 * 60);
                    if (empty($array[$time2])) {
                        continue;
                    }
                    if ($name == '') {
                        $sql = "(SELECT DISTINCT imeicode, DATE_FORMAT(createtime,'%Y-%m-%d') as createtime FROM {$tablename} WHERE `appname` = '{$arr['yw']}' AND `first`=0 AND `appmd5` ='{$vt}' AND `createtime` >= '{$startTime}' AND `createtime`< '{$endTime}' AND `imeicode` IN ($array[$time2]))";
                    } else {
                        $dataMember = Member::model()->findAll('username=:username', array(':username' => $name));
                        if (empty($dataMember)) {
                            exit(CJSON::encode(array("val" => 'fail', 'message' => "用户名不存在,无法查到数据")));
                        } else {
                            $sql = "(SELECT DISTINCT imeicode, DATE_FORMAT(createtime,'%Y-%m-%d') as createtime FROM {$tablename} WHERE `appname` = '{$arr['yw']}' AND `first`=0 AND `appmd5` ='{$vt}' AND `createtime` >= '{$startTime}' AND `createtime`<'{$endTime}' AND `uid` = {$dataMember[0]['id']} AND `imeicode` IN ($array[$time2]))";
                        }
                    }
                    $data = Yii::app()->db->createCommand($sql)->queryAll();
                    $data = $this->dealData($data);

                    for ($j = 1; $j <= $arr['zq'] - $i; $j++) {
                        $d = date("Y-m-d", $time2 + $j * 24 * 3600);
                        if (!array_key_exists($d,$data)){
                            $data[$d]=0;
                        }
                    }

                    if (array_key_exists($time2, $arr_den)) {
                        $arr_den[$time2] = $arr_den[$time2] + count(explode(',', $array[$time2]));
                    } else {
                        $arr_den[$time2] = count(explode(',', $array[$time2]));
                    }
                    $arrayIM = array();
                    foreach ($data as $key => $v) {
                        $date = $key;
                        if ($v==0){
                            $arrayIM[strtotime($date)] = 0;
                        }else{
                            $imei = $v;
                            $arrIM = explode(',', $imei);
                            $arrayIM[strtotime($date)] = count($arrIM);
                        }
                    }


                    if (array_key_exists($time2,$arr_mol)){
                        foreach($arr_mol[$time2] as $key=>$vv){
                            $arr_mol[$time2][$key] = (int)$vv+$arrayIM[$key];
                        }
                    }else{
                        $arr_mol[$time2] = $arrayIM;
                    }
                }
            }

            $arr_per = array();
            foreach ($arr_mol as $key=>$vt){
                $sum=0;
                $q = count($vt);

                foreach ($vt as $k=>$v){
                    $sum=$sum+$v;
                    if ($v==0){
                        $q=$q-1;
                    }
                    $arr_per[$key][$k] = round(($v/$arr_den[$key])*100,2);
                }
                $p=0;
                if($q!=0){
                    $p = round($sum/$q,5);
                }
                $arr_per[$key]['p'] = round(($p/$arr_den[$key])*100,2);
            }

            $arr2 = array();
            foreach ($arr_per as $index=>$k){
                $arr3 = array();
                $m = ($index-$time)/(24*3600);
                foreach ($k as $ke=>$v){
                    if ($ke!='p'){
                        $n = ($ke-$time)/(24*3600);
                        $arr3[$n-$m] = $v;
                    }else{
                        $arr3['p'] = $v;
                    }

                }
                $arr2[$index]=$arr3;
            }

            foreach ($arr2 as $key=>$vt){
                $count = count($vt)-1;
                for ($i=$count+1;$i<=$arr['zq'];$i++){
                    $arr2[$key][$i] = 0;
                }
            }

            $a=array();
            for ($g=1;$g<=$arr['zq'];$g++){
                $a[$g] = 0;
            }
            $a['q'] = 0;
            for ($k = 0; $k < $arr['zq']; $k++) {
                $t = $time+$k*24*3600;
               if (!array_key_exists($t,$arr2)){
                   $arr2[$t] = $a;
               }
            }

            exit(CJSON::encode(array("val"=>$arr2,'valzq'=>$arr['zq'],'valDate'=>$time,'valfg'=>$arr['fg'])));
        }
    }


    // 搜索数据提交ajax--显示所有用户
    public function actionAllUserAjax(){
        if (Yii::app()->request->isAjaxRequest) {

            $arr = Yii::app()->request->getParam('data');
            $md5_array = $arr['md5_version'];
            $time = strtotime($arr['date']);
            $day = date("Y-m-d",$time+($arr['zq'])*24*60*60);
            $name = $arr['username'];
            $check=$arr['check'];
            $alluser = $arr['alluser'];

            $cur_date = date('Y-m-d');
            $num=$arr['zq']; // 计算平均留存需要
            if ($day>$cur_date){
                $num = $num- (strtotime($day)-strtotime($cur_date))/86400;
            }



            if ($check =='true'){
                exit(CJSON::encode(array("val" => 'fail', 'message' => "查看所有用户时,不能选中单日显示")));
            }
            if ($name !=''){
                exit(CJSON::encode(array("val" => 'fail', 'message' => "查看所有用户时,用户名必须为空")));
            }
            $md5_arr = array();
            foreach ($md5_array as $k=>$vt){
                if ($k!=0){
                    $md5 = explode('__',$vt);
                    $md5_arr[]=$md5[0];
                }
            }
            // 获取表名--每个用户对应表明不同
            $mid = Yii::app()->user->manage_id;
            $tablename = "app_rom_appupdatalog".$mid;
            $table = Yii::app()->db->createCommand("SHOW TABLES LIKE '".$tablename."'")->queryAll();
            if ($table == null){
                exit(CJSON::encode(array("val" => 'fail', 'message' => "数据查询出错")));
            };
            $arr_den=array();
            $arr_mol=array();
            foreach ($md5_arr as $vv){
                $sql = "SELECT DISTINCT imeicode,uid,DATE_FORMAT(createtime,'%Y-%m-%d') as createtime  FROM {$tablename} WHERE `appname` = '{$arr['yw']}' AND `appmd5` = '{$vv}' AND `createtime` LIKE '%{$arr['date']}%' AND `first` = 1 ";
                $data = Yii::app()->db->createCommand($sql)->queryAll();
                $array=array();
                if (empty($data)){
                    continue;
                }
                foreach ($data as $vt){
                    if (array_key_exists($vt['uid'],$array)){
                        $array[$vt['uid']]['imeicode'] = $array[$vt['uid']]['imeicode'].",'".$vt['imeicode']."'";
                    }else{
                        $array[$vt['uid']]['imeicode'] = "'".$vt['imeicode']."'";
                        $array[$vt['uid']]['uid'] = $vt['uid'];
                        $array[$vt['uid']]['createtime'] = $vt['createtime'];
                    }
                }

                $den=$this->dealDataDen($array);
                $startTime=date('Y-m-d',$time+24*3600);
                $endTime=date('Y-m-d',$time+($arr['zq']+1)*24*3600);
                $sql='';
                foreach ($array as $key => $vt){
                    $sql =$sql." SELECT DISTINCT imeicode, uid,appmd5,DATE_FORMAT(createtime,'%Y-%m-%d') as createtime FROM {$tablename} WHERE `appname` = '{$arr['yw']}' AND `first`=0 AND `appmd5` = '{$vv}' AND `createtime` >= '{$startTime}' AND `createtime`<= '{$endTime}' AND `imeicode` IN ({$vt['imeicode']}) AND uid={$vt['uid']} UNION";
                }
                $sql=substr($sql, 0, -5);
                $data_info = Yii::app()->db->createCommand($sql)->queryAll();
                if (empty($data_info)){
                    continue;
                }
                $mol = $this->dealDataMol($data_info);

                if (!empty($arr_den)){
                    foreach ($den as $key=>$vt){
                        if (array_key_exists($key,$arr_den)){
                            $arr_den[$key] = $vt['imeicode']+$arr_den[$key];
                        }else{
                            $arr_den[$key] = $vt['imeicode'];
                        }
                    }
                }else{
                    foreach ($den as $key=>$vt){
                        $arr_den[$key] = $vt['imeicode'];
                    }
                }
               if (!empty($arr_mol)){
                    foreach ($mol as $key=>$vt){
                        if (array_key_exists($key,$arr_mol)){
                            foreach ($vt as $k=>$v){
                                if (array_key_exists($k,$arr_mol[$key])){
                                    $arr_mol[$key][$k] = $arr_mol[$key][$k]+$v;
                                }else{
                                    $arr_mol[$key][$k] = $v;
                                }
                            }
                        }else{
                            $arr_mol[$key] = $vt;
                        }
                    }
               }else{
                   $arr_mol = $mol;
               }
            }
            
            foreach ($arr_mol as $key=>$vt){
                $sum = 0;
                foreach ($vt as $k=>$v){
                    $sum = $sum+$v;
                }
                $arr_mol[$key]['p'] = round(($sum/$num),5);
            }

            for ($i=1;$i<=$arr['zq'];$i++){
                $date = date('Y-m-d',$time+$i*24*3600);
                foreach ($arr_mol as $k=>$vt){
                    if(!array_key_exists($date,$vt)){
                        $arr_mol[$k][$date] = 0;
                    }
                }
            }
            // 计算比率
            $arr_rate = array();
            foreach ($arr_mol as $key=>$vt){
                $a=array();
                if (array_key_exists($key,$arr_den)){
                    foreach ($vt as $k=>$v){
                        $a[$k] = round(($v/$arr_den[$key])*100,2);
                    }
                }
                $arr_rate[$key] = $a;
            }

            $arr1 = array();
            foreach ($arr_rate as $key=>$vt){
                $a=array();
                foreach ($vt as $k=>$v){
                    if ($k!='p'){
                        $i = (strtotime($k)-$time)/(24*3600);
                        $a[$i] = $v;
                    }else{
                        $a['p'] = $v;
                    }
                }


                $user = Member::getUsernameByMid($key);
                $a['name'] = $user;
                $arr1[] = $a;
            }

            // 对数组按照mac数降序排序
            if (!empty($arr1)){
                $p = array();
                foreach ($arr1 as $k => $v){
                    $p[$k] = $v['p'];
                }

                array_multisort($p, SORT_DESC, $arr1);
            }
            exit(CJSON::encode(array("val"=>$arr1,'valzq'=>$arr['zq'],'valDate'=>$time,'valfg'=>$arr['fg'])));
        }
    }

    // 数据处理
    public function dealData($data){
        $arr = array();
        foreach ($data as $vt){
            if (array_key_exists($vt['createtime'],$arr)){
                $arr[$vt['createtime']] = $arr[$vt['createtime']].','.$vt['imeicode'];
            }else{
                $arr[$vt['createtime']] = $vt['imeicode'];
            }

        }
        return $arr;

    }


    // 数据处理--显示所有用户--分母
    public function dealDataDen($data){
        $arr=array();
        foreach ($data as $vt){
            $a=array();
            $a['imeicode']= count(explode(',', $vt['imeicode']));
            $a['uid'] = $vt['uid'];
            $a['createtime']=$vt['createtime'];
            $arr[$vt['uid']] = $a;
        }
        return $arr;
    }

    // 数据处理--显示所有用户--分子
    public function dealDataMol($data){
        $arr=array();
        foreach ($data as $vt){
            $arr[$vt['uid']][]=$vt;
        }

        $array=array();
        foreach ($arr as $key=>$vt){
            $a=array();
            foreach ($vt as $v){
                $a[$v['createtime']][] = $v;
            }
            $array[$key]=$a;
        }

        $group = array();
        foreach ($array as $key=> $vt){
            $arr = array();
            foreach ($vt as $k=>$v){
                $arr[$k]=count($v);
            }
            $group[$key] = $arr;
        }
        return $group;
    }


    /**
     * @name 市场用户
     */
    public function actionMarket($date = '',$username = ''){
        $date = empty($date) ? date('Y-m', time()) : $date;
        $username = empty($username) ? '' : $username;
        // $invi = array('39'=>"st0001",'42'=>"st9900",'41'=>"st0002");
        // 邀请码
        $sql='SELECT mid,code from `app_invitationcode`';
        $arr=yii::app()->db->createCommand($sql)->queryAll();
        $invi=array();
        if(!empty($arr)){
            foreach ($arr as $k => $v) {
                $invi[$v['mid']]=$v['code'];
            }
        }
        $mid = Yii::app()->user->manage_id;
        if (array_key_exists($mid,$invi)){
            $invitationcode = $invi[$mid];
            $sql = "SELECT id,username FROM `app_member` WHERE `invitationcode` = '$invitationcode' ORDER BY `id` DESC";
            $sql1 = "SELECT id,username FROM `app_member` WHERE `invitationcode` = '$invitationcode' and `username`='$username'  ORDER BY `id` DESC";
        }else{
            $sql = "SELECT id,username FROM `app_member` WHERE `invitationcode` != '' ORDER BY `id` DESC";
            $sql1 = "SELECT id,username FROM `app_member` WHERE `invitationcode` != '' and `username`='$username'  ORDER BY `id` DESC";
        }
        $array = array();
        if (empty($username)){
            //$sql = "SELECT id,username FROM `app_member` WHERE `invitationcode` != '' ORDER BY `id` DESC";
            $datall = Yii::app()->db->createCommand($sql)->queryAll();
            $array = $this::getData($datall,$date);
        }else{
            //$sql1 = "SELECT id,username FROM `app_member` WHERE `invitationcode` != '' and `username`='$username'  ORDER BY `id` DESC";
            $datall1 = Yii::app()->db->createCommand($sql1)->queryAll();
            if (empty($datall1)){

            }else{
                $array = $this::getData($datall1,$date);
            }
        }
        $sum = 0;
        if (empty($array)){
            $sum = 0;
        }else{
            foreach ($array as $vt){
                $sum +=$vt['m_income'];
            }
        }

        $dataProvider=new CArrayDataProvider($array, array(
            'keyField'=>'uid',
            'sort'=>array(
                'attributes'=>array(
                    'y_income',
                ),
                'defaultOrder'=>'y_income ASC',
            ),
            'pagination'=>array(
                'pageSize'=>25,
            ),
        ));
        $this->render('market',array('dataProvider'=>$dataProvider,'date'=>$date,'username'=>$username,'sum'=>$sum));

    }

    /**
     * 获取3个月月份
     */
    private function getData($data,$date){
        $array = array();
        foreach ($data as $vt){
            $arr =array();
            $arr['uid'] = $vt['id'];
            $arr['username'] = $vt['username'];
            if ($date == date('Y-m',time())){
                $arr['y_income'] = Member::getYdayIncome($vt['id']);
            }else{
                $arr['y_income'] = 0.00;
            }
            $arr['m_income'] = Member::getMonthIncome($vt['id'],$date);
            $arr['surplus'] = Member::getSurplusBill($vt['id']);
            $array[] = $arr;
        }
        return $array;
    }

    /**
     * 新地推代理商判定
     * @author hzh
     * @datetime 2017-08-14 11:10:45
     * */
    public function actionNewdtConfirm($date='',$username='')
    {
        if(empty($date))
        {
            $date=date('Y-m-d', strtotime('-1 day'));
        }
        $time=strtotime($date);
        if(!empty($username))
        {
            $sql ="SELECT a.imeicode,a.createstamp,a.uid,a.createtime,m.username,a.id, COUNT(*)as count,count(if(a.finishstatus=1,true,null)) as count1 FROM app_rom_appresource a LEFT JOIN app_member m ON a.uid=m.id WHERE m.username='{$username}' and  a.createstamp <={$time} AND a.is_check=0 GROUP BY a.imeicode,a.uid";
            //$sql ="SELECT a.imeicode,a.createstamp,a.uid,a.createtime, COUNT(*)as count FROM app_rom_appresource a LEFT JOIN app_member m ON a.uid=m.id WHERE m.username='{$username}' and  a.finishdate='{$date}' AND a.finishstatus=1 GROUP BY a.imeicode,a.uid";
        }else{
            $sql ="SELECT a.imeicode,a.createstamp,a.uid,a.createtime,m.username,a.id, COUNT(*)as count,count(if(a.finishstatus=1,true,null)) as count1 FROM app_rom_appresource a LEFT JOIN app_member m ON a.uid=m.id WHERE m.subagent=707 and  a.createstamp <={$time} AND a.is_check=0 GROUP BY a.imeicode,a.uid";

            //$sql ="SELECT a.imeicode,a.createstamp,a.uid,a.createtime, COUNT(*)as count FROM app_rom_appresource a LEFT JOIN app_member m ON a.uid=m.id WHERE m.subagent=707 and  a.finishdate='{$date}' AND a.finishstatus=1 GROUP BY a.imeicode,a.uid";
        }

        $model = Yii::app()->db->createCommand($sql)->queryAll();
        //var_dump($model);exit;
//        $count = count($model);
//        $pages = new CPagination ($count);
//        $pages->pageSize = 10;
//        $model = Yii::app()->db->createCommand($sql . " LIMIT :offset,:limit");
//        $model->bindValue(':offset', $pages->currentPage * $pages->pageSize);
//        $model->bindValue(':limit', $pages->pageSize);
//        $data = $model->queryAll();
//
//        $this->render("newdt_confirm",array('data'=>$data,'pages'=>$pages,'username'=>$username,'date' => $date,"time"=>$time));

        $dataProvider=new CArrayDataProvider($model, array(
            'keyField'=>'uid',
            'sort'=>array(
                'attributes'=>array(
                    'imeicode',
                    'uid',
                    'createtime',
                    'count',
                    'count1',
                    'count2',
                    'username',
                ),
                'defaultOrder'=>'createtime ASC',
            ),
            'pagination'=>array(
                'pageSize'=>25,
            ),
        ));
        $this->render('newdt_confirm', array(
            'dataProvider' =>$dataProvider ,
            'date' => $date,
            'username' => $username,
        ));


//        $this->render('newdt_confirm', array(
//            'dataProvider' => new CArrayDataProvider($model, array(
//                'pagination' => array(
//                    'pageSize' => 20,
//                ),
//                'sort' => [
//                    'attributes' => [
//                        'imeicode',
//                        'uid',
//                        'createtime',
//                        'count',
//                    ],
//                ],
//            )),
//            'date' => $date,
//            'username' => $username,
//        ));

    }
    public function actionNewdtconfirm2($date,$username)
    {
        if(empty($date))
        {
            $date=date('Y-m-d', strtotime('-1 day'));
        }
        $this->render('newdtconfirm2',array(
        'date' => $date,
        'username' => $username));
    }
    public function actionNewdtconfirm3($date,$username)
    {
        if(empty($date))
        {
            $date=date('Y-m-d', strtotime('-1 day'));
        }
        $time=strtotime($date);
        $start=1501516800;//从2017-08-01 开始
        $str=Member::getStrSub(707);//获取子用户字符串
        if(!empty($username))
        {
            $member=Member::model()->getByUserName($username);
            $uid=$member->id;
            $sql ="SELECT imeicode,createstamp,uid,createtime,id, COUNT(*)as install,count(if(finishstatus=1,true,null)) as activation FROM app_rom_appresource  WHERE uid={$uid} AND  createstamp >{$start} AND  createstamp <={$time} AND is_check=0 GROUP BY imeicode,uid";
            //$sql ="SELECT a.imeicode,a.createstamp,a.uid,a.createtime, COUNT(*)as count FROM app_rom_appresource a LEFT JOIN app_member m ON a.uid=m.id WHERE m.username='{$username}' and  a.finishdate='{$date}' AND a.finishstatus=1 GROUP BY a.imeicode,a.uid";
        }else{
            $sql ="SELECT imeicode,createstamp,uid,createtime,id, COUNT(*)as install,count(if(finishstatus=1,true,null)) as activation FROM app_rom_appresource  WHERE uid IN ({$str}) AND  createstamp >{$start} AND  createstamp <={$time} AND is_check=0 GROUP BY imeicode,uid";

            //$sql ="SELECT a.imeicode,a.createstamp,a.uid,a.createtime, COUNT(*)as count FROM app_rom_appresource a LEFT JOIN app_member m ON a.uid=m.id WHERE m.subagent=707 and  a.finishdate='{$date}' AND a.finishstatus=1 GROUP BY a.imeicode,a.uid";
        }
        // echo $sql;exit;
        $model = Yii::app()->db->createCommand($sql)->queryAll();
        foreach($model as $k=>$v){
            /*2017-11-30修改为列表只显示没有判定的数据*/
            $subdata=RomSubagentdata::model()->find('imeicode=:imeicode',array(':imeicode'=>$v['imeicode']));
            if(!empty($subdata)){
                unset($model[$k]);
                continue;
            }

            $members=Member::model()->findByPk($v["uid"]);
            $aa=RomAppupdata::getByUninstall($v["imeicode"]);
            $report_count=RomAppupdataDay::getThreeCount($v["imeicode"]);
            $model[$k]['username']=$members->username;
            $model[$k]['uninstall']=$aa;
            $model[$k]['report_count']=$report_count;
            $model[$k]['yu']=$v["install"]-$aa;
            $model[$k]['percent']=sprintf("%.2f",($v["install"]-$aa)/$v["install"]);

        }
        $model=array_merge($model); 
        echo  $data= CJSON::encode($model);

    }

    /**
     * 代理商子用户数据导入
     */
    public function actionSubupdate(){

        $t = Yii::app()->db->beginTransaction();
        try {
            $rids = Yii::app()->request->getParam('rid');
            if(Yii::app()->request->getQuery('rid')!="")
            {
                $rids=array($rids);
            }
            if (is_array($rids) === false) throw new Exception();

            foreach ($rids as $k=>$v)
            {
                $subrid=explode("-",$v);
                $uid=$subrid[0];
                $imeicode=$subrid[1];
                $install=$subrid[2];
                $uninstall=$subrid[4];
                $activation=$subrid[3];
                $date=$subrid[5];
                $status=1;
                RomSubagentdata::insertData($imeicode,$uid,$install,$uninstall,$activation,$date,$status);
                RomAppresource::model()->updateAll(array('is_check'=>1),'uid=:uid and imeicode=:pass',array(':uid'=>$uid,':pass'=>$imeicode));
                /*写入收益操作日志 2017-11-14 start*/
                
                    $mid=yii::app()->user->getState('uid');
                    $content='[用户id]'.$uid.'[imiecode]'.$imeicode.'[安装数量]'.$install.'[卸载数量]'.$uninstall.'[激活数量]'.$activation.'[判定时间]'.$date;
                    $ip=$_SERVER['SERVER_ADDR'];
                    $title="代理商-业务APP判定";
                    $before_content='';
                    Income::addlogincome($mid,$uid,$content,$ip,$before_content,$title);  
                /*2017-11-14 end*/
            }
            $t->commit();
            if(Yii::app()->request->getQuery('rid')!="")
            {
                echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
                echo '<script type="text/javascript" charset="utf-8">alert("完成激活");window.history.go(-1); </script>';
            }
            else
            {
                echo 'success';
            }

        } catch (Exception $e) {
            $t->rollback();
            echo 'error';
        }
    }


    /**
     * 代理商子用户数据封号
     */
    public function actionNosubupdate(){

        $t = Yii::app()->db->beginTransaction();
        try {
            $rids = Yii::app()->request->getParam('rid');
            if(Yii::app()->request->getQuery('rid')!="")
            {
                $rids=array($rids);
            }
            //var_dump($rids);exit;
            if (is_array($rids) === false) throw new Exception();

            foreach ($rids as $k=>$v)
            {
                $subrid=explode("-",$v);
                $uid=$subrid[0];
                $imeicode=$subrid[1];
                $install=$subrid[2];
                $uninstall=$subrid[4];
                $activation=$subrid[3];
                $date=$subrid[5];
                $status=0;
                RomSubagentdata::insertData($imeicode,$uid,$install,$uninstall,$activation,$date,$status);
                RomAppresource::model()->updateAll(array('is_check'=>1),'uid=:uid and imeicode=:pass',array(':uid'=>$uid,':pass'=>$imeicode));
                /*写入收益操作日志 2017-11-14 start*/
                
                    $mid=yii::app()->user->getState('uid');
                    $content='[用户id]'.$uid.'[imiecode]'.$imeicode.'[安装数量]'.$install.'[卸载数量]'.$uninstall.'[激活数量]'.$activation.'[判定时间]'.$date;
                    $ip=$_SERVER['SERVER_ADDR'];
                    $title="代理商-业务APP封号";
                    $before_content='';
                    Income::addlogincome($mid,$uid,$content,$ip,$before_content,$title);  
                /*2017-11-14 end*/
            }
            $t->commit();
            if(Yii::app()->request->getQuery('rid')!="")
            {
                echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
                echo '<script type="text/javascript" charset="utf-8">alert("完成封号");window.history.go(-1); </script>';
            }
            else
            {
                echo 'success';
            }

        } catch (Exception $e) {
            $t->rollback();
            echo 'error';
        }
    }


}
