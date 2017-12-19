<style>
    .input-append{margin-bottom: 10px;}
    select{height: 33px;}
    tr th,tr td{text-align: center}
    thead tr{background-color: rgba(88, 121, 128, 0.45)
    }
</style>
<script type="text/javascript">
    var ws = new WebSocket('ws://47.93.85.18:5678');
    ws.onopen = function(){
        var uid = 'uid1';
        ws.send(uid);
    };
    ws.onmessage = function(e){
        var uid='<?=$uid;?>';
        var json=e.data;
        var obj=eval('('+json+')');
        var jsonobj=obj['uidConnectionMap'];
        var jsonheart=obj['heartCheck'];
        var jsonaaa=obj['aaa'];
        console.log(jsonaaa);
        console.log(jsonaaa[uid]);
        if(jsonaaa.hasOwnProperty(uid)){
            $("#info").html(jsonaaa[uid]);
        }
    };
</script>
<div class="page-header app_head">
    <h1 class="text-center text-primary">在线路由器 <small></small></h1>
    <span style="float: right;padding-top: 20px;padding-right: 20px;padding-bottom: 10px;" id="spanid"></span>
</div>

<div class="row-fluid" id="info">

</div>


