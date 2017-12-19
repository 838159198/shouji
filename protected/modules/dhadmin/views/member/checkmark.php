<?php
$this->breadcrumbs = array('用户追踪列表' => array(''));
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

<h4 class="text-center">用户追踪列表</h4>
<span style='float:right;color:#0099FF'>（共<?php echo $num ?>条任务）</span>

<table class="table table-hover table-striped table-condensed">
    <tr>
        <th>用户名</th>
        <th>用户类型</th>
        <th>用户详情</th>
        <th>相关信息</th>
        <th>用户所属客服</th>
        <th>放弃追踪</th>
    </tr>
    <?php foreach ($model as $item) { ?>

        <tr>
            <td><?php echo $item ['username'] ?></td>


            <td>
                <?php echo Member::getTypeName1($item ['category']); ?>
                <?php
                echo Bs::nbsp . CHtml::link(Bs::ICON_EDIT, 'javascript:;', array('onclick' => 'category(' . $item ['id'] . ',' . $item ['category'] . ')'))?>

            </td>
            <td>
                <i style = 'cursor:pointer'  class="glyphicon glyphicon-search" onclick = "show(<?php echo $item['id'] ?>)"><span id="clickst"></span></i>
            </td>
            <?php $arr_list = getManageMenu($item ['id']) ?>
            <td>
                <div class='btn-group' id= "bt_">
                    <a class='btn btn-info btn-mini dropdown-toggle'
                        onclick="mylist(<?php echo $item ['id'];?>)">
                    <i class='icon-cog icon-white'></i>
                    <span class='caret'></span></a>
                    <ul class="dropdown-menu" id='msg_<?php echo $item ['id']; ?>'>
                        <?php echo $arr_list; ?>
                    </ul>
                </div>
            </td>

                <?php
                if(empty($item ['manage_id'])){
                    $name = '无所属客服';
                    $html = "<span class='label label-info'  style='margin-top:5px;'><a style='color: white' href='#'>$name </a></span>";
                    echo "<td>$html</td>";
                }else{
                    $uid = $item ['manage_id'];
                    $mid = $item ['id'];

                    $name = Manage::model()->getNameById($item ['manage_id']);
                    $html = "<span class='label label-info'  style='margin-top:5px;'><a style='color: white' href='#' onclick='checkTaskStatus($uid,$mid)'>$name </a></span>";
                    echo "<td>$html</td>";
                }
                ?>
            <td>
                <?php echo  CHtml::button('放弃追踪', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'give_up_this_member(\'' . $item['id'] . '\')')));
                ?>

            </td>
        </tr>
    <?php } ?>
</table>



‭
<?php $this->renderPartial('/layouts/pop')?>


 