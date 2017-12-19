<?php

class SiteController extends Controller
{
	//public $layout='column1';

	/**
	 * Declares class-based actions.
	 */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'maxLength' => 4,
                'minLength' => 4,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }
    public function actionIndex()
    {
        $this->pageTitle = "速推APP推广联盟 - 值得信赖的手机APP推广平台";
        Yii::app()->clientScript->registerMetaTag("速推,sutuiapp,APP推广,应用推广,手机预装联盟,APP推广平台,速推APP推广联盟",'keywords');
        Yii::app()->clientScript->registerMetaTag('速推APP推广联盟是一家专注于手机APP推广服务的公司，拥有丰富的渠道分发、手机预装、ROM内置等推广经验。致力于为APP开发者、ROM开发者、手机销售门店 、手机售后维修人员提供更优质、更安全的APP应用软件推广服务。','description');
        /*if (Common::isMobile()) {
            Yii::app()->request->redirect('http://m.sutuiapp.com');
        }*/
        if (!defined('CRYPT_BLOWFISH')||!CRYPT_BLOWFISH)
            throw new CHttpException(500,"This application requires that PHP was compiled with Blowfish support for crypt().");

        //Product list
        $model = new Product();
        $criteria = new CDbCriteria();
        $criteria->compare('id',array(1,36,41,40,9,11,14,37,21,55));
        $data = $model->findAll($criteria);

        $this->render("index",array('model'=>$model,"data"=>$data));
    }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->renderPartial('error', $error);
	    }
	}
    public function actionReg()
    {
        $this->pageTitle = "注册账号";
        $model = new Member('reg');
        $this->memberReg($model);
        $this->render("reg",array("model"=>$model));
    }
    public function actionTgzc()
    {
        $this->pageTitle = "手机注册账号";
        $model = new Member('tgzc');
        $this->memberReg($model);
        $this->render("tgzc",array("model"=>$model));
    }
    /*
     * 注册
     * */
    public   function memberReg($model)
    {
        /*if (isset($_POST['ajax']) && $_POST['ajax'] === 'reg-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['Member'])) {
            $model->attributes = $_POST['Member'];
            if ($model->validate() && $model->save()) {
                $this->redirect("/login");
            }
        }*/
        if(isset($_POST['Member'])){
            $model->attributes = $_POST['Member'];
            if($model->validate()){
                //获取cookes里的代理商
                $domain = Common::getUrlDomain();
                $utm_source="";
                if (empty($domain)) {
                    $cookies = Yii::app()->request->getCookies();
                    $domain = isset($cookies['agent']) ? $cookies['agent']->value : '';
                    $utm_source = isset($cookies['utm_source']) ? $cookies['utm_source']->value : '';
                }
                $agent = null;
                if (!empty($domain)) {
                    $agent = Member::model()->getByAlias($domain);
                    $agent = Member::model()->findByPk($domain-123456);
                }

                $model->father_id = is_null($agent) ? 0 : $agent->id;//设置该用户的代理商
                $model->source_id = $utm_source;//推广来源
                $model -> username = $_POST['Member']['username'];
                $model -> password = md5(strrev(md5(strrev(trim($_POST['Member']['password'])))));
                $model -> tel = $_POST['Member']['tel'];
                $model -> regist_tel = $_POST['Member']['tel'];
                $model -> qq = $_POST['Member']['qq'];
                if(empty($_POST['Member']['weixin_name']))
                {
                    $_POST['Member']['weixin_name']="";
                }
                $model -> weixin_name = $_POST['Member']['weixin_name'];
                if($_POST['Member']['type']=="9")
                {
                    $model -> type =0;
                }
                $model -> jointime = time();
                $model -> overtime = time();
                $model -> reg_ip = Yii::app()->request->userHostAddress;
/*                if($_POST['Member']['type']=="3"){
                    $model -> agent = 77;
                }*/
                if($_POST['Member']['type']=="4"){
                    $_POST['Member']['type']="8";
                    $model -> type =8;
                }
                if($_POST['Member']['type']=="8"){
                    $agent=99;
                    //只有线下门店需要邀请码
                    if(isset($_POST['Member']['invitationcode']) && !empty($_POST['Member']['invitationcode'])){
                        $code1=substr($_POST['Member']['invitationcode'], 2, 4);
                        $code2=substr($_POST['Member']['invitationcode'], 0, 2)=='st'?1:2;
                        $invitationcode=Invitationcode::model()->find("code=:code and status=1",array(":code"=>$_POST['Member']['invitationcode']));
                        if($invitationcode){
                            if($invitationcode->mid==0){
                                //获取注册用户分组
                                $agent = $invitationcode->agent;
                                $model->subagent =$code1;
                                $model->sign =$code2;
                                $model -> invitationcode = 'st0001';
                            }else{
                                $model -> invitationcode = $_POST['Member']['invitationcode'];
                            }
                        }
                        //$model->father_id =$code1;
                    }
                    $model -> agent = $agent;
                }

                //&& $_POST['Member']['invitationcode']=="st9900"邀请码目前去掉了
/*                if($_POST['Member']['type']=="3" ){
                    $model -> subagent = 760;
                }*/
                if($model->insert()){

                    $uid=Yii::app()->db->getLastInsertID();
                    if (!empty($domain)) {
                        $credits=1000;
                        Member::model()->updateCounters(array('credits' => $credits)," id = $uid");
                        $this->addCredits($credits,$uid,$account_credits=0);
                    }

                    Member::model()->updateAll(array('alias' => $model->username.$uid)," id = $uid");

                    if($utm_source !=''){
                        $id= intval($utm_source);
                        $model_source = SpreadSource::model();
                        $model_source->updateCounters(array("source_reg"=>1),"id={$id}");//渠道注册人数+1
                    }
                    //添加注册log--可用
                    /*$reg_log_content = "[Log Time] ".date("Y-m-d H:i:s")."\r";
                    $reg_log_content .= "[用户id] ".$uid."\r";
                    $reg_log_content .= "[用户名] ".$_POST['Member']['username']."\r";
                    $reg_log_content .= "[邀请码] ".$model->invitation_code."\r";

                    $reg_log_content .= "\n\r";
                    $reg_log_file  = "log/reg_log.txt";//要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
                    if(file_put_contents($reg_log_file, $reg_log_content,FILE_APPEND)){// 这个函数支持版本(PHP 5)
                        //echo "写入成功。<br />";
                    }*/

                    //删除代理商cookies和推广来源
                    $cookies = Yii::app()->request->getCookies();
                    $cookies->remove('agent');
                    $cookies->remove('utm_source');

                    //注册同时去录入客户资料表比对数据，判断同手机、同QQ为注册状态，并将录入表的咨询记录导入到会员表
                    $tel=$_POST['Member']['tel'];
                    $qq=$_POST['Member']['qq'];

                    //判断用户已注册，更改录入注册状态
                    if($_POST['Member']['invitationcode']!=""){
                        $search_id=Common::getInvitationcode($_POST['Member']['invitationcode']);
                        if(!empty($search_id))
                        {
                            $reg_user = SerachInfo::model()->find('username=:username and search_id=:search_id',array(':username'=>$_POST['Member']['username'],":search_id"=>$search_id));
                            if(!empty($reg_user))
                            {
                                $reg_user->reg_status=1;
                                $reg_user->update();
                            }
                        }
                    }
                    else
                    {
                        $oldtel = SerachInfo::model()->findByAttributes(array('tel'=>$tel));
                        if(!empty($oldtel)){
                            SerachInfo::model()->updateAll(array( 'reg_status' => 1 ), " tel = ( " . $tel . " )");
                        }
                        $oldqq = SerachInfo::model()->findByAttributes(array('qq'=>$qq));
                        if(!empty($oldqq)){
                            SerachInfo::model()->updateAll(array( 'reg_status' => 1 ), " qq = ( " . $qq . " )");
                        }

                        if(!empty($oldtel) && !empty($oldqq))
                        {
                            $telid = SerachInfo::model()->findAllBySql('select id from app_serach_info where tel='.$tel);

                            $records_all = SerachinfoRecords::model()->findAllBySql('select * from app_serachinfo_records where sid='.$telid["0"]["id"]);
                            $len=count($records_all);

                            if($len>0)
                            {
                                for($i=0;$i<$len;$i++){
                                    $model = new AdvisoryRecords();

                                    $mid=$records_all[$i]["mid"];
                                    $content=$records_all[$i]["content"];
                                    $jointime=$records_all[$i]["jointime"];

                                    $model->uid = $uid;
                                    $model->mid = $mid;
                                    $model->content = $content;
                                    $model->jointime = $jointime;
                                    $model->insert();
                                }
                            }

                        }
                        else if(!empty($oldtel) || !empty($oldqq))
                        {
                            if(!empty($oldtel))
                            {
                                $telid = SerachInfo::model()->findAllBySql('select id from app_serach_info where tel='.$tel);

                                $records_all = SerachinfoRecords::model()->findAllBySql('select * from app_serachinfo_records where sid='.$telid["0"]["id"]);
                                $len=count($records_all);

                                if($len>0)
                                {
                                    for($i=0;$i<$len;$i++){
                                        $model = new AdvisoryRecords();

                                        $mid=$records_all[$i]["mid"];
                                        $content=$records_all[$i]["content"];
                                        $jointime=$records_all[$i]["jointime"];

                                        $model->uid = $uid;
                                        $model->mid = $mid;
                                        $model->content = $content;
                                        $model->jointime = $jointime;
                                        $model->insert();
                                    }
                                }
                            }

                            if(!empty($oldqq))
                            {
                                $telid = SerachInfo::model()->findAllBySql('select id from app_serach_info where qq='.$qq);

                                $records_all = SerachinfoRecords::model()->findAllBySql('select * from app_serachinfo_records where sid='.$telid["0"]["id"]);
                                $len=count($records_all);

                                if($len>0)
                                {
                                    for($i=0;$i<$len;$i++){
                                        $model = new AdvisoryRecords();

                                        $mid=$records_all[$i]["mid"];
                                        $content=$records_all[$i]["content"];
                                        $jointime=$records_all[$i]["jointime"];

                                        $model->uid = $uid;
                                        $model->mid = $mid;
                                        $model->content = $content;
                                        $model->jointime = $jointime;
                                        $model->insert();
                                    }
                                }
                            }
                        }
                    }
                    Yii::app()->user->setFlash("reg_ok","恭喜你，注册成功！接下来就登录账号准备赚钱吧^_^");
                    $this->redirect("/login");
                }
            }
        }

    }
    protected  function addCredits($credits,$uid,$account_credits){
        $credits_model = new MemberCreditsLog();
        $credits_model->create_datetime = time();
        $credits_model->memberId = $uid;
        $credits_model->credits = $credits;
        $credits_model->remarks = "推广链接用户注册获赠{$credits}积分";
        $credits_model->opid = 0;
        $credits_model->source ='system';
        $credits_model->account_credits =$credits+$account_credits;
        $credits_model->save();
    }


    /*
 * 推广注册移动端端
 * */
    public function actionTgzcPhone(){
        $this->layout = false;
        $model = new Member('reg');
        $this->render("tgzcPhone",array("model"=>$model));
    }
    /*
     * 推广注册PC端
     * */
    public function actionTgzcPC(){
        $this->layout = "tgzc";
        $model = new Member('reg');
        $this->render("tgzc",array("model"=>$model));
    }
    /*
     * 管理员登陆
     * */
    public function actionAdmincc()
    {
        $this->layout = "login";
        $this->pageTitle = "用户登录";
        $model = new ManageLoginForm();
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['ManageLoginForm'])) {
            $model->attributes = $_POST['ManageLoginForm'];
            if ($model->validate() && $model->login()) {
                 $this->redirect(array('/dhadmin'));
            }
        }
        //$this->renderPartial("admincp");
        $this->render("admincp",array('model'=>$model));
    }

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
        $this->pageTitle = "用户登录";
		if (!defined('CRYPT_BLOWFISH')||!CRYPT_BLOWFISH)
			throw new CHttpException(500,"This application requires that PHP was compiled with Blowfish support for crypt().");

		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{

			$model->attributes=$_POST['LoginForm'];
			$member_login = $_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				//$this->redirect(Yii::app()->user->returnUrl);
            {
				//微信登录通知
                $member = Member::model()->getByUserName($member_login['username']);
                $openid = $member->weixin_openid;
                $username = $member->username;
                $ip = Common::getIp();// 登录电脑ip地址
                if (!empty($openid) && $openid != "NULL") {
                    $t = Weixin::handleTemplateByLogin($openid,$username, $ip);
                    // 登录通知.存入数据库
                    $t = json_decode($t, true);
                    // 到账通知.存入数据库
                    if ($t['errcode'] == 0) {
//                        $message = new MessageLog();
//                        $message->theme = '登录通知';
//                        $message->mid = $member->id;
//                        $message->openid = $openid;
//                        $message->username = $username;
//                        $message->ip = $ip;
//                        $message->category = 2;
//                        $message->createtime = time();
//                        $message->send_type = 1;
//                        $message->insert();
                    }
                }
				
				
                $utyped=Yii::app()->user->getState('type');
                if($utyped=="Member" || $utyped=="Agent" || $utyped=="Synthsize")
                {
                    $this->redirect(array('/member/default/index'));
                }
                elseif($utyped=="Ditui")
                {
                    $this->redirect(array('/ditui/default/index'));
                }
                elseif($utyped=="Newdt")
                {
                    $this->redirect(array('/newdt/default/index'));
                }
                elseif($utyped=="Dealer")
                {
                    $this->redirect(array('/dealer/default/index'));
                }
                elseif($utyped=="Msg")
                {
                    $this->redirect(array('/msg/default/index'));
                }
                else
                {
                    $this->redirect("/product/review");
                }
				
            }

		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

    /**
	 * Logs out the current user and redirect to homepage.
     * logout中的两个参数都是后加的，和framework/web/auth/CWebUser的logout方法关联，修改日期2017-10-19
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout(true,true);
		$this->redirect(Yii::app()->homeUrl);
	}
    /**
     * 管理员登录用户
     */
    public function actionMlogin($uid)
    {
        $ic = base64_decode($uid);
        $ie = explode('_', $ic);
        $uid = $ie[0];
        $erg = substr($ie[1], 0, 6);

        if (!Auth::check('member_mlogin')){
            throw new CHttpException(404, '没有找到相关页面');
        }

        if ($erg != "userid") {
            throw new CHttpException(404, '没有找到相关页面');
        }

        $model = Member::model()->getById($uid);
        if (is_null($model)) {
            throw new CHttpException(404, '没有找到相关页面');
        }

        $identity = new UserIdentity($model->username, $model->password);
        $identity->setUid($model->id);
        //$identity->setHolder($model->holder);

        $user = Yii::app()->user;
        $user->login($identity, 3600 * 3);
        $user->setState('member_uid', $model->id);
        $user->setState('member_username', $model->username);
        $user->setState('member_agent', $model->agent);
        $user->setState('member_manage', true); //是否管理员登陆
        $user->setState('member_cooperate', '');
        $user->setState('type', Common::USER_TYPE_MANAGE);
        $user->setState('member_type',  $model->type);
        if($model->type==3)
        {
            $this->redirect(array('/ditui/product/index'));
        }
        elseif($model->type==0 || $model->type==1 || $model->type==2)
        {
            $this->redirect(array('/member/product/index'));
        }
        elseif($model->type==8)
        {
            $this->redirect(array('/newdt/product/index'));
        }
        elseif($model->type==4)
        {
            $this->redirect(array('/dealer/product/index'));
        }
        elseif($model->type==5)
        {
            $this->redirect(array('/msg/product/index'));
        }
        else
        {
            $this->redirect("/");
        }

    }
}
