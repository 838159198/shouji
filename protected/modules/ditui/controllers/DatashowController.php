<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/14
 * Time: 16:19
 * 产品业务
 */

class DatashowController extends DituiController
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
     * 应用安装排行
     */
    public function actionActivatop()
    {
        $sql_all ="select id,type,count(type) as counts from app_rom_appresource group by type order by counts desc limit 10";
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
        $sql_all ="select id,model,count(model) as counts from app_rom_appresource group by model order by counts desc limit 20";
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
     * 业务说明
     */
    public function actionInstruction()
    {
        $this->render('instruction', array());
    }


}