<?php
$this->breadcrumbs = array('我的回访任务' => array('visit'));
?>
<?php

function getManageMenu($id, $tw_id)
{
    $urls = array('advisory' => CHtml::link('用户咨询记录', array('advisoryrecords/index', 'uid' => $id), array('target' => '_blank')),
        'graphs' => CHtml::link('曲线图', array('member/graphs', 'uid' => $id), array('target' => '_blank')),
        'update' => CHtml::link('修改用户信息', array('member/edit', 'id' => $id), array('target' => '_blank')),
        'log' => CHtml::link('修改信息历史记录', array('member/log', 'id' => $id), array('target' => '_blank')),
        'remind' => CHtml::submitButton('我的备注信息', array_merge(Bs::cls(Bs::BTN_INFO), array('onclick' => 'remind(\'' . $id . '\',\'' . $tw_id . '\')'))));
    $menus = array();
    if (Auth::check('advisoryrecords_index'))
        $menus [] = $urls ['advisory'];
    if (Auth::check('member_graphs'))
        $menus [] = $urls ['graphs'];
    if (Auth::check('member_edit'))
        $menus [] = $urls ['update'];
    if (Auth::check('member_log'))
        $menus [] = $urls ['log'];
    $menus [] = $urls ['remind'];

    $btn = '';
    foreach ($menus as $m) {
        $btn .= '<li>' . $m . '</li>';
    }
    return $btn;

}
$url = $this->createUrl('memberPool/visit' );
$status = $_SERVER["QUERY_STRING"];
if(isset($status)&& !empty($status)){

    $str1 = substr($status,0,8);
    if($str1 == 'v_status'){
        $url2 = Yii::app()->request->hostInfo . Yii::app()->request->getUrl().'&';
    }else{
        $url2 = $url.'?';
    }
}else{
    $url2 = $url.'?';
}

?>
</br>
<div class="btn-group">
    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
        回访任务查看<span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <li><a href='<?php echo $url . '?v_status=0' ?>'>待执行任务</a></li>
        <li><a href='<?php echo $url . '?v_status=2' ?>'>显示已上报</a></li>
        <li><a href='<?php echo $url . '?v_status=3' ?>'>显示已放弃任务</a></li>
        <li><a href='<?php echo $url . '?v_status=4' ?>'>显示已通过审批任务</a></li>
        <li><a href='<?php echo $url . '?v_status=1' ?>'>显示全部</a></li>
    </ul>
</div>


<div class="btn-group">
    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
        用户排序<span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <li><a href='<?php echo $url2 . 'order=0' ?>'>提醒时间-升</a></li>
        <li><a href='<?php echo $url2 . 'order=1' ?>'>提醒时间-降</a></li>
        <li><a href='<?php echo $url2 . 'order=2' ?>'>用户等级-升</a></li>
        <li><a href='<?php echo $url2 . 'order=3' ?>'>用户等级-降</a></li>
    </ul>
</div>




<h4 class="text-center"><span style="color: #0099FF"><?php
        echo $name?></span>的回访任务列表</h4>
<span style='float:right;color:#0099FF'>（共<?php echo $num ?>条任务）</span>

<table class="table table-hover table-striped table-condensed">
    <tr>
        <th>用户名</th>
        <th>提醒时间</th>
        <th>发布时间</th>
        <th>用户详情</th>
        <th>相关信息</th>
        <?php if (($role == Role::PRACTICE_STAFF) && ($type == 0)) { ?>
            <!--<th>上报任务</th>-->
            <th>放弃任务</th>
        <?php }elseif(($role == Role::PRACTICE_STAFF) && ($type == 1)){

        } else { ?>
            <th>申请任务</th>
            <th>放弃任务</th>
        <?php } ?>
    </tr>
    <?php foreach ($data as $item) { ?>
        <tr>
            <td><?php echo $item ['username'] ?></td>
            <?php $remind = isset($item ['remind'])?date('Y-m-d H:i',$item ['remind']):'无' ?>
            <td><?php echo $remind ?></td>
            <td><?php echo date("Y-m-d ", $item ['a_time']) ?></td>
            <td>
                <i style = 'cursor:pointer'  class="glyphicon glyphicon-search" onclick = "show(<?php echo $item['m_id'] ?>)"><span id="clickst"></span></i>
            </td>
            <?php $tw_id = 0;
            $arr_list = getManageMenu($item ['m_id'], $item ['tw_id']); ?>
            <td>
                <div class='btn-group' id=bt_
                    <?php ?>>
                    <a
                        class='btn btn-info btn-mini dropdown-toggle'
                        onclick="mylist(<?php
                        echo $item ['m_id'];
                        ?>)";> <i class='icon-cog icon-white'></i><span class='caret'></span></a>
                    <ul class="dropdown-menu" id='msg_<?php echo $item ['m_id']; ?>'>
                        <?php echo $arr_list; ?>
                    </ul>
                </div>
            </td>
            <?php if (($role == Role::PRACTICE_STAFF )&& ($type == 0) ) { ?>
<!--                <td><span class="label label-info" style='margin-top: 5px;'>
			<a style='color: white' href='#'
               onclick='provisitetask(<?php /*echo $item ['id']; */?>)'>上报</a> </span>
                </td>-->
                <td><span class="label label-info" style='margin-top: 5px;'>
		 	<a style='color: white' href='#'
               onclick='delvisitetask(<?php echo $item ['id']; ?>)'>无效</a> </span>
                </td>
            <?php }elseif(($role == Role::PRACTICE_STAFF )&& ($type == 1) ){

            } else { ?>
                <td><span class="label label-info" style='margin-top: 5px;'>
			<a style='color: white' href='#'
               onclick='askforvisitetask(<?php echo $item ['id']; ?>)'>申请</a> </span>
                </td>
                <td><span class="label label-info" style='margin-top: 5px;'>
		 	<a style='color: white' href='#'
               onclick='delvisitetask(<?php echo $item ['id']; ?>)'>无效</a> </span>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
</table>

‭


<div class="pager">
    <?php
    $this->widget("CLinkPager", array('pages' => $pages, 'firstPageLabel' => '首页', 'lastPageLabel' => '末页', 'maxButtonCount' => 15));
    ?>
</div>

<?php
$this->renderPartial('/layouts/pop')?>


 