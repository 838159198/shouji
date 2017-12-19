<?php
$product=Product::model()->findAllByPk($caminfo[0]['pid']);
//var_dump($product[0]->pathname);exit;
?>
<div class="modal-header" style="background-color: rgba(86, 128, 126, 0.28);color: #ffffff">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><strong>第<?=$periods?>期活动修改</strong></h4>
</div>
<div class="modal-body">
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-2 control-label">活动标题</label>
            <div class="col-sm-10">
                <div class="col-sm-12">
                    <input class="form-control" id="title" type="text" value="<?=$caminfo[0]['title']?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">产品名称</label>
            <div class="col-sm-10">
                <div class="col-sm-12">
                    <input class="form-control" id="name" type="text" value="<?=$caminfo[0]->p->name?>" readonly>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">报名时间</label>
            <div class="col-sm-10">
                <div class="col-sm-5">
                    <input class="form-control" id="userstarttime" type="text" value="<?=$caminfo[0]['userstarttime']?>">
                </div>
                <div class="col-sm-1" style="padding: 0">
                    <input class="form-control" type="text" value="至" style="padding: 0;text-align: center" readonly>
                </div>
                <div class="col-sm-5">
                    <input class="form-control" id="userendtime" type="text" value="<?=$caminfo[0]['userendtime']?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">活动时间</label>
            <div class="col-sm-10">
                <div class="col-sm-5">
                    <input class="form-control" id="starttime" type="text" value="<?=$caminfo[0]['starttime']?>">
                </div>
                <div class="col-sm-1" style="padding: 0">
                    <input class="form-control" type="text" value="至" style="padding: 0;text-align: center" readonly>
                </div>
                <div class="col-sm-5">
                    <input class="form-control" id="endtime" type="text" value="<?=$caminfo[0]['endtime']?>">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">活动期号</label>
            <div class="col-sm-10">
                <div class="col-sm-12">
                    <input class="form-control" id="periods" type="text" value="<?=$periods?>" readonly>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">活动介绍</label>
            <div class="col-sm-10">
                <div class="col-sm-12">
                    <textarea rows="3" cols="59" id="instruction"><?=$caminfo[0]['instruction'] ?></textarea>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    <button type="button" class="btn btn-primary" onclick="edit()">确定</button>
</div>
<script type="text/javascript">
    function edit(){
        var title=$("#title").val();
        var name=$("#name").val();
        var userstarttime=$("#userstarttime").val();
        var userendtime=$("#userendtime").val();
        var starttime=$("#starttime").val();
        var endtime=$("#endtime").val();
        var instruction=$("#instruction").val();
        var periods=$("#periods").val();

        $.ajax({
            type:"POST",
            url:"/dhadmin/product/editCampaign",
            data:{title:title,name:name,periods:periods,userstarttime:userstarttime,userendtime:userendtime,starttime:starttime,endtime:endtime,instruction:instruction},
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