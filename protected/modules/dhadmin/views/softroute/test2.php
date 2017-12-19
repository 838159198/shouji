<style>
    .input-append{margin-bottom: 10px;}
    select{height: 33px;}
    tr th,tr td{text-align: center}
    thead tr{background-color: rgba(88, 121, 128, 0.45)
    }
</style>
<script type="text/javascript">
     function getOnlineTime(ms){
        //计算出相差天数
        var days=Math.floor(ms/(24*3600));
        //计算出小时数
        var leave1=ms%(24*3600)  ;  //计算天数后剩余的毫秒数
        var hours=Math.floor(leave1/(3600));
        //计算相差分钟数
        var leave2=leave1%(3600)  ;      //计算小时数后剩余的毫秒数
        var minutes=Math.floor(leave2/(60));
        //计算相差秒数
        var leave3=leave2%(60)  ;    //计算分钟数后剩余的毫秒数
        var seconds=Math.round(leave3);
         var str;
        if(days>0) {
            str=days+"天"+hours+"小时 "+minutes+" 分钟"+seconds+" 秒";
        }else if(days==0 && hours>0){
            str=hours+"小时 "+minutes+" 分钟"+seconds+" 秒";
        }else if(days==0 && hours==0 && minutes>0){
             str=minutes+" 分钟"+seconds+" 秒";
         }else{
            str=seconds+" 秒";
        }
        return str;
    }

    var ws = new WebSocket('ws://47.93.85.18:5678');
    ws.onopen = function(){
        var uid = 'uid1';
        ws.send(uid);
    };
    ws.onmessage = function(e){
        var routeUid=<?=$routeUid;?>;
        var json=e.data;
        var obj=eval('('+json+')');
        var jsonobj=obj['uidConnectionMap'];
        var jsonheart=obj['heartCheck'];
        var loginTime=obj['loginTime'];
        var html='';
        var num=0;
        var no=0;
        var timestamp = Date.parse(new Date())/1000;
        console.log(jsonobj);
        console.log(jsonheart);
        console.log(loginTime);
        for(var index in routeUid){
//            for(var i=0;i<jsonobj.length;i++){
//                if(index == jsonobj[i]){
//                    var uid=routeUid[jsonobj[i]];
//                    var time=loginTime[jsonobj[i]];
//                    if(timestamp-jsonheart[jsonobj[i]] >120) {
//                        num++;
//                    }else{
//                        html+='<tr><td><input type="checkbox" name="selectdsend[]" value="'+index+'" /></td><td>'+jsonobj[i]+'</td><td>'+uid+'</td><td>'+getOnlineTime(timestamp-time)+'</td><td><a href="http://'+document.domain+':21212/?type=publish&content=update&to='+jsonobj[i]+'" class="label label-primary" target="_blank">更新</a>&nbsp;&nbsp;<a href="http://'+document.domain+':21212/?type=publish&content=info&to='+jsonobj[i]+'" class="label label-primary" target="_blank">详情</a>&nbsp;&nbsp;<a href="http://'+document.domain+'/dhadmin/softroute/info?to='+jsonobj[i]+'" class="label label-primary" target="_blank">查看</a> </td></tr>';
//
//                    }
//                }else{
//                    html+='<tr><td><input type="checkbox" name="selectdsend[]" value="'+index+'" /></td><td>'+index+'</td><td>'+routeUid[index]+'</td><td>---</td><td><a href="http://'+document.domain+'/dhadmin/softroute/info?to='+index+'" class="label label-primary" target="_blank">查看</a> </td></tr>';
//
//                }
//            }
            //console.log(jsonobj.indexOf(index));
            if(jsonobj.indexOf(index)>=0){
                var uid=routeUid[index];
                var time=loginTime[index];
                if(timestamp-jsonheart[index] >120) {
                    num++;
                }else{
                    html+='<tr><td><input type="checkbox" name="selectdsend[]" value="'+index+'" /></td><td>'+index+'</td><td>'+uid+'</td><td>'+getOnlineTime(timestamp-time)+'</td><td><a href="http://'+document.domain+':21212/?type=publish&content=update&to='+index+'" class="label label-primary" target="_blank">更新</a>&nbsp;&nbsp;<a href="http://'+document.domain+':21212/?type=publish&content=info&to='+index+'" class="label label-primary" target="_blank">详情</a>&nbsp;&nbsp;<a href="http://'+document.domain+'/dhadmin/softroute/info?to='+index+'" class="label label-primary" target="_blank">查看</a> </td></tr>';
                }
                no++;
            }else{
                html+='<tr><td><input type="checkbox" name="selectdsend[]" value="'+index+'" /></td><td>'+index+'</td><td>'+routeUid[index]+'</td><td>---</td><td><a href="http://'+document.domain+'/dhadmin/softroute/info?to='+index+'" class="label label-primary" target="_blank">查看</a> </td></tr>';
            }

        }
        $('#online tbody').append(html);
        var noo=no-num;
        $("#spanid").html("在线路由器："+noo+"个");
        //$('#send_to_all').attr('href', 'http://'+document.domain+':21212/?type=publish&content=update');
        //console.log("当前时间戳为：" + timestamp);

    };
</script>

<div class="page-header app_head">
    <h1 class="text-center text-primary">在线路由器 <small></small></h1>
    <span style="float: right;padding-top: 20px;padding-right: 20px;padding-bottom: 10px;" id="spanid"></span>
</div>
<div class="row-fluid">
    <div class="app_button">
        <a href="" class="btn btn-success" id="send_to_all" target="_blank" onclick="show()">一键更新</a>
    </div>
    <div class="app_button">
    </div>
</div>
<div class="row-fluid">

    <table class="table table-bordered table-hover" id="online">
        <thead>
        <tr>
            <th><input type='checkbox' id='member-info-grid-all'>全选</th>
            <th>路由器码</th>
            <th>用户名</th>
            <th>在线时长</th>
            <th width="120">操作</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>


<script type="text/javascript">
    $("#member-info-grid-all").click(function () {
        $(":checkbox[name='selectdsend[]']").filter(":enabled").attr("checked", this.checked);
    });
    //获取用户名弹出框
    function show(){
        var usernames='';
        $("input:checkbox[name='selectdsend[]']:checked").each(function() {
            usernames += $(this).val() + ",";
        });
        if (usernames.charAt(usernames.length - 1) == ",") {
            usernames=usernames.substring(0,usernames.length-1);
        }
        var url= 'http://'+document.domain+':21212/?type=publish&content=update&uids='+usernames;

        window.open(url);
//        $.ajax({
//            type:"GET",
//            url: 'http://'+document.domain+':21212/',
//            data:{type:'publish',content:'update',uids:usernames},
//            datatype: "jsonp",
//            jsonp:'callback',
//            success:function(data){
//                alert(data);
//            },
//            error: function(){
//                alert("未知错误");
//            }
//        });

    }
</script>