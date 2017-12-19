<?php
$this->breadcrumbs = array('逾期未联系用户任务列表' => array(''));
?>
<?php

function getManageMenu($id)
{
    $urls = array('advisory' => CHtml::link('用户咨询记录', array('advisoryrecords/index', 'uid' => $id), array('target' => '_blank')),
        'graphs' => CHtml::link('曲线图', array('member/graphs', 'uid' => $id), array('target' => '_blank')),
        'update' => CHtml::link('修改用户信息', array('member/edit', 'id' => $id), array('target' => '_blank')),
        'log' => CHtml::link('修改信息历史记录', array('member/log', 'id' => $id), array('target' => '_blank')));


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

<h4 class="text-center">逾期未联系用户列表</h4>
<span style='float:right;color:#0099FF'>（共<?php echo $last_contact['num'] ?>条任务）</span>

<table class="table table-hover table-striped table-condensed">
        <tr>
            <th>用户名</th>
            <th>最后关注时间</th>
            <th>用户详情</th>
            <th>相关信息</th>
            <th>跳转用户池</th>
        </tr>
    <?php foreach ($last_contact as $item) { ?>

        <tr>
            <td><?php echo $item ['name'] ?></td>
            <td><?php echo date("Y-m-d ", $item ['time']) ?></td>
            <td>
                <i style = 'cursor:pointer'  class="glyphicon glyphicon-search" onclick = "show(<?php echo $item['mid'] ?>)"><span id="clickst"></span></i>
            </td>
            <?php $arr_list = getManageMenu($item ['mid']) ?>
            <td>
                <div class='btn-group' id=bt_<?php ?>>
                    <a
                        class='btn btn-info btn-mini dropdown-toggle'
                        onclick="mylist(<?php echo $item ['mid'];?>)";>
                    <i class='icon-cog icon-white'></i><span class='caret'></span></a>
                    <ul class="dropdown-menu" id='msg_<?php echo $item ['mid']; ?>'>
                        <?php echo $arr_list; ?>
                    </ul>
                </div>
            </td>
            <td>
                <?php
                if($item['spare']==1){
                    $url = $this->createUrl('memberPool/indexSpare');
                }else{
                    $url = $this->createUrl('memberPool/indexNoPro');
                }
                ?>
                <span class="label label-info" style='margin-top: 5px;'>
			         <a style='color: white' href='<?php echo $url.'/member/'.$item ['name']?>'>跳转</a>
                </span>
            </td>

        </tr>
<?php } ?>
</table>

<hr>
<hr>
<h4 class="text-center">从未联系用户</h4>
<span style='float:right;color:#0099FF'>（共<?php echo $never_contact['num'] ?>条任务）</span>

<table class="table table-hover table-striped table-condensed">
    <tr>
        <th>用户名</th>
        <th>用户详情</th>
        <th>相关信息</th>
        <th>跳转用户池</th>
    </tr>
    <?php foreach ($never_contact as $val) { ?>

        <tr>
            <td><?php echo $val ['username'] ?></td>
<!--            <td>--><?php //echo date("Y-m-d ", $item ['time']) ?><!--</td>-->
            <td>
                <i style = 'cursor:pointer'  class="glyphicon glyphicon-search" onclick = "show(<?php echo $val['mid'] ?>)"><span id="clickst"></span></i>
            </td>
            <?php $arr_list = getManageMenu($val ['mid']) ?>
            <td>
                <div class='btn-group' id=bt_<?php ?>>
                    <a
                        class='btn btn-info btn-mini dropdown-toggle'
                        onclick="mylist(<?php echo $val ['mid'];?>)";>
                    <i class='icon-cog icon-white'></i><span class='caret'></span></a>
                    <ul class="dropdown-menu" id='msg_<?php echo $val ['mid']; ?>'>
                        <?php echo $arr_list; ?>
                    </ul>
                </div>
            </td>
            <td>
                <?php
                if($val['spare']==1){
                    $url = $this->createUrl('memberPool/indexSpare');
                }else{
                    $url = $this->createUrl('memberPool/indexNoPro');
                }
                ?>
                <span class="label label-info" style='margin-top: 5px;'>
			         <a style='color: white' href='<?php echo $url.'/member/'.$val ['username']?>'>跳转</a>
                </span>
            </td>

        </tr>
    <?php } ?>
</table>
‭
<?php $this->renderPartial('/layouts/pop')?>


 