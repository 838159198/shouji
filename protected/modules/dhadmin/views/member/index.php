<style type="text/css" rel="stylesheet">
    .container-fluid {padding-right: 125px;padding-left: 105px;}
    ul,li{list-style-type: none;list-style: none;padding-left: 3px;}
    li{line-height: 40px;}
    .table-condensed{margin-top: 50px;}
    .input-sm{font-size: 14px;}
font{font-weight: bold;}
    #t_sbtn{margin-top: 30px;}
    .alert {padding: 2px;}
</style>
<?php
/* @var $this MemberController */
/* @var $dataProvider CActiveDataProvider */

/* @var $gets array */
/* @var $haveTaskUid string */
/* @var $advisoryRecordsList array */
/* @var $closeMemberResourceList array */
/* @var $memberCategoryList array */
/* @var $memberList Member[] */
/* @var $last_task array */
$memberList = $dataProvider->getData();

$this->breadcrumbs = array(
    '会员管理列表',
);
/**
 * @param $id
 * @return array
 */
function getManageMenu($id)
{
    $urls = array(
        'advisory' => CHtml::link('用户咨询记录', array('advisoryrecords/index', 'uid' => $id), array('target' => '_blank')),
        'graphs' => CHtml::link('曲线图', array('member/graphs', 'uid' => $id), array('target' => '_blank')),
        'update' => CHtml::link('修改用户信息', array('edit', 'id' => $id),array('target' => '_blank')),
        'getmemberincome' => CHtml::link('查看用户收益', array('getmemberincome', 'id' => $id),array('target' => '_blank')),

    );

    $menus = array();
    if (Auth::check('advisoryrecords_index')) $menus[] = $urls['advisory'];
    if (Auth::check('member_graphs')) $menus[] = $urls['graphs'];
    if (Auth::check('member_edit')) $menus[] = $urls['update'];
    if (Auth::check('member_getmemberincome')) $menus[] = $urls['getmemberincome'];

    $btn = '<div class="btn-group">'
        . '<a class="btn btn-info btn-mini dropdown-toggle" data-toggle="dropdown" href="javascript:;">'
        . '<i class="icon-cog icon-white"></i><span class="caret"></span></a>'
        . '<ul class="dropdown-menu" role="menu">';
    foreach ($menus as $m) {
        $btn .= '<li>' . $m . '</li>';
    }
    $btn .= '</ul></div>';
    return $btn;
}

function getAdvisoryTime($id, $list = array())
{
    if (isset($list[$id]) == false) return '';
    $jointime = $list[$id]['jointime'];
    $count = $list[$id]['count'];
    return DateUtil::dateFormate($jointime, 'Y-m-d H:i:s') . '(' . $count . ')';
}

function getCloseCount($id, $list = array())
{
    return isset($list[$id]) ? $list[$id] : '';
}

/**
 * @param $id
 * @param string $host
 * @return string
 */
function getLoginUrl($id, $host = 'local')
{
    $loginID = base64_encode($id . '_userid' . time());
    $url = '';
    switch ($host) {
        case 'local':
            $url = Yii::app()->createUrl('site/mlogin', array('uid' => $loginID));
            break;
    }
    return $url;
}

echo '<div class="page-header app_head"><h1 class="text-center text-primary">会员管理</h1></div>';

//Menu
$this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));

//Search
echo '<div class="search-form">';
$this->renderPartial('_search', array(
    'model' => $model,
    'gets' => $gets,
    'memberCategoryList' => $memberCategoryList,
));
echo '</div>';

$id = Yii::app()->user->manage_id;

$role = Manage::model()->getRoleByUid($id);

