<style type="text/css">
    .alert {
        padding: 8px 35px 8px 14px;
        margin-bottom: 20px;
        text-shadow: 0 1px 0 rgba(255,255,255,0.5);
        background-color: #fcf8e3;
        border: 1px solid #fbeed5;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
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
        <div class="lable">业务状态：</div>
        <div class="controls">
            <select id="theme">
                <option value="1">业务上架</option>
                <option value="2">业务下架</option>
            </select>
        </div>
    </div>

    <div class="control-group"  id="type_down">
        <div class="lable">业务类型：</div>
        <div class="controls">
            <select id="type2">
                <option value="">== 请选择 ==</option>
                <?php
                foreach(Ad::getAdList() as $key=>$val)
                {
                    echo "<option value=".$key.">".$val."</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="control-group">
        <div class="lable">日 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;期：</div>
        <div class="controls">
            <input type="text" id="date" class="date form_date" data-date-format="yyyy-mm-dd">
        </div>
    </div>
    <div class="control-group" id="sj">
        <div class="lable">发送对象：</div>
        <div class="controls">
            <select id="resource_sj">
                <option value="">== 请选择 ==</option>
                <option value="10">全体会员</option>
                <option value="0">ROM开发者</option>
                <option value="3">线下人员</option>
                <option value="4">经销商</option>
            </select>
        </div>
    </div>
    <div class="control-group" style="display: none" id="xj">
        <div class="lable">发送对象：</div>
        <div class="controls">
            <select id="resource_xj">
                <option value="">== 请选择 ==</option>
                <option value="10">开启业务会员</option>
            </select>
        </div>
    </div>

    <div class="control-group">
        <div class="lable">发送内容：</div>
        <p style="font-size: 10px;margin-left: 420px;color: grey;display: inline">已经输入<span class="textnum">0</span>个字</p>
        <div class="controls">
            <textarea rows="8" cols="70" id="content"></textarea>
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

       // 多输入框显示字数
       $('#content').keyup(function () {
           $('.textnum').text($(this).val().length);
       });
       $('#theme').change(function () {
           var theme=$("#theme").val();
           if (theme == 2){
               $("#xj").css('display','block');
               $("#sj").css('display','none');
           }else if (theme == 1){
               $("#sj").css('display','block');
               $("#xj").css('display','none');
           }
       });

       $("#submit").click(function(){
           var theme=$("#theme").val();
           var type=$("#type2").val();
           var date=$("#date").val();
           var content=$("#content").val();
           var resource=theme==1 ? $("#resource_sj").val():$("#resource_xj").val();
           if(type==''){
               alert('业务类型不能为空');
               return false;
           }
           if(date==''){
               alert('收益日期不能为空');
               return false;
           }
           if(resource==''){
               alert('发送对象必能为空');
               return false;
           }
           //$("#submit").attr('disabled','disabled').die('click');
           $.ajax({
               type:"POST",
               url:"/dhadmin/sendMessage/weixin",
               data:{theme:theme,date:date,content:content,type:type,resource:resource},
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

