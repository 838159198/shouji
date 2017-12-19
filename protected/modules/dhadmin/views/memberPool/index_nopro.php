<?php
$this->breadcrumbs = array(
    '我的用户池' => array('index'),
);
$url = $this->createUrl('myTask/weekly');
$status = $_SERVER["QUERY_STRING"]; //路径参数
$changepool = $this->createUrl('memberPool/indexSpare');
$topool = $changepool . '?' . $status;
$MP_VISITE = $this->createUrl('memberPool/visit');
$MP_DROP = $this->createUrl('memberPool/dropTask');
$MT_WEEKLY = $this->createUrl('mytask/weekly');
$TASK_TYPE = $this->createUrl('memberPool/taskType');
?>
<?php
function getManageMenu($id, $tw_id, $category, $now)
{
    if ($now == 1) {
        $urls = array(
            'advisory' => CHtml::link('用户咨询记录', array('advisoryrecords/index', 'uid' => $id), array('target' => '_blank')),
            'graphs' => CHtml::link('曲线图', array('member/graphs', 'uid' => $id), array('target' => '_blank')),
            'update' => CHtml::link('修改用户信息', array('member/edit', 'id' => $id), array('target' => '_blank')),
            'log' => CHtml::link('修改信息历史记录', array('member/log', 'id' => $id), array('target' => '_blank')),
            'remind' => '<a href="#" onclick =remind(\'' . $id . '\',\'' . $tw_id . '\') >我的备注信息</a>',
            'changetype' => '<a href="#" onclick =category(' . $id . ',' . $category . ')>修改用户类型</a>',

        );
    } else {
        $urls = array(
            'advisory' => CHtml::link('用户咨询记录', array('advisoryrecords/index', 'uid' => $id), array('target' => '_blank')),
            'graphs' => CHtml::link('曲线图', array('member/graphs', 'uid' => $id), array('target' => '_blank')),
            'update' => CHtml::link('修改用户信息', array('member/edit', 'id' => $id), array('target' => '_blank')),
            'log' => CHtml::link('修改信息历史记录', array('member/log', 'id' => $id)), array('target' => '_blank'));
    }
    $menus = array();
    if (Auth::check('advisoryrecords_index')) $menus[] = $urls['advisory'];
    if (Auth::check('member_graphs')) $menus[] = $urls['graphs'];
    if (Auth::check('member_edit')) $menus[] = $urls['update'];
    if (Auth::check('member_log')) $menus[] = $urls['log'];
    if ($now == 1) {
        $menus[] = $urls['remind'];
        $menus[] = $urls['changetype'];
    }

    $btn = '';
    foreach ($menus as $m) {
        $btn .= '<li>' . $m . '</li>';
    }
    return $btn;

}
?>

