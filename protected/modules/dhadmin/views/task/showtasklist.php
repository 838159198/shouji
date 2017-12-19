<?php
function getManageMenu($id, $tw_id, $category)
{
    $urls = array(
        'advisory' => CHtml::link('用户咨询记录', array('advisoryrecords/index', 'uid' => $id), array('target' => '_blank')),
        'graphs' => CHtml::link('曲线图', array('member/graphs', 'uid' => $id), array('target' => '_blank')),
        'update' => CHtml::link('修改用户信息', array('member/edit', 'id' => $id), array('target' => '_blank')),
        'log' => CHtml::link('修改信息历史记录', array('member/log', 'id' => $id), array('target' => '_blank')),
        'remind' => '<a href="#" onclick =remind(\'' . $id . '\',\'' . $tw_id . '\') >我的备注信息</a>',
        'changetype' => '<a href="#" onclick =category(' . $id . ',' . $category . ')>修改用户类型</a>',

    );

    $menus = array();
    if (Auth::check('advisoryrecords_index')) $menus[] = $urls['advisory'];
    if (Auth::check('member_graphs')) $menus[] = $urls['graphs'];
    if (Auth::check('member_edit')) $menus[] = $urls['update'];
    if (Auth::check('member_log')) $menus[] = $urls['log'];
    $menus[] = $urls['remind'];
    $menus[] = $urls['changetype'];

    $btn = '';
    foreach ($menus as $m) {
        $btn .= '<li>' . $m . '</li>';
    }
    return $btn;

}

?>
<!--/*********************多条件查询开始****************************/-->


<div class="input-prepend">
    <div class="btn-group">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
            客服列表 <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <?php foreach ($manage_list AS $manage_key => $item) { ?>
                <li onclick="sendMsg('manage_id','<?php echo $item->username; ?>','<?php echo $item->id; ?>')"><a
                        href="#"><?php echo $item->username; ?></a></li>
            <?php } ?>
        </ul>
    </div>


    <div class="btn-group">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
            任务类型 <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <?php foreach ($tasks_type AS $tasks_key => $val) { ?>
                <li onclick="sendMsg('task_type','<?php echo $val ?>','<?php echo $tasks_key ?>')"><a
                        href="#"><?php echo $val ?></a></li>
            <?php } ?>
        </ul>
    </div>


    <div class="btn-group">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
            任务状态 <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li onclick="sendMsg('task_status','申请等待审批中','0')"><a href="#">申请中等待审核</a></li>
            <li onclick="sendMsg('task_status','正在执行中','1')"><a href="#">正在执行中</a></li>
            <li onclick="sendMsg('task_status','已上报完成（上报成功）','2')"><a href="#">已上报完成（上报成功）</a></li>
            <li onclick="sendMsg('task_status','已上报完成（上报失败）','3')"><a href="#">已上报完成（上报失败）</a></li>
            <li onclick="sendMsg('task_status','已完成（管理员查看并评分）','4')"><a href="#">已完成（管理员查看并评分）</a></li>
        </ul>
    </div>


    <div class="btn-group">
        <button class="btn dropdown-toggle btn-info" data-toggle="dropdown">
            日期/用户查找<span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li class="blur_this" value='5'><a href='#' onclick="sendMsg('member_name','name')">按用户名查找</a></li>
            <li class="blur_this" value='2'><a href='#' onclick="sendMsg('task_sendtime','send')">任务发布/申请日期</a></li>
            <li class="blur_this" value='2'><a href='#' onclick="sendMsg('task_protime','pro')">任务上报日期</a></li>
            <input type='hidden' id='searchtype' value="">
        </ul>
    </div>

    <input type='text'  name='member_info' id='member_info' value='';>
    <input type='text' style='display: none' lang="date" name='remind_time' id='time_send_s'>
    <input type='text' style='display: none' lang="date" name='remind_time1' id='time_send_e'>
    <input type='text' style='display: none' lang="date" name='remind_time' id='time_pro_s'>
    <input type='text' style='display: none' lang="date" name='remind_time1' id='time_pro_e'>
    <!--        <button class="btn btn-info" type="button" onclick="searchMyMember(0)">Go!</button>-->
</div>

