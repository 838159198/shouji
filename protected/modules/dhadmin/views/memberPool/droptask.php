<?php 
$this->breadcrumbs = array(
    '我的降量任务' => array(''),
);
?>
<style type="text/css">
    input[type="submit"]{margin-left: 20px;}
</style>
<?php 
function getManageMenu($id,$tw_id)
{
    $urls = array(
        'advisory' => CHtml::link('用户咨询记录', array('advisoryrecords/index', 'uid' => $id), array('target' => '_blank')),
        'graphs' => CHtml::link('曲线图', array('member/graphs', 'uid' => $id), array('target' => '_blank')),
        'edit' => CHtml::link('修改用户信息', array('member/edit', 'id' => $id), array('target' => '_blank')),
        'log' => CHtml::link('修改信息历史记录', array('member/log', 'id' => $id), array('target' => '_blank')),
    	'remind' => CHtml::submitButton('我的备注信息', array_merge(Bs::cls(Bs::BTN_INFO),array('onclick' => 'remind(\''.$id.'\',\''.$tw_id.'\')'))),
    
    );

    $menus = array();
    if (Auth::check('advisoryrecords_index')) $menus[] = $urls['advisory'];
    if (Auth::check('member_graphs')) $menus[] = $urls['graphs'];
    if (Auth::check('member_edit')) $menus[] = $urls['edit'];
    if (Auth::check('member_log')) $menus[] = $urls['log'];
    $menus[] = $urls['remind'];


  	$btn='';
    foreach ($menus as $m) {
        $btn .= '<li>' . $m . '</li>';
    }
    return $btn;

}
?>

<h4 class="text-center">我的降量任务表</h4>
<span style = 'float:right;color:#0099FF'>（共<?php echo $num ?>条任务）</span>
<table class="table table-hover table-striped table-condensed">
    <tr>
      	<th>用户名</th>
      	<th>任务申请/发布时间</th>
        <th>任务上报时间</th>
        <th>任务是否可继续</th>
        <th>管理员评分</th>
        <th>任务收益</th>
        <th>用户信息</th>
        <th>管理</th>
        <th>相关信息</th>
    </tr>
    <?php if(!empty($dt_list)){?>
    <?php foreach($dt_list AS $dt_msg){?>
    <tr>
    	<td><?php echo $dt_msg['holder'];?></td>
    	<td><?php echo date('Y-m-d H:i',$dt_msg['a_time']);?></td>
    	<?php $date = isset($dt_msg['porttime'])?date("Y-m-d H:i",$dt_msg['porttime']):'未上报';?>
    	<td><?php echo $date;?></td>
    	
    	<td><?php if($dt_msg['isfail']==0){echo "可继续";}elseif ($dt_msg['isfail']==1){echo '任务失败';}?></td>
    	
    	<?php $sc = ($dt_msg['score']!=0)?$dt_msg['score']:'无';?>
    	<td><?php echo $sc;?></td>
		
    	<?php if(($dt_msg['isfail']!=0)){?>
    	
    		<td>任务失败，无收益</td>
    	
    	<?php }else{?>
    	
    		<td><?php echo $dt_msg['pay_back']?>元</td>
    		
    	<?php }?>
    	
    	<td>
            <i style = 'cursor:pointer'  class="glyphicon glyphicon-search" onclick = "show(<?php echo $dt_msg['mid'] ?>)"><span id="clickst"></span></i>
    	</td>
    	
    	<td>
    	
    	<?php $root_url = $this->createUrl('mytask');?>
    	<?php $id = Yii::app()->user->manage_id;?>
			<div class='btn-group'>
	       		<a class='btn btn-info btn-mini dropdown-toggle' target="_blank" href="<?php echo $root_url?>/tid/<?php echo $dt_msg['t_id']; ?>/uid/<?php echo $id; ?>" >
	        	<i class='icon-cog icon-white'></i><span class='caret'></span></a>
	      		<ul class='dropdown-menu'>
	      	</div>
    	
    	</td>
    	<?php $arr_list = getManageMenu( $dt_msg['mid'] ,$dt_msg['tw_id']);?>
        <td>
	        <div class='btn-group' id = bt_<?php echo $dt_msg['mid'] ; ?>>
	        	<a class='btn btn-info btn-mini dropdown-toggle' onclick="mylist(<?php echo $dt_msg['mid'] ;?>)";>
	        	<i class='icon-cog icon-white'></i><span class='caret'></span></a>
	        	<ul class="dropdown-menu" id = 'msg_<?php echo $dt_msg['mid'] ;?>'>
	        		<?php echo $arr_list;?>
	        	</ul>
	      	</div>
        </td>
    	
    </tr>
    <?php }?>
    <?php }?>
</table>

‭<div class="pager">  
    <?php $this->widget("CLinkPager", array(  
        'pages' => $pages  
    ));?>  
</div> 

<?php $this->renderPartial('/layouts/pop') ?>


