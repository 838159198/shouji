<style>
    .input-append{margin-bottom: 10px;}
    select{height: 33px;}
    tr th,tr td{text-align: center}
    thead tr{background-color: rgba(88, 121, 128, 0.45)
    }
</style>

<div class="page-header app_head">
    <h1 class="text-center text-primary"> 业务关闭历史记录<small></small></h1>
    <span style="float: right;padding-top: 20px;padding-right: 20px;padding-bottom: 10px;"><?php echo "共".$count."条";?></span>
</div>
<?php
$type='llq';
echo CHtml::beginForm('closeType', 'get', array('class'=>'input-append')),
    '渠道分组： <select name="agent" id="agent">
                <option  value="1">全部</option>
                <option  value="0" selected="selected">ROM开发者</option>
                <option value="99">线下手机销售</option>
            </select>&nbsp;&nbsp;
         业务类型：'.CHtml::dropDownList('type', $type, Ad::getAdList(),array('id'=>'type')).'&nbsp;&nbsp;&nbsp;&nbsp;',
CHtml::Button('查询开通人数',  array('class'=>'btn btn-info','id'=>'open_num')).'&nbsp;&nbsp;&nbsp;&nbsp;',
CHtml::submitButton('一键关闭',  array('class'=>'btn btn-info','id'=>'close_all')),'',
CHtml::endForm();
?>
<div class="row-fluid">
    <div id="find_num">
    </div>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>序号</th>
            <th>业务名</th>
            <th>关闭数量</th>
            <th>操作人员</th>
            <th>渠道分组</th>
            <th>操作时间</th>
            <th width="120">详情</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=0;foreach($data as $row):$i++?>
        <tr>
            <td><?php echo $i;?></td>
            <td><?php echo Product::getByType($row->type);?></td>
            <td><?php echo $row->num;?></td>
            <td><?php echo $name=$row->m->name;?></td>
            <td><?php echo CloseAll::getAgent($row->agent);?></td>
            <td><?php echo $row->datetime;?></td>
            <td>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/closeAll/detail?id=".$row['id'])."&type=". $row->type;?>" class="label label-primary" target="_blank">查看</a>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
</div>
<div class="row-fluid text-center">
    <nav>
        <?php
        $this->widget('CLinkPager',array(
                'header'=>'',
                'cssFile'=>false,
                'firstPageLabel' => '首页',
                'lastPageLabel' => '末页',
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
                'pages' => $pages,
                'maxButtonCount'=>8,
                'htmlOptions'=>array("class"=>"pagination pagination-lg"),
            )
        );
        ?>
    </nav>
</div>

<script type="text/javascript">

    $(function(){
        $("#open_num").click(function(){
            var agent=$("#agent").val();
            var type=$("#type").val();
            if(agent==''){
                alert('请选择渠道');
                return false;
            }
            if(type==''){
                alert('请选择业务类型');
                return false;
            }
            $.ajax({
                type:"POST",
                url:"/dhadmin/closeAll/openAll",
                data:{agent:agent,type:type},
                datatype: "json",
                success:function(data){
                    var jsonStr = eval("("+data+")");
                    if(jsonStr.status==403){
                        alert(jsonStr.message);
                        return false;
                    }else if(jsonStr.status==200){
                        $("#find_num").empty();
                        var html='';
                        html+=' <div class="alert alert-danger">业务名称：'+jsonStr.type+'&nbsp;&nbsp;&nbsp;&nbsp; 业务人数：'+jsonStr.num+'</div>';
                        //console.log(html);
                        $("#find_num").append(html);
                        //location.replace(location.href);
                    }else{
                        alert("发生错误"+jsonStr.status);
                        return false;
                    }
                },
                error: function(){
                    alert("未知错误");
                }
            });

        });
    });
    $("#close_all").click(function(){
        var msg = "您真的确定要一键关闭吗？";
        if (confirm(msg)==true){
            return true;
        }else{
            return false;
        }
    });

</script>