<!--/*********************多条件查询开始****************************/-->
<?php $action = $this->createUrl('task/showTaskList') ?>

<form action="<?php echo $action; ?>" method="get">

    <input type="hidden" value="" id='manage_id' name='manage_id'>
    <input type="hidden" value="" id='task_type' name='task_type'>
    <input type="hidden" value="" id='task_status' name='task_status'>
    <input type="hidden" value="" id='task_sendtime_start' name='task_sendtime_start'>
    <input type="hidden" value="" id='task_sendtime_end' name='task_sendtime_end'>
    <input type="hidden" value="" id='task_protime_start' name='task_protime_start'>
    <input type="hidden" value="" id='task_protime_end' name='task_protime_end'>
    <input type="hidden" value="" id='member_name' name='member_name'>


    <input type='submit' value='开始查询' class="<?php echo Bs::BTN_DANGER ?>">
    <?php
    // echo CHtml::submit('开始查询', array_merge(Bs::cls(Bs::BTN_DANGER)));

    //echo CHtml::button('开始查询', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'startToCheck()')));
    ?>

</form>
<div>
    <div class="active" role="presentation" id='manage_id_show' style='display: none'>

        <a href="#" onclick="delThisOp(this)" class='manage_id'><span class="badge">X</span></a>
        <span class="label label-info">客服姓名</span>
        <span id='manage_id_'>yysonice</span>
    </div>

    <div class="active" role="presentation" id='task_type_show' style='display: none'>

        <a href="#" onclick="delThisOp(this)" class='task_type'><span class="badge">X</span></a>
        <span class="label label-info">任务类型</span>
        <span id='task_type_'>新用户任务</span>
    </div>

    <div class="active" role="presentation" id='task_status_show' style='display: none'>

        <a href="#" onclick="delThisOp(this)" class='task_status'><span class="badge">X</span></a>
        <span class="label label-info">任务状态</span>
        <span id='task_status_'>申请中等待审核</span>
    </div>

    <div class="active" role="presentation" id='task_sendtime_show' style='display: none'>

        <a href="#" onclick="delThisOp(this)" class='task_sendtime'><span class="badge">X</span></a>
        <span class="label label-info">发布日期</span>
        <span id='task_sendtime_s'></span>
        <span class="label label-info">至</span>
        <span id='task_sendtime_e'></span>
    </div>

    <div class="active" role="presentation" id='task_protime_show' style='display: none'>

        <a href="#" onclick="delThisOp(this)" class='task_protime'><span class="badge">X</span></a>
        <span class="label label-info">上报日期</span>
        <span id='task_protime_s'></span>
        <span class="label label-info">至</span>
        <span id='task_protime_e'></span>
    </div>

    <div class="active" role="presentation" id='member_name_show' style='display: none'>

        <a href="#" onclick="delThisOp(this)" class='member_name'><span class="badge">X</span></a>
        <span class="label label-info">用户姓名</span>
        <span id='member_name_'>asdf</span>
    </div>
</div>

<!--/*********************多条件查询结束****************************/-->


