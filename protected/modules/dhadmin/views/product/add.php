<?php $caminfo=Campaign::model()->findAll(array(
    'select' =>array('id,pid'),
    'condition' => 'periods=:periods',
    'params' => array(':periods'=>$periods),
));
$product=Product::model()->findAllByPk($caminfo[0]['pid']);
//var_dump($product[0]->pathname);exit;
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><strong>添加活动用户</strong></h4>

</div>
<div class="modal-body">
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-2 control-label">活动用户</label>
            <div class="col-sm-10">
                <div class="col-sm-12">
                    <input class="form-control" id="username" type="text">
                </div>
                <div class="col-sm-12" id="username_error"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">添加人数</label>
            <div class="col-sm-10">
                <div class="col-sm-12">
                    <input class="form-control" id="num" type="text">
                </div>
                <div class="col-sm-12" id="num_error"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">活动排名</label>
            <div class="col-sm-10">
                <div class="col-sm-5">
                    <input class="form-control" id="sort_start" type="text">
                </div>
                <div class="col-sm-1" style="padding: 0">
                    <input class="form-control" type="text" value="至" style="padding: 0;text-align: center" readonly>
                </div>
                <div class="col-sm-5">
                    <input class="form-control" id="sort_end" type="text">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">业务类型</label>
            <div class="col-sm-10">
                <div class="col-sm-12">
                    <input class="form-control" id="type" type="text" value="<?=$product[0]->pathname?>" readonly>
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

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    <button type="button" class="btn btn-primary" onclick="addSort()">确定</button>
</div>

<script>


//        $('#username').blur(function () {
//            if ($('#username').val() == '') {
//                $('#username_error').text('用户名不能为空！').css( { color: "#FF0000", "font-family": '"Microsoft Yahei"' });
//            }
//            else { $('#username_error').text('') }
//        });

    function addSort(){
        var username=$("#username").val();
        var num=$("#num").val();
        var sort_start=$("#sort_start").val();
        var sort_end=$("#sort_end").val();
        var type=$("#type").val();
        var periods=$("#periods").val();

        $.ajax({
            type:"POST",
            url:"/dhadmin/product/sortAdd",
            data:{username:username,type:type,periods:periods,sort_end:sort_end,sort_start:sort_start,num:num},
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