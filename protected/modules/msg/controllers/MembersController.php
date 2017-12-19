<?php

/**
 * Created by AWangBa.com
 * User: hanyoujun@gmail.com
 * Date: 13-8-2 上午11:06
 * Explain: 推广用户及提成管理
 */
class MembersController extends MsgController
{
    public $incomeList = array();


    /**
     * 推广用户详情
     */
    public function actionIndex()
    {
        $uid = $this->uid;
        $members = Member::model()->findAll('father_id=:father_id',array(':father_id'=>$uid));

        $userinfo=array();
        foreach($members as $_k => $_v)
        {
            $userinfo[$_k]["id"] = $_v["id"];
            $member = Member::model()->find('id=:id',array(':id'=>$_v["id"]));
            $userinfo[$_k]["username"] = substr($member["username"],0,3)."*****";
            $userinfo[$_k]["jointime"] = date('Y-m-d',$member["jointime"]);
            $sql_all ="SELECT count(distinct imeicode) as counts FROM `app_rom_appresource` WHERE `uid` = '{$_v["id"]}';";
            $resdata = Yii::app()->db->createCommand($sql_all)->queryAll();
            $userinfo[$_k]["usercounts"] = $resdata[0]["counts"];
        }

        $data=$userinfo;
        foreach ($data as $key => $row) {
            if(empty($row['jointime']))
            {
                $row['jointime']="空";
            }
            $volume[$key]  = $row['jointime'];
        }
        array_multisort($volume, SORT_DESC, $data);
        $this->render('index', array(
            'dataProvider' => new CArrayDataProvider($data, array(
                'pagination' => array(
                    'pageSize' => Common::PAGE_SIZE,
                ),
            )),
        ));
    }


    /**
     * @param $id
     * @return MemberInfo|null
     * @throws CHttpException
     */
    private function loadModel($id)
    {
        $model = Member::model()->getById($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
	/*
     * 收货地址管理
     * */
    public function actionLocation()
    {
        $uid = $this->uid;
        $model = new MemberLocation();
        $data = $model->findAll("mid={$uid}");
        if(isset($_POST['MemberLocation'])){

        }
        $this->render("location_index",array("data"=>$data));
    }
    /*
     * 添加收货地址
     * */
    public function actionLocationadd()
    {
        $uid = $this->uid;
        $model = new MemberLocation();
        $location_count = $model->count("mid={$uid}");
        if($location_count >= 3){
            //exit("hhh");
            throw new CHttpException(403,"抱歉，最多只能添加3条收货信息");
        }

        if(isset($_POST['MemberLocation'])){
            foreach($_POST['MemberLocation'] as $_k => $_v){
                $model -> $_k = $_v;
            }
            $model -> mid = $uid;
            $model -> create_datetime = time();
            $model -> update_datetime = time();
            if($model->save()){
                $this->redirect("/member/members/location");
            }

        }

        $this->render("location_add",array("model"=>$model));
    }
    /*
     * 修改收货地址
     * */
    public function actionLocationedit($id)
    {
        $uid = $this->uid;
        $model = MemberLocation::model();
        $id = intval($id);
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"您访问的信息不存在");
        }elseif($data["mid"]!=$uid){
            throw new CHttpException(403,"您无权修改该信息");
        }else{
            if(isset($_POST['MemberLocation'])){
                foreach($_POST['MemberLocation'] as $_k => $_v){
                    $data -> $_k = $_v;
                }
                $data -> update_datetime = time();
                if($data->save()){
                    $this->redirect("/member/members/location");
                }
            }
            $this->render("location_edit",array("model"=>$data));
        }
    }
    /*
     * 积分记录
     * */
    public function actionCreditslog()
    {
        $uid = $this->uid;
        $credits_model = new MemberCreditsLog();

        $criteria = new CDbCriteria();
        $criteria -> condition = "memberId={$uid}";
        $criteria -> order = "id DESC";
        $count = $credits_model -> count($criteria);

        $pager = new CPagination($count);
        $pager->pageSize=20;
        $pager->applyLimit($criteria);

        $data = $credits_model -> findAll($criteria);

        $this->render("credits_log",array("data"=>$data,"pages"=>$pager));
    }

}