<h4 class="text-center">任务查询</h4>
<div><a href='#' style='float: right'>（共<?php echo $num ?>条任务）</a></div>
<hr>
<table class="table table-hover table-striped table-condensed">
    <tr>
        <th>所属客服</th>
        <th>用户名</th>
        <th>用户信息</th>
        <!--<th>用户状态</th>-->
        <th>用户类型</th>
        <th>任务收益(总计:<span style = 'color: red'><?php echo $sum .'元'?></span>)</th>
        <th>收益对比</th>
        <th>发布时间</th>
        <th>上报时间</th>
        <th>相关信息</th>
    </tr>
    <?php foreach ($data AS $k => $va) { ?>
        <tr>
            <td><?php echo $va['mname'] ?></td>
            <td><?php echo $va['username'] ?></td>
            <td>
                <i style = 'cursor:pointer'  class="glyphicon glyphicon-search" onclick = "show(<?php echo $va['m_id'] ?>)"><span id="clickst"></span></i>
            </td>
            <?php $sql_catid = 'SELECT name FROM app_catalogue WHERE id = \'' . $va['cataid'] . '\' AND status = 0';
            $catName = Yii::app()->db->createcommand($sql_catid)->queryAll();
            if (empty($catName)) {
                $catName = '';
            } else {
                $catName = $catName[0]['name'];
            }?>
           <!-- <td><?php /*echo CHtml::link($catName . Bs::ICON_CATALOG, 'javascript:showCatalog(' . $va['m_id'] . ')') */?></td>-->
            <td>
                <?php
                $category = '';
                switch ($va['category']) {
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
                echo $category . Bs::nbsp . CHtml::link(Bs::ICON_EDIT, 'javascript:;', array(
                        'onclick' => 'category(' . $va['m_id'] . ',' . $va['category'] . ')'
                    ));
                ?>
            </td>
            <td>
                <div>
                    <div><?php echo '任务实获收益  :' . round($va['pay_back'], 3) . '  元'; ?></div>
                </div>
            </td>
            <td>
                <div>
                    <div><?php echo '发布  :  ' . round($va['a_pay'], 3) . '  元'; ?></div>
                    <div><?php echo '上报  :  ' . round($va['b_pay'], 3) . '  元'; ?></div>
                </div>
            </td>
            <td><?php echo date("Y-m-d", $va['allow_time']) ?></td>
            <?php if (!empty($va['porttime'])) {
                $time = date("Y-m-d", $va['porttime']);
            } else {
                $time = '未上报';
            } ?>
            <td><?php echo $time ?></td>
            <?php $arr_list = getManageMenu($va['m_id'], $va['tw_id'], $va['category']); ?>
            <td>
                <div class='btn-group' id=bt_<?php echo $va['m_id']; ?>>
                    <a class='btn btn-info btn-mini dropdown-toggle' onclick="mylist(<?php echo $va['m_id']; ?>)";>
                    <i class='icon-cog icon-white'></i><span class='caret'></span></a>
                    <ul class="dropdown-menu" id='msg_<?php echo $va['m_id']; ?>'>
                        <?php echo $arr_list; ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php } ?>
</table>
<div class="pager">
    <?php
    $this->widget("CLinkPager", array('pages' => $pages, 'firstPageLabel' => '首页', 'lastPageLabel' => '末页', 'maxButtonCount' => 15));
    ?>
</div>


<?php $this->renderPartial('/layouts/pop') ?>



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

    $(function () {
        $("#time_send_s").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#time_send_s").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#time_send_e").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#time_send_e").datepicker("option", "maxDate", selectedDate);
            }
        });

        $("#time_pro_s").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#time_pro_s").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#time_pro_e").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#time_pro_e").datepicker("option", "maxDate", selectedDate);
            }
        });

    });
</script>
<!--<div id="add_member_tree" title="创建用户分类" style="display: none;">
    <label id="catalog_type"></label>
    <input type='hidden' value='' id='nowrank'>
    <dl class="dl-horizontal">
        <dt>标题（50字）：</dt>
        <dd><?php
/*            echo CHtml::textField('catalogue_title')*/?></dd>
        <dt>说明（100字）：</dt>
        <dd><?php
/*            echo CHtml::textArea('catalogue_content')*/?></dd>
        <dt>&nbsp;</dt>
        <dd><?php
/*            echo CHtml::button('确认添加', array_merge(Bs::cls(Bs::BTN_DANGER), array('id' => 'ADDTOPTREE')))*/?></dd>

    </dl>
</div>-->


<!--<div id="ShowMsg" title="用户状态分类详情" style="display:none;">
    <input type='hidden' value='' id='thiscatid'>
    <dt>标题：</dt>
    <dd><?php
/*        echo CHtml::textField('title')*/?></dd>
    <dt>说明</dt>
    <dd>
        <textarea rows="5" cols="10" id='msg_content'></textarea>
    </dd>
    <dt>&nbsp;</dt>
    <dd>
        <button class="btn btn-success" onclick="updateCatalogue()" id='updateCatalogue'>确认修改</button>
    </dd>
</div>-->


