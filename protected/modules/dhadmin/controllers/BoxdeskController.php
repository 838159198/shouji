<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2016/12/20
 * Name: 管理员
 */
class BoxdeskController extends DhadminController
{
    /*
     * 包列表
     * */
    public function actionIndex()
    {
        $model = new Boxdesk('search');
        $model->unsetAttributes();
        if (isset($_GET['Boxdesk'])) {
            $model->attributes = $_GET['Boxdesk'];
        }
        $this->render("index",array('model'=>$model));
    }
    /*
     * 上传包
     * */
    public function actionCreate()
    {
        $model = new Boxdesk('create');
        $model->unsetAttributes();
        if(isset($_POST['Boxdesk'])){
            $model->attributes = $_POST['Boxdesk'];
            //文件上传
            $file=CUploadedFile::getInstance($model,'downloadurl'); //获取表单名为filename的上传信息
            if(isset($file)){
                $fileextension=$file->getExtensionName();//获取文件扩展名
                if($fileextension=='apk'){
                    $filename=$file->getName();//获取文件名
                    $filesize=$file->getSize();//获取文件大小
                    $version=$_POST['Boxdesk']['version'];


                    $tid=$_POST['Boxdesk']['tid'];
                    $name='launcherV'.$version.'('.$tid.').apk';
                    $url='uploads/launcher/'.$name;
                    $model->downloadurl='/'.$url;
                    $uploadfile='./'.$url;
                    $model->filesize=$this->getFileSize($filesize);
                    $model->createtime=time();
                    $model->status=1;
                    $model->uid=0;
                    $model->tid=$tid;
                    $model->version=$version;
                    $model->filename='launcher.apk';
                    if($model->save()){
                        $file->saveAs($uploadfile,true);//上传操作
                        $model->md5=strtoupper(md5_file($url));
                        $model->update();

                        //日志记录
                        $mid = Yii::app()->user->manage_id;
                        $detail='[manage] '.$mid.' [insert] [uid] 0 [version] '.$version.' [tid] '.$tid.' [downPath] /'.$url.' [filesize]'.$this->getFileSize($filesize).' [name] '.$filename.' [md5]'.strtoupper(md5_file($url));
                        NoteLog::addLog($detail,$mid,$uid=0,$tag='上传桌面文件',$update='');

                        Yii::app()->user->setFlash('status','上传文件成功');
                        $this->redirect(array('boxdesk/index'));
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
    
    protected function getFileSize($filesize){
        if($filesize >= 1073741824) {
            $filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
        } elseif($filesize >= 1048576) {
            $filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
        } elseif($filesize >= 1024) {
            $filesize = round($filesize / 1024 * 100) / 100 . ' KB';
        } else {
            $filesize = $filesize . ' bytes';
        }
        return $filesize;
    }
    /*
     * 更新文件信息
     * */
    public function actionEdit($id)
    {
        $model= new Boxdesk('edit');
        $data =$model-> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在");
        }else{
            $update=json_encode($data->attributes);
            if(isset($_POST['Boxdesk'])){
                //文件上传
                $file=CUploadedFile::getInstance($model,'downloadurl'); //获取表单名为filename的上传信息
                if(isset($file)){
                    $fileextension=$file->getExtensionName();//获取文件扩展名
                    if($fileextension=='apk'){
                        $filename=$file->getName();//获取文件名
                        $filesize=$file->getSize();//获取文件大小
                        $tid=$_POST['Boxdesk']['tid'];
                        $uid=$_POST['Boxdesk']['uid'];
                        $version=$_POST['Boxdesk']['version'];
                        $name='launcherV'.$data->version.'('.$data->tid.').apk';
                        $name1='launcherV'.$version.'('.$tid.').apk';
                        $url='uploads/launcher/'.$name1;
                            unlink('.'.$data->downloadurl);
                        $data->downloadurl='/'.$url;
                        $uploadfile='./'.$url;
                        $data->updatetime=date("Y-m-d H:i:s",time());
                        $data->filename='launcher.apk';
                        $data->tid=$tid;
                        $data->version=$version;

                        if($data->save()){
                            $file->saveAs($uploadfile,true);//上传操作
                            $data->md5=strtoupper(md5_file($url));
                            $data->update();
                            //CDN推送
                            Common::newPush($url);
                            //日志记录
                            $mid = Yii::app()->user->manage_id;
                            $detail='[manage] '.$mid.' [update]   [version] '.$version.' [tid] '.$tid.' [downPath] /'.$url.' [filesize]'.$this->getFileSize($filesize).' [name] '.$filename.' [md5]'.strtoupper(md5_file($url));
                            NoteLog::addLog($detail,$mid,$uid,$tag='更新桌面文件',$update);
                            Yii::app()->user->setFlash('status','更新文件成功');
                            $this->redirect(array('boxdesk/index'));
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
        $path = "uploads/launcher/";
        $mid = Yii::app()->user->manage_id;
        if (!empty($_FILES)) {
            //得到上传的临时文件流
            $tempFile = $_FILES['Filedata']['tmp_name'];

            //允许的文件后缀
            $fileTypes = array('jpg','jpeg','gif','png');

            //得到文件原名
            $fileName = iconv("UTF-8","GB2312",$_FILES["Filedata"]["name"]);

            $tid=Common::cutString('(',')',$fileName);//统计
            $version= Common::cutString('V','(',$fileName); //版本号

            $fileParts = pathinfo($_FILES['Filedata']['name']);
            $size=$this->getFileSize($_FILES['Filedata']['size']);
            //接受动态传值
            $files=$_POST['typeCode'];

            //最后保存服务器地址
            if(!is_dir($path))
                mkdir($path);
            if (move_uploaded_file($tempFile, $path.$fileName)){
                $boxDesk=Boxdesk::model()->find("tid=:tid",array(":tid"=>$tid));
                //判断是更新或上传
                if($boxDesk){
                    $update=json_encode($boxDesk->attributes);
                    $url=$boxDesk->downloadurl;
                    $boxDesk->downloadurl='/uploads/launcher/'.$fileName;
                    $boxDesk->version=$version;
                    $boxDesk->md5=strtoupper(md5_file($path.$fileName));
                    $boxDesk->updatetime=date('Y-m-d H:i:s');
                    $boxDesk->filesize=$size;
                    if($boxDesk->save()){
                        //CDN推送
                        Common::newPush($path.$fileName);
                        //日志记录
                        $detail='[manage] '.$mid.' [update]   [version] '.$version.' [tid] '.$tid.' [downPath] /uploads/launcher/'.$fileName.' [filesize]'.$size.' [md5]'.strtoupper(md5_file($path.$fileName));
                        NoteLog::addLog($detail,$mid,$boxDesk->uid,$tag='更新桌面文件',$update);

                        if('/'.$path.$fileName != $url) unlink('.'.$url);//删除统计相同、但文件名不同的
                        echo "上传成功";
                    }else{
                        unlink('./uploads/launcher/'.$fileName);//删除已上传，但未保存数据库的文件
                        echo $fileName."更新失败！";
                    }
                }else{
                    $model = new Boxdesk();
                    $model->downloadurl='/uploads/launcher/'.$fileName;
                    $model->tid=$tid;
                    $model->uid=0;
                    $model->status=1;//默认1 未分配
                    $model->version=$version;
                    $model->filename='launcher.apk';
                    $model->createtime=time();
                    $model->filesize=$size;
                    $model->md5=strtoupper(md5_file($path.$fileName));
                    if($model->save()){
                        //日志记录
                        $detail='[manage] '.$mid.' [insert] [uid] 0 [version] '.$version.' [tid] '.$tid.' [downPath] /uploads/launcher/'.$fileName.' [filesize] '.$size.' [md5] '.strtoupper(md5_file($path.$fileName));
                        NoteLog::addLog($detail,$mid,$uid=0,$tag='上传桌面文件',$update='');
                        echo "上传成功";
                        //$this->redirect(array('romSoftpak/index'));
                    }else{
                        //print_r($model->getErrors());exit;
                        unlink('./uploads/launcher/'.$fileName);//删除已上传，但未保存数据库的文件
                        echo $fileName."保存失败！";
                    }

                }

            }else{
                echo $fileName."上传失败！";
            }
        }
    }

}