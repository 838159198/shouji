<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2017/11/27
 * Time: 10:25
 * @name 统计回收控制器
 */
class RecycleSoftpakController extends DhadminController
{

    /**
     * @name 统计软件回收记录首页
     */
    public function actionRecycle($agent='',$income='',$income_day='',$install_day='',$install='',$logout_day='',$logou='',$username='',$serial_number='')
    {
        $model = new RecycleSoftpak('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['RecycleSoftpak'])) {
            $model->attributes = $_GET['RecycleSoftpak'];
        }
        $data_dt=RomSoftpak::getCountByAgent($type='newdt');//线下统计分配数据
        $data_rom=RomSoftpak::getCountByAgent($type='tongji');//ROM统计分配数据
        $data_desk=Boxdesk::getDeskNum();//线下桌面分配数据

        $arr=array();
        $sql="select * from `app_recycle_softpak` WHERE agent='{$agent}'";
        if(!empty($income_day)){
            $income_day=trim($income_day);
            $sql.=" AND income_day{$income}{$income_day}";
        }
        if(!empty($logout_day)){
            $logout_day=trim($logout_day);
            $sql.=" AND logout_day{$logou}{$logout_day}";
        }
        if(!empty($install_day)){
            $install_day=trim($install_day);
            $sql.=" AND install_day{$install}{$install_day}";
        }
        if(!empty($username)){
            $username=trim($username);
            $sql.=" AND username ='{$username}'";
        }
        if(!empty($serial_number)){
            $serial_number=trim($serial_number);
            $sql.=" AND serial_number ={$serial_number}";
        }
        $arr=yii::app()->db->createCommand($sql)->queryAll();

        //总的封号手机数量
        $totalnum=count($arr);
        if(isset($_GET['agent']) && isset($_GET['logou']) && isset($_GET['logout_day'])&& isset($_GET['income'])&&isset($_GET['income_day']) && isset($_GET['install_day'])&& isset($_GET['install'])){
            Yii::app()->user->setFlash('status_sum',"<span>本次查询结果是".$totalnum."条记录</span>");
        }
        $dataProvider=new CArrayDataProvider($arr, array(
            'keyField'=>'uid',
            'sort'=>array(
                'attributes'=>array(
                    'createtime','username','serial_number','logout_day','install_day','income_day'
                ),
                'defaultOrder'=>'createtime ASC',
            ),
            'pagination'=>array(
                'pageSize'=>50,
            ),
        ));
        $this->render("recycle",array('dataProvider'=>$dataProvider,"data_dt"=>$data_dt,"data_rom"=>$data_rom,"data_desk"=>$data_desk,"totalnum"=>$totalnum));

    }
    /**
     * @name 统计软件回收记录首页
     */
    public function actionIndex()
    {
        $model = new RecycleSoftpakLog('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['RecycleSoftpakLog'])) {
            $model->attributes = $_GET['RecycleSoftpakLog'];
        }
        //ROM统计软件个数
        $sql="SELECT COUNT(id)count,type FROM app_recycle_softpak_log GROUP BY type";
        $arr=yii::app()->db->createCommand($sql)->queryAll();
        $data=array();
        foreach($arr as $v){
            $data[$v['type']]=$v['count'];
        }
        $this->render("index",array('model'=>$model,'data'=>$data));
    }
    /**
     * @para 恢复回收记录
     */
    public function actionReply()
    {
        if(Yii::app()->request->isAjaxRequest && isset($_POST['id']))
        {
            $id=$_POST['id'];
            $data = RecycleSoftpakLog::model() -> findByPk($id);
            if($data){
                if($data){
                    $mid = Yii::app()->user->manage_id;
                    $uid=$data->uid;
                    $menber=Member::model()->findByPk($uid);
                    $romSoftpak=RomSoftpak::model()->find("uid=:uid",array(":uid"=>$uid));//判断该用户是否已有统计
                    if($romSoftpak){
                        $tongji=$romSoftpak->serial_number;
                        $str='用户'.$menber->username.'已分配统计ID'.$tongji;
                        exit(CJSON::encode(array("status"=>403,"message"=>$str)));
                    }

                    $serial_number=$data->serial_number;
                    $romSoftpak=RomSoftpak::model()->find("serial_number=:serial_number",array(":serial_number"=>$serial_number));
                    if($romSoftpak->uid ==0){
                        //存在桌面的
                        if($romSoftpak->type=='newdt'){
                            $deskBox=Boxdesk::model()->find("tid=:tid",array(":tid"=>$serial_number));
                            if($deskBox){
                                if($deskBox->uid==0){
                                    //恢复统计
                                    $romSoftpak->uid=$uid;
                                    $romSoftpak->status=0;
                                    $romSoftpak->update();
                                    //恢复桌面
                                    $deskBox->uid=$uid;
                                    $deskBox->status=0;
                                    $deskBox->update();

                                    //修改日志表状态
                                    $models=RecycleSoftpakLog::model()->findAll("serial_number=:tj and status=0 and uid=:uid",array(":tj"=>$serial_number,":uid"=>$uid));
                                    if($models){
                                        foreach($models as $v){
                                            $v->status=1;//恢复
                                            $v->reply_date=date('Y-m-d H:i:s',time());
                                            $v->reply_mid=$mid;
                                            $v->update();
                                        }
                                    }
                                    RecycleSoftpak::addReplyData($uid,$serial_number,$menber->username,$romSoftpak->type);
                                    $str='用户'.$menber->username.'的统计ID'.$serial_number.'已恢复成功';
                                    exit(CJSON::encode(array("status"=>200,"message"=>$str)));

                                }else{
                                    $str="该统计对应的桌面已被分配";
                                    exit(CJSON::encode(array("status"=>403,"message"=>$str)));
                                }
                            }else{
                                $str="桌面不存在";
                                exit(CJSON::encode(array("status"=>403,"message"=>$str)));
                            }
                        }else{
                            //不存在桌面的
                            //恢复统计
                            $romSoftpak->uid=$uid;
                            $romSoftpak->status=0;
                            $romSoftpak->update();

                            //修改日志表状态
                            $data->status=1;//恢复
                            $data->reply_date=date('Y-m-d H:i:s',time());
                            $data->reply_mid=$mid;
                            $data->update();

                            RecycleSoftpak::addReplyData($uid,$serial_number,$menber->username,$romSoftpak->type);
                            $str='用户'.$menber->username.'的统计ID'.$serial_number.'已恢复成功';
                            exit(CJSON::encode(array("status"=>200,"message"=>$str)));
                        }
                    }else{
                        //已分配
                        $uid=$romSoftpak->uid;
                        $menber=Member::model()->findByPk($uid);
                        $str='统计ID'.$serial_number.'已分配给'.$menber->username;
                        exit(CJSON::encode(array("status"=>403,"message"=>$str)));
                    }
                }
            }else{
                exit(CJSON::encode(array("status"=>403,"message"=>"需要恢复的记录不存在！")));
            }
        }else{
            exit(CJSON::encode(array("status"=>400,"message"=>"请使用正确的姿势提交！")));
        }
    }

