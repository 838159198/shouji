<?php
/**
 * 统计APP
 */
class TongjiController extends Controller
{
    const KEY = "5T4632F5CA29833F76E09158252472DM";

	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('RomSoftpak');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

    /**
     * 业务APP激活判定
     */
    public function actionConfirm()
    {
        $datey=date('Ymd', strtotime('-1 day'));
        $dates=date('Y-m-d', strtotime('-1 day'))." 23:59:59";
        $username=2;

        $p=Product::model()->findAll('status=1');
        foreach($p as $v)
        {
            $type= $v["pathname"];
            $actrule=$v["actrule"];

            $data = RomAppupdata::model()->getDataProviderByDate($dates,$type,$username,$actrule);
            if(!empty($data))
            {
                foreach ($data as $rkey=>$rval)
                {
                    $rid=$rval["uid"]."-".$rval["appname"]."-".$rval["imeicode"]."-".$datey;
                    $this->actionUpdates($rid);
                }
            }
        }

    }

    /**
     * @throws CHttpException
     * /dhadmin/tongji/updates?rid=1-ucllq-123456128946453-20170222
     * 判定激活
     */
    public function actionUpdates($rid)
    {
        $rids = $rid;
        if (is_array($rids) === false) {$rids=array($rids);};
        foreach ($rids as $k=>$v)
        {
            $subrid=explode("-",$v);
            if (empty($subrid[0]) || empty($subrid[1]) || empty($subrid[2]) || empty($subrid[3])) continue;
            $this->activity($subrid[0],$subrid[1],$subrid[2],$subrid[3]);
        }

    }
    /**
     * 具体激活
     * @param $id
     * @param $type
     * @param $imei
     * @param $date
     * @throws Exception
     */
    private function activity($id,$type,$imei,$date)
    {
        $model = RomAppresource::model()->find('uid=:uid AND status=1 AND finishstatus=0 AND type=:type AND imeicode=:imeicode',array(':uid'=>$id,':type'=>$type,':imeicode'=>$imei));
        if (is_null($model))
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
        else
        {
            $model->finishdate = $date;
            $model->finishtime = date('Y-m-d H:i:s');
            $model->finishstatus = 1;
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
                    $modelupsub->finshstatustime=$date;
                    $modelupsub->update();
                }
            }

        }
    }

}
