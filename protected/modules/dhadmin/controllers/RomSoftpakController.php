<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2016/12/20
 * Name: 管理员
 */
class RomSoftpakController extends DhadminController
{
    /*
     * 包列表
     * */
    public function actionIndex()
    {
        $model = new RomSoftpak('search');
        $model->unsetAttributes();
        if (isset($_GET['RomSoftpak'])) {
            $model->attributes = $_GET['RomSoftpak'];
        }
        $this->render("index",array('model'=>$model));
    }
    /*
     * 上传文件
     * */
    public function actionCreate()
    {
        $model = new RomSoftpak('create');
        $model->unsetAttributes();
        if(isset($_POST['RomSoftpak'])){
            $model->attributes = $_POST['RomSoftpak'];
            //文件上传
            $file=CUploadedFile::getInstance($model,'url'); //获取表单名为filename的上传信息
            if(isset($file)){
                $fileextension=$file->getExtensionName();//获取文件扩展名
                if($fileextension=='apk'){
                    //$filename=$file->getName();//获取文件名
                    //$filesize=$file->getSize();//获取文件大小
                    $version=$_POST['RomSoftpak']['version'];
                    $tid=$_POST['RomSoftpak']['serial_number'];
                    $name='statV'.$version.'.0('.$tid.').apk';
                    $url='uploads/tongji/'.$name;
                    $model->url='/'.$url;
                    $uploadfile='./'.$url;
                    $model->serial_number=$tid;
                    $model->uid=0;
                    $model->status=1;//默认1 未分配
                    $model->version=$version;
                    $model->updatetime=date("Y-m-d H:i:s");
                    if($model->save()){

                        $file->saveAs($uploadfile,true);//上传操作
                        $model->md5=strtoupper(md5_file($url));
                        $model->update();

                        //日志记录
                        $mid = Yii::app()->user->manage_id;
                        $detail='[manage] '.$mid.' [insert] [uid] 0  [version] '.$version.' [serial_number] '.$tid.' [url] /'.$url.' [status]  1 [md5] '.strtoupper(md5_file($url));
                        NoteLog::addLog($detail,$mid,$uid='',$tag='上传统计软件',$update='');

                        Yii::app()->user->setFlash('status','上传统计软件成功');
                        $this->redirect(array('romSoftpak/index'));
                    }
                }else{
                    throw new CHttpException(403,"请上传文件包类型错误");
                }

            }else{
                throw new CHttpException(403,"请上传包文件");
            }

        }
        $this->render("create",array("model"=>$model));
    }