    /*
    * 统计回收
    * */
    public function actionRecycleTj(){
        if(Yii::app()->request->isAjaxRequest && isset($_POST['tid'])) {
            $tid = $_POST['tid'];
            $romSoftPak=RomSoftpak::model()->find("serial_number=:serial_number and status=0",array(":serial_number"=>$tid));
            if($romSoftPak){
                $mid = Yii::app()->user->manage_id;
                $uid=$romSoftPak->uid;
                $serial_number=$romSoftPak->serial_number;
                $type=$romSoftPak->type;
                if($type=='tongji'){
                    $type=0;
                }elseif($type=='newdt'){
                    $type=1;
                }else{
                    $type=2;
                }
                //回收统计
                $romSoftPak->uid=0;
                $romSoftPak->status=1;
                $romSoftPak->update();

                $sql="INSERT INTO `app_recycle_softpak_log` (`id`,`uid`,`serial_number`,`createtime`,`recycle_mid`,`type`)VALUES
                                ('','".$uid."','".$serial_number."','".date("Y-m-d H:i:s")."','".$mid."','".$type."');";
                Yii::app()->db->createCommand($sql)->execute();
                //判断是否有桌面 有直接回收
                if($type==1){
                    $sql ="SELECT id FROM app_rom_boxdesk WHERE `uid`={$uid} AND  tid={$serial_number}";
                    $model = Yii::app()->db->createCommand($sql)->queryRow();
                    if($model){
                        $type=3;//门店桌面
                        $sqlupdate = "update `app_rom_boxdesk` set `uid` =0 ,status=1 where id = {$model['id']}";
                        Yii::app()->db->createCommand($sqlupdate)->execute();

                        $sql="INSERT INTO `app_recycle_softpak_log` (`id`,`uid`,`serial_number`,`createtime`,`recycle_mid`,`type`)VALUES
                                ('','".$uid."','".$serial_number."','".date("Y-m-d H:i:s")."','".$mid."','".$type."');";
                        Yii::app()->db->createCommand($sql)->execute();
                    }
                }
                $sql="DELETE FROM app_recycle_softpak WHERE serial_number={$serial_number}";
                Yii::app()->db->createCommand($sql)->execute();
                exit(CJSON::encode(array("status"=>200,"message"=>"回收成功！")));

            }else{
                exit(CJSON::encode(array("status"=>400,"message"=>"统计错误，回收失败！")));
            }
        }else{
            exit(CJSON::encode(array("status"=>400,"message"=>"请使用正确的姿势提交！")));
        }
    }
    /*
    * 统计软件批量回收
    * */
    public function actionRecycleAll(){
        if(Yii::app()->request->isAjaxRequest && isset($_POST['id'])){
            $ids=$_POST['id'];
            foreach($ids as $v){
                $romSoftPak=RomSoftpak::model()->find("serial_number=:serial_number and status=0",array(":serial_number"=>$v));
                if($romSoftPak){
                    $mid = Yii::app()->user->manage_id;
                    $uid=$romSoftPak->uid;
                    $serial_number=$romSoftPak->serial_number;
                    $type=$romSoftPak->type;
                    if($type=='tongji'){
                        $type=0;
                    }elseif($type=='newdt'){
                        $type=1;
                    }else{
                        $type=2;
                    }
                    //回收统计
                    $romSoftPak->uid=0;
                    $romSoftPak->status=1;
                    $romSoftPak->update();

                    $sql="INSERT INTO `app_recycle_softpak_log` (`id`,`uid`,`serial_number`,`createtime`,`recycle_mid`,`type`)VALUES
                                ('','".$uid."','".$serial_number."','".date("Y-m-d H:i:s")."','".$mid."','".$type."');";
                    Yii::app()->db->createCommand($sql)->execute();
                    //判断是否有桌面 有直接回收
                    if($type==1){
                        $sql ="SELECT id FROM app_rom_boxdesk WHERE `uid`={$uid} AND  tid={$serial_number}";
                        $model = Yii::app()->db->createCommand($sql)->queryRow();
                        if($model){
                            $type=3;//门店桌面
                            $sqlupdate = "update `app_rom_boxdesk` set `uid` =0, status=1 where id = {$model['id']}";
                            Yii::app()->db->createCommand($sqlupdate)->execute();

                            $sql="INSERT INTO `app_recycle_softpak_log` (`id`,`uid`,`serial_number`,`createtime`,`recycle_mid`,`type`)VALUES
                                ('','".$uid."','".$serial_number."','".date("Y-m-d H:i:s")."','".$mid."','".$type."');";
                            Yii::app()->db->createCommand($sql)->execute();
                        }
                    }
                    $sql="DELETE FROM app_recycle_softpak WHERE serial_number={$serial_number}";
                    Yii::app()->db->createCommand($sql)->execute();
                    //exit(CJSON::encode(array("status"=>200,"message"=>"回收成功！")));
                }else{
                    //exit(CJSON::encode(array("status"=>400,"message"=>"统计错误，回收失败！")));
                }
            }
            exit(CJSON::encode(array("status"=>200,"message"=>"回收成功！")));
        }else{
            exit(CJSON::encode(array("status"=>400,"message"=>"请使用正确的姿势提交！")));
        }
    }

    /*
     * 统计软件回收数据更新
     */
    public function actionUpdateRecycle(){
        $num=RecycleSoftpak::getRecycleData();
        if($num>0){
            exit(CJSON::encode(array("status"=>200,"message"=>"更新成功！")));
        }else{
            exit(CJSON::encode(array("status"=>400,"message"=>"发生错误，请重新请求！")));
        }
    }

}