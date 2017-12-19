<?php
$this->breadcrumbs = array(
    '我的用户池' => array('IndexNoPro'),
    '任务提醒列表' => array('TaskType'),
    $title => array('RefuseTask'),
);
?>

<?php
function getManageMenu($id)
{
    $urls = array('advisory' => CHtml::link('用户咨询记录', array('advisoryrecords/index', 'uid' => $id), array('target' => '_blank')),
        'graphs' => CHtml::link('曲线图', array('member/graphs', 'uid' => $id), array('target' => '_blank')),
        'update' => CHtml::link('修改用户信息', array('member/edit', 'id' => $id), array('target' => '_blank')),
        'log' => CHtml::link('修改信息历史记录', array('member/log', 'id' => $id), array('target' => '_blank')),
    );


    $menus = array();
    if (Auth::check('advisoryrecords_index'))
        $menus [] = $urls ['advisory'];
    if (Auth::check('member_graphs'))
        $menus [] = $urls ['graphs'];
    if (Auth::check('member_edit'))
        $menus [] = $urls ['update'];
    if (Auth::check('member_log'))
        $menus [] = $urls ['log'];


    $btn = '';
    foreach ($menus as $m) {
        $btn .= '<li>' . $m . '</li>';
    }
    return $btn;

}

?>

    <h4 class="text-center"><span style="color:black"><?php echo $title ?></h4>


    <table class="table table-hover table-striped table-condensed">
        <tr>
            <?php //if (isset($allow) && ($allow == AskTask::IS_ALLOW_FALSE)) { ?>
                <th><input type='checkbox' id='check_all' onclick="selectAll(this);">全选</th>
            <?php //} ?>
            <th>用户名</th>
            <th>用户信息</th>
            <th>任务类别</th>
            <th>申请时间</th>
            <th>管理员回复</th>
            <th>相关信息</th>
        </tr>
        <?php foreach ($data AS $item) { ?>
            <tr>
                <?php //if (isset($allow) && ($allow == 0)) { ?>
                    <td><input type='checkbox' name='del' value='<?php echo $item ['id'] ?>'></td>
                <?php //} ?>
                <td><?php echo $item['username'] ?></td>
                <td>
                    <i style = 'cursor:pointer'  class="glyphicon glyphicon-search" onclick = "show(<?php echo $item['mid'] ?>)"><span id="clickst"></span></i>
                </td>
                <td>
                    <?php switch ($item['type']) {
                        case Task::TYPE_NEW:
                            echo '新用户任务';
                            break;
/*                        case Task::TYPE_DROP:
                            echo '降量任务';
                            break;
                        case Task::TYPE_WEEK:
                            echo '周任务';
                            break;*/
                        case Task::TYPE_OTHER:
                            echo '其他任务';
                            break;
                        case Task::TYPE_VISIT:
                            echo '回访任务';
                            break;
                    }?>
                </td>
                <td><?php echo date('Y-m-d', $item['a_time']) ?></td>
                <td onclick='showmsg(this)' value='<?php echo $item['content']; ?>'>
                    <?php echo mb_substr($item['content'], 0, 4, 'utf-8') . '...' ?>
                </td>

                <?php $arr_list = getManageMenu($item['mid']); ?>
                <td>
                    <div class='btn-group' id=bt_<?php echo $item['mid']; ?>>
                        <a class='btn btn-info btn-mini dropdown-toggle' onclick="mylist(<?php echo $item['mid']; ?>)";>
                        <i class='icon-cog icon-white'></i><span class='caret'></span></a>
                        <ul class="dropdown-menu" id='msg_<?php echo $item['mid']; ?>'>
                            <?php echo $arr_list; ?>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php if (isset($allow) && ($allow == AskTask::IS_ALLOW_FALSE)) { ?>
    <?php
    echo CHtml::button('删除被拒绝任务', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'del_asktask()')));
    ?>
<?php } ?>
<?php if (isset($allow) && ($allow == AskTask::IS_ALLOW_WAIT)) { ?>
    <?php
    echo CHtml::button('删除待批准任务', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'del_waittoallow()')));
    ?>
<?php } ?>

<?php $this->renderPartial('/layouts/pop') ?>