$sql_mark = 'SELECT mark FROM app_manage WHERE id = \'' . $id . '\'';
$mark = Yii::app()->db->createcommand($sql_mark)->queryAll();
$mark = $mark[0]['mark'];
$arr = '';
$arr = explode(",", $mark);
?>
<table class="table table-hover table-striped table-condensed" id='member'>
    <tr id='hidethis'>
        <th>任务申请</th>
        <th><label><input type="checkbox" value="1" id="member-info-grid-all"/></label></th>
        <th>ID</th>
        <th>用户名</th>
        <th>客服</th>
        <th>标记</th>
        <th>信息</th>
        <th>姓名</th>
        <th>用户类别</th>
        <th>用户身份</th>
        <th>所属销售</th>
        <th>任务状态</th>
        <!--<th>状态分类</th>-->
        <th>封号</th>
        <th>注册时间</th>
        <th>登陆时间</th>
        <th>联系实际</th>
        <th>管理</th>
    </tr>
    <?php
    foreach ($memberList as $member) {
        $tr = array();
        //checkbox
        $incode=Member::getInvitationcode(CHtml::encode($member->invitationcode));
        $usertype=Member::getXtype(CHtml::encode($member->type));
        //判定角色，是否显示此条记录
        if(($usertype=="<font color=#f0070c>线下门店</font>" || $usertype=="<font color=#f0070c>原线下门店</font>") && $incode!="" && in_array($role,array(9,10,11,12)))
        {

        }
        elseif(($usertype=="<font color=#f0070c>线下门店</font>" || $usertype=="<font color=#f0070c>原线下门店</font>") && $incode!="" && !in_array($role,array(9,10,11,12)) && ($id==40 || $id==3 || $id==26 || $id==31 || $id==14 || $id==1))
        {

        }
        elseif(($usertype=="<font color=#f0070c>线下门店</font>" || $usertype=="<font color=#f0070c>原线下门店</font>") && $incode!="" && !in_array($role,array(9,10,11,12)))
        {
            continue;
        }
        elseif(($usertype=="<font color=#f0070c>线下门店</font>" || $usertype=="<font color=#f0070c>原线下门店</font>") && $incode=="" && in_array($role,array(9,10,11,12)))
        {
            continue;
        }
        elseif(($usertype=="<font color=#f0070c>线下门店</font>" || $usertype=="<font color=#f0070c>原线下门店</font>") && $incode=="" && !in_array($role,array(9,10,11,12)))
        {

        }
        elseif(($usertype=="<font color=#f0070c>线下门店</font>" || $usertype=="<font color=#f0070c>原线下门店</font>") && in_array($role,array(9,10,11,12)))
        {
            continue;
        }
        elseif(($usertype=="<font color=#f0070c>线下门店</font>" || $usertype=="<font color=#f0070c>原线下门店</font>") && !in_array($role,array(9,10,11,12)))
        {

        }
        else
        {

        }

        if($member->type=="3" && !empty($incode))
        {
            $vtype=Member::getTaskStatus(CHtml::encode($member->username));
            if($vtype=="<font color=darkgreen>有效</font>")
            {
                $tr[] ="<font class='alert alert-success'>可以发布</font>";
                $tr[] = Auth::check('member_task') ? CHtml::checkBox('uids', false, array('value' => $member->username, 'mid' => $member->id)) : '';
            }
            else
            {
                $tr[] = "<font class='alert alert-danger'>不可发布</font>";
                $tr[] = "";
            }
        }
        else
        {
            $model = Member::model()->findByPk($member->id);
            if(!empty($model->manage_id)){//任务已经申请，则失去焦点
                $tr[] = CHtml::button('申请任务', array_merge(Bs::cls(Bs::BTN_DANGER),array('disabled'=>'disabled'), array('onclick' => 'ask_for_up(\'' . $member->id . '\',\'' . $role . '\')')));
            }else{
                $tr[] = CHtml::button('申请任务', array_merge(Bs::cls(Bs::BTN_DANGER),array('onclick' => 'ask_for_up(\'' . $member->id . '\',\'' . $role . '\')')));
            }
            
            $tr[] = Auth::check('member_task') ? CHtml::checkBox('uids', false, array('value' => $member->username, 'mid' => $member->id)) : '';
        }

        //id
        $tr[] = CHtml::encode($member->id);
        //username
        if (Auth::check('member_mlogin')) {
            $tr[] = CHtml::link($member->username, getLoginUrl($member->id), array('target' => '_blank'));
        } else {
            $tr[] = CHtml::encode($member->username);
        }

        $sql = 'SELECT name,mark FROM app_manage WHERE id = \'' . $member->manage_id . '\'';
        $manageName = Yii::app()->db->createcommand($sql)->queryAll();
        $tr[] = Auth::check('member_task') ? ((empty($member->manage_id) && ($member->manage_id == 0)) ?
                '' :
                "<div onclick = 'showManageList($member->id)'>" . $manageName[0]['name']) . '</div>' : '';

        //mark
        if (in_array($member->id, $arr)) {
            $tr[] = CHtml::link(Bs::ICON_SIGN);
        } else {
            $tr[] = CHtml::link(Bs::ICON_HOME, 'javascript:mark(' . $member->id . ')');
        }

        //info
        $tr[] = CHtml::link(Bs::ICON_SEARCH, 'javascript:show(' . $member->id . ')');

        //holder
        $tr[] = CHtml::encode($member->holder);
        //category
        $tr[] = getCloseCount($member->category, $memberCategoryList) . Bs::nbsp . CHtml::link(Bs::ICON_EDIT, 'javascript:;', array(
                'onclick' => 'category(' . $member->id . ',' . $member->category . ')'
            ));
        $tr[] = $usertype;
        $tr[] = $incode;

        //任务状态
        $tr[] = Member::getTaskStatus(CHtml::encode($member->username));

        //catelogue

/*        $sql_catid = 'SELECT name FROM app_catalogue WHERE id = \'' . $member->cataid . '\' AND status = 0';
        $catName = Yii::app()->db->createcommand($sql_catid)->queryAll();
        if (empty($catName)) {
            $catName = '';
        } else {
            $catName = $catName[0]['name'];
        }
        $tr[] = CHtml::link($catName . Bs::ICON_CATALOG, 'javascript:showCatalog(' . $member->id . ')');*/

        //closeResource
        $tr[] = getCloseCount($member->id, $closeMemberResourceList);
        //jointime
        $tr[] = DateUtil::getDate($member->jointime);
        //overtime
        $tr[] = DateUtil::getDate($member->overtime);
        //advisory
        $tr[] = getAdvisoryTime($member->id, $advisoryRecordsList);
        //menu
        $tr[] = getManageMenu($member->id);

        echo '<tr class = "hidethistoo" >';
        foreach ($tr as $td) {
            echo '<td>', $td, '</td>';
        }
        echo '</tr>';
    }
    ?>