<div class="bs-docs-example">
    <ul class="nav nav-pills">
        <!--<li class="dropdown active" >
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                周任务信息
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href='<?php /*echo $url . '/week/' . WeekTask::THIS_WEEK */?>'>需标记<?php /*echo $kind_num['least'] */?>
                        条任务为本周周任务</a>
                </li>
                <li>
                    <a href='<?php /*echo $url . '/week/' . WeekTask::THIS_WEEK */?>'>已标记<?php /*echo $kind_num['this'] */?>条本周周任务</a>
                </li>
                <li>
                    <a href='<?php /*echo $url . '/week/' . WeekTask::NEXT_WEEK */?>'>已标记<?php /*echo $kind_num['next'] */?>条下一周周任务</a>
                </li>
            </ul>
        </li>-->
        <li class="blur_this active"><a href='memberpoolBak'  target="_blank">备选用户池</a></li>
        <li class="dropdown active">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                页面快捷导航
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <!--<li class="blur_this" value='5'><a href='<?php /*echo $topool */?>'>我的备选用户池</a></li>-->
                <li class="blur_this" value='10'><a href='<?php echo $TASK_TYPE; ?>'>任务提醒列表</a></li>
                <li class="blur_this" value='5'><a href='<?php echo $MP_VISITE; ?>'>回访任务</a></li>
                <!--<li class="blur_this" value='2'><a href='<?php /*echo $MP_DROP; */?>'>降量任务</a></li>-->
                <!--<li class="blur_this" value='6'><a href='<?php /*echo $MT_WEEKLY; */?>'>周任务</a>-->
                    <ul class="blur_this" id='showweeklist' style=" display: none">
                        <li><a href='<?php echo $MT_WEEKLY; ?>/week/1'>上周</a></li>
                        <li><a href='<?php echo $MT_WEEKLY; ?>/week/2'>本周</a></li>
                        <li><a href='<?php echo $MT_WEEKLY; ?>/week/3'>下周</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <?php $url = $this->createUrl('IndexNoPro');?>
        <li class="dropdown active">
            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                任务分类
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li><a href='<?php echo $url . '/tstatus/' . Task::TYPE_VISIT ?>'>只看回访任务</a></li>
                <li><a href='<?php echo $url . '/tstatus/' . Task::TYPE_NEW ?>'>只看新用户任务</a></li>
                <!--<li><a href='<?php /*echo $url . '/tstatus/' . Task::TYPE_DROP */?>'>只看降量任务</a></li>-->
            </ul>
        </li>
        <?php if ($now == 1) {
            if ($rank == 0) {?>
                <li class = 'active'><a href='<?php echo $url . '/rank/1' ?>'>按用户等级排序</a></li>
            <?php } else { ?>
                <li class = 'active'><a href='<?php echo $url . '/rank/0' ?>'>按提醒时间排序</a></li>
            <?php } ?>
                <li class="blur_this active" value='6'><a href='#' onclick="checkPro()">查看已上报任务</a></li>

             <?php if (Auth::check('memberpool_delvisitetaskall')) {?>
            <li class="blur_this active"><a href='#' onclick='delvisitetaskall(<?php if(!empty($data)) { foreach ($data as $key=>$val) { $arr1[$key] =$val['at_id']; }$totl_id=""; $totl_id .= implode(",",$arr1).",";echo '"'.$totl_id.'"';} ?>)'>释放此页用户</a></li>
            <?php } ?>

        <li>
            <div class="input-prepend">
                <div class="btn-group">
                    <button class="btn dropdown-toggle btn-primary" data-toggle="dropdown">
                        条件查找
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li class="blur_this" value='5'><a href='#' onclick='searchtype("name")' >按用户名查找</a></li>
                        <li class="blur_this" value='2'><a href='#' onclick='searchtype("remind")'>提醒时间查找</a></li>
                        <li class="blur_this" value='2'><a href='#' onclick='searchtype("youxiao")'>有效回访查询</a></li>
                        <input type = 'hidden' id = 'searchtype' value="">
                    </ul>
                </div>

                <input type='text' class="span2" style = 'width:200px' name='member_info' id='member_info' value='';>
                <input type = 'text' style = 'display: none' lang="date" name = 'remind_time' id = 'remind_time'>
                <input type = 'text' style = 'display: none' lang="date" name = 'remind_time1' id = 'remind_time1'>
                <input type = 'text' style = 'display: none' lang="date" name = 'youxiao_time' id = 'youxiao_time'>
                <input type = 'text' style = 'display: none' lang="date" name = 'youxiao_time1' id = 'youxiao_time1'>
                <button class="btn btn-primary" type="button" onclick="searchMyMember(0)">Go!</button>
            </div>
        </li>
        <?php } ?>
    </ul>
</div>


<?php
    $ucount=WeekTask::model()->checkConByMonths(100,Yii::app()->user->manage_id);
?>
<div style="clear:both;margin-top:10px">
    <h3 class="text-center" style="font-weight: bold;"><span style="color:#0099FF"><?php echo $name ?></span>的用户池</h3>
    <span style='float:right;color:#0099FF'>（上月用户总<?php echo $ucount ?>、共<?php echo $num ?>条任务

        <?php if ($role == Role::PRACTICE_STAFF) { ?>
            +<?php echo $vnum ?>条已放弃有效回访任务
        <?php } ?>
        ）
        <?php if ($role == Role::PRACTICE_STAFF) {
            $mids=Yii::app()->user->manage_id;
            $weeekcounts=RomAppresource::model()->memberPoolSeeCount($mids);
            ?>
            <span style="font-weight: bold;color: red">上周<?php echo $weeekcounts[0][0]["counts"]; ?>条有效回访，本周<?php echo $weeekcounts[1][0]["counts"]; ?>条有效回访</span>
        <?php } ?>

    </span>
    <hr>
</div>
<?php $param_id=Yii::app()->request->getParam("id"); ?>
<table class="table table-hover table-striped table-condensed" id = 'memberpool'>
    <tr class = 'ready_show' id = 'hidethis'>
