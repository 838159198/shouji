<style type="text/css">
	td a{ color: #333; text-decoration: none; }
	td a:hover{ color: #333; text-decoration: none; }
</style>
<?php
/* @var $this CampaignSortController */
/* @var $model CampaignSort */
Yii::app()->clientScript->registerScript('CampaignSort', "
$('.CampaignSort-button').click(function(){
	$('.CampaignSort-form').toggle();
	return false;
});

$('.CampaignSort-form form').submit(function(){
	$('#CampaignSort-info-grid').yiiGridView('update', {

		data: $(this).serialize()
	});
	return false;
});
");

$time=Campaign::model()->findByPeriods($_GET['periods']);

?>
<div class="page-header app_head">
    <h1 class="text-center text-primary"><span style="color:red ">第<?=$_GET['periods']?>期：<?=$_GET['name']?></span> 活动排名
        <span style="font-size: 18px">（<?php echo empty($time)?"未发布":date("Y-m-d H:i:s",$time);?>）</span></h1>

</div>
<div class="row-fluid">
    <div class="app_button">
        <a href="/dhadmin/product/huodong2?periods=<?=$_GET['periods']?>&name=<?=$_GET['name']?>" class="btn btn-primary">报名中心</a>
        <a href="/dhadmin/product/hddata?periods=<?=$_GET['periods']?>&name=<?=$_GET['name']?>" class="btn btn-warning">活动数据</a>
        <a href="/dhadmin/product/hdorder?periods=<?=$_GET['periods']?>&name=<?=$_GET['name']?>" class="btn btn-success">活动排名</a>
    </div>
</div>
<h5 style="color:#2b79a8;margin-bottom:-10px;margin-top: 10px;line-height: 25px;">
<!--    --><?php
//    $caminfo=Campaign::model()->findAll(array(
//        'select' =>array('title,id,periods'),
//        'order' => 'periods DESC',
//    ));
//    $pcamlog_data = array();
//    foreach($caminfo as $k=>$t)
//    {
//        $pcamlog_data[$t['periods']]= $t['title'];
//    }
//    echo CHtml::beginForm('/dhadmin/product/hdorder', 'post', array('class'=>'input-append')),
//        ''.CHtml::dropDownList('title', $periods, $pcamlog_data,array('class'=>'form-control','style'=>'width:260px;float:left')).'&nbsp;&nbsp;',
//    CHtml::submitButton('选择',  array('class'=>'btn btn-info')),
//    CHtml::endForm();
//    ?>
    <div style="margin-top: 15px">
        <a href="javascript:add(<?=$_GET['periods']?>)" class="btn btn-danger">添加排名</a>
        <a href="javascript:publish(<?=$_GET['periods']?>)" class="btn btn-danger">发布排名</a>
        <a href='<?php echo Yii::app()->createUrl($this->getModule()->id."/product/delAll/periods/".$_GET['periods']);?>' class="btn btn-danger">一键删除</a>
    </div>
</h5>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'CampaignIncome-info-grid',
    'dataProvider'=>$model->search($periods),
    'emptyText'=>'暂时没有数据',
    //'filter'=>$model,
    'columns'=>array(
        array(
            'htmlOptions'=>array('width'=>"30px"),
            'class' => 'CCheckBoxColumn',
            'name'=>'username',
            'value'=>'$data->id',
            'id'=>'ids',
            'headerTemplate'=>'{item}',
            'selectableRows'=>2,
        ),
        array('header'=>'排名',
            'value'=>'++$row',
        ),
        'username',
        'type',
        'num',
        array(
            'htmlOptions'=>array('width'=>"200"),
            'name'=>'sort',
            'type'=>'raw',
            'value'=>' "<input type=text id=sort_$data->id value=$data->sort>" ',
        ),
        array(
            'name'=>'createtime',
            'value'=>'date("Y-m-d H:i:s", $data->createtime)',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'truth',
            'type'=>'raw',
            'value'=>'$data->truth==0? "<span style=font-weight:bold;>假</span>":"<span></span>" ',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'htmlOptions'=>array('width'=>"130px"),
            'header'=>'操作',
            'class' => 'CButtonColumn',
            'htmlOptions'=>array('style'=>'text-align:center;'),
           // 'updateButtonUrl'=>'Yii::app()->createUrl("dhadmin/product/sortUpdate",array("id"=>$data->id));',
            'deleteButtonUrl'=>'Yii::app()->createUrl("dhadmin/product/del",array("id"=>$data->id));',
            'template'=>'{delete}',
            'afterDelete'=>'function(link,success,data){alert(data) }',
            'buttons'=>array(
                 'delete'=>array(
                    'label'=>'删除',
                     'type'=>'raw',
                     'url'=>'Yii::app()->controller->createUrl("del", array("id"=>$data->id))',
                    // 'value'=>' "<input type=button value=排序 class=btn-danger onclick=sort($data->id)>" ',
                 ),
            ) ,
        ),
        array(
            'header'=>'排序操作',
            'type'=>'raw',
            'value'=>' "<input type=button value=排序 class=btn-danger onclick=sort($data->id)>" ',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),

    )
));
?>


<!-- 获取用户名弹出框 -->
<div class="modal fade" id="tt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal fade" id="add_sort" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
<script type="text/javascript">

    function publish(periods){

        $.ajax({
            type:"POST",
            url:"/dhadmin/product/sortPublish",
            data:{periods:periods},
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
    //添加排名弹出框
    function add(periods){
        //alert(66);

        var _url="/dhadmin/product/add?periods="+periods;
        $('#add_sort').removeData("bs.modal");
        $("#add_sort").modal({remote: _url});
    }
    //获取用户名弹出框
    function show(){
        var usernames='';
        $("input:checkbox[name='ids[]']:checked").each(function() {
            usernames += $(this).val() + ",";
        });
        if (usernames.charAt(usernames.length - 1) == ",") {
            usernames=usernames.substring(0,usernames.length-1);
        }
        var _url="/dhadmin/product/usernames?usernames="+usernames;
        $('#tt').removeData("bs.modal");
        $("#tt").modal({remote: _url});
    }
    //排序修改
    function sort(id){
        var _sort=$("#sort_"+id).val();
        if(_sort==''){
            alert("请填写排序");
            return false;
        }
       //alert(_sort);
        $.ajax({
            type:"POST",
            url:"/dhadmin/product/sortUpdate",
            data:{sort:_sort,id:id},
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
</script>
