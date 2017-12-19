<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2016/12/20
 * Name: 管理员
 */
class BoxdataController extends DhadminController
{
    /*
     * 包列表
     * */
    public function actionIndex()
    {
        $model = new Boxdata('search');
        $model->unsetAttributes();
        if (isset($_GET['Boxdata'])) {
            $model->attributes = $_GET['Boxdata'];
        }
        $this->render("index",array('model'=>$model));
    }
    /*
     * 上传包
     * */
    public function actionCreate()
    {
        $model = new Boxdata();
        $model->unsetAttributes();
        if(isset($_POST['Boxdata'])){
            $model->attributes = $_POST['Boxdata'];
            //文件上传
            $file=CUploadedFile::getInstance($model,'downPath'); //获取表单名为filename的上传信息
            if(isset($file)){
                $fileextension=$file->getExtensionName();//获取文件扩展名
                if($fileextension=='apk' || $fileextension=='jar' ||$fileextension=='txt'|| $fileextension==''){
                    $filename=$file->getName();//获取文件名
                    $filesize=$file->getSize();//获取文件大小
                    $classify=$_POST['Boxdata']['classify'];
                    $uid=$_POST['Boxdata']['uid'];
                    $tid=$_POST['Boxdata']['tid'];
                    $version=$_POST['Boxdata']['version'];
                    $model->classify=$classify;

                    if($classify=="otherapk"){
                        $url='uploads/otherapk/'.$filename;
                        $model->uid=$uid;
                        $model->tid=$tid;
                    }elseif($classify=="apklist"){
                        $member=Member::model()->findByPk($uid);
                        if($member){
                            $model->uid=$uid;
                            $url='uploads/apklist/'.$uid.$filename;
                        }else{
                            throw new CHttpException(403,"请上传文件包错误");
                        }

                    }else{
                        $member=Member::model()->findByPk($uid);
                        if($member){
                            $model->uid=$uid;
                            $url='uploads/StFlashTool2/user/'.$uid.$filename;
                        }else{
                            $url='uploads/StFlashTool2/'.$filename;
                        }
                    }
                    $model->downPath='/'.$url;
                    $uploadfile='./'.$url;
                    $model->filesize=$this->getFileSize($filesize);
                    $model->createtime=time();
                    $model->name=$filename;
                    $model->version=$version;
                    $file->saveAs($uploadfile,true);//上传操作
                    $model->md5=strtoupper(md5_file($url));
                    if($model->save()){
                        //日志记录
                        $mid = Yii::app()->user->manage_id;
                        $detail='[manage] '.$mid.' [insert] [classify] '.$classify. ' [uid] '.$uid.' [version] '.$version.' [tid] '.$tid.' [downPath] /'.$url.' [filesize]'.$this->getFileSize($filesize).' [name] '.$filename.' [md5]'.strtoupper(md5_file($url));
                        NoteLog::addLog($detail,$mid,$uid,$tag='上传盒子文件',$update='');

                        Yii::app()->user->setFlash('status','上传文件成功');
                        $this->redirect(array('boxdata/index'));

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
            $filesize = round($filesize / 1073741824 * 100) / 100 .'GB';
        } elseif($filesize >= 1048576) {
            $filesize = round($filesize / 1048576 * 100) / 100 .'MB';
        } elseif($filesize >= 1024) {
            $filesize = round($filesize / 1024 * 100) / 100 .'KB';
        } else {
            $filesize = $filesize .'bytes';
        }
        return $filesize;
    }
    /*
     * 更新文件信息
     * */
    public function actionEdit($id)
    {
        $model= new Boxdata();
        $data =$model-> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在");
        }else{
            $update=json_encode($data->attributes);
            if(isset($_POST['Boxdata'])){
                //文件上传
                $file=CUploadedFile::getInstance($model,'downPath'); //获取表单名为filename的上传信息
                if(isset($file)){
                    $fileextension=$file->getExtensionName();//获取文件扩展名
                    if($fileextension=='apk' || $fileextension=='jar' ||$fileextension=='txt'|| $fileextension==''){
                        $filename=$file->getName();//获取文件名
                        $filesize=$file->getSize();//获取文件大小
                        if(file_exists($filename)){//删除具有相同文件名的文件
                            unlink('./'.$data->downPath);
                        }elseif($filename=='launcher.apk'){
                            unlink('./'.$data->downPath);
                        }
                        $classify=$_POST['Boxdata']['classify'];
                        $tid=$_POST['Boxdata']['tid'];
                        $uid=$_POST['Boxdata']['uid'];
                        $version=$_POST['Boxdata']['version'];
                        $data->classify=$classify;
                        if($classify=="otherapk"){
                            $url='uploads/otherapk/'.$filename;
                            $data->uid=$uid;
                        }elseif($classify=="apklist"){
                            $member=Member::model()->findByPk($uid);
                            if($member){
                                $model->uid=$uid;
                                $url='uploads/apklist/'.$uid.$filename;
                            }else{
                                throw new CHttpException(403,"请上传文件包错误");
                            }

                        }
                        else{
                            $member=Member::model()->findByPk($uid);
                            if($member){
                                $data->uid=$uid;
                                $url='uploads/StFlashTool2/user/'.$uid.$filename;
                            }else{
                                $url='uploads/StFlashTool2/'.$filename;
                            }
                        }
                        $data->downPath='/'.$url;
                        $uploadfile='./'.$url;
                        $data->filesize=$this->getFileSize($filesize);
                        $data->updatetime=date("Y-m-d H:i:s",time());
                        $data->name=$filename;
                        $data->version=$version;
                        $file->saveAs($uploadfile,true);//上传操作
                        $data->md5=strtoupper(md5_file($url));
                        if($data->save()){
                            //日志记录
                            $mid = Yii::app()->user->manage_id;
                            $detail='[manage] '.$mid.' [update] [classify] '.$classify. ' [uid] '.$uid.' [version] '.$version.' [tid] '.$tid.' [downPath] /'.$url.' [filesize]'.$this->getFileSize($filesize).' [name] '.$filename.' [md5]'.strtoupper(md5_file($url));
                            NoteLog::addLog($detail,$mid,$uid,$tag='更新盒子文件',$update);

                            //CDN推送
                            Common::newPush($url);
                            Yii::app()->user->setFlash('status','更新文件成功');
                            $this->redirect(array('boxdata/index'));
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

}