<!--        <th>-->
<!--            <span class="label label-info" style='margin-top:5px;' onclick="changeFromPoolToSpace(1)">-->
<!--                    <a style='color:white' href='#'>备选</a>-->
<!--            </span>-->
<!--        </th>-->
        <th >用户名</th>
        <th >提醒</th>
        <th >排名</th>
        <th >信息</th>
        <th >任务类别</th>
        <th >用户类型</th>
        <!--<th >用户状态</th>-->
        <!--  <th>平均收益/天</th>  -->
        <th >当天收益</th>
        <th >可获收益</th>
        <th >月/周比</th>
        <th >发布时间</th>
        <th >详情</th>
        <th >相关信息</th>
        <th >活动报名</th>
        <?php if ($now == 1) { ?>
            <th>上报/申请</th>
        <?php } ?>
        <?php /*if ($role == Role::PRACTICE_STAFF || (!empty($param_id))) { */?>
            <th>安装量</th>
        <?php /*} */?>
        <?php if ($role == Role::PRACTICE_STAFF) { ?>
            <th>有效回访</th>
        <?php } ?>

    </tr>
    <input type='hidden' value='<?php echo $now; ?>' id='now'>

    <?php foreach ($data as $member) { ?>
        <?php $time = date('Y-m-d', time());
        if (isset($member['remind']) && ($member['tw_status'] != 1) && ($member['remind'] != 0) && ($time >= date('Y-m-d', $member['remind']))) {
            ?>
            <tr style="color:#FF6FB7;font-weight:bold;" class = 'hidethistoo'>
        <?php } else if ($member['wt_id'] != 0) { ?>
            <tr style="color:#C9BEE2;font-weight:bold;" class = 'hidethistoo'>
        <?php } else { ?>
            <tr class = 'hidethistoo'>
        <?php } ?>

<!--        <td><input type = 'checkbox' name = 'tospace' value = '--><?php //echo $member['tw_id']?><!--'></td>-->
        <td><?php echo $member['username'] ?></td>

        <?php if (isset($member['remind']) && ($member['remind'] != 0)) { ?>
            <td>
                <?php echo date('Y-m-d', $member['remind']); ?>
            </td>
        <?php } else { ?>
            <td>
                无
            </td>
        <?php } ?>

        <td value = 'byebye'>
            <?php echo $member['important'];
            $mem = $member['mid']?>

        </td>

        <td>
            <i style = 'cursor:pointer'  class="glyphicon glyphicon-search" onclick = "show(<?php echo $member['mid'] ?>)"><span id="clickst"></span></i>
        </td>
        <?php
            $task_type = '';
            switch ($member['type'])
            {
                case 1:
                    $task_type = '新用户任务';
                    break;
/*                case 2:
                    $task_type = '降量任务';
                    break;*/
                case 5:
                    $task_type = '回访任务';
                    break;
                case '':
                    $task_type = '';
                    break;
            }
        ?>
        <td><?php echo $task_type;?></td>
        <td>
            <?php
            $category = '';
            switch ($member['category'])
            {
                case 1:
                    $category = '已在做';
                break;
                case 2:
                    $category = '未上量';
                break;
                case 3:
                    $category = '无效';
                break;
                case 4:
                    $category = '待定';
                break;
                case 5:
                    $category = '流失中';
                break;
                case '':
                    $category = '';
                break;

            }

            ?>
            <a  style = 'color: #000000;cursor:pointer' onclick = "category(<?php echo $member['mid'] ?>)"><?php echo $category?><i class="icon-edit"></i></a>

        </td>
<!--        <td>
            <?php
/*            $sql_catid = 'SELECT name FROM app_catalogue WHERE id = \'' . $member['cataid'] . '\' AND status = 0';
            $catName = Yii::app()->db->createcommand($sql_catid)->queryAll();
            if(empty($catName)){
                $catName = '';
            }else{
                $catName = $catName[0]['name'];
            }
            echo CHtml::link($catName.Bs::ICON_CATALOG, 'javascript:showCatalog(' . $member['mid'] . ')');

            */?>
        </td>-->
        <?php $data = TaskWhen::getDataByNow($member['mid'], $member['type'], $member['a_time'], $role,$member['at_id']); ?>

        <?php if ($member['type'] == 5) { ?>
            <td>无</td>
        <?php } else { ?>
            <td>
                <div style='float:left;margin-right:5px;'>
                    <div>发布：<?php echo round($data['the_day'], 3) ?></div>
                    <div>前9天：<?php echo round($data['yesterday'], 3) ?></div>
                </div>
                <div style='margin-left:8px;float:left;width:30px;height:40px;text-align:center;line-height:40px;'>
                    <img src='<?php echo $data['img'] ?>';>
                </div>
            </td>
        <?php } ?>
        <td>
            <?php if ($member['type'] == 5) {
                echo '无收益';
            } else {
                echo round($data['data'], 3);
            }?>
        </td>
        <td>
            <?php
                echo round($data['week_ratio'], 3)."%";
            ?>
        </td>

        <td><?php echo date("Y-m-d", $member['createtime']) ?></td>

        <td>
            <?php if ($member['wt_id'] != 0) { ?>
                <i class="glyphicon glyphicon-lock"></i>
            <?php } elseif($role==7 || !empty($param_id)){ ?>
                无
            <?php } else { ?>
                    <?php $root_url = $this->createUrl('MyTask'); ?>
                    <a target="_blank" href="<?php echo $root_url ?>/tid/<?php echo $member['tid']; ?>/uid/<?php echo $id; ?>">
                        <i class="glyphicon glyphicon-th-list"></i>
                    </a>
            <?php } ?>
        </td>

        <?php $arr_list = getManageMenu($member['mid'], $member['tw_id'], $member['category'], $now); ?>
        <td>
            <div class='btn-group' id=bt_<?php echo $member['mid']; ?>>
                <a class='btn btn-info btn-mini dropdown-toggle' onclick="mylist(<?php echo $member['mid']; ?>)";>
                <i class='icon-cog icon-white'></i><span class='caret'></span></a>
                <ul class="dropdown-menu" id='msg_<?php echo $member['mid']; ?>'>
                    <?php echo $arr_list; ?>
                </ul>
            </div>
        </td>
        <td>
            <?php
                $camdate=Campaign::model()->find('"'.$time.'">=userstarttime and "'.$time.'" <=endtime');
            if(!empty($camdate))
            {
                $cammember=CampaignLog::model()->find('cid=:cid and uid=:uid and status=1',array(':uid'=>$member['mid'],':cid'=>$camdate['periods']));
            }

            ?>
            <?php if (empty($camdate)) { ?>
               无活动
            <?php }  elseif(!empty($cammember)) { ?>
            <span class="label label-success">已报名</span>
            <?php }  else { ?>
            <span class="label label-default">未报名</span>
            <?php } ?>
        </td>
        <?php if ($member['type'] == 5) { ?>
           <?php if (($role ==7)) { ?>
            <td>
      		<span class="btn btn-info btn-sm">
				<div style='color:white' >申请</div>
			</span>
			<span class="btn btn-info btn-sm">
				<div style='color:white' onclick='delvisitetask(<?php echo $member['at_id']; ?>)'>无效</div>
			</span>
            </td>
               <?php } elseif (!empty($param_id)) { ?>

        <?php } else { ?>

            <td>
      		<span class="btn btn-info btn-sm">
				<div style='color:white;' onclick='askforvisitetask(<?php echo $member['at_id']; ?>)'>申请</div>
			</span>
			<span class="btn btn-info btn-sm">
				<div style='color:white;' onclick='delvisitetask(<?php echo $member['at_id']; ?>)'>无效</div>
			</span>
            </td>
        <?php } ?>




        <?php } else { ?>
            <?php if ($now == 1) { ?>
                <td><button class="btn btn-primary" type="button">无操作</button>
<!--       		<?php /*if ($member['wt_id'] == 0) { */?>
                <button class="btn btn-primary" type="button" onclick='proweekly(<?php /*echo $member['at_id']; */?>,<?php /*echo $kind_num['this'] */?>,
                <?php /*echo $kind_num['next'] */?>,<?php /*echo $role */?>)'>周任务</button>
            <?php /*} else { */?>
                <button class="btn btn-danger" type="button">已标记</button>
            --><?php /*} */?>
                </td>
            <?php } ?>
        <?php } ?>

        <?php /*if ($role ==7 || (!empty($param_id))) { */?>
        <td>
            <div style='float:left;margin-right:5px;' class="getweekst">
                <button class="btn btn-primary" type="button" name="<?php echo $member['mid']; ?>">查看</button>
            </div>
        </td>
        <?php /*} */?>

        <?php if (($role ==7)&&(($member['type']==5) || ($member['type']==1))) { ?>
            <td>
                <?php if ($member['availability'] == 1) { ?>
                    <button class="btn btn-primary" type="button" >有效</button>

                <?php } else if ($member['availability'] == 2)  { ?>

                    <button class="btn btn-danger" type="button" onclick='askforvisitetask2(<?php echo $member['at_id']; ?>)'>无效</button>

                <?php } else { ?>

                    <button class="btn btn-danger" type="button" onclick='askforvisitetask2(<?php echo $member['at_id']; ?>)'>申请</button>
                <?php } ?>
            </td>
        <?php } ?>



        </tr>
    <?php } ?>



