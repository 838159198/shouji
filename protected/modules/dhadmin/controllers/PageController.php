<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/13
 * Time: 14:28
 * Name: 页面
 */
class PageController extends DhadminController
{
    /*
     * 页面列表
     * */
    public function actionIndex()
    {
        $model = new Page('search');
        // clear any default values
        $model->unsetAttributes();
        if (isset($_GET['Page'])) {
            $model->attributes = $_GET['Page'];
        }
        $this->render("index",array('model'=>$model));
    }
    /*
     * 创建
     * */
    public function actionCreate()
    {
        $model = new Page();
        if(isset($_POST['Page'])){
            foreach($_POST['Page'] as $_k => $_v){
                $model -> $_k = $_v;
            }
            $model -> createtime = time();
            $model -> lasttime = time();
            $model -> uid = Yii::app()->user->manage_id;
            $model -> hits = 0;
            if($model -> save()){
                Yii::app()->user->setFlash("status","恭喜你，添加页面成功！");
                $this->redirect(array("page/index"));
            }
        }
        $this->render("create",array('model'=>$model));
    }
    /*
     * 修改
     * */
    public function actionUpdate($id)
    {
        $model = Page::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的用户id");
        }else{
            if(isset($_POST['Page'])){
                foreach($_POST['Page'] as $_k => $_v){
                    $data -> $_k = $_v;
                }
                $data -> lasttime = time();
                if($data -> save()){
                    Yii::app()->user->setFlash("status","恭喜你，页面修改成功！");
                    $this->redirect(array("page/index"));
                }
            }
            $this->render("edit",array("model"=>$data));
        }
    }
    /*
     * 删除
     * */
    public function actionDel($id)
    {
        $model = new Page();
        $data = $model -> findByPk($id);
        if(empty($data)){
            echo "该信息不存在，请刷新列表";
        }else{
            if($data -> delete()){
                echo "删除成功";
            }else{
                echo "删除失败，请重新删除";
            }
        }
    }


    /**
     * 路由设备会员管理
     * 2017-12-11 13:05
     * 业务流id=3320
     * zlb
     * @name 路由设备会员管理
     */
    public function actionLuyou(){
        header("Content-type:text/html;charset=utf-8");
        //添加会员
        if(isset($_POST['account_number'])){

            //判断有没有该用户
            $sql="select id,scale,type from app_member where username='{$_POST['account_number']}'";
            $result=yii::app()->db->createCommand($sql)->queryAll();

            if(empty($result)){
                echo '<script>alert("系统中不存在该用户");location.href="/dhadmin/page/luyou";</script>';exit;
            }else{
                if(isset($result[0]['type']) && $result[0]['type']==0){
                echo '<script>alert("不可以添加ROM用户");location.href="/dhadmin/page/luyou";</script>';exit;
                }
            }
            //路由台数和设备编码数量一致
            $coding= isset($_POST['coding']) ? explode(';',$_POST['coding']) : array();
            if(count($coding)!=$_POST['number']){
                echo '<script>alert("路由台数和路由编码数量应一致");location.href="/dhadmin/page/luyou";</script>';exit;
            }

            if($_POST['type']==1){//先资金
                $modelBill = MemberBill::model()->getByUid($result[0]['id']);
                $modelBill->yj_total=$modelBill->yj_total+$_POST['sum'];//总的押金
                $modelBill->dj_total=$modelBill->dj_total+$_POST['sum'];//总的冻结金
                $modelBill->save();
                $dj_sum=$_POST['sum'];
            }else{//先设备，用户收益或余额存在冻结金中
                //查看用户余额和实际收益，如果余额足够，就从余额减去，存入冻结金，不够从收益中减去
                $modelBill = MemberBill::model()->getByUid($result[0]['id']);
                $modelBill->yj_total=$modelBill->yj_total+$_POST['sum'];//总的押金
                if($modelBill->surplus-$_POST['sum']>=0){
                    $dj_sum=$_POST['sum'];
                    $modelBill->surplus=$modelBill->surplus-$_POST['sum'];//余额
                    $modelBill->dj_total=$modelBill->dj_total+$_POST['sum'];//总的冻结金
                }else{
                    $dj_sum=$modelBill->surplus;
                    $modelBill->dj_total=$modelBill->dj_total+$modelBill->surplus;//总的冻结金
                    $modelBill->surplus=0;//余额
                }

                $modelBill->save();
            }
            $a=Yii::app()->db->createCommand()->insert('app_router_manage',
                array(
                // 'id'=>$data->id, 
                'uid'=>$result[0]['id'],
                'type'=>$_POST['type'],
                'name'=>$_POST['name'],
                'tel'=>$_POST['iphone'],
                'router_num'=>$_POST['number'],
                'sum'=>$_POST['sum'],
                'device_coding'=>$_POST['coding'],
                'beizhu'=>$_POST['beizhu'],
                'address'=>$_POST['address'],
                'dj_sum'=>$dj_sum,
                'status'=>2,//默认未发货
                'createtime'=>date('Y-m-d H:i:s'),
                
                )
            );
            //得到插入数据的id
            $b=Yii::app()->db->getLastInsertID();
            $coding= isset($_POST['coding']) ? explode(';',$_POST['coding']) : array();
            if(!empty($coding)){
                foreach($coding as $k=>$v ){
                   yii::app()->db->createCommand()->insert('app_router_list',
                        array(
                            'uid'=>$result[0]['id'],
                            'coding'=>$v,
                            'router_id'=>$b,
                            'yj_sum'=>150,//每台设备的押金金额，定死的
                            'jd_sum'=>0,//解冻金额
                            'kc_sum'=>0

                        )
                    ); 
                }
            }

            /*路由操作记录 start*/
            yii::app()->db->createCommand()->insert('app_router_log',
                array(
                    'uid'=>$result[0]['id'],
                    'createtime'=>date('Y-m-d H:i:s'),
                    'type'=>1,
                    'status'=>$_POST['type'],
                    'mid'=>Yii::app()->user->manage_id,
                    'content'=>'{"shebei":'.$_POST['number'].',"yajin":'.$_POST['sum'].',"bianma":"'.$_POST['coding'].'","beizhu":"'.$_POST['beizhu'].'"}',
                )
            );
            /*end*/
            echo "<script>location.href='/dhadmin/page/luyou'</script>";exit;
        }

        //分页内容显示和搜索
        $sql="select ma.id as id,ma.status as status,ma.type as type,ma.createtime as createtime,ma.router_num as router_num,ma.sum as sum,me.username as username from `app_router_manage` as ma left join `app_member` as me on ma.uid=me.id where true ";
        if(isset($_GET['status']) && !empty($_GET['status'])){
            $sql.=' and ma.status='.$_GET['status'];
        }
        if(isset($_GET['type']) && !empty($_GET['type'])){
            $sql.=' and ma.type='.$_GET['type'];
        }
        if(isset($_GET['begin']) && !empty($_GET['begin'])){
            $sql.=' and ma.createtime >="'.$_GET['begin'].' 00:00:00"';
        }
        if(isset($_GET['end']) && !empty($_GET['end'])){
            $sql.=' and ma.createtime <="'.$_GET['end'].' 23:59:59"';
        }
        if(isset($_GET['username']) && !empty($_GET['username'])){
            $sql.=' and me.username="'.$_GET['username'].'"';
        }
        $data=yii::app()->db->createCommand($sql)->queryAll();
        $model = new CArrayDataProvider($data, array(
            'id' => 'incomeSum222',
            'sort' => array(
                'attributes' => array(
                    'id', 'type', 'username','createtime','router_num','sum','status'
                ),
                'defaultOrder' => 'createtime desc'
            ),
            'pagination' => array(
                'pageSize' => 25,
            ),
        ));


          



        $this->render('luyou',array('model'=>$model));
    }

    /**
     * @name 路由详情
     * 2017-12-12
     * 
     */
    public function actionDetail(){
        //详情
        if(isset($_GET['id'])){
            $sql="select ma.id as id,ma.status as status,ma.type as type,ma.createtime as createtime,ma.router_num as router_num,ma.sum as sum,me.username as username,ma.name as name,ma.address as address,ma.tel as tel,ma.beizhu as beizhu,ma.sendtime as sendtime,ma.dj_sum as dj_sum,ma.uid as uid from `app_router_manage` as ma left join `app_member` as me on ma.uid=me.id where ma.id={$_GET['id']}";
            $result=yii::app()->db->createCommand($sql)->queryAll();
            $result=$result[0];
            $result['kc_sum']=0;//扣款金额
            $sql="select kc_sum from `app_router_list` where router_id={$_GET['id']}";
            $att=yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($att)){
                $kc_total=0;
                foreach ($att as $key => $value) {
                    $kc_total+=$value['kc_sum'];
                }
                $result['kc_sum']=$kc_total;
            }
        }
        //确认修改发货状态
        if(isset($_POST['status']) && isset($_POST['s_id'])){
            $a=yii::app()->db->createCommand()->update('app_router_manage',array('status'=>$_POST['status'],'sendtime'=>date('Y-m-d H:i:s')),'id='.$_POST['s_id']);

            /*路由操作记录 start*/
            $sql="select * from `app_router_manage` where id={$_POST['s_id']} limit 1";
            $result=yii::app()->db->createCommand($sql)->queryAll();
            yii::app()->db->createCommand()->insert('app_router_log',
                array(
                    'uid'=>$result[0]['uid'],
                    'createtime'=>date('Y-m-d H:i:s'),
                    'type'=>4,
                    'status'=>$result[0]['type'],
                    'mid'=>Yii::app()->user->manage_id,
                    'content'=>'{"shebei":'.$result[0]['router_num'].',"yajin":'.$result[0]['sum'].',"bianma":"'.$result[0]['device_coding'].'","beizhu":"无"}',
                )
            );
            /*end*/

            if($a){
                echo 1;exit;
            }else{
                echo 2;exit;
            }

        }
        //设备管理分页
        $sql="select id,uid,status,coding,kc_sum,router_id,handle_status from `app_router_list` where router_id=".$_GET['id'];
        $arr=yii::app()->db->createCommand($sql)->queryAll();
        $model = new CArrayDataProvider($arr, array(
            'id' => 'incomeSum222',
            'sort' => array(
                'attributes' => array(
                    'id', 'status','coding','uid','sum','router_id','handle_status'
                ),
                'defaultOrder' => 'id desc'
            ),
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
        $this->render('detail',array('data'=>$result,'model'=>$model));
    }

    /**
     * 
     * 
     * @name 设备押金处理
     */
    public function actionDealwith(){
        header("Content-type:text/html;charset=utf-8");
        if(isset($_POST) && !empty($_POST['list_id'])){
            $a=yii::app()->db->createCommand()->update('app_router_list',
                array('handle_status'=>2,
                      'kc_sum'=>$_POST['kouchu'],
                      'jd_sum'=>$_POST['jiedong'],
                      'status'=>$_POST['t_status'],
                      'beizhu'=>$_POST['beizhu'],
                      'updatetime'=>date('Y-m-d H:i:s'),
                    ),
                'id='.$_POST['list_id']
            );
            $sql="select router_id,dj_sum,`app_router_list`.uid as uid,`app_router_manage`.type as type,`app_router_manage`.device_coding as device_coding, `app_router_manage`.router_num as router_num,`app_router_list`.beizhu as beizhu,coding  from `app_router_list` left join `app_router_manage` on `app_router_list`.router_id=`app_router_manage`.id where `app_router_list`.id=".$_POST['list_id'];
            // echo $sql;exit;
            $result=yii::app()->db->createCommand($sql)->queryAll();
            if(!empty($result)){
                $modelBill = MemberBill::model()->getByUid($result[0]['uid']);
                $modelBill->dj_total=$modelBill->dj_total-150;
                $modelBill->yj_total=$modelBill->yj_total-150;//押金；减少一台的押金
                $modelBill->surplus=$modelBill->surplus+150-$_POST['kouchu'];//余额
                $modelBill->save();
                //解冻金额->冻结金额-解冻金额->余额+解冻金额
                if(isset($_POST['jiedong']) && !empty($_POST['jiedong'])){
                    //冻结金额-解冻金额
                    
                    // yii::app()->db->createCommand()->update('app_router_manage',array('dj_sum'=>$result[0]['dj_sum']-$_POST['jiedong']),'id='.$result[0]['router_id']);
                    //余额+解冻金额
                    // $sql="select surplus from `app_member_bill` where uid={$result[0]['uid']} limit 1";
                    // $arr=yii::app()->db->createCommand($sql)->queryAll();
                    // if(!empty($arr)){
                    //     yii::app()->db->createCommand()->update('app_member_bill',array('surplus'=>$arr[0]['surplus']+$_POST['jiedong']),'uid='.$result[0]['uid']);
                    // }
                    // else{
                    //     yii::app()->db->createCommand()->insert('app_member_bill',
                    //         array(
                    //             'uid'=>$result[0]['uid'],
                    //             'paid'=>0,
                    //             'nopay'=>0,
                    //             'surplus'=>0-$_POST['jiedong'],
                    //             'cy'=>0

                    //         )
                    //     );
                    // }
                }
                
                /*路由操作记录 start*/
                yii::app()->db->createCommand()->insert('app_router_log',
                    array(
                        'uid'=>$result[0]['uid'],
                        'createtime'=>date('Y-m-d H:i:s'),
                        'type'=>2,
                        'status'=>$result[0]['type'],
                        'mid'=>Yii::app()->user->manage_id,
                        'content'=>'{"shebei":'.$result[0]['router_num'].',"jiefeng":'.$_POST['jiedong'].',"bianma":"'.$result[0]['device_coding'].'","beizhu":"'.$result[0]['beizhu'].'","luyou":"'.$result[0]['coding'].'"}',
                    )
                );
                /*end*/
                /*路由操作记录 start*/
                yii::app()->db->createCommand()->insert('app_router_log',
                    array(
                        'uid'=>$result[0]['uid'],
                        'createtime'=>date('Y-m-d H:i:s'),
                        'type'=>3,
                        'status'=>$result[0]['type'],
                        'mid'=>Yii::app()->user->manage_id,
                        'content'=>'{"shebei":'.$result[0]['router_num'].',"kouchu":'.$_POST['kouchu'].',"bianma":"'.$result[0]['device_coding'].'","beizhu":"'.$result[0]['beizhu'].'","luyou":"'.$result[0]['coding'].'"}',
                    )
                );
                /*end*/
                
                if($a){
                    echo '<script>location.href="/dhadmin/page/detail?id='.$result[0]['router_id'].'"</script>';
                }else{
                    echo'<script>alert("提交失败");location.href="/dhadmin/page/detail?id='.$result[0]['router_id'].'"</script>';
                }

            }
           
        }
    }

    /**
     * 操作记录
     * 2017-12-14
     * zlb
     * @name  路由操作记录
     */
    public function actionRecord(){


        $sql="select * from `app_router_log` where true";

        if(isset($_GET['type']) && !empty($_GET['type'])){
            $sql.=" and type=".$_GET['type'];
        }
        if(isset($_GET['status']) && !empty($_GET['status'])){
            $sql.=" and status=".$_GET['status'];
        }
        if(isset($_GET['username']) && !empty($_GET['username'])){
            $mysql="select id from `app_member` where username='{$_GET['username']}' limit 1";
            $result=yii::app()->db->createCommand($mysql)->queryAll();
            $id=0;
            if(!empty($result)){
                $id=$result[0]['id'];
            }
            $sql.=" and uid=".$id; 

        }
        if(isset($_GET['begin']) && !empty($_GET['begin'])){
            $sql.=" and createtime >= '".$_GET['begin']." 00:00:00'";
        }
        if(isset($_GET['end']) && !empty($_GET['end'])){
            $sql.=" and createtime <= '".$_GET['end']." 23:59:59' ";
        }


        $data=yii::app()->db->createCommand($sql)->queryAll();
        $model = new CArrayDataProvider($data, array(
            'id' => 'incomeSum222',
            'sort' => array(
                'attributes' => array(
                    'id', 'status','type','uid','createtime','content','mid'
                ),
                'defaultOrder' => 'id desc'
            ),
            'pagination' => array(
                'pageSize' => 25,
            ),
        ));
        $this->render('record',array('model'=>$model));
    }
    
}