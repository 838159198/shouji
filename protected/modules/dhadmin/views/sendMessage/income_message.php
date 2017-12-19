<style type="text/css">
    input[type="text"], input[type="password"]{
        width: 517px;
        display: inline-block;
        padding: 4px;
        font-size: 13px;
        height: 30px;
        line-height: 30px;
        color: #808080;
        border: 1px solid #ccc;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        margin-top:10px; margin-bottom:10px;
    }
    select{
        width: 517px;
        background-color: #fff;
        border: 1px solid #ccc;
        height: 30px;
        line-height: 30px;
        display: inline-block;
        padding: 4px 6px;
        margin-bottom: 10px;
        font-size: 14px;
        color: #555;
        vertical-align: middle;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        margin-top:10px; margin-bottom:10px;
    }
    .lable{float: left;height: 30px;margin-right: 50px;margin-top: 10px;margin-bottom: 10px}
</style>
<?php
$this->breadcrumbs = array(
    '微信平台'
);
?>

<h2 class="text-center">微信平台</h2>
<hr size="5">
<div class="form">
    <div class="app_button">
        <a href="/dhadmin/sendMessage/weixinMessage" class="btn btn-primary">业务上下线模板</a>
        <a href="/dhadmin/sendMessage/yestodayIncome" class="btn btn-success">收益发放模板</a>
    </div>


    <div class="control-group">
        <div class="lable">日 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;期：</div>
        <div class="controls">
            <input type="text" id="date" class="date form_date" data-date-format="yyyy-mm-dd">
        </div>
    </div>
    <div class="control-group">
        <div class="lable">发送对象：</div>
        <div class="controls">
            <select id="resource" disabled>
                <option value="">== 请选择 ==</option>
                <option value="1" selected>全体会员</option>
                <option value="2">线下人员</option>
                <option value="4">ROM开发者</option>
                <option value="5">经销商</option>
                <option value="3">VIP测试人员</option>
            </select>
        </div>
    </div>

    <div class="control-group">
        <div class="lable"></div>
        <div class="controls" style="margin-left: 120px;margin-top: 10px">
            <input type="submit" class="btn btn-primary btn-large" value="发送" id="submit">
        </div>
    </div>

</div><!-- form -->
<script type="text/javascript">
    $(function () {
        //日期控件
        $('.form_date').datetimepicker({
            language:'zh-CN', weekStart:1,todayBtn:1,
            autoclose:1,
            todayHighlight:1,
            startView:2,
            minView:2,
            forceParse:0
        });
    });
</script>
<script type="text/javascript">
   $(function(){
       $("#submit").click(function(){
           var date=$("#date").val();
           if(date==''){
               alert('收益日期不能为空');
               return false;
           }

           //$("#submit").attr('disabled','disabled').die('click');
           $.ajax({
               type:"POST",
               url:"/dhadmin/sendMessage/income",
               data:{date:date},
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
       })
   })
</script>