</table>
<?php $this->renderPartial('/layouts/remind') ?>
<?php $this->renderPartial('/layouts/pop') ?>

‭
<div class="pager">
    <?php $this->widget("CLinkPager", array(
        'pages' => $pages,
        'firstPageLabel' => '首页',
        'lastPageLabel' => '末页',
        'maxButtonCount' => 15
    ));?>
</div>

<input type='hidden' value='<?php echo $week_type ?>' id='week_type'>
<input type='hidden' value='<?php echo $role ?>' id='role'>
<input type='hidden' value='0' id='towhere'>






<div id="add_member_tree" title="创建用户分类" style="display: none;">
    <label id="catalog_type"></label>
    <input type = 'hidden' value = '' id = 'nowrank'>
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


<!--<div id="ShowMsg" title="用户状态分类详情" style="display:none;">
    <input type = 'hidden' value = '' id = 'thiscatid'>
    <dt>标题：</dt>
    <dd><?php
/*        echo CHtml::textField('title')*/?></dd>
    <dt>说明</dt>
    <dd>
        <textarea rows="5" cols="10" id='msg_content'></textarea>
    </dd>
    <dt>&nbsp;</dt>
    <dd>
        <button class="btn btn-success" onclick="updateCatalogue()" id = 'updateCatalogue'>确认修改</button>
    </dd>
