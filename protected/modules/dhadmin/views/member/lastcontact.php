<style type="text/css">
    .container-fluid {
        padding-right: 125px;
        padding-left: 105px;
    }
    .btn-group-vertical>.btn, .btn-group>.btn {
        float: none;
    }
    .btn-info:hover, .btn-info:focus, .btn-info:active, .btn-info.active, .btn-info.disabled, .btn-info[disabled] {
        color: #fff;
        font-weight: bold;
        background-color: #2f96b4;
    }
    .ui-dialog a:hover,a:-webkit-any-link{text-decoration: none;}
    .btn-info {
        color: #fff;
        text-shadow: 0 -1px 0 rgba(0,0,0,0.25);
        background-color: #49afcd;
        background-image: -moz-linear-gradient(top,#5bc0de,#49afcd);
        background-image: -webkit-gradient(linear,0 0,0 100%,from(#5bc0de),to(#49afcd));
        background-image: -webkit-linear-gradient(top,#5bc0de,#49afcd);
        background-image: -o-linear-gradient(top,#5bc0de,#49afcd);
        background-image: linear-gradient(to bottom,#5bc0de,#49afcd);
        background-repeat: repeat-x;
        border-color: #49afcd #49afcd #1f6377;
        border-color: rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5bc0de',endColorstr='#ff2f96b4',GradientType=0);
        filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
    }
    .dl-horizontal dd{text-decoration: none;}

    #t_sbtn2{margin-top: 30px;}
</style>
<div class="page-header app_head"><h1 class="text-center text-primary">会员联络统计</h1></div>
<?php
function getManageMenu($id,$category)
{
    $urls = array(
        'advisory' => CHtml::link('用户咨询记录', array('advisoryrecords/index', 'uid' => $id), array('target' => '_blank')),
        'graphs' => CHtml::link('曲线图', array('member/graphs', 'uid' => $id), array('target' => '_blank')),
        'update' => CHtml::link('修改用户信息', array('edit', 'id' => $id),array('target' => '_blank')),

    );

    $menus = array();
    if (Auth::check('advisoryrecords_index')) $menus[] = $urls['advisory'];
    if (Auth::check('member_graphs')) $menus[] = $urls['graphs'];
    if (Auth::check('member_edit')) $menus[] = $urls['update'];

    $btn = '<div class="btn-group" id="clickst">'
        . '<a class="btn btn-info btn-mini dropdown-toggle" data-toggle="dropdown" href="javascript:;">'
        . '<i class="icon-cog icon-white"></i><span class="caret"></span></a>'
        . '<ul class="dropdown-menu" role="menu">';
    foreach ($menus as $m) {
        $btn .= '<li>' . $m . '</li>';
    }
    $btn .= '</ul></div>';
    return $btn;
}

?>

<?php
$changepool     = $this->createUrl('memberPool/indexSpare');
$checkcategory  = $this->createUrl('member/memberLastContaceTime');
?>
<?php
$url = $this->createUrl('member/memberLastContaceTime');

$url1 = $url;

$category_name = isset($category) ? $category : 0;

?>

<!--------用户类别开始------------->
<!--------用户类别开始------------->
<div>

    <div class="btn-group">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
            用户类别<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li onclick='checkoutCategory(0)'><a href='#'>未标记(<?php echo $noCategory?>条)</a></li>
            <?php foreach($arr AS $item){?>

               <?php $type = $item['type'];?>
                <li onclick='checkoutCategory(<?php echo $type; ?>)'><a href='#'><?php  echo $item['name'] ?>(<?php echo $item['count'] ?>条)</a></li>
            <?php }?>
        </ul>
    </div>

    +

    <div class="btn-group">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
            最后关注时间<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li onclick='checkoutMounth(<?php echo '3'; ?>)'><a href='#'>3个月内</a></li>
            <li onclick='checkoutMounth(<?php echo '6'; ?>)'><a href='#'>3-6个月内</a></li>
            <li onclick='checkoutMounth(<?php echo '9'; ?>)'><a href='#'>6-9个月内</a></li>
            <li onclick='checkoutMounth(<?php echo '12'; ?>)'><a href='#'>9-12月内</a></li>
            <li onclick='checkoutMounth(<?php echo '13'; ?>)'><a href='#'>一年以上</a></li>
            <li onclick='checkoutMounth(<?php echo '0'; ?>)'><a href='#'>从未联络</a></li>
        </ul>
    </div>

    <div class="btn-group">

            <input type="text" lang="date" id = 'start' name = 'start'>

            <input type="text" lang="date" id = 'end'   name = 'end' >

            <input type = 'button' value="注册时间" class = 'btn btn-info' onclick="checkoutRegisterTime()">
    </div>
    <br><br>
    <div class="btn-group">

        <input type = 'button' value="用户名：" class = 'btn btn-info'><input type="text"  id = 'username' name = 'username' style="margin-left:10px; margin-top:10px;">

    </div>


    <br><br>


    <br><br>
    <label>业务分类
        <?php echo CHtml::dropDownList('workType', '', array(""=>"","0"=>"线上","3"=>"线下"), Bs::cls(Bs::INPUT_SMALL)) ?>
    </label>
    <br><br>


    <div>
        <div class="btn btn-info" id = 'catego'></div>
        <div class="btn btn-info" id = 'mounth'></div>
        <div class="btn btn-info" id = 'timecheck_s'></div>
        <div class="btn btn-info" id = 'timecheck_e'></div>
        <div class="btn btn-info" id = 'username_c'></div>
        <div class="btn btn-info" id = 'workType'></div>

        <input type = 'hidden' val = '' id = 'category_t'>
        <input type = 'hidden' val = '' id = 'mounth_t'>
        <input type = 'hidden' val = '' id = 'timecheck_st'>
        <input type = 'hidden' val = '' id = 'timecheck_et'>
        <input type = 'hidden' val = '' id = 'username_ct'>
        <input type = 'hidden' val = '' id = 'workType'>


        <div class="btn btn-info" id = 'check' onclick="chekcStatus()">GO!!!</div><br><br>
    </div>

</div>


<!--------用户类别结束------------->
<!--------用户类别结束------------->

<!---------title开始--------------->
<!---------title开始--------------->

<?php
//获取类型内容
if($category==0){
    $type = '未标记';
}else{
    $type = MemberCategory::model()->findByPk($category);
    $type = $type->name;
}
?>
<div style="clear:both;margin-top:10px">
    <h4 class="text-center" style="font-weight: bold;"><span style="color:#0099FF"></span>用户联络统计（<?php echo $type ?>）
</div>
<span style='float:right;color:#0099FF'>（共<?php  echo $num;?>条数据）</span>
<div><?php echo CHtml::button('发布任务', Bs::cls(Bs::BTN_INFO) + array('onclick' => 'GetCheckbox(this)')) ?></div>

<!---------title结束--------------->
<!---------title结束--------------->


<!---------列表开始--------------->
<!---------列表开始--------------->
<table class="table table-hover table-striped table-condensed">
    <tr>
        <th><input type='checkbox' id='member-info-grid-all'>全选</th>
        <th>用户名</th>
        <th>所属销售</th>
        <th>注册时间
            <a href="#"><img src="/images/memberpool/2.jpg" onclick='orderByJoinTime(0)'></a>
            <a href="#" style='margin-left: 5px'>
                <img src="/images/memberpool/1.jpg" onclick='orderByJoinTime(1)'>
            </a>
        </th>
        <th>最后登录时间
            <a href="#"><img src="/images/memberpool/2.jpg" onclick='orderByOverTime(0)'></a>
            <a href="#" style='margin-left: 5px'>
                <img src="/images/memberpool/1.jpg" onclick='orderByOverTime(1)'>
            </a>
        </th>
        <th>所属客服</th>
        <th>最后关注时间
            <a href="#"><img src="/images/memberpool/2.jpg" onclick='orderByManageJoinTime(0)'></a>
            <a href="#" style='margin-left: 5px'>
                <img src="/images/memberpool/1.jpg" onclick='orderByManageJoinTime(1)'>
            </a>
        </th>
        <th>用户信息</th>
        <th>相关信息</th>
    </tr>
    <?php foreach ($list AS $key => $item) { ?>
        <tr>
            <td><input type='checkbox' name='selectdsend' value='<?php echo $item ['id'] ?>'
                       atid='<?php echo $item ['id'] ?>'
                    <?php if (!empty($item['manage_id']) && ($item['manage_id'] != 0)) { ?>
                        DISABLED
                    <?php } ?> >
            </td>
            <td><?php echo $item['username'] ?></td>
            <td><?php
                    $minfo=Member::model()->find('id=:id',array(":id"=>$item ['id']));
                    echo Member::getInvitationcode(CHtml::encode($minfo['invitationcode']));
                ?></td>


            <td><?php $jt = ($item['jt'] !='')?date('Y-m-d H:i:s', $item['jt']):'';echo $jt; ?></td>
            <?php $overtime = isset($item['overtime']) && $item['overtime'] != 0 ? date('Y-m-d H:i:s', $item['overtime']) : '从未登陆'; ?>
            <td><?php echo $overtime ?></td>
            <?php if (!empty($item['manage_id']) && ($item['manage_id'] != 0)) {
                $name = Manage::model()->getNameById($item['manage_id']);
            } else {
                $name = '&nbsp;';
            }?>
            <td onclick = 'showManageList(<?php echo $item ['id'] ?>)' ><?php echo $name ?></td>
            <?php $res = !empty($item['mjt']) ? date('Y-m-d H:i:s', $item['mjt']) : '无记录'; ?>
            <td><?php echo $res; ?></td>
            <td>
                <div class='btn-group' id='bt_<?php echo $item ['id']; ?>'>
                    <a class='btn btn-info btn-mini dropdown-toggle'
                       onclick="show(<?php echo $item ['id']; ?>)";>
                    <i class='icon-cog icon-white'></i>
                    <span class='caret'></span>
                    </a>

                </div>
            </td>
            <?php
            $cat = isset($item['category'])?$item['category']:'';
            $arr_list = getManageMenu($item['id'],$cat);
            ?>
            <td>
                <div class='btn-group' id=bt_<?php echo $item['id']; ?>>
                    <a class='btn btn-info btn-mini dropdown-toggle' onclick="mylist(<?php echo $item['id']; ?>)";>
                    <i class='icon-cog icon-white'></i><span class='caret'></span></a>
                    <ul class="dropdown-menu" id='msg_<?php echo $item['id']; ?>'>
                        <?php echo $arr_list; ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php } ?>
</table>


<!---------列表结束--------------->
<!---------列表结束--------------->
<div class="pager">
    <?php $this->widget("CLinkPager", array(
        'pages' => $pages,
        'firstPageLabel' => '首页',
        'lastPageLabel' => '末页',
        'maxButtonCount' => 15
    ));?>
</div>


‭
<script type="text/javascript">
    var STAT            = '<?php echo $_SERVER["QUERY_STRING"];?>';		//路径参数
    var MI_CHECK_MEMBER_BY_CATEGORY = '<?php echo $this->createUrl('member/memberLastContaceTime') ?>';
    var newUrl = '<?php echo $url = Yii::app()->request->getHostInfo().Yii::app()->request->url;?>';
    var thisUrl         = '<?php echo $this->createUrl('') ?>';
    var URL = '<?php echo Yii::app()->request->getHostInfo().Yii::app()->request->url ?>';
    var REG_TIME    = '<?php echo $this->createUrl('member/checkMemberByRegister') ?>';
    var MI_SHOW_MA_LIST = '<?php echo $this->createUrl('member/showManageList') ?>';
    var teamCheck = '<?php echo $this->createUrl('member/memberLastContaceTime') ?>';
    var SEND_TASK = '<?php echo $this->createUrl('member/sendTask') ?>';
    var MI_SHOW_ADV_REC = '<?php echo $this->createUrl('member/showAdvRec') ?>';

</script>

<div id="sendtask" title="发布任务" style="display:none;">
    <?php echo CHtml::beginForm('task', 'post', array('id' => 't_formtask')) ?>
    <?php echo CHtml::hiddenField('member_id_list') ?>
    <?php echo CHtml::hiddenField('t_mid') ?>
    <label id="suname"></label>
    <dl class="dl-horizontal">

        <dt>任务类型：</dt>
        <dd><?php echo CHtml::dropDownList('type', '', Task::getTypeList(Task::TYPE_VISIT, Task::TYPE_NEW, Task::TYPE_DROP)) ?></dd>

        <dt>接收人：</dt>
        <?php $manage_list = Task::model()->manageCheckList(); ?>
        <dd><select id='manage_list'>
                <?php foreach ($manage_list as $_list) { ?>
                    <?php $role = Manage::model()->getRoleByUid($_list ['id']) ?>
                    <option id="<?php echo $_list ['id'] ?>" role='<?php echo $role ?>'
                            value="<?php echo $_list ['name'] ?>">
                        <?php echo $_list ['name']; ?>(<?php echo $_list ['rname']; ?>)
                    </option>
                <?php } ?>
            </select>
            <input type='hidden' id='to_manage' value=''>
            <input type='hidden' id='manager' value='<?php echo $this->uid ?>'>
        </dd>
        <dt>&nbsp;</dt>
        <dd><?php echo CHtml::button('发布', Bs::cls(Bs::BTN_INFO) + array('id' => 't_sbtn2')) ?></dd>
    </dl>
    <?php echo CHtml::endForm() ?>
</div>


<div id="ShowManageList" title="客服关注历史记录" style="display:none;">
    <div id = 'sendin'></div>
</div>

<div id="ShowADVREC" title="客服留言/修改详情查看" style="display:none;">
    <textarea rows="5" cols="10" id='msg_content_adv'></textarea>
</div>
<script type="text/javascript">
    var MEMBER_INFO_URL = '<?php echo $this->createUrl('info') ?>';
    $(function () {
        $("#start").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#start").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#end").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#end").datepicker("option", "maxDate", selectedDate);
            }
        });

        //$(a).removeAttr("href");

    });
</script>