</table>

<div class="text-center">
    <?php $this->widget('CLinkPager', array(
        'pages' => $dataProvider->getPagination(),
    )) ?>
</div>

<div style="padding:10px;"></div>

<div id="modalcategory" title="修改用户类别" style="display:none;">
    <?php echo CHtml::beginForm($this->createUrl('member/category'), 'post', Bs::cls(Bs::FORM_INLINE)),
    CHtml::label('用户类别：', 'm_category'),
    CHtml::dropDownList('m_category', '', MemberCategory::model()->getListToArray()),
    CHtml::hiddenField('m_uid'), Bs::nbsp,
    CHtml::submitButton('保存', Bs::cls(Bs::BTN_INFO)),
    CHtml::endForm();?>
</div>

<div id="modaltask" title="发布任务" style="display:none;">
    <?php echo CHtml::beginForm('task', 'post', array('id' => 't_formtask')) ?>
    <?php echo CHtml::hiddenField('t_uname') ?>
    <?php echo CHtml::hiddenField('t_mid') ?>
    <label id="suname"></label>
    <dl class="dl-horizontal">
        <!--        <dt>标题（50字）：</dt>-->
        <!--        <dd>--><?php //echo CHtml::textField('t_title') ?><!--</dd>-->
        <dt>任务类型：</dt>
        <dd><?php echo CHtml::dropDownList('type', '', Task::getTypeList(Task::TYPE_VISIT, Task::TYPE_NEW/*, Task::TYPE_DROP*/)) ?></dd>
        <!--        <dt>说明（200字）：</dt>-->
        <!--        <dd>--><?php //echo CHtml::textArea('t_content') ?><!--</dd>-->
        <dt>接收人：</dt>
        <dd><?php echo CHtml::dropDownList('t_accept', '', array()) ?></dd>
        <dt>&nbsp;</dt>
        <dd><?php echo CHtml::button('发布', Bs::cls(Bs::BTN_INFO) + array('id' => 't_sbtn')) ?></dd>
    </dl>
    <?php echo CHtml::endForm() ?>
</div>