<!--<div id="showMyPower" title="用户状态分类设置" style="display:none;">
    <input type='hidden' id='thisMemberId' value='';>
    <?php /*$model = Catalogue::model()->getIdByFid(0); */?>
    <div>
        <dl class="dl-horizontal">
            <div>
                <div class="dropdown" id='tree_1' style='float:left'>
                    <a class="btn btn-primary btn-large" href="#" data-toggle="dropdown">
                        顶级状态树
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu" rank='1' value='got you!'>
                        <?php /*foreach ($model AS $key => $val) { */?>
                            <li onclick="shownext(<?php /*echo $val['id'] */?>,<?php /*echo $val['fid']; */?>,this)"
                                style='width:200px;' name='<?php /*echo $val['name'] */?>'>
                                <a tabindex="-1" href="#" style='float: left;' value='<?php /*echo $val['id'] */?>'
                                   fid='<?php /*echo $val['fid']; */?>'>
                                    <?php /*echo $val['name']; */?>
                                    <div style='float: right;'>
                                        <input type='radio' name='setThis' value='<?php /*echo $val['id'] */?>'>
                                        <a href='#' style='margin-right: 5px;'
                                           onclick="ShowCatalogue(<?php /*echo $val['id'] */?>,<?php /*echo $val['fid']; */?>,1)"><i
                                                class='icon-eye-open'></i></a>
                                        <a href='#' style='margin-right: 5px;'
                                           onclick="DelCatalogue(<?php /*echo $val['id'] */?>,<?php /*echo $val['fid']; */?>,1)"><i
                                                class="icon-remove"></i></a>
                                        <a href='#' style='margin-right: 5px;'
                                           onclick="AddCatalogue(<?php /*echo $val['id'] */?>,<?php /*echo $val['fid']; */?>,1)"><i
                                                class="icon-plus-sign"></i></a>
                                    </div>
                                </a>
                            </li>
                        <?php /*} */?>
                        <li>
                            <a tabindex="-1" href="#" onclick="addTopTree()">添加顶级状态树</a>
                        </li>
                    </ul>
                </div>


                <div class="dropdown" id='tree_2' style='float:left;display: none' isshow='0'>
                    <a class="btn btn-primary btn-large" href="#" data-toggle="dropdown">
                        二级状态树
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu" rank='2'>
                    </ul>
                </div>

                <div class="dropdown" id='tree_3' style='float:left;display: none' isshow='0'>
                    <a class="btn btn-primary btn-large" href="#" data-toggle="dropdown">
                        三级状态树
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu" rank='3'>
                    </ul>
                </div>
            </div>
            <div style='clear:both'>


                <div class="dropdown" id='tree_2' style='float:left;display: none' isshow='0'>
                    <a class="btn btn-primary btn-large" href="#" data-toggle="dropdown">
                        二级状态树
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu" rank='2'>
                    </ul>
                </div>

                <div class="dropdown" id='tree_3' style='float:left;display: none' isshow='0'>
                    <a class="btn btn-primary btn-large" href="#" data-toggle="dropdown">
                        三级状态树
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu" rank='3'>
                    </ul>
                </div>
            </div>
            <div style='clear:both;margin-top: 20px;'>

                <dt>所属顶级状态</dt>
                <dd><input type='text' id='theTopTree' value=''></dd>
                <dt>所属二级状态</dt>
                <dd><input type='text' id='theSecTree' value=''></dd>
                <dt>所属三级状态</dt>
                <dd><input type='text' id='theThrTree' value=''></dd>
                <div style='margin-top: 30px;'>

                    <div class="btn-group">
                        <button class="btn btn-danger" id="DelThisMemberCatalogue" onclick="delMemberCata">删除用户已选状态
                        </button>
                        <button class="btn btn-success" id='setCatalogue'>确认设置</button>
                    </div>

                </div>
            </div>
        </dl>
    </div>
</div>-->

<div id="modalcategory" title="修改用户类别" style="display:none;">
    <?php echo CHtml::beginForm($this->createUrl('member/category'), 'post', Bs::cls(Bs::FORM_INLINE)),
    CHtml::label('用户类别：', 'm_category'),
    CHtml::dropDownList('m_category', '', MemberCategory::model()->getListToArray()),
    CHtml::hiddenField('m_uid'), Bs::nbsp,
    CHtml::submitButton('保存', Bs::cls(Bs::BTN_INFO)),
    CHtml::endForm();?>
</div>