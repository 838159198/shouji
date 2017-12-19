<style type="text/css">
	td a{ color: #333; text-decoration: none; }
	td a:hover{ color: #333; text-decoration: none; }
</style>
<?php
/* @var $this ProductController */
/* @var $model CampaignIncome */
Yii::app()->clientScript->registerScript('CampaignIncome', "
$('.CampaignIncome-button').click(function(){
	$('.CampaignIncome-form').toggle();
	return false;
});

$('.CampaignIncome-form form').submit(function(){
	$('#CampaignIncome-info-grid').yiiGridView('update', {

		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="page-header app_head">
    <h1 class="text-center text-primary"><span style="color:red ">第<?=$_GET['periods']?>期：<?=$_GET['name']?></span> 活动数据</h1>
</div>
<div class="row-fluid">
    <div class="app_button">
        <a href="/dhadmin/product/huodong2?periods=<?=$_GET['periods']?>&name=<?=$_GET['name']?>" class="btn btn-primary">报名中心</a>
        <a href="/dhadmin/product/hddata?periods=<?=$_GET['periods']?>&name=<?=$_GET['name']?>" class="btn btn-warning">活动数据</a>
        <a href="/dhadmin/product/hdorder?periods=<?=$_GET['periods']?>&name=<?=$_GET['name']?>" class="btn btn-success">活动排名</a>
    </div>
</div>
<h5 style="color:#2b79a8;margin-bottom:-10px;margin-top: 10px;line-height: 25px;">
    默认排序为先期数倒序，后原单价收益倒序<br>
	多行选择，点击“获取用户名”可获取对应用户名拼接字符串
</h5>

<?php
//var_dump($model->search($_GET['periods']));exit;
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'CampaignIncome-info-grid',
    'dataProvider'=>$model->search($periods),
    'filter'=>$model,
    'columns'=>array(
        array(
            'htmlOptions'=>array('width'=>"30px"),
            'class' => 'CCheckBoxColumn',
            'name'=>'uid',
            'value'=>'Member::model()->getUsernameByMid($data->uid)."|".$data->uid."|".$data->cid."|".$data->type."|".$data->olddata',
            'id'=>'ids',
            'headerTemplate'=>'{item}',
            'selectableRows'=>2,
        ),
        array('header'=>'序号',
            'value'=>'++$row',
        ),
        array(
            'name'=>'username',
            'type'=>'raw',
            'value'=>'$data->m->username'
        ),
        'type',
        'olddata',
        'campaigndata',
        array(
            'name'=>'status',
            'type'=>'raw',
            'value'=>'$data->status=="0"?"<span style=font-weight:bold;>无效</span>":"<span style=color:darkgreen;font-weight:bold;>有效</span>"'
        ),
        'createtime',

    )
));
?>
<div>
    <a href="javascript:show()" class="btn btn-danger">获取用户名</a>
    <a href="javascript:createAction()" class="btn btn-primary">导入排名</a>
</div>

<!-- 获取用户名弹出框 -->
<div class="modal fade" id="tt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
<script type="text/javascript">
    //获取用户名弹出框
    function createAction(){
        var usernames='';
        $("input:checkbox[name='ids[]']:checked").each(function() {
            usernames += $(this).val() + ",";
        });
        if (usernames.charAt(usernames.length - 1) == ",") {
            usernames=usernames.substring(0,usernames.length-1);
        }
        //alert(usernames);
        if(usernames==''){
            alert('请选择用户');
            return false;
        }
        $.ajax({
            type:"POST",
            url:"/dhadmin/product/sort",
            data:{usernames:usernames},
            datatype: "json",
            success:function(data){
                var jsonStr = eval("("+data+")");
                if(jsonStr.status==403){
                    alert(jsonStr.message);
                    return false;
                }else if(jsonStr.status==200){
                    alert(jsonStr.message);
                    location.replace(location.href);
                }else{
                    alert("发生错误"+jsonStr.status);
                    return false;
                }
            },
            error: function(){
                alert("未知错误");
            }
        });
    }
    function show(){
        var usernames='';
        var str;
        var arr = new Array();
        $("input:checkbox[name='ids[]']:checked").each(function() {
            str=$(this).val();
            arr = str.split("_");
            usernames += arr[0] + ",";
        });
        if (usernames.charAt(usernames.length - 1) == ",") {
            usernames=usernames.substring(0,usernames.length-1);
        }
        var _url="/dhadmin/product/usernames?usernames="+usernames;
        $('#tt').removeData("bs.modal");
        $("#tt").modal({remote: _url});
    }
</script>
