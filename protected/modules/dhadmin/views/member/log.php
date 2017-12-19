<?php
/* @var $this MemberController */
/* @var $list MemberInfoLog[] */
/* @var $member Member */

$this->breadcrumbs = array(
    '会员管理列表'
);
?>
<h4 class="text-center">会员修改用户信息历史记录</h4>
<table class="table table-bordered">
    <tr>
        <th style="width:6em;">修改时间</th>
        <th style="width:10em;">修改人</th>
        <th>修改前信息</th>
    </tr>
    <tr>
        <td>当前信息</td>
        <td>&nbsp;</td>
        <td><?php echo $member->toString() ?></td>
    </tr>
    <?php foreach ($list as $memberLog): ?>
        <tr>
            <td><?php echo DateUtil::dateFormate($memberLog->createtime, 'Y-m-d H:i:s') ?></td>
            <td><?php echo $memberLog->username, '(', $memberLog->utype, ')' ?></td>
            <td><?php echo $memberLog->detail ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
?>
