<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2016/12/20
 * Name: 管理员
 */
class ProductListController extends DhadminController
{
    /*
     * 包列表
     * */
    public function actionIndex()
    {
        $model = new ProductList('search');
        $model->unsetAttributes();
        if (isset($_GET['ProductList'])) {
            $model->attributes = $_GET['ProductList'];
        }
        $this->render("index",array('model'=>$model));
    }
    /*
     * 上传包
     * */
    public function actionCreate()
    {
        $model = new ProductList();
        $this->performAjaxValidation($model);
        if(isset($_POST['ProductList'])){
            // print_r($_POST['ProductList']);exit;
            $model->attributes = $_POST['ProductList'];
            //文件上传
            $file=CUploadedFile::getInstance($model,'appurl'); //获取表单名为filename的上传信息
            if(isset($file)){
                $fileextension=$file->getExtensionName();//获取文件扩展名
                if($fileextension=='apk'){
                    $filename=$file->getName();//获取文件名
                    $filesize=$file->getSize();//获取文件大小
                    $filename=$_POST['ProductList']['type'].'_'.strtolower(trim($_POST['ProductList']['version'])).'_'.trim($_POST['ProductList']['pakid']).'.apk';//数据库中要存放文件名
                    $model->appurl='/uploads/apk/'.$filename;
                    $model->filesize=$this->getFileSize($filesize);
                    $version=strtoupper(substr(trim($_POST['ProductList']['version']),0,1)).substr(trim($_POST['ProductList']['version']),1);
                    $pakid=trim($_POST['ProductList']['pakid']);
                    $pid=Product::model()->getByKeyword($_POST['ProductList']['type'])->id;
                    $pakname=trim($_POST['ProductList']['pakname']);
                    $agent=$_POST['ProductList']['agent'];
                    $model->isshow=$_POST['ProductList']['isshow'];
                    $model->version=$version;
                    $model->pakid=$pakid;
                    $model->agent=$_POST['ProductList']['agent'];
                    $model->pakname=trim($_POST['ProductList']['pakname']);
                    $model->pid=$pid;
                    $uploadfile='./uploads/apk/'.$filename;
                    $file->saveAs($uploadfile,true);//上传操作
                    $sign=strtoupper(md5_file('uploads/apk/'.$filename));
                    $model->sign=$sign;
                    if($model->save()){
                        $mid = Yii::app()->user->manage_id;
                        //日志记录
                        $detail='[manage] '.$mid.' [insert] [appurl] /uploads/apk/'.$filename. ' [filesize]'.$this->getFileSize($filesize).' [version]'.$version.' [pakid]'.$pakid.' [agent]'.$agent.' [pakname]'.$pakname.' [pid]'.$pid.' [sign]'.$sign;
                        NoteLog::addLog($detail,$mid,$uid='',$tag='上传业务包',$update='');

                        Yii::app()->user->setFlash('status','上传文件成功');
                        $this->redirect(array('productList/index'));
                    }
                }else{
                    throw new CHttpException(403,"文件包类型上传错误，请重新上传
");
                }

            }else{
                throw new CHttpException(403,"请上传包文件");
            }

        }
        $this->render("create",array("model"=>$model));
    }
    protected function performAjaxValidation($model){
        if(isset($_POST['ajax'])&& $_POST['ajax']=='login-form'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
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
     * 编辑包信息
     * */
    public function actionEdit($id)
    {
        $model = ProductList::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在");
        }else{
            if(isset($_POST['ProductList'])){
                $update=json_encode($data->attributes);
                $version=strtoupper(substr(trim($_POST['ProductList']['version']),0,1)).substr(trim($_POST['ProductList']['version']),1);
                $pakid=trim($_POST['ProductList']['pakid']);
                $agent=$_POST['ProductList']['agent'];
                $status=$_POST['ProductList']['status'];
                $isshow=$_POST['ProductList']['isshow'];
                $type=$_POST['ProductList']['type'];
                $pid=Product::model()->getByKeyword($_POST['ProductList']['type'])->id;
                $pakname=trim($_POST['ProductList']['pakname']);

                $data->version=$version;
                $data->pakid=$pakid;
                $data->pakname=$pakname;
                $data->agent=$agent;
                $data->status=$status;
                $data->isshow=$isshow;
                $data->type=$type;
                $data->pid=$pid;

                if($data -> save()){
                    $mid = Yii::app()->user->manage_id;
                    //日志记录
                    $detail='[manage] '.$mid.' [update]  [version]'.$version.' [pakid] '.$pakid.' [agent] '.$agent.' [pakname] '.$pakname.' [pid] '.$pid.' [status] '.$status.' [isshow] '.$isshow.' [type] '.$type;
                    NoteLog::addLog($detail,$mid,$uid='',$tag='业务包信息修改',$update);

                    Yii::app()->user->setFlash("status","恭喜你，业务包信息修改成功！");
                    $this->redirect(array("productList/index"));
                }
            }
            $this->render("edit",array("model"=>$data));
        }
    }


    /**
     * 业务包二次确认
     *2017-10-31
     *业务流id=3161
     * zlb
     */
    public function actionConfirm(){
        if(isset($_POST['id'])){
            //更改状态
            yii::app()->db->createCommand()->update('app_product_list',array('isshow'=>1),'id=:id',array(':id'=>$_POST['id']));
            //写入操作日志
            $mid=yii::app()->user->manage_id;
            $detail='[manage] '.$mid.'[业务包二次确认页面操作][表product_list的id]'.$_POST["id"];
            NoteLog::addLog($detail,$mid,$_POST["id"],$tag='业务包二次确认',$update='confirmtwo');
            echo 1;exit;
        }

        //isshow 2为等待二次确认
        //业务对外展示
        $sql="select id,pid,agent,pakname,pakid,createtime,version,sign from `app_product_list` where isshow=2";
        $data=yii::app()->db->createCommand($sql)->queryAll();
        $model = new CArrayDataProvider($data, array(
            'id' => 'incomeSum',
            'sort' => array(
                'attributes' => array(
                    'id', 'pid', 'agent','pakname','pakid','createtime','version','sign'
                ),
                'defaultOrder' => 'createtime desc'
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));
        //业务对外展示记录
        $sql="select a.id,pid,agent,pakname,pakid,b.createtime as createtime,version,sign,mid from `app_product_list` as a left join `app_note_log` as b on a.id=b.uid where update_detail='confirmtwo' ";
        $result=yii::app()->db->createCommand($sql)->queryAll();
        $model_2 = new CArrayDataProvider($result, array(
            'id' => 'incomeSum222',
            'sort' => array(
                'attributes' => array(
                    'id', 'pid', 'agent','pakname','pakid','createtime','version','sign','mid'
                ),
                'defaultOrder' => 'createtime desc'
            ),
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));
        $this->render('confirm',array('data1'=>$model,'data2'=>$model_2));
    }

    /**
     * 黑名单
     *业务流id=3163
     * zlb 2017-11-01
     */
    public function actionBlacklist(){
        //设置安装数
        $sql="select update_detail from `app_note_log` where tag='imei定时封号' order by id desc limit 1";
        $result=yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($result)){
            $dingshi=$result[0]['update_detail'];
        }else{
            $dingshi=0;
        }
        //今日和昨日拉黑的手机数
        $today=date('Y-m-d');
        $yesteday=date('Y-m-d',strtotime('-1 day'));
        $sql='select count(distinct imeicode) as num from `app_blacklist` where createtime like "'.$today.'%"';
        $data1=yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($data1)){
            $day1= $data1[0]['num'];
        }else{
            $day1=0;
        }
        $sqll='select count(distinct imeicode) as num from `app_blacklist` where createtime like "'.$yesteday.'%"';
        $data2=yii::app()->db->createCommand($sqll)->queryAll();
        if(!empty($data2)){
            $day2= $data2[0]['num'];
        }else{
            $day2=0;
        }

        //黑名单列表
        $sql="select imeicode,uid,brand,model,createtime,mid,id from `app_blacklist` group by imeicode ";
        $arr=yii::app()->db->createCommand($sql)->queryAll();

        //总的封号手机数量
        $totalnum=count($arr);
        $model = new CArrayDataProvider($arr, array(
            'id' => 'incomeSum222',
            'sort' => array(
                'attributes' => array(
                    'id', 'uid', 'imeicode','brand','model','createtime','mid'
                ),
                'defaultOrder' => 'createtime desc'
            ),
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
        $this->render('blacklist',array('data'=>$model,'day'=>array($day1,$day2,$totalnum,$dingshi)));
    }

    /**
     * 安装次数大于查询的值
     * 
     */
    public function actionMaxnum(){
        set_time_limit(0);
        $maxnum=isset($_POST['num']) ? $_POST['num'] :'';
        $handle=isset($_POST['handle']) ? $_POST['handle'] : '';
        //只是个查询
        if(!empty($maxnum) && empty($handle)){
            $sql="select count(distinct(imeicode)) as total from `app_rom_appresource` left join `app_member` on `app_rom_appresource`.uid=`app_member`.id where agent in(0,69) and closeend='0000-00-00 00:00:00' and installcount > ".$maxnum;
            $data=yii::app()->db->createCommand($sql)->queryAll();
            echo $data[0]['total'];
        }
        //一键导入黑名单
        if(!empty($maxnum) && !empty($handle)){
            $sql="select imeicode,max(`app_rom_appresource`.id) as id from `app_rom_appresource` left join `app_member` on `app_rom_appresource`.uid=`app_member`.id where agent in(0,69) and closeend='0000-00-00 00:00:00' and installcount > ".$maxnum." group by imeicode";
            $daa=yii::app()->db->createCommand($sql)->queryAll();
            $data=array();
            if(!empty($daa)){
                $id='';
                foreach ($daa as $key => $value) {
                    $id.=$value['id'].',';
                }
                $id=!empty($id) ? substr($id,0,-1) : '';
                $sql="select uid,model,brand,imeicode from `app_rom_appresource` where id in({$id})";
                $data=yii::app()->db->createCommand($sql)->queryAll();
            }
            if(!empty($data)){
                $cishu=0;
                $imei='';
                foreach ($data as $key => $value) {
                    $imei.='"'.$value["imeicode"].'"';
                    $imei.=',';
                    // $a=yii::app()->db->createCommand()->update('app_rom_appresource',array('finishstatus'=>0,'closeend'=>date('Y-m-d H:i:s'),'finishdate'=>'0000-00-00','finishtime'=>'0000-00-00 00:00:00','status'=>0),'imeicode=:imeicode',array(':imeicode'=>$value['imeicode'])
                    // );
                    //写入操作日志
                    $mid=yii::app()->user->manage_id;
                    // $detail='[manage] '.$mid.'[imei黑名单][imeicode]'.$value['imeicode'];
                    // NoteLog::addLog($detail,$mid,0,$tag='imei黑名单',$update=$value['imeicode']);
                    
                    //imei是否已经拉黑
                    $sql="select id from `app_blacklist` where imeicode='{$value['imeicode']}'";
                    $arr=yii::app()->db->createCommand($sql)->queryAll();
                    if(!empty($arr)){
                        continue;
                    }
                    //导入app_blacklist黑名单表中
                    // $sql="select uid,imeicode,brand,model from `app_rom_appresource` left join `app_member` on `app_rom_appresource`.uid=`app_member`.id where agent in(0,69) and imeicode='".$value['imeicode']."' order by installtime desc limit 1";
                    // $result=yii::app()->db->createCommand($sql)->queryAll();
                    yii::app()->db->createCommand()->insert('app_blacklist',
                        array(
                            'imeicode'=>$value['imeicode'],
                            'mid'=>$mid,
                            'createtime'=>date('Y-m-d H:i:s'),
                            'brand'=>$value['brand'],
                            'model'=>$value['model'],
                            'uid'=>$value['uid']
                        )
                    );
                    $cishu+=1;

                }
                //update
                $imei=substr($imei,0,-1);
                // echo $imei;exit;
                $a=yii::app()->db->createCommand()->update('app_rom_appresource',array('finishstatus'=>0,'closeend'=>date('Y-m-d H:i:s'),'finishdate'=>'0000-00-00','finishtime'=>'0000-00-00 00:00:00','status'=>0),'imeicode in ('.$imei.')'
                    );
                if($a){
                    echo $cishu;//共处理了多少个手机
                }
                // echo $cishu;//共处理了多少条数据
                
            }else{
                echo 0;
            }
        }
    }
    /**
     * 
     * 指定imei封号处理
     */
    public function actionFeng(){
        //判定数据库中有没有该imei
        $imei=isset($_POST['imei']) ? $_POST['imei'] : '';
        if($imei!=null){
            
            $sql="select uid,brand,model from `app_rom_appresource` left join `app_member` on `app_rom_appresource`.uid=`app_member`.id where agent in(0,69) and imeicode='{$imei}' order by `app_rom_appresource`.id desc limit 1";
            $result=yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($result)){
                $sql="select username from `app_member` where id=".$result[0]['uid'];
                $att=yii::app()->db->createCommand($sql)->queryAll();
                $result[0]['uid']=!empty($att) ? $att[0]['username'] : '';
                echo json_encode($result[0]);exit;
            }else{
                echo json_encode(array());exit;
            }
        }
        //没有就插入修改，有的话就仅仅修改就行
        $imeicode=isset($_POST['imeicode']) ? $_POST['imeicode'] : '';
        $brand=isset($_POST['brand']) ? $_POST['brand'] : '';
        $model=isset($_POST['model']) ? $_POST['model'] : '';
        $username=isset($_POST['username']) ? $_POST['username'] : '';
        if(!empty($imeicode)){
            //imei是否已经拉黑
            $sql="select id from `app_blacklist` where imeicode='{$imeicode}'";
            $arr=yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($arr)){
                echo 3;exit;
            }

            $sql="select `app_rom_appresource`.id from `app_rom_appresource` left join `app_member` on `app_rom_appresource`.uid=`app_member`.id where agent in(0,69) and imeicode='{$imeicode}'  order by `app_rom_appresource`.id desc limit 1";
            $result=yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($result)){
                // $a=yii::app()->db->createCommand()->update('app_rom_appresource',array('status'=>0,'closeend'=>date('Y-m-d H:i:s')),'id=:id',array(':id'=>$result[0]['id']));
                $a=yii::app()->db->createCommand()->update('app_rom_appresource',array('finishstatus'=>0,'closeend'=>date('Y-m-d H:i:s'),'finishdate'=>'0000-00-00','finishtime'=>'0000-00-00 00:00:00','status'=>0),'imeicode=:imeicode',array(':imeicode'=>$imeicode)
                );
                if($a){
                    //写入操作日志
                    $mid=yii::app()->user->manage_id;
                    $detail='[manage] '.$mid.'[imei黑名单][imeicode]'.$imeicode;
                    NoteLog::addLog($detail,$mid,0,$tag='imei黑名单',$update=$imeicode);
                    //导入app_blacklist黑名单表中
                    $sql="select uid,imeicode,brand,model from `app_rom_appresource` where imeicode='".$imeicode."' order by installtime desc limit 1";
                    $result=yii::app()->db->createCommand($sql)->queryAll();
                    yii::app()->db->createCommand()->insert('app_blacklist',
                        array(
                            'imeicode'=>$result[0]['imeicode'],
                            'mid'=>$mid,
                            'createtime'=>date('Y-m-d H:i:s'),
                            'brand'=>$result[0]['brand'],
                            'model'=>$result[0]['model'],
                            'uid'=>$result[0]['uid']
                        )
                    );

                    echo 1;
                }else{
                    echo 0;
                }
            }else{
                $sql="select id from `app_member` where username='".$username."'";
                // echo $sql;exit;
                $result=yii::app()->db->createCommand($sql)->queryAll();
                if(!empty($result)){
                //     $b=Yii::app()->db->createCommand()->insert('app_rom_appresource',
                //     array(
                //     // 'id'=>$data->id, 
                //     'uid'=>$result[0]['id'],
                //     // 'type'=>$data->type,
                //     'imeicode'=>$imeicode,
                //     // 'simcode'=>$data->simcode,
                //     // 'tjcode'=>$data->tjcode,
                //     'brand'=>$brand,
                //     // 'status'=>0,
                //     'model'=>$model,
                //     'finishstatus'=>0,
                //     // 'createtime'=>$data->createtime,
                //     'closeend'=>date('Y-m-d H:i:s'),
                //     'finishdate'=>'0000-00-00',
                //     'finishtime'=>'0000-00-00 00:00:00',
                //     // 'installtime'=>$data->installtime,
                //     // 'installcount'=>$data->installcount,
                //     // 'ip'=>$data->ip,
                //     // 'from'=>$data->from,
                //     // 'sys'=>$data->sys,
                //     // 'tc'=>$data->tc,
                //     // 'tcid'=>$data->tcid,
                //     // 'tcfirsttime'=>$data->tcfirsttime,
                //     // 'noincome'=>$data->noincome,
                //     // 'createstamp'=>$data->createstamp,
                //     // 'is_check'=>$data->is_check,
                //     // 'uptype'=>$uptype
                //     )
                // );
                    // if($b){
                        //写入操作日志
                        $mid=yii::app()->user->manage_id;
                        $detail='[manage] '.$mid.'[imei黑名单][imeicode]'.$imeicode;
                        NoteLog::addLog($detail,$mid,0,$tag='imei黑名单',$update=$imeicode);
                        //导入黑名单
                        yii::app()->db->createCommand()->insert('app_blacklist',
                        array(
                            'imeicode'=>$imeicode,
                            'mid'=>$mid,
                            'createtime'=>date('Y-m-d H:i:s'),
                            'brand'=>$brand,
                            'model'=>$model,
                            'uid'=>$result[0]['id']
                        )
                        );
                        echo 1;
                    // }else{
                        // echo 0;
                    // }
                }else{
                    echo 2;
                }
                

            }
        }
    }
    /**
     * 设置安装次数，定时处理数据
     *
     * (我把安装次数值放在app_note_log)
     */
    public function actionDingshi(){
        $number_cha=isset($_POST['number_cha']) ? $_POST['number_cha'] : '';
        $sql="select id from `app_note_log` where tag='imei定时封号' order by id desc limit 1";
        $result=yii::app()->db->createCommand($sql)->queryAll();
        $mid=yii::app()->user->manage_id;
        if(!empty($result)){
            $a=yii::app()->db->createCommand()->update('app_note_log',array('update_detail'=>$number_cha,'mid'=>$mid,'detail'=>'[manage]'.$mid.'[设置安装次数installcount]'.$number_cha),'id=:id',array(':id'=>$result[0]['id']));
            if($a){
                echo 1;
            }else{
                echo 0;
            }
        }else{
            //写入操作日志
            $mid=yii::app()->user->manage_id;
            $detail='[manage] '.$mid.'[设置安装次数installcount]'.$number_cha;
            NoteLog::addLog($detail,$mid,0,$tag='imei定时封号',$update=$number_cha);
            echo 1;
        }
    }

