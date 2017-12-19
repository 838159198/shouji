<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2015/5/12
 * Time: 10:25
 */
class MemberController extends DhadminController
{
    protected function skipAuth()
    {
        return array(
            'manage.Member.captcha', //验证码
            'manage.Member.taskinfo', //获取发布任务信息
        );
    }

    public function actions()
    {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'maxLength' => 4,
                'minLength' => 4,
            ),
        );
    }
    /*
     * 用户列表
     * */
    public function actionIndex($lock = '')
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberinfo.index.js');
        Script::registerScriptFile('manage/memberinfo.controller/memberinfo.category.js');
        Script::registerScriptFile('manage/common.js');
        Script::registerCssFile('asyncbox.css');

        $model = new Member('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Member'])) {

            $model->attributes = $_GET['Member'];
        }


        //判断是否有提交，并且提交内容是否为空或0，如果为空则不显示内容信息
        $attr = array_unique(array_values($model->attributes));
        $gets = Common::get2Array('sel', 'workType', 'workValue', 'lastjointime', 'lastovertime');
        if (!$this->checkArray($attr)) $model->status = -1;
        //判断是否搜索业务类型
        if (!empty($gets['workValue'])) $model->status = '';
        if (($model->status != -1) && (!empty($lock))) {
            //查询锁定与未锁定用户
            if ($lock == 'none') {
                $model->status = 1;
            } else if ($lock == 'lock') {
                $model->status = 0;
            }
        }
        $id = Yii::app()->user->manage_id;
        $dataProvider = $model->search($gets, $id);

        $memberList = $dataProvider->getData();
        $midList = array();
        foreach ($memberList as $member) {
            /* @var $member Member */
            $midList[] = $member->id;
        }

        unset($memberList);

        //用户联系记录数量及最后联系时间
        $advisoryRecordsList = AdvisoryRecords::model()->getCountAndLastTimeByUidList($midList);
        //用户封号数量
        $closeMemberResourceList = MemberResource::model()->getCloseList($midList);
        //用户类型
        $memberCategoryList = MemberCategory::model()->getListToArray();
        //最近一次发布的任务；
        $last_task = Task::model()->getMaxCreatetime();
        $param = array(
            'dataProvider' => $dataProvider,
            'model' => $model,
            'gets' => $gets,
            'advisoryRecordsList' => $advisoryRecordsList,
            'closeMemberResourceList' => $closeMemberResourceList,
            'memberCategoryList' => $memberCategoryList,
            'last_task' => $last_task,
        );

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('index', $param);
        } else {
            $this->render('index', $param);
        }
    }
    /**
     * 验证数组中的值是否都是空
     * @param $arr
     * @return bool
     */
    private function checkArray($arr)
    {
        foreach ($arr as $v) {
            $nval=trim($v);
            if (!empty($nval)) {
                return true;
            }
        }
        return false;
    }
    /**
     * 修改用户类型
     * @throws CHttpException
     * @name 修改用户类型
     */
    public function actionCategory()
    {
        $request = Yii::app()->request;
        if ($request->isPostRequest == false) {
            throw new CHttpException(404, '无此页面');
        }
        $uid = $request->getPost('m_uid');
        $category = $request->getPost('m_category');

        $model = $this->loadModel($uid);
        $model->category = $category;
        $model->update();

        /* @var $mc MemberCategory */
        $mc = MemberCategory::model()->findByPk($category);

        $advisory = new AdvisoryRecords();
        $advisory->uid = $uid;
        $advisory->mid = Yii::app()->user->getState("manage_id");
        $advisory->content = '修改用户类型为 - ' . (is_null($mc) ? '' : $mc->name);
        $advisory->jointime = time();
        $advisory->insert();

        $this->redirect($request->urlReferrer);
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Member the loaded model
     * @throws CHttpException
     */
    private function loadModel($id)
    {
        $model = Member::model()->findByPk($id);
        if ($model === null) {
            if (Yii::app()->request->isAjaxRequest) {
                exit;
            } else {
                throw new CHttpException(404, 'The requested page does not exist.');
            }
        }
        return $model;
    }
    /*
     * 修改更新
     * */
    public function actionEdit($id)
    {
        $model = Member::model();
        $data = $model -> findByPk($id);
        $info = $data->toString();
        $data -> scenario  = "edit";
        $mid = Yii::app()->user->manage_id;
        $user = Yii::app()->user;
        $log="";
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            if(isset($_POST['Member'])){
                if($_POST['Member']['type']==3)
                {
                    $_POST['Member']['agent']=77;
                }
                elseif($_POST['Member']['type']==8)
                {
                    $_POST['Member']['agent']=99;
                }
                elseif($_POST['Member']['type']==4)
                {
                    $_POST['Member']['agent']=88;
                }
                elseif($_POST['Member']['type']==2)
                {
                    $_POST['Member']['agent']=69;
                }
                elseif($_POST['Member']['type']==7)
                {
                    $_POST['Member']['agent']=0;
                }
                elseif($_POST['Member']['type']==5)
                {
                    $_POST['Member']['agent']=0;
                }
                elseif($_POST['Member']['type']==0)
                {
                    $_POST['Member']['agent']=0;
                }

                $log = '[old]' . json_encode($data->attributes) . "\n";
                foreach($_POST['Member'] as $_k => $_v){
                    $data -> $_k = $_v;
                }

                if($data->save()){
                    //添加log
                    if ($info != $model->toString()) {
                        MemberInfoLog::addLog($user,$info,$data->username);
                    }
                    $log .= '[new]' . json_encode($data->attributes);
                    Log::editMember($log);
                    Yii::app()->user->setFlash("status","恭喜你，用户信息修改成功！");
                    $this->redirect(array("member/detail?id=".$id));
                }
            }

            $this->render("edit",array("data"=>$data));
        }
    }
    /*
     * 详情detail
     * */
    public function actionDetail($id)
    {
        $model = new Member();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            $this->render("detail",array("data"=>$data));
        }
    }
    /*
     * 锁定账号
     * */
    public function actionLock($id)
    {
        $model = Member::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            $data -> status = 0;
            if($data -> update()){
                Yii::app()->user->setFlash("status","恭喜你，锁定用户账号成功！");
                $this->redirect(array("member/detail?id=".$id));
            }
        }
    }
    /*
     * 解锁账号
     * */
    public function actionUnLock($id)
    {
        $model = Member::model();
        $data = $model -> findByPk($id);
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            $data -> status = 1;
            if($data -> update()){
                Yii::app()->user->setFlash("status","恭喜你，解锁成功！");
                $this->redirect(array("member/detail?id=".$id));
            }
        }
    }
    /*
     * 修改财务信息
     * */
    public function actionBank($id)
    {
        $model = Member::model();
        $data = $model -> findByPk($id);
        $data -> scenario  = "bank";
        $info = $data->toString();
        $user = Yii::app()->user;
        if(empty($data)){
            throw new CHttpException(404,"不存在的id");
        }else{
            if(isset($_POST['Member'])){
                $log = '[old]' . json_encode($data->attributes) . "\n";
                $data -> holder = $_POST['Member']['holder'];
                $data -> id_card = $_POST['Member']['id_card'];
                $data -> bank = $_POST['Member']['bank'];
                $data -> bank_no = $_POST['Member']['bank_no'];

                $data -> province = $_POST['Member']['province'];
                $data -> city = $_POST['Member']['city'];
                $data -> county = $_POST['Member']['county'];
                $data -> qrcode = $_POST['Member']['qrcode'];
                //开户地址

                $data -> bank_site = $this->loadArea($data -> province).$this->loadArea($data -> city).$this->loadArea($data -> county);
                if($data -> save()){
                    //添加log
                    if ($info != $model->toString()) {
                        MemberInfoLog::addLog($user,$info,$data->username);
                    }
                    $log .= '[new]' . json_encode($data->attributes);
                    Log::editMember($log);
                    Yii::app()->user->setFlash("status","恭喜你，银行信息修改成功！");
                    $this->redirect(array("member/detail?id=".$id));
                }
            }
            $this->render("bank_edit",array("data"=>$data));
        }
    }
    /*
     * 重置密码
     * */
    public function actionResetPassword($id)
    {
        $member_model = new Member();
        $member_data = $member_model->findByPk($id);
        if(empty($member_data)){
            throw new CHttpException(404,"不存在的用户id");
        }else{
            $update=' [password] '.$member_data->password;
            $member_data -> password = md5(strrev(md5(strrev(trim("sutui521")))));
            $member_data -> update();
            //日志记录
            $mid = Yii::app()->user->manage_id;
            $detail=' [password] '. md5(strrev(md5(strrev(trim("sutui521")))));
            NoteLog::addLog($detail,$mid,$uid=$member_data->id,$tag='重置密码',$update);
            Yii::app()->user->setFlash("status","恭喜你，密码重置为：sutui521");
            $this->redirect(array("member/detail?id=".$id));
        }
    }
    /*
     * 无需密码直接进入用户后台
     * */
    public function actionUrl($id)
    {
        $member_model = new Member();
        $member_data = $member_model -> findByPk($id);
        Yii::app()->user->setState("member_id",$id);
        Yii::app()->user->setState("member_holder",$member_data->holder);
        Yii::app()->user->setState("member_username",$member_data->username);
        $this->redirect("/member");
        //print_r($member_data);
        //Yii::app()->user->setState("type","member");
    }
    /*
     *  地区
     * */
    private function loadArea($id)
    {
        $area_model = new Area();
        $data = $area_model->findByPk($id);
        return $data['name'];
    }

    /**
     * 用户详细信息(ajax)
     * @param $mid
     * @name 查看用户详细信息 (弹窗)
     */
    public function actionInfo()
    {

        if (Yii::app()->request->isAjaxRequest) {
            $mid = ( int )Yii::app()->request->getParam('mid'); //用户id

            $model = Member::model()->findByPk($mid);

            if ($model === null) {
                if (Yii::app()->request->isAjaxRequest) {
                    exit ();
                } else {
                    throw new CHttpException (404, 'The requested page does not exist.');
                }
            }

            $back = '';

            $sql = 'SELECT tw.remark FROM app_ask_task AS a
                    JOIN app_task AS t
                    JOIN app_task_when AS tw
                    ON a.tw_id = tw.id AND a.t_id = t.id AND tw.tid = t.id
                    WHERE a.f_id = \''.$model->manage_id.'\' AND a.m_id = \''.$mid.'\' AND t.mid = \''.$mid.'\' AND a.t_status = 2 AND t.status = 0';
            $res = Yii::app()->db->createCommand($sql)->queryAll();

            if(empty($res[0]['remark'])){
                $back = '无备注';
            }else{
                $back = $res[0]['remark'];
            }

            $memberCategoryList = MemberCategory::model()->getListToArray();
            $category = isset ($memberCategoryList [$model->category]) ? $memberCategoryList [$model->category] : '';
            $html = '<dl class="dl-horizontal">';
            $html .= '<dt>' . $model->getAttributeLabel('username') . '</dt><dd>' . $model->username . '</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('holder') . '</dt><dd>' . $model->holder . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('tel') . '</dt><dd>' . $model->tel . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('regist_tel') . '</dt><dd>' . $model->regist_tel . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('mail') . '</dt><dd>' . $model->mail . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('qq') . '</dt><dd>' . $model->qq . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('weixin_name') . '</dt><dd>' . $model->weixin_name . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('bank') . '</dt><dd>' . $model->bank . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('clients') . '</dt><dd>' . $model->clients . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('jointime') . '</dt><dd>' . date('Y-m-d H:i:s', $model->jointime) . '</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('overtime') . '</dt><dd>' . date('Y-m-d H:i:s', $model->overtime) . '</dd>';
            $html .= '<dt>用户分类</dt><dd>' . Member::getNameByType($model->type) . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('alias') . '</dt><dd>' . $model->alias . '&nbsp;</dd>';
            $html .= '<dt>' . $model->getAttributeLabel('category') . '</dt><dd>' . $category . '&nbsp;</dd>';
            $html .= '<dt>任务备注</dt><dd>' . $back . '</dd>';

            if (!empty ($model->father_id)) {
                $agent = Member::model()->getById($model->father_id);
                $html .= '<dt>推广上级</dt><dd>' . $agent->username . '&nbsp;</dd>';
            }

            //已开启和已被封的业务key
            $html .= '<dt>&nbsp;</dt><dd><h5>业务</h5></dd>';
            $resourceList = MemberResource::model()->getByUid($mid);

            foreach ($resourceList as $resource) {
                $html .= '<dt>' . Ad::getAdNameById($resource->type) . '</dt><dd>';
                $html .= $resource->key;
                $html .= $resource->openstatus == MemberResource::OPEN_TRUE ? ' [开启] ' : ' ';

                if ($resource->status == MemberResource::STATUS_FALSE) {
                    $bindSample = BindSample::model()->getByVal($resource->key, $resource->type);

                    $closed = isset($bindSample->closed) && !empty($bindSample->closed)?BindSample::CLOSED_TRUE:BindSample::CLOSED_FALSE;
                    $status = isset($bindSample->status) && !empty($bindSample->status)?BindSample::STATUS_FALSE:BindSample::CLOSED_TRUE;
                    if (($closed == BindSample::CLOSED_TRUE) && ($status == BindSample::STATUS_FALSE)) {

                        $html .= '(封号 - ' . DateUtil::dateFormate($resource->motifytime) . ')';
                    } else {

                        $html .= '(回收 - ' . DateUtil::dateFormate($resource->motifytime) . ')';
                    }
                }
                $html .= '</dd>';
            }

            //任务绑定的客服
            $html .= '<dt>&nbsp;</dt><dd><h5>客服</h5></dd>';

            $taskList = Task::model()->getListByMemberId($model->id);
            foreach ($taskList as $task) {
                $html .= '<dt>' . $task->manageAccept->name . '</dt>';
                $html .= '<dd>' . DateUtil::dateFormate($task->createtime) . ' - ' . $task->getStatusName($task->status) . '</dd>';
            }

            $html .= '</dl>';

            echo $html;
            exit ();
        }
    }
    /**
     * @name 用户状态树添加 -普通权限
     */
    public function actionSetMemberCatalogue()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $mid = (int)Yii::app()->request->getParam('mid');
            $catid = (int)Yii::app()->request->getParam('catid');

            $model = Member::model()->findByPk($mid);

            if (isset($model->id)) {

                $sql = 'UPDATE app_member SET cataid = \'' . $catid . '\' WHERE id = \'' . $mid . '\' ';
                $res = Yii::app()->db->createcommand($sql)->execute();
                if ($res == 1) {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //收益发布成功
                } else {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //收益发布失败
                }
            }


        }
    }

    /**
     * @name 删除用户当前所属状态
     */
    public function actionDelMemberCatalogue()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $mid = (int)Yii::app()->request->getParam('mid');
            $model = Member::model()->findByPk($mid);

            if (isset($model->id)) {
                $sql = 'UPDATE app_member SET cataid = "" WHERE id = \'' . $mid . '\' ';
                $res = Yii::app()->db->createcommand($sql)->execute();
                if ($res == 1) {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //收益发布成功
                } else {
                    echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //收益发布失败
                }
            }
        }
    }

    /**
     * @name 显示当前用户状态树 ——列表
     */
    public function actionShowCatalogueFid()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $mid = (int)Yii::app()->request->getParam('mid');
            $model = Member::model()->findByPk($mid);
            $msg = '';
            if (empty($model->cataid) || ($model->cataid == 0)) {
                $msg['error'] = '0';
            } else {
                $cata = Catalogue::model()->findByPk($model->cataid);
                if (($cata->rank == 1) && ($cata->fid == 0)) {
                    $msg['name'] = $cata->name;
                    $msg['error'] = '1';
                } elseif ($cata->rank == 2) {
                    $get_f_name = Catalogue::model()->findByPk($cata->fid);
                    $msg['name'] = $get_f_name->name;
                    $msg['name1'] = $cata->name;
                    $msg['error'] = '2';
                } elseif ($cata->rank == 3) {
                    $get_f_name = Catalogue::model()->findByPk($cata->fid);
                    $get_top_name = Catalogue::model()->findByPk($get_f_name->fid);
                    $msg['name'] = $get_top_name->name;
                    $msg['name1'] = $get_f_name->name;
                    $msg['name2'] = $cata->name;
                    $msg['error'] = '3';
                }
            }

            echo CJSON::encode(array('msg' => $msg)); //收益发布成功

        }
    }


    /**
     * @name 用户关注列表 -高级权限
     */
    public function actionMemberLastContaceTime()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.controller/memberinfo.checkmember.js');
        Script::registerScriptFile('manage/memberinfo.index.js');
        Script::registerCssFile('asyncbox.css');

        $id = Yii::app()->user->manage_id;;
        $role = Manage::model()->getRoleByUid($id);

        //不是经理或者客服主管
        if (($role == 1) || ($role == 2) || ($role == 3) || ($id == 33)) {

            $role = $role;
        } else {

            throw new CHttpException (404, '没有权限这么做');
        }

        //未标记的任务数量
        $noCategory = Member::model()->count("category=:category", array(":category" => ''));

        //查看任务类型
        $sql = 'SELECT id,name FROM app_member_category';
        $category_list = Yii::app()->db->createCommand($sql)->queryAll();
        $arr = '';

        //任务类型的数量
        foreach ($category_list AS $key => $item) {

            $arr[$key]['count'] = Member::model()->count("category=:category", array(":category" => $item['id']));
            $arr[$key]['name'] = $item['name'];
            $arr[$key]['type'] = $item['id'];
        }


        /****************类别列表********************/
        $table_cat = 'app_member AS mi';

        $FIND = ',MAX(a.jointime) AS mjt,a.uid';
        $JOIN = 'JOIN app_advisory_records AS a
                 ON mi.id = a.uid
                 GROUP BY a.uid ORDER BY mjt DESC';


        $type = 1;
        $categoty_non = '';

        //类别
        if (isset($_GET['cate']) && ($_GET['cate'] == '')) {

            $category = '';
            $and_c = '';
        } else if (isset($_GET['cate']) && ($_GET['cate'] == 0)) {
            $AN = '';

            if ((isset($_GET['time_s']) && ($_GET['time_s'] != '')) &&
                (isset($_GET['mounth']) && ($_GET['time_s'] == ''))
            ) {
                $AN = ' AND';
            }

            $and_c = ' AND';
            $category = 'b.category = 0' . $AN;
            $categoty_non = 0;
        } else if (isset($_GET['cate']) && ($_GET['cate'] != 0)) {

            $AN = '';
            if ((isset($_GET['time_s']) && ($_GET['time_s'] != '')) &&
                (isset($_GET['mounth']) && ($_GET['time_s'] == ''))
            ) {
                $AN = ' AND';
            }
            $and_c = 'AND';
            $category = ' b.category = \'' . $_GET['cate'] . '\' ' . $AN;
        } else {

            $category = '';
            $and_c = '';
        }


        //查看月份
        if (isset($_GET['mounth']) && ($_GET['mounth'] == '')) {

            $and_m = '';
            $time_ago = '';
        } else {

            //查看的月份
            $and_m = 'AND';
            $mounth = isset($_GET['mounth']) ? $_GET['mounth'] : 3;

            if ($mounth == '0') { //从未联系
                $category = '';
                $type = 0;
                $c = (isset($_GET['cate']) && ($_GET['cate'] != '') ? $_GET['cate'] : 0);

                $categoty_non1 = "mi.category = $c AND ";

                $categoty_non = (isset($_GET['cate']) && ($_GET['cate'] != '') ? $categoty_non1 : '');

                $time_ago = 'b.mjt = " "' . '  ' . $and_c;
            } else if ($mounth == 13) { //一年以上

                $time_ago = strtotime("-12 month"); //当前时间的mounth月前
                $time_ago = "b.mjt < " . $time_ago . '  ' . $and_c;
            } else { //一年内

                $time_ago = strtotime("-$mounth month"); //当前时间的mounth月前
                $date_ago = date('Y-m-d 23:59:59', $time_ago);
                $time_ago = strtotime($date_ago);

                $mounth_betwwen = WeekTask::model()->GetNextMonthByTime($date_ago);
                $time_ago = "b.mjt >= " . $time_ago . " AND b.mjt <= " . $mounth_betwwen . '  ' . $and_c;
            }
        }


        if (!empty($_GET['mounth']) || !empty($_GET['cate'])) {

            $and = 'AND ';
        } else {

            $and = '';
        }

        //查看注册时间-s
        if (!empty($_GET['time_s']) && empty($_GET['time_e'])) {

            $start = strtotime($_GET['time_s']);
            $register = $and . " b.jt > $start AND b.jt < " . time();
            $register2 = " mi.jointime > $start AND mi.jointime < " . time() . '  AND';
        } else if ((!empty($_GET['time_e'])) && empty($_GET['time_s'])) {

            $end = strtotime($_GET['time_e']);
            $register = $and . " b.jt > $end AND b.jt < " . time();
            $register2 = " mi.jointime > $end AND mi.jointime < " . time() . '  AND';
        } elseif (!empty($_GET['time_e']) && !empty($_GET['time_s'])) {

            $start = strtotime($_GET['time_s']);
            $end = strtotime($_GET['time_e']);
            $register = $and . " b.jt > " . $start . " AND b.jt < " . $end;
            $register2 = " mi.jointime > " . $start . " AND mi.jointime < " . $end . '  AND';
        } else {

            $register = '';
            $register2 = '';
        }



        /************** getmounth ********************/

        //只查看的月份
        if (isset($_GET['omounth'])) {
            $type = 2;
            $omounth = $_GET['omounth'];
            if ($omounth == 'never') { //从未联系

                $otime_ago = 'b.mjt = " "' . '  ' . $and;
            } else if ($omounth == 13) { //一年以上

                $otime_ago = strtotime("-12 month"); //当前时间的mounth月前

                $otime_ago = "b.mjt < " . $otime_ago . '  ' . $and;
            } else { //一年内

                $otime_ago = strtotime("-$omounth month"); //当前时间的mounth月前
                $odate_ago = date('Y-m-d 23:59:59', $otime_ago);
                $otime_ago = strtotime($odate_ago);

                $omounth_betwwen = WeekTask::model()->GetNextMonthByTime($odate_ago);
                $otime_ago = "b.mjt >= " . $otime_ago . " AND b.mjt <= " . $omounth_betwwen . $and;
            }

        } else {

            $otime_ago = '';
        }


        /****************注册时间查看*************************/
        $ORDER1 = '';
        $ORDER2 = '';
        if (isset($_GET['type']) && ($_GET['type'] == 'jt')) {
            $jt = isset($_GET['jt']) ? $_GET['jt'] : 0;
            if ($jt == 0) {
                $ORDER1 = ' ORDER BY jt DESC';
                $ORDER2 = ' ORDER BY b.jt DESC';
            } elseif ($jt == 1) {
                $ORDER1 = ' ORDER BY jt ASC';
                $ORDER2 = ' ORDER BY b.jt ASC';
            }
        } else if (isset($_GET['type']) && ($_GET['type'] == 'ot')) {

            $ot = isset($_GET['ot']) ? $_GET['ot'] : 0;
            if ($ot == 0) {
                $ORDER1 = ' ORDER BY mi.overtime DESC';
                $ORDER2 = ' ORDER BY b.overtime DESC';
            } elseif ($ot == 1) {
                $ORDER1 = ' ORDER BY mi.overtime ASC';
                $ORDER2 = ' ORDER BY b.overtime ASC';
            }
        } elseif (isset($_GET['type']) && ($_GET['type'] == 'mjt')) {
            $mjt = isset($_GET['mjt']) ? $_GET['mjt'] : 0;
            if ($mjt == 0) {
                $ORDER1 = '';
                $ORDER2 = ' ORDER BY b.mjt DESC';
            } elseif ($mjt == 1) {
                $ORDER1 = '';
                $ORDER2 = ' ORDER BY b.mjt ASC';
            }
        }






        $criteria = new CDbCriteria ();



        //查看用户名
        if (isset($_GET['username_c']) && ($_GET['username_c'] != '')) {
            $username_c='\''.$_GET['username_c'].'\'';
            $sql_member="SELECT b.* FROM (SELECT mi.id,mi.username,mi.jointime AS jt ,mi.overtime,mi.manage_id, mi.category ,MAX(a.jointime) AS mjt,a.uid FROM app_member AS mi JOIN app_advisory_records AS a ON mi.id = a.uid
GROUP BY a.uid ORDER BY mjt DESC) AS b WHERE b.username = ".$username_c;
        }
        elseif(isset($_GET['workType']) && ($_GET['workType'] != '')) {
            $workType=$_GET['workType'];
            $sql_member="SELECT b.* FROM (SELECT mi.id,mi.username,mi.type,mi.jointime AS jt ,mi.overtime,mi.manage_id, mi.category ,MAX(a.jointime) AS mjt,a.uid FROM app_member AS mi JOIN app_advisory_records AS a ON mi.id = a.uid
GROUP BY a.uid ORDER BY mjt DESC) AS b WHERE b.type = ".$workType;
        }
        else {

            $sql_member = Member::model()->changeSql($table_cat, $JOIN, $FIND, $type, $categoty_non, $category, $ORDER1, $ORDER2, $time_ago, $otime_ago, $register, $register2);
        }

        $category1 = isset($_GET['cate']) ? $_GET['cate'] : 0;
        $model = Yii::app()->db->createCommand($sql_member)->queryAll();
        $num = count($model);
        $pages = new CPagination (count($model));
        $pages->pageSize = DefaultParm::DEFAULT_PAGE_SIZE;
        $pages->applylimit($criteria);
        $model = Yii::app()->db->createCommand($sql_member . " LIMIT :offset,:limit");
        $model->bindValue(':offset', $pages->currentPage * $pages->pageSize);
        $model->bindValue(':limit', $pages->pageSize);
        $list = $model->queryAll();
        $parm = array(
            'arr' => $arr,
            'noCategory' => $noCategory,
            'list' => $list,
            'pages' => $pages,
            'category' => $category1,
            'num' => $num,
            //  'mounth' => $mounth
        );
        $this->render('lastcontact', $parm);
    }


    /**
     * @name 撤销任务
     */
    public function actionRepeal()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $id_list = Yii::app()->request->getParam('id_list'); //task id
            $mid_list = array();
            $atid_list = array();
            $tid_list = array();
            $twid_list = array();
            $str_atid = DefaultParm::DEFAULT_EMPTY;
            $str_mid = DefaultParm::DEFAULT_EMPTY;
            $str_twid = DefaultParm::DEFAULT_EMPTY;
            $str_tid = DefaultParm::DEFAULT_EMPTY;

            $t = Yii::app()->db->beginTransaction();
            try {
                $sql = 'SELECT id,t_status,t_id,tw_id,m_id FROM app_ask_task
    		        WHERE FIND_IN_SET(t_id,\'' . $id_list . '\')';
                $ask_task = Yii::app()->db->createCommand($sql)->queryAll();
                foreach ($ask_task AS $key => $item) {

                    $atid_list[$key] = $item['id'];
                    $mid_list [$key] = $item['m_id'];
                    $twid_list[$key] = $item['tw_id'];
                    $tid_list [$key] = $item['t_id'];
                }
                $str_atid = (count($atid_list) == 1) ? $atid_list[0] : implode(",", $atid_list);
                $str_mid = (count($mid_list) == 1) ? $mid_list[0] : implode(",", $mid_list);
                $str_tid = (count($tid_list) == 1) ? $tid_list[0] : implode(",", $tid_list);
                $str_twid = (count($twid_list) == 1) ? $twid_list[0] : implode(",", $twid_list);

                $asktask = AskTask::model()->updateAll(array(
                        't_status' => AskTask::STATUS_DEL),
                    " id in ( " . $str_atid . " )");

                Task::model()->updateAll(array(
                        'status' => Task::STATUS_DEL, 'motifytime' => time()),
                    " id in ( " . $str_tid . " )");

                TaskWhen::model()->updateAll(array(
                        'status' => TaskWhen::STATUS_SUBMAIT,
                        'isfail' => TaskWhen::IS_FAIL_TRUE,
                        'motifytime' => time()),
                    " id in ( " . $str_twid . " )");

                Member::model()->updateAll(array(
                        'manage_id' => DefaultParm::DEFAULT_ZERO),
                    " id in ( " . $str_mid . " )");

                $t->commit();
                echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //收益发布成功

            } catch (Exception $e) {
                $t->rollback();

                echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //收益发布失败
            }
        }
    }

    /**
     * @name 首页 -会员列表管理-任务申请
     */
    public function actionAskForTask()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $m_id = ( int )Yii::app()->request->getParam('m_id'); //申请的用户的id member_id
            $type = ( int )Yii::app()->request->getParam('type'); //任务类型
            $f_id = Yii::app()->user->manage_id;; //当前登录的客服id
            $time = time(); //当前时间
            //查看当前用户的任务条数

            $model = Member::model()->findByPk($m_id);
            if (($model->manage_id == 0) || empty($model->manage_id)) {
                //判断该客服是否申请过该任务
                $task_model = new Task();
                $asktaskData = $task_model->find("`accept`=:accept and `mid`=:mid and `type`=1",array(":accept"=>$f_id,":mid"=>$m_id));
                if($asktaskData)
                {
                    //可以多次申请
                    //exit(CJSON::encode(array('msg' => '9',"message"=>"你已申请过该任务，同一人不能多次申请")));
                }
                $t = Yii::app()->db->beginTransaction();
                try {
                    $asktask = new AskTask();
                    $asktask->f_id = Yii::app()->user->manage_id;;
                    $asktask->m_id = $m_id;
                    $asktask->a_time = $time;
                    $asktask->is_allow = AskTask::IS_ALLOW_WAIT;
                    $asktask->t_status = AskTask::STATUS_AASK;
                    $asktask->type = $type;
                    $asktask->insert();

                    $rec = Member::model()->updateByPk($m_id, array('manage_id' => $f_id));

                    $t->commit();
                    echo CJSON::encode(array('msg' => '0')); //收益发布成功

                } catch (Exception $e) {
                    $t->rollback();
                    echo CJSON::encode(array('msg' => '1')); //收益发布失败
                }

            } else {
                echo CJSON::encode(array('msg' => '1'));
                exit;

            }

        }
    }


    /**
     * @param $uid
     * @param string $firstDate
     * @param string $lastDate
     * @throws CHttpException
     * @name 曲线图
     */
    public function actionGraphs($uid, $firstDate = '', $lastDate = '')
    {
        if (!ctype_digit($uid)) {
            throw new CHttpException(500, '参数错误');
        }
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberinfo.controller/memberinfo.category.js');
        Script::registerCssFile('asyncbox.css');
        Script::registerScriptFile(Script::HIGHSTOCK);
        Script::registerScriptFile('manage/memberinfo.graphs.js');
        if (!empty($firstDate) && !empty($lastDate)) {
            $firstDate = date('Y-m-d', strtotime($firstDate));
            $lastDate = date('Y-m-d', strtotime($lastDate));
        } else {
            $firstDate = date('Y-m-d', strtotime('-6 month'));
        }

        $incomeList = MemberIncome::getListByDateInterval($uid, $firstDate, $lastDate);
        $incomes = array();

        $adList = Ad::getAdList();
        foreach ($incomeList as $userIncome) {
            $income = array();
            $aa=array();
            foreach ($adList as $k => $v)
            {
                $income[$k] = isset($userIncome[$k]) ? $userIncome[$k] : '0.00';
                //js不支持数字开头，更改键名 { name: 'UC浏览器', data: categories.ucllq, type: 'spline', tooltip: { valueDecimals: 2 }},
                switch($k)
                {
                    case "2345ydw":
                        $income["ydw2345"]=$income[$k];
                        unset($income[$k]);
                        break;
                    case "2345sjzs":
                        $income["sjzs2345"]=$income[$k];
                        unset($income[$k]);
                        break;
                    case "2345ysdq":
                        $income["ysdq2345"]=$income[$k];
                        unset($income[$k]);
                        break;
                    case "2345wpllq":
                        $income["wpllq2345"]=$income[$k];
                        unset($income[$k]);
                        break;
                    case "2345tqw":
                        $income["tqw2345"]=$income[$k];
                        unset($income[$k]);
                        break;
                }
                $aa[]=array("name"=>$v,'data'=>$k,"type"=>'spline',"tooltip"=>array("valueDecimals"=>2));
            }
            $date = strtotime($userIncome['dates']);
            $income['y'] = intval(date('Y', $date));
            $income['m'] = intval(date('m', $date));
            $income['d'] = intval(date('d', $date));

            $incomes[] = $income;
        }

        $array = array();
        foreach($incomes as $key=>$vt){
            $date=$vt['y'].'-'.$vt['m'].'-'.$vt['d'].' 08:00:00';
            $time = strtotime($date)*1000;
            foreach($vt as $k=>$v){
                if((float)$v>0 &&  $k!='y' && $k!='m' && $k!='d'){
                    $array[$k][$key][]=$time;
                    $array[$k][$key][]=(float)$v;
                }
            }
        }
        $aa=array();
        foreach ($array as $k => $v)
        {
            //if(in_array($k,array('2345tqw','2345ysdq'))) continue;
            if(isset($array[$k])){
                $data=[];
                foreach($array[$k] as $item){
                    $data[]=$item;
                }
            }
            //js不支持数字开头，更改键名 { name: 'UC浏览器', data: categories.ucllq, type: 'spline', tooltip: { valueDecimals: 2 }},
            $aa[]=array("name"=>$adList[$k],'data'=>$data,"type"=>'spline',"tooltip"=>array("valueDecimals"=>2));
        }
        //echo json_encode($aa);exit;


        $modelMember = $this->loadModel($uid);
        $memberName = CHtml::encode($modelMember->username . $modelMember->holder);
        $this->render('graphs', array(
            'json' => json_encode($incomes),
            'types' => json_encode($adList),
            'aa' => json_encode($aa),
            'uid' => $uid,
            'first' => $firstDate,
            'last' => $lastDate,
            'memberName' => $memberName,
        ));
    }

    /**
     * By Auth
     * @throws CHttpException
     * @name 按注册 、登陆时间搜索
     */
    public function actionSearchLoginDate()
    {
        throw new CHttpException(404, '无此页面');
    }

    /**
     * By Auth
     * @throws CHttpException
     * @name 登陆用户后台
     */
    public function actionLoginMember()
    {
        throw new CHttpException(404, '无此页面');
    }

    /**
     * @name 代注册
     */
    public function actionCreate()
    {
        $model = new Member('insert');
        $model->point = Member::DEFAULT_POINT;

        if (isset($_POST['Member'])) {
            $model->attributes = $_POST['Member'];
            $model->password = Member::RESET_PASSWORD;
            if ($model->validate()) {
                $model->regist_tel = $model->tel;
                $model->password = $model->createPassword(Member::RESET_PASSWORD);
                $model->jointime = time();
                $model->overtime = time();
                $model->content = $this->username . '代注册' . $model->content;
                if ($model->insert()) {
                    Common::redirect($this->createUrl('index'), '注册成功');
                }
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * @param $id
     * @name 修改
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $info = $model->toString();
        $user = Yii::app()->user;
        if (isset($_POST['Member'])) {
            $log = '[old]' . json_encode($model->attributes) . "\n";
            $model->attributes = $_POST['Member'];
            if ($model->save()) {
                //添加log
                if ($info != $model->toString()) {
                    MemberInfoLog::addLog($user,$info,$model->username);
                }
                $log .= '[new]' . json_encode($model->attributes);
                Log::editMember($log);
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param Member $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'member-info-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * 把用户密码重置为123456
     * @param $uid
     * @name 重置密码
     */
    public function actionResetpwd($uid)
    {
        $model = $this->loadModel($uid);

        if (Yii::app()->request->getParam('verifyCode')) {
            /** @var $captcha CCaptchaAction */
            $captcha = $this->createAction('captcha');
            $code = $captcha->getVerifyCode();
            $input = Yii::app()->request->getParam('verifyCode');
            if ($code == $input) {
                $model->password = $model->createPassword(Member::RESET_PASSWORD);
                $model->update();
                Common::redirect($this->createUrl('index'), '修改成功');
            } else {
                Yii::app()->user->setFlash('error', '验证码输入错误');
            }
        }

        $this->render('resetpwd', array(
            'model' => $model
        ));
    }

    /**
     * 任务批量发布
     * @name 任务批量发布
     */
    public function actionSendTask()
    {
        if (Yii::app()->request->isPostRequest) {
            if (isset(Yii::app()->request->isAjaxRequest)) {
                $selectsend = Yii::app()->request->getParam('t_uname');
                $content = Yii::app()->request->getParam('t_content');
                $title = Yii::app()->request->getParam('t_title');
                $f_id = (int)Yii::app()->request->getParam('t_accept');
                $type = (int)Yii::app()->request->getParam('type');
                $id = Yii::app()->user->manage_id;;
                $role = Manage::model()->getRoleByUid($id);
                if (($role > 4) && ($id !=17) ) {
                    echo 'no_power';
                    exit;
                }
                $arr = '';

                $arr = explode(",", $selectsend);
                $sql = 'SELECT manage_id FROM app_member WHERE FIND_IN_SET(id,\'' . $selectsend . '\');';
                $res = Yii::app()->db->createCommand($sql)->queryAll();
                foreach ($res AS $key => $item) {
                    if ($item['manage_id'] != 0) {
                        echo 'a_in';
                        exit;
                    }
                }
                $t = Yii::app()->db->beginTransaction();
                try {
                    foreach ($arr as $send) {
                        //添加新任务
                        $model = new Task('insert');
                        $model->title = $title;
                        $model->content = date('Y-m-d', time()) . '发布此任务</br>' . $content;
                        $model->type = $type;
                        $model->accept = $f_id;
                        $model->publish = Yii::app()->user->manage_id;
                        $model->createtime = DateUtil::time();
                        $model->status = 0;
                        $model->mid = $send;
                        $model->insert();

                        $taskWhen = new TaskWhen('insert');
                        $taskWhen->tid = $model->id;
                        $taskWhen->createtime = $model->createtime;
                        $taskWhen->insert();

                        $askTask = new AskTask('insert');
                        $askTask->allow_time = $model->createtime; //任务批准/发布时间
                        $askTask->a_time = $model->createtime; //任务批准/发布时间
                        $askTask->t_status = 2; //已申请
                        $askTask->f_id = $f_id; //申请人id/任务接收人id
                        $askTask->a_id = $model->publish; //发布任务的管理员id
                        $askTask->is_allow = 1; //批准任务
                        $askTask->tw_id = $taskWhen->id;
                        $askTask->t_id = $model->id;
                        $askTask->type = $model->type;
                        $askTask->m_id = $send;
                        $askTask->insert();

                    }
                    $sql = "UPDATE app_member SET manage_id = $f_id WHERE id IN ($selectsend) ";
                    Yii::app()->db->createCommand($sql)->execute();

                    $t->commit();
                    echo 'success';
                } catch (Exception $e) {
                    $t->rollback();
                    echo 'error';
                }
            }
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * @throws CHttpException
     * @name 首页 -会员列表管理-任务发布
     */
    public function actionTask()
    {
        $r = Yii::app()->request;

        if (!$r->isPostRequest) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        $usernames = $r->getParam('t_uname');
        // var_dump($usernames);
        if (empty($usernames)) {
            throw new CHttpException(500, '未选择用户');
        }
        $accept = $r->getParam('t_accept');
        if (empty($accept)) {
            echo '未选择接收人';
            exit;
        }

        $_title = $r->getParam('t_title');
        $_content = $r->getParam('t_content');
        $_type = $r->getParam('type');

        $t = Yii::app()->db->beginTransaction();
        try {
            $usernameList = explode(' ', $usernames);
            // var_dump($usernameList);
            $memberList = Member::model()->getByUserNameList($usernameList);

            foreach ($usernameList as $username) {
                if (empty($username)) continue;
                if (isset($memberList[$username]) == false) continue;


                $member = $memberList[$username];
                // 该用户已经存在分配客服的id
                if ($member->manage_id != 0) {
                    echo 'a_done';
                    exit;
                }
                //添加新任务
                $model = new Task('insert');
                $model->title = $_title . '-' . $username;
                $model->content = $_content;
                $model->type = $_type;
                $model->accept = $accept;
                $model->publish = Yii::app()->user->manage_id;;
                $model->createtime = DateUtil::time();
                $model->status = Task::STATUS_NORMAL;
                $model->mid = $member->id;
                $model->insert();

                $taskWhen = new TaskWhen('insert');
                $taskWhen->tid = $model->id;
                $taskWhen->createtime = $model->createtime;
                $taskWhen->insert();

                $askTask = new AskTask('insert');
                $askTask->allow_time = $model->createtime; //任务批准/发布时间
                $askTask->a_time = $model->createtime; //任务批准/发布时间
                $askTask->t_status = 2; //已申请
                $askTask->m_id = $model->mid; //用户id
                $askTask->f_id = $accept; //申请人id/任务接收人id
                $askTask->a_id = $model->publish; //发布任务的管理员id
                $askTask->is_allow = 1; //批准任务
                $askTask->tw_id = $taskWhen->id;
                $askTask->t_id = $model->id;
                $askTask->type = $_type;
                $askTask->insert();

                //任务客服添加到用户信息表中
                $member->manage_id = $accept;
                $member->update();
            }

            $t->commit();
            echo 'success';
        } catch (Exception $e) {
            $t->rollback();
            echo 'error';
        }
        exit;
    }

    /**
     * 获取任务信息
     * 根据角色获取下级客服列表
     * @name 任务发布2
     */
    public function actionTaskinfo()
    {
        // $role = Yii::app()->user->getState('role');
        $role = 2;
        $modelRole = Role::model();
        $manageList = $modelRole->getChildManageList($role);
        $downList = array();
        foreach ($manageList as $manage) {
            /** @var $manage Manage */
            if (empty($manage->r)) {
                continue;
            }
            $_id = $manage->id;
            $manage_role = Manage::model()->getRoleByUid($_id);
            $downList[] = array(
                'id' => $_id,
                'role' => $manage_role,
                'name' => $manage->name . '(' . $manage->r->name . ')'
            );
        }

        echo json_encode(array(
            'downlist' => $downList
        ));
        exit;
    }

    /**
     * @param $id
     * @name 设置业务单价
     */
    public function actionPrice($id)
    {
        $member = $this->loadModel($id);

        //保存用户业务单价
        if (Yii::app()->request->isPostRequest) {
            $t = Yii::app()->db->beginTransaction();
            try {
                foreach ($_POST as $type => $arr) {
                    if (Ad::getAdNameById($type) === '') continue;
                    $this->createMemberResourcePrice($id, $type, $arr['price'], $arr['quote']);
                }
                $t->commit();
                Common::redirect($this->createUrl('', array('id' => $id)), '保存成功');
            } catch (Exception $e) {
                $t->rollback();
                Common::redirect($this->createUrl('', array('id' => $id)), '保存失败，请重试');
            }
        }

        $priceList = MemberResourcePrice::model()->getListByUid($id);
        $resourceList = Resource::model()->getAll(Resource::AUTH_ALL);
        $this->render('price', array(
            'member' => $member,
            'priceList' => $priceList,
            'resourceList' => $resourceList
        ));
    }

    /**
     * @param $id
     * @name 修改信息历史记录
     */
    public function actionLog($id)
    {
        $member = $this->loadModel($id);
        $list = MemberInfoLog::model()->getListByMid($id);
        $this->render('log', array(
            'list' => $list,
            'member' => $member
        ));
    }

    /**
     * 为用户添加业务单价
     * @param $id
     * @param $type
     * @param $price
     * @param $quote
     */
    private function createMemberResourcePrice($id, $type, $price, $quote)
    {
        if ($price > $quote) return;
        $model = MemberResourcePrice::model()->getByUidAndType($id, $type);
        $log = ' [uid]' . $id . ' [type]' . $type . ' [price]' . $price . ' [quote]' . $quote . ' [category]0';
        if (is_null($model)) {
            //单价为0不保存
            if ($this->checkPriceAndQuote($price, $quote)) return;
            $model = new MemberResourcePrice();
            $model->uid = $id;
            $model->type = $type;
            $model->price = $price;
            $model->quote = $quote;
            $model->category = MemberResourcePrice::CATEGORY_ALONE;
            $model->createtime = time();
            $model->motifytime = 0;
            $model->status = MemberResourcePrice::STATUS_TRUE;
            $model->insert();
            Log::editMemberResourcePrice('[insert]' . $log);
        } else {
            $log .= ' [oldprice]' . $model->price . ' [oldquote]' . $model->quote;
            //单价设置为0，删除数据，使用默认单价
            if ((empty($price) || $price == 0) && (empty($quote) || $quote == 0)) {
                $model->status = MemberResourcePrice::STATUS_FALSE;
                $model->motifytime = time();
                $model->update();
                Log::editMemberResourcePrice('[delete]' . $log);
            } else {
                if (($model->price == $price) && ($model->quote == $quote)) return;
                $model->price = $price;
                $model->quote = $quote;
                $model->category = MemberResourcePrice::CATEGORY_ALONE;
                $model->motifytime = time();
                $model->update();
                Log::editMemberResourcePrice('[update]' . $log);
            }
        }
    }

    /**
     * 验证单价及报价是否为0
     * @param $price
     * @param $quote
     * @return bool
     */
    private function checkPriceAndQuote($price, $quote)
    {
        return (empty($price) || $price == 0) && (empty($quote) || $quote == 0);
    }


    /**
     * 追踪用户状态
     */
    public function actionMark()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $id = Yii::app()->user->getState("manage_id");
            $mid = (int)Yii::app()->request->getParam('mid'); //用户id
            $res = '';
            $t = Yii::app()->db->beginTransaction();
            try {

                $sql = 'SELECT mark FROM app_manage WHERE id = \'' . $id . '\' ';
                $mark = Yii::app()->db->createCommand($sql)->queryAll();
                $mark = $mark[0]['mark'];

                if (empty($mark)) {

                    $sql_in = 'UPDATE app_manage SET mark =\'' . $mid . '\' WHERE id = \'' . $id . '\' ';
                    $res = Yii::app()->db->createCommand($sql_in)->execute();

//                    $sql = 'INSERT INTO app_mark_log (mid,uid,m_time) VALUES (\'' . $mid . '\',\'' . $id . '\',\'' . time() . '\')';
//                    $res2 = Yii::app()->db->createCommand($sql)->execute();

                    if ($res == 1) {
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //评论提交成功
                    } else {
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //评论提交失败
                    }

                } else {
                    $arr = '';
                    $arr = explode(",", $mark);
                    $count = COUNT($arr);
                    if ($count >= 20) {
                        echo CJSON::encode(array('msg' => 'more')); //评论提交失败
                        exit;
                    }

                    if (in_array($mid, $arr)) {
                        echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR_AEXISTS)); //已存在
                    } else {

                        $mark = $mark . "," . $mid;

                        $sql_ud = 'UPDATE app_manage SET mark =\'' . $mark . '\' WHERE id = \'' . $id . '\' ';
                        $re = Yii::app()->db->createCommand($sql_ud)->execute();

//                        $sql = 'INSERT INTO app_mark_log (mid,uid,m_time) VALUES (\'' . $mid . '\',\'' . $id . '\',\'' . time() . '\')';
//                        $res2 = Yii::app()->db->createCommand($sql)->execute();

                        if ($re == 1) {
                            echo CJSON::encode(array('msg' => AjaxBack::DATA_SUCCESS)); //评论提交成功
                        } else {
                            echo CJSON::encode(array('msg' => AjaxBack::DATA_ERROR)); //评论提交失败
                        }
                    }
                }

                $t->commit();

            } catch (Exception $e) {
                $t->rollback();
                echo CJSON::encode(array('msg' => 10)); //评论提交失败
                exit;
            }
        }


    }

    /**
     * @name 追踪用户状态列表
     */
    public function actionCheckMark()
    {
        Script::registerScriptFile(Script::JQUERY_TOOLS);
        Script::registerScriptFile('manage/memberpool.public/memberpool.js');
        Script::registerScriptFile('manage/memberpool.public/AsyncBox.v1.4.js');
        Script::registerScriptFile('manage/memberpool.controller/memberinfo.checkmember.js');
        Script::registerCssFile('asyncbox.css');

        $arr = '';
        $id = Yii::app()->user->manage_id;;
        $sql = 'SELECT mark FROM app_manage WHERE id = \'' . $id . '\' ';
        $mark = Yii::app()->db->createCommand($sql)->queryAll();
        $mark = $mark[0]['mark'];
        if (empty($mark)) {
            $mark = 0;
        }

        $sql_mark = 'SELECT ' . '  mi.username,mi.category,mi.manage_id,mi.id
                     FROM app_member AS mi
                     WHERE FIND_IN_SET(mi.id,\'' . $mark . '\')';

//        $sql = 'SELECT '.'ml.*,mi.username,mi.category,mi.manage_id,mi.id FROM app_mark_log AS ml
//                JOIN app_member AS mi
//                ON ml.mid = mi.id';
        $model = Yii::app()->db->createCommand($sql_mark)->queryAll();
        $num = COUNT($model);

        $parm = array(
            'model' => $model,
            'num' => $num
        );
        $this->render('checkmark', $parm);
    }


    public function actionUpdateMarkMsg()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $mid = (int)Yii::app()->request->getParam('mid'); //用户id
            $id = Yii::app()->user->manage_id;;
            $str = '';
            $manage = Manage::model()->findByPk($id);

            $arr = '';
            $array = explode(",", $manage->mark);
            $count = COUNT($array);

            $del_value = $mid;
            unset($array[array_search($del_value, $array)]); //利用unset删除这个元素

            $str = implode(",", $array);

            $manage->mark = $str;

            $res = $manage->update();
            if ($res == 1) {
                echo CJSON::encode(array('msg' => 'success')); //评论提交成功
            } else {
                echo CJSON::encode(array('msg' => 'error')); //评论提交失败
            }
        }
    }


    /**
     * @name 放弃追踪用户
     */
    public function actionGiveUpThisMember()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $mid = (int)Yii::app()->request->getParam('mid'); //用户id
            $id = Yii::app()->user->manage_id;;
            $str = '';
            $manage = Manage::model()->findByPk($id);

            $arr = '';
            $array = explode(",", $manage->mark);
            $count = COUNT($array);

            $del_value = $mid;
            unset($array[array_search($del_value, $array)]); //利用unset删除这个元素

            $str = implode(",", $array);

            $manage->mark = $str;

            $res = $manage->update();
            if ($res == 1) {
                echo CJSON::encode(array('msg' => 'success')); //评论提交成功
            } else {
                echo CJSON::encode(array('msg' => 'error')); //评论提交失败
            }
        }
    }

    /**
     * @name 显示用户 -客服历史记录
     */
    public function actionShowManageList()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $mid = (int)Yii::app()->request->getParam('mid'); //用户id

            $id = Yii::app()->user->manage_id;;
            $sql = 'SELECT ' . 'ar.id AS arid,ar.mid,ar.content AS con,mi.username,max(ar.jointime) AS jointime FROM app_advisory_records AS ar
                    JOIN app_member AS mi
                    JOIN app_manage AS m
                    ON mi.id = ar.uid
                    WHERE mi.id = \'' . $mid . '\' GROUP BY ar.mid ORDER BY jointime DESC';

            $model = Yii::app()->db->createCommand($sql)->queryAll();

            if (isset($model[0]['mid'])) {

                foreach ($model AS $key => $item) {

                    $model[$key]['jointime'] = date("Y-m-d", $item['jointime']);
                    $mod = Manage::model()->findByPk($item['mid']);
                    $model[$key]['managename'] = $mod->username;
                    $model[$key]['con'] = ($item['con'] != '') ? $item['con'] : '无修改';
                }

                echo CJSON::encode($model); //评论提交成功
            } else {

                echo CJSON::encode(array('msg' => 'error')); //评论提交成功
            }
            exit;
        }
    }


    /**
     * @name 显示用户 -客服历史记录-客服回复查询
     */
    public function actionShowAdvRec()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $arid = (int)Yii::app()->request->getParam('arid'); //用户id

            $id = Yii::app()->user->manage_id;;
            $sql = 'SELECT content FROM app_advisory_records WHERE id = \'' . $arid . '\' ';
            $model = Yii::app()->db->createCommand($sql)->queryAll();


            if (isset($model[0]['content'])) {

                $content = $model[0]['content'];
                echo CJSON::encode(array('msg' => $content)); //评论提交成功
            } else {

                echo CJSON::encode(array('msg' => 'error')); //评论提交成功
            }
            exit;
        }
    }

    /**
     * @name 登录hy_wangbax_net
     */
    public function actionLoginHy()
    {
        Yii::app()->end();
    }
    /*
     * 客服查看用户收益
     * */
    public function actionGetmemberincome($id)
    {
        $member_model = new Member();
        $member_data = $member_model -> findByPk($id);
        Yii::app()->user->setState("member_uid",$id);
        Yii::app()->user->setState("member_manage",true);
        Yii::app()->user->setState("member_holder",$member_data->holder);
        Yii::app()->user->setState("member_username",$member_data->username);
        if($member_data["type"]==3)
        {
            $this->redirect(array("/ditui/default/income?id=".$id));
        }
        elseif($member_data["type"]==8)
        {
            $this->redirect(array("/newdt/default/income?id=".$id));
        }
        else
        {
            $this->redirect(array("/member/default/income?id=".$id));
        }

    }

    /**
     * 2017-09-19
     *修改已判定业务数据
     * zlb
     */
    public function actionOperation(){
        $param=array();
        if(!empty($_GET)){
            $data=$_GET;
            // print_r($data);exit;
            $model=Member::model();
            $data=$model->upsearch($data);
        }else{
            $model=Member::model();
            $data=$model->upsearch(array('row'=>0));
            // print_r($data);exit;
        }
        $this->render('operation',array('data'=>$data));
    }
    /**
     * 封号和解封
     * 
     */
    public function actionFenghao(){
        echo '<meta charset="utf-8">';
        if(isset($_GET['status']) && $_GET['status']==0){
            echo "<script>confirm('是否确定解封')?location.href='/dhadmin/member/feng?id=".$_GET['id']."&status=0':window.history.back(-1);</script>";
        }
        else if (isset($_GET['status']) && $_GET['status']==1) {
            echo "<script>confirm('是否确定封号')?location.href='/dhadmin/member/feng?id=".$_GET['id']."&status=1':window.history.back(-1);</script>";
        }
        

    }


    public function actionFeng(){
        echo '<meta charset="utf-8">';
        if(isset($_GET['id']) && isset($_GET['status'])){
            $sql="select finishdate,closeend from app_rom_appresource where id=".$_GET['id'];
            $data=yii::app()->db->createCommand($sql)->queryAll();
            if($_GET['status']==0){
                Yii::app()->db->createCommand()->update(
                      'app_rom_appresource',
                      array('finishstatus'=>1,'finishdate'=>date('Y-m-d',strtotime($data[0]['closeend'])),'closeend'=>'0000-00-00 00:00:00','finishtime'=>date('Y-m-d H:i:s')),
                      'id = :id',
                      array(':id'=>$_GET['id'])
                );
                //记录管理员操作日志
                $id=$_GET['id'];
                $uid=yii::app()->user->getState('uid');
                $log='[manage]'.$uid.'[管理员修改已判定业务数据][app_rom_appresource][解封][id]'.$id;
                NoteLog::addLog($log,$uid,0,$tag='修改判定业务',$update='');
                echo '<script>alert("操作成功");window.history.back(-2);</script>';

            }
            else if($_GET['status']==1){
                Yii::app()->db->createCommand()->update(
                      'app_rom_appresource',
                      array('finishstatus'=>0,'finishdate'=>'0000-00-00','closeend'=>date('Y-m-d H:i:s',strtotime($data[0]['finishdate'])),'finishtime'=>'0000-00-00 00:00:00'),
                      'id = :id',
                      array(':id'=>$_GET['id'])
                );
                //记录管理员操作日志
                $id=$_GET['id'];
                $uid=yii::app()->user->getState('uid');
                $log='[manage]'.$uid.'[管理员修改已判定业务数据][app_rom_appresource][封号][id]'.$id;
                NoteLog::addLog($log,$uid,0,$tag='修改判定业务',$update='');
                echo '<script>alert("操作成功");window.history.back(-2);</script>';
            }
            

        }
    }
    /**
     * 批量封号和解封
     * 
     */
    public function actionJiefeng(){
        set_time_limit(0);
        $id='';
        if(isset($_POST['id']) && isset($_POST['status']) && isset($_POST['date'])){
            $id=substr($_POST['id'],0,-1);
            if($_POST['status']==0){
                Yii::app()->db->createCommand()->update(
                      'app_rom_appresource',
                      array('finishstatus'=>1,'finishdate'=>date('Y-m-d',strtotime($_POST['date'])),'closeend'=>'0000-00-00 00:00:00','finishtime'=>date('Y-m-d H:i:s')),
                      'id in ('.$id.')'
                );
                //记录管理员操作日志
                $uid=yii::app()->user->getState('uid');
                $log='[manage]'.$uid.'[管理员修改已判定业务数据][app_rom_appresource][解封][id]'.$id;
                NoteLog::addLog($log,$uid,0,$tag='修改判定业务',$update='');
                echo 1;
            }
            else if($_POST['status']==1){
                Yii::app()->db->createCommand()->update(
                      'app_rom_appresource',
                      array('finishstatus'=>0,'finishdate'=>'0000-00-00','closeend'=>date('Y-m-d H:i:s',strtotime($_POST['date'])),'finishtime'=>'0000-00-00 00:00:00'),
                      'id in ('.$id.')'
                );
                //记录管理员操作日志
                $uid=yii::app()->user->getState('uid');
                $log='[manage]'.$uid.'[管理员修改已判定业务数据][app_rom_appresource][封号][id]'.$id;
                NoteLog::addLog($log,$uid,0,$tag='修改判定业务',$update='');
                echo 1;
            }
            
        }else {

            echo 2;
        }
        
    }

}