<div id="last_action" title="最近发布的任务信息" style="display:none;">
    <dl class="dl-horizontal">
        <table class="table table-hover table-striped table-condensed">
            <tr style="font-weight:bold;">
                <th>发布人</th>
                <th>用户名</th>
                <th>接收客服</th>
                <th>发布时间</th>
            </tr>
            <?php $str = DefaultParm::DEFAULT_EMPTY; ?>
            <?php foreach ($last_task AS $item) { ?>
                <tr>
                    <td><?php $publish_name = Manage::model()->getNameById($item['publish']);
                        echo $publish_name ?></td>
                    <td><?php echo $item['username'] ?></td>
                    <td><?php $accept_name = Manage::model()->getNameById($item['accept']);
                        echo $accept_name ?></td>
                    <td><?php echo date('Y-m-d H:i l', $item['createtime']) ?></td>
                </tr>
                <?php $str .= $item['id'] . ','; ?>
            <?php } ?>
        </table>
        <dt>&nbsp;</dt>
        <input type='hidden' value='<?php echo empty($last_task) ? '' : $last_task[0]['createtime'] ?>' id='last_time'>
        <input type='hidden' value='<?php echo $str ?>' id='last_time_id_list'>
        <dd><?php echo CHtml::button('确认撤销', Bs::cls(Bs::BTN_INFO) + array('id' => 't_repeal')) ?></dd>
    </dl>
</div>


<div id="add_member_tree" title="创建用户分类" style="display: none;">
    <label id="catalog_type"></label>
    <input type='hidden' value='' id='nowrank'>
    <dl class="dl-horizontal">
        <dt>标题（50字）：</dt>
        <dd><?php
            echo CHtml::textField('catalogue_title')?></dd>
        <dt>说明（100字）：</dt>
        <dd><?php
            echo CHtml::textArea('catalogue_content')?></dd>
        <dt>&nbsp;</dt>
        <dd><?php
            echo CHtml::button('确认添加', array_merge(Bs::cls(Bs::BTN_DANGER), array('id' => 'ADDTOPTREE')))?></dd>

    </dl>
</div>


<div id="ShowMsg" title="用户状态分类详情" style="display:none;">
    <input type='hidden' value='' id='thiscatid'>
    <dt>标题：</dt>
    <dd><?php
        echo CHtml::textField('title')?></dd>
    <dt>说明</dt>
    <dd>
        <textarea rows="5" cols="10" id='msg_content'></textarea>
    </dd>
    <dt>&nbsp;</dt>
    <dd>
        <button class="btn btn-success" onclick="updateCatalogue()" id='updateCatalogue'>确认修改</button>
    </dd>
</div>



<div id="ShowManageList" title="客服历史记录" style="display:none;">
    <div id='sendin'></div>
</div>

<div id="ShowADVREC" title="客服留言/修改详情查看" style="display:none;">

    <textarea rows="5" cols="10" id='msg_content_adv'></textarea>

</div>


<script type="text/javascript">
    var MEMBER_INFO_URL = '<?php echo $this->createUrl('info') ?>';
    var MEMBER_MARK = '<?php echo $this->createUrl('mark') ?>';
    var TASK_INFO_URL = '<?php echo $this->createUrl('taskinfo') ?>';
    var TASK_POST_URL = '<?php echo $this->createUrl('task') ?>';
    var REPEAL_TASK = '<?php echo $this->createUrl('member/repeal') ?>';

    var MI_SET_CAT = '<?php echo $this->createUrl('member/setMemberCatalogue') ?>';
    var MI_DEL_CAT = '<?php echo $this->createUrl('member/delMemberCatalogue') ?>';
    var MI_SHOW_FATHER = '<?php echo $this->createUrl('member/showCatalogueFid') ?>';
    var MI_SHOW_MA_LIST = '<?php echo $this->createUrl('member/showManageList') ?>';
    var MI_SHOW_ADV_REC = '<?php echo $this->createUrl('member/showAdvRec') ?>';


    var MC_SHOWNEXT = '<?php echo $this->createUrl('membercategory/showNext') ?>';
    var MC_ADDTREE = '<?php echo $this->createUrl('membercategory/addTree') ?>';
    var MC_ADDTOPTREE = '<?php echo $this->createUrl('membercategory/addTopTree') ?>';
    var MC_SHOWMSG = '<?php echo $this->createUrl('membercategory/showMsg') ?>';
    var MC_DELMSG = '<?php echo $this->createUrl('membercategory/delMsg') ?>';

    var MC_UPMSG = '<?php echo $this->createUrl('membercategory/updateCataMsg') ?>';


</script>