</div>-->


<script type="text/javascript">
    var MI_SET_CAT = '<?php echo $this->createUrl('member/setMemberCatalogue') ?>';
    var MI_DEL_CAT = '<?php echo $this->createUrl('member/delMemberCatalogue') ?>';
    var MI_SHOW_FATHER = '<?php echo $this->createUrl('member/showCatalogueFid') ?>';

    var MC_SHOWNEXT = '<?php echo $this->createUrl('membercategory/showNext') ?>';
    var MC_ADDTREE = '<?php echo $this->createUrl('membercategory/addTree') ?>';
    var MC_ADDTOPTREE = '<?php echo $this->createUrl('membercategory/addTopTree') ?>';
    var MC_SHOWMSG = '<?php echo $this->createUrl('membercategory/showMsg') ?>';
    var MC_DELMSG = '<?php echo $this->createUrl('membercategory/delMsg') ?>';
    var MC_UPMSG = '<?php echo $this->createUrl('membercategory/updateCataMsg') ?>';
</script>

<script type="text/javascript">
    $(function () {
        $("#remind_time").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#remind_time").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#remind_time1").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#remind_time1").datepicker("option", "maxDate", selectedDate);
            }
        });

    });

    function askforvisitetask2(at_id){
        var at_id = at_id;
        var url = MP_VISITE_VTASK;
        asyncbox.confirm(question_before_action.MAKE_SURE_YOUX_AND_CHANGE,title.QUESTION);
        $("#asyncbox_confirm_ok").click(function () {
            $.post(url,{at_id:at_id,availability:1},function(data){
                var obj = (new Function("return " + data))();
                if((obj.msg == data_back.DATA_SUCCESS)){
                    asyncbox.alert(data_back_msg.DATA_SUCCESS,title.TITLE_SUCCESS);
                    location.reload();
                }else if(obj.msg == data_back.DATA_ERROR){
                    asyncbox.alert(data_back_msg.DATA_ERROR,title.TITLE_ERROR);
                    return;
                }
            });
        })
    }

    $('button.btn[name]').click(function(){
        var thisval=$(this);
        var mid=$(this).attr('name');
        var url = 'getWeeekcount';
        $.post(url,{mid:mid},function(data){
            var obj = (new Function("return " + data))();
            if((obj.msg != 0)){
                thisval.css("display","none");
                thisval.parent().append('<div>上周：'+obj.msg[0][0]["counts"]+'</div><div>本周：'+obj.msg[1][0]["counts"]+'</div>');
            }else {
                return;
            }
        });
    });

</script>