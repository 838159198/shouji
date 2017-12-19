<?php

/**
 * 财务管理
 */
class PayController extends DhadminController
{
    /**
     * @var string[]
     */
    protected $memberCategoryNames;

    public function actionGetLateResourceIncome($type, $date)
    {

    }

    /**
     *  财务首页
     */
    public function actionIndex()
    {
        $this->render('index');
    }

    /**
     * @param $status
     *  查看提现申请与支付名单
     */
    public function actionMember($status,$date='')
    {
       // Script::registerScriptFile('manage/manage.pay.member.js');
        if(empty($date)){
            $date = date('Y-m');
        }else{
            $date = Yii::app()->request->getParam('date');
        }
        $data = MemberPaylog::model()->getDataProviderByDate($date, $status);
        $sqlex ='SELECT sum(sums) as su FROM `app_member_paylog` where status='.$status.' and ask_time like \''.$date.'%\' and valid=1;';
        $sumdata = Yii::app()->db->createCommand($sqlex)->queryAll();


        $this->render('member', array(
            'data' => $data,
            'sumdata' => $sumdata,
            'status' => $status,
            'date'=>$date
        ));
    }
    /**
     * 导出未支付Excel--未使用
     *
     */
    public function actionExcel()
    {
        $date = date('Y-m');
        $mplList = MemberPaylog::model()->getListByDate($date, MemberPaylog::STATUS_FALSE);
        $data = array();
        foreach ($mplList as $mpl) {
            /* @var $mpl MemberPaylog */
            $data[] = array(
                'username' => $mpl->member->username,
                'sums' => $mpl->sums,
                'ask_time' => $mpl->ask_time,
                'holder' => $mpl->member->holder,
                'bank' => $mpl->member->bank,
                'bank_no' => ' ' . $mpl->member->bank_no,
                'bank_site' => $mpl->member->bank_site,
                'phone' => $mpl->member->tel,
            );
        }

        $excel = new Excel();
        $excel->download($excel->createExcel(
            Ad::getAdNameById(Ad::TYPE_SGTS) . date('Y-m-d'),
            array('用户', '提现金额', '申请时间', '收款人', '开户银行', '账号', '开户地址', '电话'),
            array('username', 'sums', 'ask_time', 'holder', 'bank', 'bank_no', 'bank_site', 'phone'),
            $data
        ));
    }

    /**
     * @param $id
     * @throws CHttpException
     * 支付功能
     */
    public function actionUpdate($id)
    {
        $t = Yii::app()->db->beginTransaction();
        try {
            $this->pay($id);
            $t->commit();
            $this->redirect(array('member', 'status' => MemberPaylog::STATUS_FALSE));
        } catch (Exception $e) {
            $t->rollback();
            throw new CHttpException(500, '支付出现错误，请重试');
        }
    }

    /**
     * @throws CHttpException
     * 批量支付
     */
    public function actionUpdates()
    {
        if (Yii::app()->request->isPostRequest === false) {
            throw new CHttpException(404, '无此页面');
        }

        $t = Yii::app()->db->beginTransaction();
        try {
            $rids = Yii::app()->request->getParam('rid');
            if (is_array($rids) === false) throw new Exception();

            foreach ($rids as $id) {
                if (empty($id)) continue;
                $this->pay($id);
            }

            $t->commit();
            echo 'success';
        } catch (Exception $e) {
            $t->rollback();
            echo 'error';
        }
    }