    /*
     * 更新文件信息
     * */
    public function actionEdit($id)
    {
        $model= new RomSoftpak();
        $data =$model-> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在");
        }else{
            $update=json_encode($data->attributes);
            if(isset($_POST['RomSoftpak'])){
                //文件上传
                $file=CUploadedFile::getInstance($model,'url'); //获取表单名为filename的上传信息
                if(isset($file)){
                    $fileextension=$file->getExtensionName();//获取文件扩展名
                    if($fileextension=='apk'){
                        $uid=$_POST['RomSoftpak']['uid'];
                        $version=$_POST['RomSoftpak']['version'];
                        $tid=$_POST['RomSoftpak']['serial_number'];
                        $name='statV'.$version.'.0('.$tid.').apk';
                        $url='uploads/tongji/'.$name;
                       //删除原来的文件
                        unlink('.'.$data->url);
                        $data->url='/'.$url;
                        $uploadfile='./'.$url;
                        $data->uid=$uid;
                        $data->serial_number=$tid;
                        $data->version=$version;
                        $data->updatetime=date("Y-m-d H:i:s");
                        if($data->save()){
                            $file->saveAs($uploadfile,true);//上传操作
                            $data->md5=strtoupper(md5_file($url));
                            $data->update();
                            //日志记录
                            $mid = Yii::app()->user->manage_id;
                            $detail='[manage] '.$mid.' [update] [uid] '.$uid.' [version] '.$version.' [serial_number] '.$tid.' [url] /'.$url.'  [md5] '.strtoupper(md5_file($url));
                            NoteLog::addLog($detail,$mid,$uid,$tag='更新统计软件',$update);

                            //CDN推送
                            Common::newPush($url);
                            Yii::app()->user->setFlash('status','更新文件成功');
                            $this->redirect(array('romSoftpak/index'));
                        }
                    }else{
                        throw new CHttpException(403,"请上传文件包类型错误");
                    }

                }else{
                    throw new CHttpException(403,"请上传包文件");
                }

            }
            $this->render("edit",array("model"=>$data));
        }
    }


    /*
        name：统计软件批量上传
        Date:2017-5-17
        uploadify 后台处理！
    */
    public function actionMoreUpload(){
        //设置上传目录
        $path = "uploads/tongji/";
        $mid = Yii::app()->user->manage_id;
        if (!empty($_FILES)) {
            //得到上传的临时文件流
            $tempFile = $_FILES['Filedata']['tmp_name'];

            //允许的文件后缀
            $fileTypes = array('jpg','jpeg','gif','png');

            //得到文件原名
            $fileName = iconv("UTF-8","GB2312",$_FILES["Filedata"]["name"]);

            $tid=Common::cutString('(',')',$fileName);//统计
            $version= Common::cutString('V','.',$fileName); //版本号
            $type=$this->checkType($tid);//统计类型

            $fileParts = pathinfo($_FILES['Filedata']['name']);

            //接受动态传值
            $files=$_POST['typeCode'];

            //最后保存服务器地址
            if(!is_dir($path))
                mkdir($path);
            if (move_uploaded_file($tempFile, $path.$fileName)){
                $romSoftpak=RomSoftpak::model()->find("serial_number=:tid",array(":tid"=>$tid));
                //判断是更新或上传
                if($romSoftpak){
                    $update=json_encode($romSoftpak->attributes);
                    $url=$romSoftpak->url;
                    $romSoftpak->url='/uploads/tongji/'.$fileName;
                    $romSoftpak->version=$version;
                    $romSoftpak->updatetime=date("Y-m-d H:i:s");
                    $romSoftpak->md5=strtoupper(md5_file($path.$fileName));
                    if($romSoftpak->save()){
                        if('/'.$path.$fileName != $url) unlink('.'.$url);//删除统计相同、但文件名不同的
                        $detail='[manage] '.$mid.' [update] [uid] '.$romSoftpak->uid.' [version] '.$version.' [serial_number] '.$tid.' [url] /uploads/tongji/'.$fileName.'  [md5] '.strtoupper(md5_file($path.$fileName));
                        NoteLog::addLog($detail,$mid,$romSoftpak->uid,$tag='更新统计软件',$update);

                        //CDN推送
                        Common::newPush($path.$fileName);
                        echo "上传成功";
                    }else{
                        unlink('./uploads/tongji/'.$fileName);//删除已上传，但未保存数据库的文件
                        echo $fileName."更新失败！";
                    }
                }else{
                    $model = new RomSoftpak();
                    $model->url='/uploads/tongji/'.$fileName;
                    $model->serial_number=$tid;
                    $model->uid=0;
                    $model->status=1;//默认1 未分配
                    $model->version=$version;
                    $model->md5=strtoupper(md5_file($path.$fileName));
                    $model->type=$type;
                    $model->updatetime=date("Y-m-d H:i:s");
                    if($model->save()){
                        echo "上传成功";

                        $detail='[manage] '.$mid.' [insert] [uid] 0  [version] '.$version.' [serial_number] '.$tid.' [url] /uploads/tongji/'.$fileName.' [status]  1 [md5] '.strtoupper(md5_file($path.$fileName));
                        NoteLog::addLog($detail,$mid,$uid='',$tag='上传统计软件',$update='');
                        //$this->redirect(array('romSoftpak/index'));
                    }else{
                        //print_r($model->getErrors());exit;
                        unlink('./uploads/tongji/'.$fileName);//删除已上传，但未保存数据库的文件
                        echo $fileName."保存失败！";
                    }

                }

            }else{
                echo $fileName."上传失败！";
            }
        }
    }
    /*
       name：统计软件回收
       Date:2017-11-17
   */
    public function actionRecycle(){
        $data_dt=RomSoftpak::getCountByAgent($type='newdt');//线下统计分配数据
        $data_rom=RomSoftpak::getCountByAgent($type='tongji');//ROM统计分配数据
        $data_desk=Boxdesk::getDeskNum();//线下桌面分配数据
        $this->render("recycle",array("data_dt"=>$data_dt,"data_rom"=>$data_rom,"data_desk"=>$data_desk));
    }

    /*
     * 获取统计回收数据
     * */
    public function actionGetRecycleData(){
        //领安装天数计算
        $sql ="SELECT uid,installtime FROM app_rom_appresource GROUP BY uid ORDER BY installtime DESC";
        $model_install = Yii::app()->db->createCommand($sql)->queryAll();
        $data_install = array();
        foreach($model_install as $v){
            $uid=$v['uid'];
            $day=time()-strtotime($v['installtime']);
            $data_install[$uid]=floor($day/(86400));//向下取整
        }//var_dump($data);

        //未登录天数计算
        $sql ="SELECT id,username,overtime,agent FROM app_member WHERE `status`=1";
        $model = Yii::app()->db->createCommand($sql)->queryAll();
        $data = array();
        foreach($model as $v){
            $uid=$v['id'];
            $day=time()-$v['overtime'];
            $softPak=RomSoftpak::model()->find("uid=:uid and status=0 and closed=0",array(":uid"=>$uid));
            //存在统计
            if($softPak){
                if($v['agent']==707){
                    $sql ="SELECT MAX(datetimes) datetimes FROM app_rom_subagentdata WHERE uid={$uid}";
                    $model = Yii::app()->db->createCommand($sql)->queryRow();
                    $income_day=time()-$model['datetimes'];
                }else{
                    $data_income=MemberIncome::getUserLastDate($uid);
                    $pos=array_search(max($data_income),$data_income);
                    $income_day= time()-strtotime($data_income[$pos]);
                }
                $install=isset($data_install[$uid])?$data_install[$uid]:'';
                $data[]=array("id"=>$softPak->id,"install_day"=>$install,"username"=>$v['username'],"login_days"=>floor($day/(86400)),"agent"=>$v['agent'],"income_days"=>floor($income_day/(86400)),"tongji"=>$softPak->serial_number);//向下取整
            }
        }
        echo  $data= CJSON::encode($data);
    }
    /*
    * 获取统计回收数据
    * */
    public function actionRecycleTj($tid){
        $romSoftPak=RomSoftpak::model()->findByPk($tid);
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
            $sql ="SELECT id FROM app_rom_boxdesk WHERE `uid`={$uid} AND  tid={$serial_number}";
            $model = Yii::app()->db->createCommand($sql)->queryRow();
            if($model){
                $type=3;//门店桌面
                $sqlupdate = "update `app_rom_boxdesk` set `uid` =0 AND status=1 where id = {$model['id']}";
                Yii::app()->db->createCommand($sqlupdate)->execute();

                $sql="INSERT INTO `app_recycle_softpak_log` (`id`,`uid`,`serial_number`,`createtime`,`recycle_mid`,`type`)VALUES
                                ('','".$uid."','".$serial_number."','".date("Y-m-d H:i:s")."','".$mid."','".$type."');";
                Yii::app()->db->createCommand($sql)->execute();
                echo "<meta http-equiv='Content-Type'' content='text/html; charset=utf-8'>";
                echo '<script type="text/javascript" charset="utf-8">alert("回收成功");history.go(-1);</script>';
            }
        }else{
            echo '<script>alert("回收失败")</script>';
        }
    }


    //返回用户统计类型
    protected function checkType($tid){
        if(substr($tid, 0, 2) == 70) {
            $type='newdt';
         }elseif(substr($tid, 0, 2) == 90) {
            $type='dealer';
         }elseif(substr($tid, 0, 2) == 10) {
            $type='tongji';
         }else{
            $type='';
        }
        return $type;
    }
}