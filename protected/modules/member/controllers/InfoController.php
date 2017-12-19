<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/15
 * Time: 10:43
 * 个人信息
 */
class InfoController extends MemberController
{
    /*
     * 个人信息
     * */
    public function actionIndex()
    {
        //$uid = Yii::app()->user->member_id;
        $uid = $this->uid;
        $model = new Member();
        $data = $model -> findByPk($uid);
        $this->render("detail",array("data"=>$data));
    }
    /*
     * 资料修改
     * */
    public function actionEdit()
    {
        //$uid = Yii::app()->user->member_id;
        $uid = $this->uid;
        $model = Member::model();
        $data = $model -> findByPk($uid);
        $data -> scenario = "edit";
        $info = $data->toString();

        $user = Yii::app()->user;
        if(empty($data)){
            throw new CHttpException(404,"您访问的页面不存在，请联系客服。");
        }else{
            if(isset($_POST['Member'])){
                $data -> tel = $_POST['Member']['tel'];
                $data -> mail = $_POST['Member']['mail'];
                $data -> qq = $_POST['Member']['qq'];
                $data -> weixin_name = $_POST['Member']['weixin_name'];
                if($data->save()){
                    //添加log
                    if ($info != $data->toString()) {
                        MemberInfoLog::addLog($user,$info,$data->username);
                    }
                    $this->redirect(array("index"));
                }
            }
            $this->render("edit",array("data"=>$data));
        }
    }
    /*
     * 银行资料修改
     * */
    public function actionBank()
    {
        //$uid = Yii::app()->user->member_id;
        $uid = $this->uid;
        $model = Member::model();
        $data = $model -> findByPk($uid);
        $data -> scenario = "bank";
        $info = $data->toString();
        $user = Yii::app()->user;
        if(empty($data)){
            throw new CHttpException(404,"您访问的页面不存在，请联系客服。");
        }else{
            if($data['bank'] =="" || $data['bank_no'] =="" || $data['bank_site'] =="" || $data['holder'] =="" || $data['id_card'] ==""){
                //如果有一项为空即可修改，否则禁止用户修改银行信息
                if(isset($_POST['Member'])){
                    $data -> holder = $_POST['Member']['holder'];
                    $data -> id_card = $_POST['Member']['id_card'];
                    $data -> bank = $_POST['Member']['bank'];
                    $data -> bank_no = $_POST['Member']['bank_no'];

                    $data -> province = $_POST['Member']['province'];
                    $data -> city = $_POST['Member']['city'];
                    $data -> qrcode = $_POST['Member']['qrcode'];
                    $data -> county = $_POST['Member']['county'];
                    $area_model = new Area();
                    $sheng = $this->loadArea($data -> province);
                    $shi = $this->loadArea($data -> city);
                    $qu = $this->loadArea($data -> county);
                    $data -> bank_site = $sheng.$shi.$qu;

                    if($data->save()){
                        //添加log
                        if ($info != $data->toString()) {
                            MemberInfoLog::addLog($user,$info,$data->username);
                        }
                        $this->redirect(array("index"));
                    }
                }
                $this->render("bank",array("data"=>$data));
            }else{
                throw new CHttpException(403,"如要修改收款信息，请联系在线客服。");
            }

        }
    }
    /*
     * 密码修改
     * */
    public function actionPassword()
    {
        $uid = $this->uid;
        $model = Member::model();
        $data = $model -> findByPk($uid);
        $ip=Yii::app()->request->userHostAddress;
        $model = new ChangePwdForm();
        if (isset($_POST['ChangePwdForm'])) {
            $model->attributes = $_POST['ChangePwdForm'];
            //var_dump($data);exit;
            if ($model->validate() && $model->change()) {
                //Common::redirect($this->createUrl('index'), '修改密码成功');
                Yii::app()->user->setFlash("pwd_status","密码修改成功");
                //推送到微信接口
                if($data['weixin_openid'] !='' && $data['weixin_openid']!=null){
                    Weixin::handleTemplateByPassword($data['weixin_openid'],$data['username'],$ip);
                }

                $this->redirect(array("index"));
            }
        }
        $this->render('pwd', array('model' => $model));
    }
    private function loadArea($id)
    {
        $area_model = new Area();
        $data = $area_model->findByPk($id);
        return $data['name'];
    }
}