    /**
     * @throws CHttpException
     * 统计上月用户收入数据--发布收益
     */
    public function actionStats()
    {
        $log = Log::getSystemLog('MANAGE', SystemLog::TYPE_COUNT);
        if (Yii::app()->request->isPostRequest) {
            if (!empty($log)) {
                throw new CHttpException(500, '上月数据已统计');
            }

            //$username = $this->username;
            $username = Yii::app()->user->getState('username');
            $yearMonth = date('Y-m', strtotime('-1 month'));
            $str = '更新' . $yearMonth . '/月用户收益';
            $t = Yii::app()->db->beginTransaction();
            try {
                //用户id与收入合计列表
                $incomeList = MemberIncome::getAllMemberIncomeCount($yearMonth);
                $uidList = array_keys($incomeList);
                //向用户余额表中添加数据
                foreach ($uidList as $uid) {
                    $income = isset($incomeList[$uid]) ? $incomeList[$uid] : 0;
                    if (isset($memberScaleList[$uid])) {
                        $income = Ad::computeMemberSum($income, $memberScaleList[$uid]);
                    }

                    $member=Member::model()->getById($uid);
                    $memberlist=Member::model()->findAll("father_id=".$uid);
                    //如果该用户有推广的用户，计算提成:推广周期：3个月，用户推广的所有用户收益总和计算百分比20161228--子用户不扣钱平台补
                    if(!empty($memberlist))
                    {
                        $incomels=0;
                        foreach ($memberlist as $lkey=>$lval)
                        {
                            $memberalone=Member::model()->findByPk($lval["id"]);
                            if(round((time()-$memberalone["jointime"])/3600/24)<=93)
                            {
                                foreach ($incomeList as $lk => $lv)
                                {
                                    if($lval["id"] == $lk)
                                    {
                                        $incomels += $lv;
                                        break;
                                    }
                                }
                            }
                        }
                        $member_agent_income=Member::model()->getAgentIncome($incomels);
                        $member_agent_income=round($member_agent_income, 2);
                        $income = round($member_agent_income+$income, 2);

                        $connection = Yii::app()->db;
                        $datel=date('Y-m-d',time());
                        $sql = "INSERT INTO `app_import_agent_log` (`uid`, `month`, `data`, `date`) VALUES ('{$uid}','{$yearMonth}','{$member_agent_income}','{$datel}')";
                        $command=$connection->createCommand($sql);
                        $command->execute();

                    }

                    //如果该用户有代理商子用户(包括自己)，直接计算提成给代理商，子用户不扣钱20160109
                    $type=$member["type"];
                    $incomes=0;
                    $point=0;

                    if ($type==1)
                    {
                        $agent=$member["agent"];
                        $point=$member["point"];
                        $members=Member::model()->findAll("agent='$agent'");
                        foreach ($members as $key=>$val)
                        {
                            foreach ($incomeList as $k => $v)
                            {
                                if($val["id"] == $k)
                                {
                                    $incomes += $v;
                                    break;
                                }
                            }
                        }
                        $income = round($incomes*$point+$income, 2);
                    }
                    if ($income <= 0) continue;
                    MemberBill::model()->addSurplus($uid, $income);
                    //推送至微信接口
                    if(!empty($member->weixin_openid) && $member->weixin_openid!="NULL")
                    {
/*                        $username = $member->username;
                        Weixin::handleTemplateMonthIncome($member->weixin_openid,$income,$username);*/
                    }
                }

                //代理商子用户收益导入
                $sum=0;
                $subuser_income=RomSubagentdata::getSubIncome($agent=707);
                foreach($subuser_income as $item){
                    if($item['income']<=0) continue;
                    $sum += $item['cha'];
                    MemberBill::model()->addSurplus($item['uid'], $item['income']);
                }
                //代理商收益导入
                MemberBill::model()->addSurplus(707, $sum);

                $t->commit();
                Log::addSystemLog(SystemLog::TYPE_COUNT, $str, 'MANAGE', $username, SystemLog::STATUS_TRUE);
                $this->redirect('stats');
            } catch (Exception $e) {
                $t->rollback();
                Log::addSystemLog(SystemLog::TYPE_COUNT, $str, 'MANAGE', $username, SystemLog::STATUS_FALSE);
            }
        }
        $this->render('stats', array('log' => $log));
    }



    /**
     * @param $id
     * @return MemberPaylog
     * @throws CHttpException
     */
    private function loadModel($id)
    {
        $modle = MemberPaylog::model()->findByPk($id);
        if (is_null($modle)) throw new CHttpException(500, '错误，无此信息');
        return $modle;
    }