    /**
     * 接口
     *调用该接口实现自动封号功能
     * 
     */
    public function actionImeikill(){
        set_time_limit(0);
        echo '<meta charset="utf-8">';
        $sql="select update_detail from `app_note_log` where tag='imei定时封号' limit 1";
        $result=yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($result)){
            //设定的最大安装次数
            $num=$result[0]['update_detail'];
            //找出大于安装数的全部手机imei 
            $sql="select distinct imeicode from `app_rom_appresource` left join `app_member` on `app_rom_appresource`.uid=`app_member`.id where agent in(0,69) and closeend='0000-00-00 00:00:00' and installcount > ".$num;
            $data=yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($data)){
                foreach ($data as $key => $value) {
                    $a=0;
                    yii::app()->db->createCommand()->update('app_rom_appresource',
                        array('finishstatus'=>0,'closeend'=>date('Y-m-d H:i:s'),'finishdate'=>'0000-00-00','finishtime'=>'0000-00-00 00:00:00','status'=>0),'imeicode=:imeicode',array(':imeicode'=>$value['imeicode'])
                    );
                    //写入操作日志
                    // $mid=yii::app()->user->manage_id;
                    $mid=0;
                    $detail='[manage] '.$mid.'[imei黑名单][imeicode]'.$value['imeicode'];
                    NoteLog::addLog($detail,$mid,0,$tag='imei黑名单',$update=$value['imeicode']);
                    //imei是否已经拉黑
                    $sql="select id from `app_blacklist` where imeicode='{$value['imeicode']}'";
                    $arr=yii::app()->db->createCommand($sql)->queryAll();
                    if(!empty($arr)){
                        continue;
                    }
                    //导入app_blacklist黑名单表中
                    $sql="select uid,imeicode,brand,model from `app_rom_appresource` where imeicode='".$value['imeicode']."' order by installtime desc limit 1";
                    $result=yii::app()->db->createCommand($sql)->queryAll();
                    yii::app()->db->createCommand()->insert('app_blacklist',
                        array(
                            'imeicode'=>$result[0]['imeicode'],
                            'mid'=>$mid,
                            'createtime'=>date('Y-m-d H:i:s'),
                            'brand'=>$result[0]['brand'],
                            'model'=>$result[0]['model'],
                            'uid'=>$result[0]['uid']
                        )
                    );
                    $a+=1;
                }
                echo '封杀手机'.$a.'个';exit;

            }
            echo '封杀手机0个';exit;
        }
        echo '封杀手机0个';exit;
    }
    

}