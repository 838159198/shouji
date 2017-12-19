<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/10
 * Time: 16:04
 */
class WebController extends Controller{
    public $token;
    public function init()
    {
        $this->token = Weixin::getToken();
    }
    public function actionIndex(){
        $request = Yii::app()->request;
        $userid = $request->getParam("userid",'');
        Weixin::curlGet("POST","https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=".$this->token,json_encode(array("openid"=>$userid,"remark"=>"haha")));
        $json['status'] = 200;
        exit(CJSON::encode($json));
    }
    /*
     * 获取所有用户分组
     * */
    public function actionGetGroups(){
        $groupsData=Weixin::getGroupsData($this->token);
        exit(CJSON::encode($groupsData));
    }
    /*
     * 绑定账号
     * */
    public function actionBindAccount(){
        $this->pageTitle = "绑定账号";
        $request = Yii::app()->request;
        $userid = $request->getParam("userid",'');
        if($userid == ""){
            //错误页面
            $this->errorMessage(array("title"=>"发生错误","msg"=>"无效的参数"));
        }
        //获取用户信息
        $userData = Weixin::getUserData($userid,$this->token);
        if(!is_array($userData) ||  (is_array($userData)  && array_key_exists("errcode",$userData))){
            //错误
            $this->errorMessage(array("title"=>"发生错误","msg"=>"获取用户信息失败"));
        }
        if($userData['subscribe'] == 0){
            //未关注公众号
            $this->errorMessage(array("title"=>"抱歉","msg"=>"您还没有关注本公众号，微信号：sutuiapp"));
        }
        //查询是否已经绑定
        $bindData = Member::model()->find("weixin_openid=:openid",array(":openid"=>$userid));
        if($bindData){
            $this->errorMessage(array("title"=>"无需再次绑定","msg"=>"您的微信已经和速推平台绑定。<br>速推账号：{$bindData['username']}"));
        }

        if($request->isAjaxRequest && isset($_POST)){
            $username = $request->getPost("username","");
            $password = $request->getPost("password","");
            if($username!="" && $password!=""){
                $model = Member::model();
                $data = $model->find("username=:username",array(":username"=>$username));
                if($data && $data->validatePassword($password) && empty($data['weixin_openid'])){
                    $data -> weixin_openid = $userid;
                    $data -> weixin_createtimes = time();
                    if($data -> update()){
                        //设置备注名
                        Weixin::curlGet("POST","https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=".$this->token,json_encode(array("openid"=>$userid,"remark"=>$username)));
                        header('Content-type: application/json');
                        $json['status'] = 200;
                        exit(CJSON::encode($json));
                    }else{
                        header('Content-type: application/json');
                        $json['status'] = 400;
                        exit(CJSON::encode($json));
                    }

                }else{
                    header('Content-type: application/json');
                    $json['status'] = 400;
                    exit(CJSON::encode($json));
                }
            }else{
                header('Content-type: application/json');
                $json['status'] = 400;
                exit(CJSON::encode($json));
            }
        }


        $this->render("bind_account",array("user"=>$userData,'userid'=>$userid));
    }
    public function actionAjaxBindAccount(){
        $request = Yii::app()->request;
        $userid = $request->getParam("userid",'');
        if($userid == ""){
            //错误页面
            header('Content-type: application/json');
            $json['status'] = 400;
            $json['title'] = "发生错误";
            $json['msg'] = "无效的参数";
            exit(CJSON::encode($json));
        }



        if($request->isAjaxRequest && isset($_POST) && $userid != ""){
            //获取用户信息
            $userData = Weixin::getUserData($userid,$this->token);
            if(!is_array($userData) ||  (is_array($userData)  && array_key_exists("errcode",$userData))){
                //错误
                header('Content-type: application/json');
                $json['status'] = 400;
                $json['title'] = "发生错误";
                $json['msg'] = "获取用户信息失败";
                exit(CJSON::encode($json));
            }
            if($userData['subscribe'] == 0){
                //未关注公众号
                header('Content-type: application/json');
                $json['status'] = 400;
                $json['title'] = "抱歉";
                $json['msg'] = "您还没有关注本公众号，微信号：sutuiapp";
                exit(CJSON::encode($json));
            }

            $username = $request->getPost("username","");
            $password = $request->getPost("password","");
            if($username!="" && $password!=""){
                $model = Member::model();
                $data = $model->find("username=:username",array(":username"=>$username));
                if(!empty($data['weixin_openid'])){
                    header('Content-type: application/json');
                    $json['status'] = 400;
                    $json['title'] = "无需再次绑定";
                    $json['msg'] = "您的微信已经和速推平台绑定。速推账号：{$data['username']}";
                    exit(CJSON::encode($json));
                }
                if($data && $data->validatePassword($password) ){
                    $data -> weixin_openid = $userid;
                    $data -> weixin_createtimes = time();
                    if($data -> update()){
                        //绑定微信赠送1000积分功能，每个账号只能赠送一次
                        //查询是否已经赠送
                        $memberCreditsLog = MemberCreditsLog::model()->find("memberId=:uid and source=:source",array(":uid"=>$data->id,":source"=>'weixin'));
                        if(!$memberCreditsLog){
                            //绑定赠送增加积分日志oFspSwazn4KfZCZkMsa23R7WlmLI
                            $model ->updateCounters(array("credits"=>1000),"id={$data->id}");
                            MemberCreditsLog::updateCreditsLog($data->id,$data->credits,'weixin');
                        }
                        //设置指定分组
                        Weixin::moveGroups($this->token,$userid,$to_groupid=100);
                        //设置备注名
                        Weixin::curlGet("POST","https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=".$this->token,json_encode(array("openid"=>$userid,"remark"=>$username)));
                        header('Content-type: application/json');
                        $json['status'] = 200;
                        $json['title'] = "绑定成功";
                        $json['msg'] = "您的微信和速推平台账号已经成功绑定！";
                        exit(CJSON::encode($json));
                    }else{
                        header('Content-type: application/json');
                        $json['status'] = 400;
                        $json['title'] = "发生错误";
                        $json['msg'] = "微信绑定失败";
                        exit(CJSON::encode($json));
                    }

                }else{
                    header('Content-type: application/json');
                    $json['status'] = 400;
                    $json['title'] = "发生错误";
                    $json['msg'] = "用户不存在或密码错误";
                    exit(CJSON::encode($json));
                }
            }else{
                header('Content-type: application/json');
                $json['status'] = 400;
                $json['title'] = "发生错误";
                $json['msg'] = "用户名或密码不得为空";
                exit(CJSON::encode($json));
            }
        }else{
            header('Content-type: application/json');
            $json['status'] = 400;
            $json['title'] = "发生错误";
            $json['msg'] = "无效的参数";
            exit(CJSON::encode($json));
        }
    }
    public function actionBindOk(){
        $this->pageTitle = "提示消息";
        $request = Yii::app()->request;
        $userid = $request->getParam("userid",'');
        if($userid == ""){
            //错误页面
            $this->errorMessage(array("title"=>"发生错误","msg"=>"无效的参数"));
        }
        //查询是否已经绑定
        $bindData = Member::model()->find("weixin_openid=:openid",array(":openid"=>$userid));
        if(!$bindData){
            $this->errorMessage(array("title"=>"还未绑定账号","msg"=>"您的微信还没有和速推平台绑定。"));
        }
        $this->render("bind_ok");
    }
    /*
     * 公告
     * */
    public function actionNotice(){
        $this->pageTitle = "公告";
        $model = new Posts();
        $criteria = new CDbCriteria();
        $criteria -> order = "id DESC";
        $criteria -> condition = "cid = 1 AND status = 1";
        $criteria -> limit = 20;
        $data = $model -> findAll($criteria);
        $this->render("notice_list",array("data"=>$data));
    }
    /*
    * 公告详情
    * */
    public function actionNoticeDetail(){
        $this->pageTitle = "公告";
        $request = Yii::app()->request;
        $id = $request->getParam("id",'');
        $postsModel = Posts::model()->findAllByPk($id);
        if(!$postsModel){
            $this->errorMessage(array("title"=>"发生错误","msg"=>"您没有权限查看。"));
        }
        $model = new Posts();
        $model->updateCounters(array("hits"=>1),"id={$id}");//更新点击量
        if(!$postsModel){
            $this->errorMessage(array("title"=>"发生错误","msg"=>"站内信不存在。"));
        }
        $this->render("notice_detail",array("data"=>$postsModel));
    }
    /**
     * 站内信
     * @string userid 用户绑定的openid
     * */
    public function actionMail(){
        $this->pageTitle = "站内信";
        $request = Yii::app()->request;
        $userid = $request->getParam("userid",'');
        if($userid == ""){
            //错误页面
            $this->errorMessage(array("title"=>"发生错误","msg"=>"无效的参数"));
        }
        //查询绑定
        $bindData = Member::model()->find("weixin_openid=:openid",array(":openid"=>$userid));
        if(!$bindData){
            $this->errorMessage(array("title"=>"还未绑定账号","msg"=>"您的微信还没有和速推平台绑定。"));
        }
        $sql = "SELECT t.id AS id,m.status AS status,t.title AS title,t.content AS content FROM app_mail_content AS t
                LEFT JOIN app_mail AS m ON m.content = t.id WHERE m.recipient = {$bindData['id']} AND m.status in (0,1) ORDER BY t.id DESC LIMIT 0,20";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        //print_r($data);exit;
        $this->render("mail_list",array("data"=>$data,"userid"=>$userid));
    }
    /*
     * 站内信详情
     * */
    public function actionMailDetail(){
        $this->pageTitle = "站内信";
        $request = Yii::app()->request;
        $userid = $request->getParam("userid",'');
        $id = $request->getParam("id",'');
        if($userid == "" || $id ==""){
            //错误页面
            $this->errorMessage(array("title"=>"发生错误","msg"=>"无效的参数"));
        }
        //查询绑定
        $bindData = Member::model()->find("weixin_openid=:openid",array(":openid"=>$userid));
        if(!$bindData){
            $this->errorMessage(array("title"=>"还未绑定账号","msg"=>"您的微信还没有和速推平台绑定。"));
        }
        $mailModel = Mail::model();
        $mailData = $mailModel -> find("recipient=:recipient AND content=:content",array(":recipient"=>$bindData['id'],":content"=>$id));
        if(!$mailData){
            $this->errorMessage(array("title"=>"发生错误","msg"=>"您没有权限查看。"));
        }
        $mailData -> status = 1;
        $mailData -> update();
        $data = MailContent::model()->findByPk($mailData['content']);
        if(!$data){
            $this->errorMessage(array("title"=>"发生错误","msg"=>"站内信不存在。"));
        }
        $this->render("mail_detail",array("data"=>$data));
    }
    /*
     * 注册账号
     * */
    public function actionReg(){
        $this->pageTitle = "注册账号";
        $request = Yii::app()->request;
        $userid = $request->getParam("userid",'');
        //获取用户信息
        $userData = Weixin::getUserData($userid,$this->token);
        if(!is_array($userData) ||  (is_array($userData)  && array_key_exists("errcode",$userData))){
            //错误
            $this->errorMessage(array("title"=>"发生错误","msg"=>"获取用户信息失败"));
        }
        if($userData['subscribe'] == 0){
            //未关注公众号
            $this->errorMessage(array("title"=>"抱歉","msg"=>"您还没有关注本公众号，微信号：sutuiapp"));
        }
        //查询是否已经绑定
        $bindData = Member::model()->find("weixin_openid=:openid",array(":openid"=>$userid));
        if($bindData){
            $this->errorMessage(array("title"=>"无需再次绑定","msg"=>"您的微信已经和速推平台绑定。<br>速推账号：{$bindData['username']}"));
        }
        $this->render("reg",array("user"=>$userData));
    }
    /*
     * 注册账号 提交表单
     * */
    public function actionAjaxReg(){
        $request = Yii::app()->request;
        if($request->isAjaxRequest && isset($_POST)){
            $userid = $request->getParam("userid",'');
            if($userid == ""){
                $this->returnErrorJson(400,'发生错误','参数错误');
            }
            //获取用户信息
            $userData = Weixin::getUserData($userid,$this->token);
            if(!is_array($userData) ||  (is_array($userData)  && array_key_exists("errcode",$userData))){
                //错误
                //$this->errorMessage(array("title"=>"发生错误","msg"=>"获取用户信息失败"));
                $this->returnErrorJson(400,'发生错误','获取用户信息失败');
            }
            if($userData['subscribe'] == 0){
                //未关注公众号
                //$this->errorMessage(array("title"=>"抱歉","msg"=>"您还没有关注本公众号，微信号：sutuiapp"));
                $this->returnErrorJson(400,'抱歉','您还没有关注本公众号，微信号：sutuiapp');
            }
            //查询是否已经绑定
            $bindData = Member::model()->find("weixin_openid=:openid",array(":openid"=>$userid));
            if($bindData){
                //$this->errorMessage(array("title"=>"无需再次绑定","msg"=>"您的微信已经和速推平台绑定。<br>速推账号：{$bindData['username']}"));
                $this->returnErrorJson(400,'无需再次绑定','您的微信已经和速推平台绑定。速推账号：{$bindData[\'username\']}');
            }
            //获取表单信息
            $username = $request->getPost("username","");
            $password = $request->getPost("password","");
            $mobile = $request->getPost("mobile","");
            $qq = $request->getPost("qq","");
            $type = $request->getPost("type","");
            if($username == "" || $password == "" || $mobile == "" || $qq == "" || $type == ""){
                $this->returnErrorJson(400,'发生错误','参数错误');
            }
            $memberData = Member::model()->find("username=:username",array(":username"=>$username));
            if($memberData){
                $this->returnErrorJson(400,'发生错误','该用户名已被注册');
            }

            $model = new Member('reg');
            $model -> username = $username;
            $model -> password = $model->createPassword($password);
            $model -> tel = $mobile;
            $model -> qq = $qq;
            $model -> reg_ip = Common::getIp();
            $model -> jointime = time();
            $model -> overtime = time();
            $model -> weixin_openid = $userid;
            $model -> weixin_createtimes = time();
            //$model -> mail = "";
            if($model -> insert()){
                $this->returnErrorJson(200,'成功','success');
            }else{
                $this->returnErrorJson(400,'失败','注册失败');
            }
        }else{
            $this->returnErrorJson(400,'发生错误','请求方式不对');
        }

    }
    public function actionRegOk(){
        $this->pageTitle = "提示消息";
        $request = Yii::app()->request;
        $userid = $request->getParam("userid",'');
        if($userid == ""){
            //错误页面
            $this->errorMessage(array("title"=>"发生错误","msg"=>"无效的参数"));
        }
        //查询是否已经绑定
        $bindData = Member::model()->find("weixin_openid=:openid",array(":openid"=>$userid));
        if(!$bindData){
            $this->errorMessage(array("title"=>"还未绑定账号","msg"=>"您的微信还没有和速推平台绑定。"));
        }
        $this->render("reg_ok");
    }
    public function actionWithdrawOk(){
        $this->pageTitle = "提现";
        $request = Yii::app()->request;
        $userid = $request->getParam("userid",'');
       // $userid='oFspSwazn4KfZCZkMsa23R7WlmLI';
        if($userid == ""){
            //错误页面
            $this->errorMessage(array("title"=>"发生错误","msg"=>"无效的参数"));
        }
        //查询是否已经绑定
        $bindData = Member::model()->find("weixin_openid=:openid",array(":openid"=>$userid));
        if(!$bindData){
            $this->errorMessage(array("title"=>"还未绑定账号","msg"=>"您的微信还没有和速推平台绑定。"));
        }
        $this->render("withdraw_ok");
    }
    /*
     * 提现操作页面
     * */
    public function actionWithdraw(){
        $this->pageTitle = "提现";
        $request = Yii::app()->request;
        $userid = $request->getParam("userid",'');
        $date = date("j",time());
        //$date=12;
        if($userid == ""){
            //错误页面
            $this->errorMessage(array("title"=>"发生错误","msg"=>"无效的参数"));
        }
        if($date > 15 || $date < 10){
            $this->errorMessage(array("title"=>"抱歉","msg"=>"联盟每月10-15号可申请提现"));
        }
        //获取用户信息
        $userData = Weixin::getUserData($userid,$this->token);
        if(!is_array($userData) ||  (is_array($userData)  && array_key_exists("errcode",$userData))){
            //错误
            $this->errorMessage(array("title"=>"发生错误","msg"=>"获取用户信息失败"));
        }
        if($userData['subscribe'] == 0){
            //未关注公众号
            $this->errorMessage(array("title"=>"抱歉","msg"=>"您还没有关注本公众号，微信号：sutuiapp"));
        }
        //查询是否已经绑定
        $bindData = Member::model()->find("weixin_openid=:openid",array(":openid"=>$userid));
        if(!$bindData){
            $this->errorMessage(array("title"=>"发生错误","msg"=>"您的微信还未和速推平台绑定"));
        }
        $modelBill = MemberBill::model()->getByUid($bindData->id);
        $this->render("withdraw",array('user'=>$userData,'surplus'=>$modelBill->surplus));
    }
    /*
     * 提现ajax
     * */
    public function actionAjaxWithdraw(){
        $request = Yii::app()->request;
        $userid = $request->getParam("userid",'');
        if($userid == ""){
            //错误页面
            $this->returnErrorJson(400,'发生错误','无效的参数');
        }
        $date = date("j",time());
        //$date=12;
        if($date > 15 || $date < 10){
            $this->returnErrorJson(400,'抱歉','联盟每月10-15号可申请提现');
        }
        //获取用户信息
        $userData = Weixin::getUserData($userid,$this->token);
        if(!is_array($userData) ||  (is_array($userData)  && array_key_exists("errcode",$userData))){
            //错误
            $this->returnErrorJson(400,'发生错误','获取用户信息失败');
        }
        if($userData['subscribe'] == 0){
            //未关注公众号
            $this->returnErrorJson(400,'抱歉','您还没有关注本公众号，微信号：sutuiapp');
        }
        //查询是否已经绑定
        $bindData = Member::model()->find("weixin_openid=:openid",array(":openid"=>$userid));
        if(!$bindData){
            $this->returnErrorJson(400,'发生错误',"您的微信还未和速推平台绑定");
        }

        //增加提现功能 判断 + 修改数据库
        $uid=$bindData->id;
        $date = date('Y-m', time());
        $modelPalylog = MemberPaylog::model()->getByUidAndDate($uid, $date);
        if (!is_null($modelPalylog)) {
            $this->returnErrorJson(400,'抱歉','您本月已提交过申请');
        }
        //计算手续费
        //Ad::computeFees($price);
        $fee = 0; //2013-12-23起免收手续费
        //支付金额
        $price = $request->getPost('money');
        $pay = $price - $fee;
        if ($price <= 0) {
            $this->returnErrorJson(400,'发生错误',"提现金额输入错误，不能≤0");
        }
        if (empty($price)) {
            $this->returnErrorJson(400,'发生错误',"程序错误，没有提现数值");
        }
        if ($price < 50) {
            $this->returnErrorJson(400,'发生错误',"提现金额输入错误，提现金额必须≥50元");
        }
        $memberBill = MemberBill::model()->getByUid($uid);
        if ($price > $memberBill->surplus) {
            $this->returnErrorJson(400,'发生错误',"提现金额输入错误，大于可提现的余额");
        }
        if($bindData['bank']=="" || $bindData['bank_no']=="" || $bindData['bank_site']=="" || $bindData['holder']==""){
            $this->returnErrorJson(400,'发生错误',"请先到会员后台填写财务信息再进行财务提现");
        }

        //添加提现记录
        $t = Yii::app()->db->beginTransaction();
        try {
            MemberPaylog::model()->addOne($uid, $pay, $price);
            MemberBill::model()->setPay($uid, $price, $pay);
            $t->commit();
            //调用申请提现成功模板，发送消息
            Weixin::handleTemplatec($userid,$bindData['username'],$bindData['bank'],$bindData['bank_no'],$bindData['holder'],$price );
            $this->returnErrorJson(200,'恭喜你','申请提现成功');
        } catch (Exception $e) {
            $t->rollback();
            $this->returnErrorJson(400,'抱歉','申请提现失败');
        }
    }
    /*
     * 每天收益详情
     * */
    public function actionDaysEarnings(){
        $this->pageTitle = "收益明细";
        $request = Yii::app()->request;
        $userid = $request->getParam("userid",'');
        if($userid == ""){
            //错误页面
            $this->errorMessage(array("title"=>"发生错误","msg"=>"无效的参数"));
        }
        $userData = Weixin::getUserData($userid,$this->token);
        if(!is_array($userData) ||  (is_array($userData)  && array_key_exists("errcode",$userData))){
            //错误
            $this->returnErrorJson(400,'发生错误','获取用户信息失败');
        }
        if($userData['subscribe'] == 0){
            //未关注公众号
            $this->returnErrorJson(400,'抱歉','您还没有关注本公众号，微信号：sutuiapp');
        }
        //查询是否已经绑定
        $bindData = Member::model()->find("weixin_openid=:openid",array(":openid"=>$userid));
        if(!$bindData){
            $this->returnErrorJson(400,'发生错误',"您的微信还未和速推平台绑定");
        }
        $date = date('Y-m-d', strtotime('-1 day'));//昨日收益
        //昨日收益计算
        $adList = Product::model()->getKeywordList();
        //判断业务隐藏开关
        $datetime=SystemLog::getLogDate($date);
        $model=SystemLog::model()->findAll("type=:type and date =:date and status=1 and is_show=0",
            array(":type"=>SystemLog::TYPE_UPLOAD,":date"=>$datetime));
        if($model){
            foreach($model as $v){
                $key=strtolower($v->target);
                unset($adList[$key]);
            }
        }
        $data = MemberIncome::getDataProviderByDate($bindData->id, $date, $bindData->scale, $adList);
        $this->render("days_earnings",array('data'=>$data,'adList'=>$adList,'bindData'=>$bindData));
    }
    /*
   * 历史收益详情
   *
   * 2017-10-10
   */
    public function actionWeeksEarnings(){
        $this->pageTitle = "历史收益";
        $request = Yii::app()->request;
        $userid = $request->getParam("userid",'');
        $scale=$request->getParam("scal",'');
        $openid=$request->getParam("openid",'');
        // $userid=23;
        // $scale=0;
        if($openid == ""){
            //错误页面
            $this->errorMessage(array("title"=>"发生错误","msg"=>"无效的参数"));
        }
        $userData = Weixin::getUserData($openid,$this->token);
        if(!is_array($userData) ||  (is_array($userData)  && array_key_exists("errcode",$userData))){
            //错误
            // $this->returnErrorJson(400,'发生错误','获取用户信息失败');
            $this->errorMessage(array("title"=>"发生错误","msg"=>"获取用户信息失败"));
        }
        if($userData['subscribe'] == 0){
            //未关注公众号
            // $this->returnErrorJson(400,'抱歉','您还没有关注本公众号，微信号：sutuiapp');
            $this->errorMessage(array("title"=>"发生错误","msg"=>"您还没有关注本公众号，微信号：sutuiapp"));
        }
        //查询是否已经绑定
        $bindData = Member::model()->find("weixin_openid=:openid",array(":openid"=>$openid));
        if(!$bindData){
            // $this->returnErrorJson(400,'发生错误',"您的微信还未和速推平台绑定");
            $this->errorMessage(array("title"=>"发生错误","msg"=>"您的微信还未和速推平台绑定"));
        }
        $adList = Product::model()->getKeywordList();//全部业务
        $sql="select date from `app_system_log` where type='UPLOAD' order by id desc limit 1 ";
        $result=yii::app()->db->createCommand($sql)->queryAll();
        $date=$result[0]['date'];
        for($i=0;$i<7;$i++){//七天
            $array[]=date('Y-m-d', strtotime('-'.$i.' day', strtotime($date)));
        }
        foreach ($array as $key => $value) {
            //判断业务隐藏开关
            // $datetime=SystemLog::getLogDate($value);
            $model=SystemLog::model()->findAll("type=:type and date =:date and status=1 and is_show=0",
            array(":type"=>SystemLog::TYPE_UPLOAD,":date"=>$value));
            if($model){
                foreach($model as $v){
                    $key=strtolower($v->target);
                    unset($adList[$key]);
                }
            }

            $datee=date('Y-m-d',strtotime('-1 days',strtotime($value)));
            $data[] = MemberIncome::getDataProviderByDate($userid, $datee, $scale, $adList);
        } 
        // print_r($adList);exit;   
        $this->render("weeks_earnings",array('data'=>$data,'scale'=>$scale,'userid'=>$userid));
    }
    /**
     * 业务收益详情
     * 2017-10-10
     * @return [type] [description]
     */
    public function actionEarndetail(){
        $this->pageTitle = "业务收益详情";
        $request = Yii::app()->request;
        $date = $request->getParam("date",'');
        $scale=$request->getParam("scale",'');
        $userid=$request->getParam("userid",'');
        $adList = Product::model()->getKeywordList();//全部业务
        $sql="select date from `app_system_log` where type='UPLOAD' order by id desc limit 1 ";
        $result=yii::app()->db->createCommand($sql)->queryAll();
        $datee=$result[0]['date'];
        for($i=0;$i<7;$i++){//七天
            $array[]=date('Y-m-d', strtotime('-'.$i.' day', strtotime($datee)));
        }
        $date=date('Y-m-d',strtotime('+1 day',strtotime($date)));
        if(in_array($date,$array)){
            $model=SystemLog::model()->findAll("type=:type and date =:date and status=1 and is_show=0",
            array(":type"=>SystemLog::TYPE_UPLOAD,":date"=>$date));
            if($model){
                foreach($model as $v){
                    $key=strtolower($v->target);
                    unset($adList[$key]);
                }
            }
            $date=date('Y-m-d',strtotime('-1 day',strtotime($date)));
            $data[] = MemberIncome::getDataProviderByDate($userid, $date, $scale, $adList);
        }else{
            $data=array();
        }
        $this->render('earndetail',array('data'=>$data,'date'=>$date));

        
    }
    /*
    * 每天收益详情
    * */
    public function actionYesdaysEarnings(){
        $this->pageTitle = "收益明细";
        $request = Yii::app()->request;
        $userid = $request->getParam("weixin_openid",'');
        $date = $request->getParam("date",'');
        if($userid == "" || $date==""){
            //错误页面
            $this->errorMessage(array("title"=>"发生错误","msg"=>"无效的参数"));
        }
        $userData = Weixin::getUserData($userid,$this->token);
        if(!is_array($userData) ||  (is_array($userData)  && array_key_exists("errcode",$userData))){
            //错误
            $this->returnErrorJson(400,'发生错误','获取用户信息失败');
        }
        if($userData['subscribe'] == 0){
            //未关注公众号
            $this->returnErrorJson(400,'抱歉','您还没有关注本公众号，微信号：sutuiapp');
        }
        //查询是否已经绑定
        $bindData = Member::model()->find("weixin_openid=:openid",array(":openid"=>$userid));
        if(!$bindData){
            $this->returnErrorJson(400,'发生错误',"您的微信还未和速推平台绑定");
        }
        //昨日收益计算
        $adList = Product::model()->getKeywordList();
        //判断业务隐藏开关
        $datetime=SystemLog::getLogDate($date);
        $model=SystemLog::model()->findAll("type=:type and date =:date and status=1 and is_show=0",
            array(":type"=>SystemLog::TYPE_UPLOAD,":date"=>$datetime));
        if($model){
            foreach($model as $v){
                $key=strtolower($v->target);
                unset($adList[$key]);
            }
        }
        $data = MemberIncome::getDataProviderByDate($bindData->id, $date, $bindData->scale, $adList);
        $this->render("days_earnings",array('data'=>$data,'adList'=>$adList,'bindData'=>$bindData));
    }
    protected function errorMessage($message){
        $this->render("error_message",array("message"=>$message));
        exit;
    }
    protected function returnErrorJson($status,$title,$msg){
        header('Content-type: application/json');
        $json['status'] = $status;
        $json['title'] = $title;
        $json['msg'] = $msg;
        exit(CJSON::encode($json));
    }
    
}