    /**
     * 具体支付
     * @param $id
     * @throws Exception
     */
    private function pay($id)
    {
        try {
            $mid = Yii::app()->user->manage_id;
            $paid=0;
            $model = $this->loadModel($id);
            $update=json_encode($model->attributes);
            $model->answer_time = date('Y-m-d H:i:s');
            $model->status = MemberPaylog::STATUS_TRUE;
            $update=json_encode($model->attributes);//没修改之前的数据
            $model->update();
            /*收入操作日志 start 2017-11-15*/
            $content='表[member_paylog]中修改数据为'.json_encode($model->attributes);
            $ip=$_SERVER['SERVER_ADDR'];
            $title='月提现申请与支付';
            $before_content=$update;
            Income::addlogincome($mid,$model->uid,$content,$ip,$before_content,$title);
            /*收入操作日志 end*/


            //日志记录
            $detail=' [manage] '.$mid.' [update] [uid] '.$model->uid.' [status] 1 [answer_time] '.date('Y-m-d H:i:s');
            NoteLog::addLog($detail,$mid,$model->uid,$tag='支付提现申请',$update);
            
            $bill = MemberBill::model()->getByUid($model->uid);
            $update=json_encode($bill->attributes);
            $money=$bill->nopay;
            $bill->paid += $bill->nopay;
            $paid += $bill->nopay;
            $bill->nopay = 0;
            $bill->update();
            //日志记录
            $detail=' [manage] '.$mid.' [update] [uid] '.$model->uid.'[nopay] 0 [paid] '.$paid;
            NoteLog::addLog($detail,$mid,$model->uid,$tag='支付提现申请',$update);

            /*收入操作日志 start 2017-11-15*/
            $content='表[member_bill]中数据修改为'.json_encode($bill->attributes);
            $ip=$_SERVER['SERVER_ADDR'];
            $title='月提现申请与支付';
            $before_content=$update;
            Income::addlogincome($mid,$bill->uid,$content,$ip,$before_content,$title);
            /*收入操作日志 end*/

            //推送至微信接口
            $member=Member::model()->getById($bill->uid);
            $openid = $member->weixin_openid;
            $username = $member->username;
            if(!empty($openid) && $openid!="NULL")
            {
                $date = date('Y-m', strtotime('-1 month'));
                Weixin::handleTemplateByPay($openid,$username,$money,$date);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 月财务统计
     */
    public function actionStatement()
    {
        $request = Yii::app()->request;
        $year = $request->getQuery('year');
        $month = $request->getQuery('month');
        $username = $request->getQuery('username');
        $typeas = $request->getQuery('typeas');

        $year = empty($year) ? date('Y') : $year;
        $month = empty($month) ? date('m') : $month;

        $yearMonth = $year . '-' . $month;

        //$memberIncomeList = MemberIncome::getAllMemberIncomeSumByYearMonth($yearMonth);
        /*
         * 修改内容
         * 加入上月数据对比
         * */
        if(empty($username)){
            $uid = "0";
        }else{
            $user_data = Member::model()->getByUserName($username);
            if(empty($username)){
                $uid = "0";
            }else{
                $uid = $user_data['id'];
            }
        }
        $memberIncomeList = MemberIncome::getAllMemberIncomeSumByYearMonthContrast($yearMonth,$uid,$typeas);

        //统计上月所有收益用户名
/*        $us="";
        foreach($memberIncomeList as $ak=>$av)
        {
            if($av>100)
            {
                $us=$us.",'".$av['username']."'";
            }

        }
        print_r($us);exit;*/
        /*for ($i = 0; $i < $length; $i++) {
            $row = $memberIncomeList[$i];
            unset($row['id'], $row['username'], $row['holder']);
            $memberIncomeList[$i]['sum'] = array_sum($row);
        }*/

        //线上线下用户做区分
        $xxuids=Member::model()->findAll('type=8 and status=1');
        $uids="";
        $uidsyes="";
        $uidsno="";
        foreach($xxuids as $xk=>$xv)
        {
            if(!empty($xv["invitationcode"]))
            {
                $uidsyes=$uidsyes.",".$xv["id"];
            }
            else
            {
                $uidsno=$uidsno.",".$xv["id"];
            }
            $uids=$uids.",".$xv["id"];
        }
        $uidsyes = substr($uidsyes, 1, (strlen($uidsyes) - 1));
        $uidsno = substr($uidsno, 1, (strlen($uidsno) - 1));
        $uids = substr($uids, 1, (strlen($uids) - 1));


        //$incomeList = MemberIncome::getAllIncomeSumByYearMonth($yearMonth);
        //修改版，获取当月和上月合计对比
        //$type=1线下
        $incomeListxx = MemberIncome::getAllIncomeSumByYearMonthNew($yearMonth,$uids,$uidsyes,$uidsno,$type=1);
        $incomeListxx[0][0]['id'] = 0;
        $xxsumyes=$incomeListxx[1];
        $xxsumno=$incomeListxx[2];
        $incomeListxx=$incomeListxx[0];

        //$type=0线上
        $incomeList = MemberIncome::getAllIncomeSumByYearMonthNew($yearMonth,$uids,$uidsyes="",$uidsno="",$type=0);
        $incomeList[0][0]['id'] = 0;
        $incomeList=$incomeList[0];

        $incomeSum = MemberIncome::getAllIncomeSumByYearMonthTotal($yearMonth,$xxsumyes,$xxsumno);
        $this->render('statement', array(
            'year' => $year,
            'month' => $month,
            'typeas' => $typeas,
            'memberIncomeData' => new CArrayDataProvider($memberIncomeList, array(
                'pagination' => array(
                    'pageSize' => Common::PAGE_SIZE,
                ),
            )),
            'data' => new CArrayDataProvider($incomeList),
            'dataxx' => new CArrayDataProvider($incomeListxx),
            'incomeSum' => $incomeSum,
        ));
    }

    /**
     * 用户收益补入
     * @param string $username
     * @param string $type
     * @param string $date
     */
    public function actionMendIncome($username='',$type='',$date=''){
        $username =  preg_replace('# #', '', $username);
        $dateY = $date ==''? date('Y-m-d',strtotime('-1 day')):$date;
        $a=2;// 默认状态,不显示封账状态
        $i=2;// 默认状态,不显示导入状态
        $price = 0;
        $arr = $this::actionSystemlog();// 获取所有封账的年月
        if (!empty($username)){
            $a=0;// 未封账
            $i=0;// 未导入
            // 判断是否封账
            $mon = date('Y-m',strtotime($dateY));
            if (in_array($mon,$arr)){
                $a= 1;// 已封账
            }
            // 判断是否导入
            $logi = Log::getTargetBySystemLog($type, SystemLog::TYPE_UPLOAD,$dateY);
            if (!empty($logi)){
                if (strpos($logi[0]['content'],$dateY) !== false){
                    $i= 1;// 已导入
                }
            }

            $price = Product::model()->getPriceByType($type);

        }

        
        $this->render('mendincome',array('type'=>$type,'username'=>$username,'date'=>$dateY,'a'=>$a,'i'=>$i,'price'=>$price,'arr'=>json_encode($arr)));
    }

    /**
     * 获取所有封账年月
     * @return array
     */
    private function actionSystemlog()
    {
       $data= Log::getCountLog('MANAGE',SystemLog::TYPE_COUNT);
        $array =array();
       foreach ($data as $vt){
           $patterns = "/\d+/";
           $content = $vt['content'];
           preg_match_all($patterns,$content,$arr);
           $array[] = $arr[0][0].'-'.$arr[0][1];
       }
        return $array;
    }

    /**
     * 收益修改ajax
     */
    public function actionUpdateincome(){

        if(Yii::app()->request->isAjaxRequest){

            $username = Yii::app()->request->getParam('username');
            $type = Yii::app()->request->getParam('type');
            $date = Yii::app()->request->getParam('date');
            $num = Yii::app()->request->getParam('num');
            $price = Yii::app()->request->getParam('price');
            $note = Yii::app()->request->getParam('note');


            $mid = Yii::app()->user->manage_id; // 管理员id
            $manager = Yii::app()->user->username;
            $AdList = Ad::getAdList();// 业务列表
            $member = new Member();
            // 通过用户名获取用户uid
            $data = $member->getByUserName($username);
            if (empty($data)){
                exit(CJSON::encode(array("val" => 'u')));// 用户名不存在
            }
            // 获取用户状态---判断用户是否被封号
            $status = $data->status;
            if ($status==0){
                exit(CJSON::encode(array("val" => 'f'))); //用户已封号
            }
            // 获取用户uid
            $uid = $data->id;
            // 获取用户类型
            $channel = $data->type;
            // 获取资源关系ID
            $resource = MemberResource::model()->findAllByAttributes(array('uid'=>$uid,'type'=>$type,'status'=>1));
            if (empty($resource)){
                exit(CJSON::encode(array("val" => 'm'))); //资源关系ID不存在,即此用户的该业务已被封
            }
            $mrid = $resource[0]['key'];// 资源关系ID
            $t = Yii::app()->db->beginTransaction();
            try{
                // 创建业务model
                $model = IncomeFactory::factory($type);
                $income = $model->findAllByAttributes(array('uid'=>$uid,'mrid'=>$mrid,'createtime'=>$date));

                // 操作记录
                $mend = new MendIncomeLog();
                $mend->uid = $uid;
                $mend->username = $username;
                $mend->mid = $mid;
                $mend->manager = $manager;
                $mend->channel = Member::getXtype($channel);
                $mend->type = $type;
                $mend->typename = $AdList[$type];
                $mend->income_date = $date;
                $mend->operatime = date('Y-m-d');
                $mend->stamptime = time();
                $mend->mend_data = $num*$price;
                $mend->mend_num = $num;
                $mend->note = $note;
                $mend->sign = 1; // 1:代表手机补入, 2:余额补入


                if (empty($income)){
                    $model->setIsNewRecord(true);
                    $model->uid = $uid;
                    $model->mrid = $mrid;
                    $model->data = $num * $price;
                    $model->createtime = $date;
                    $model->status = 1;
                    if ($model->insert()){
                        $mend->pre_data = 0;
                        $mend->after_data = $num*$price;
                        $mend->insert();
                    }
                }else{
                    if ($income[0]['status']==0){
                        exit(CJSON::encode(array("val" => 'f'))); //用户已封号
                    }else{
                        $pre = $income[0]['data'];// 补入前收益
                        $inc = $num*$price;
                        $count=$model->updateCounters(array('data'=>$inc),'uid=:uid and mrid=:mrid and createtime=:createtime',array(':uid'=>$uid,':mrid'=>$mrid,':createtime'=>$date));
                        if ($count){
                            $mend->pre_data = $pre;
                            $mend->after_data = $pre+$num*$price;// 补入后收益
                            $mend->insert();
                        }

                    }
                }
                $t->commit();
                exit(CJSON::encode(array("val" => 'success')));
            }catch (Exception $e){
                $t->rollback();
                exit(CJSON::encode(array("val" => 'error')));
            }

        }
    }

    /**
     * 补收益操作记录
     */
    public function actionMendLog(){
        if(Yii::app()->request->isAjaxRequest){
            $micr_start =microtime(true);
            $datetime = date('Y-m-d H:i:s');
            $pageSize = Yii::app()->request->getParam('pageSize'); // 每页行数
            $currentPage = Yii::app()->request->getParam('currentPage');// 当前页数
            $username = trim(Yii::app()->request->getParam('username_log'));// 当前页数
            $type = Yii::app()->request->getParam('type_log');// 当前页数
            $date = Yii::app()->request->getParam('date_log');// 当前页数
            $sign = Yii::app()->request->getParam('sign');// 查询类型:1:收益补入;2:余额补入
            
            $sql = "";
            if (!empty($username)){
                $sql = $sql." username='".$username."' and ";
            }
            if (!empty($type) && $type!='moren'){
                $sql = $sql."type='".$type."' and ";
            }
            if (!empty($date)){
                $sql = $sql."operatime='".$date."' and ";
            }

            if (empty($sql)){
                $sql='sign='.$sign;
            }else{
                $sql = $sql.'sign='.$sign;
            }

            $data = MendIncomeLog::model()->findAllBySql("SELECT * FROM `app_mend_income_log` WHERE {$sql}");
            $count = count($data);// 总行数
            // 对总行数拆分
            $arr=array();
            $totalPage=1;
            if (!empty($count)){
                for($a=0;$a*$pageSize<$count;$a++){
                    $arr[$a+1]=$a*$pageSize;
                }
                $totalPage = count($arr);
            }else{
                $time = floor(microtime(true)*1000)-floor($micr_start*1000);
                exit(CJSON::encode(array("val" => array(),'totalpage'=>$totalPage,'counts'=>0,'stamp'=>$time,'datetime'=>$datetime)));
            }


            $data = MendIncomeLog::model()->findAllBySql("SELECT * FROM `app_mend_income_log` WHERE {$sql} ORDER by id DESC limit {$arr[$currentPage]},{$pageSize}");
            $time = floor(microtime(true)*1000)-floor($micr_start*1000);
            exit(CJSON::encode(array("val" => $data,'totalpage'=>$totalPage,'counts'=>$count,'stamp'=>$time,'datetime'=>$datetime)));
        }
    }

    /**
     * 分页
     */
    public function actionRevoke(){
        if(Yii::app()->request->isAjaxRequest){
            $id = Yii::app()->request->getParam('id'); // 操作记录中id
            $sign = (int)Yii::app()->request->getParam('sign'); // 查询类型:1:收益补入;2:余额补入
            $data = MendIncomeLog::model()->find('id=:id',array(':id'=>$id));
            $type = $data->type;
            $uid = $data->uid;
            $pre_data = $data->pre_data;
            $after_data = $data->after_data;
            $mend_data = $data->mend_data;
            $income_date = $data->income_date;//补入时间

            $manager = Yii::app()->user->username;
            $t = Yii::app()->db->beginTransaction();
            try{
                if ($sign==1) {
                    // 创建业务model
                    $model = IncomeFactory::factory($type);
                    $count = $model->updateCounters(array('data' => '-' . $mend_data), 'uid=:uid and createtime=:createtime', array(':uid' => $uid, ':createtime' => $income_date));
                    if ($count > 0) {
                        MendIncomeLog::model()->updateAll(array('status' => 0, 'revoketime' => time(), 'revokeman' => $manager), 'id=:id', array(':id' => $id));
                    }
                }else{
                    $data = MemberBill::model()->findAll('uid=:uid',array(':uid'=>$uid));
                    if (empty($data)){
                        exit(CJSON::encode(array("val" => 'notenough')));
                    }
                    if ($data[0]['surplus']<$mend_data){
                            exit(CJSON::encode(array("val" => 'notenough')));
                    }
                    $count = MemberBill::model()->updateCounters(array('surplus'=>'-'.$mend_data),'uid=:uid',array(':uid'=>$uid));
                    if ($count > 0) {
                        MendIncomeLog::model()->updateAll(array('status' => 0, 'revoketime' => time(), 'revokeman' => $manager), 'id=:id', array(':id' => $id));
                    }
                }
                $t->commit();
                exit(CJSON::encode(array("val" => 'success')));
            }catch (Exception $e){
                $t->rollback();
                exit(CJSON::encode(array("val" => 'error')));
            }
            
        }
    }

    /**
     * 余额补入
     */
    public function actionSurplus()
    {

        $this->render('surplus');
    }

    /**
     * 余额补入ajax
     */
    public function actionSurplusAjax(){
        if(Yii::app()->request->isAjaxRequest){
            $username = Yii::app()->request->getParam('username');
            $surmoney = Yii::app()->request->getParam('surmoney');
            $note = Yii::app()->request->getParam('note');

            $mid = Yii::app()->user->manage_id; // 管理员id
            $manager = Yii::app()->user->username; // 管理员名字
            $member = new Member();
            // 通过用户名获取用户uid
            $data = $member->getByUserName($username);
            if (empty($data)){
                exit(CJSON::encode(array("val" => 'u')));// 用户名不存在
            }
            // 获取用户状态---判断用户是否被封号
            $status = $data->status;
            if ($status==0){
                exit(CJSON::encode(array("val" => 'f'))); //用户已封号
            }
            // 获取用户uid
            $uid = $data->id;
            // 获取用户类型
            $channel = $data->type;
            if (!is_numeric($surmoney)||floor($surmoney)!=$surmoney || $surmoney<=0){
                exit(CJSON::encode(array("val" => 'z'))); //补入余额只能为正整数
            }

            $t = Yii::app()->db->beginTransaction();
            try{
                $member_bill = new MemberBill();
                $data = $member_bill->model()->findAll('uid=:uid',array(':uid'=>$uid));
                // 操作记录
                $mend = new MendIncomeLog();
                $mend->uid = $uid;
                $mend->username = $username;
                $mend->mid = $mid;
                $mend->manager = $manager;
                $mend->channel = Member::getXtype($channel);
                $mend->type = '';
                $mend->typename = '';
                $mend->income_date = '';
                $mend->operatime = date('Y-m-d');
                $mend->stamptime = time();
                $mend->mend_data = $surmoney;
                $mend->mend_num = 0;
                $mend->note = $note;
                $mend->sign = 2; // 1:代表手机补入, 2:余额补入
                if (empty($data)){
                    $member_bill->uid=$uid;
                    $member_bill->surplus =$surmoney;
                    if ($member_bill->insert()){
                        $mend->pre_data = 0;
                        $mend->after_data = $surmoney;
                        $mend->insert();
                    }
                }else{
                    $count = $member_bill->updateCounters(array('surplus'=>'+'.$surmoney),'uid=:uid',array(':uid'=>$uid));
                    if ($count>=1){
                        $mend->pre_data = $data[0]['surplus'];
                        $mend->after_data = $data[0]['surplus']+$surmoney;
                        $mend->insert();
                    }
                }
                $t->commit();
                exit(CJSON::encode(array("val" => 'success')));
            }catch (Exception $e){
                $t->rollback();
                exit(CJSON::encode(array("val" => 'error')));
            }

        }

    }


}