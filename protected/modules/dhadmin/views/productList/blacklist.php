<div class="page-header app_head">
    <h1 class="text-center text-primary">imei黑名单<small></small></h1>
</div>
<div class="alert alert-danger" style="text-align: center" >今日封掉 <?php echo $day[0];?>个手机，昨日封掉<?php echo $day[1]?>个手机，共封掉 <?php echo $day[2]?>个手机<br></div>
<div style="float:left">
	<span>安装次数：</span><input style="height:34px" type="text" name="an_ci" id="an_ci"  value="">
	<button style="margin-left: 5px;margin-right:10px" class="btn btn-primary" type="button" onclick="chaxun(0)">查询</button>
	<button class="btn btn-primary" type="button" onclick="chaxun(1)">一键导入黑名单</button>
</div>
<div style="float:right">
	<button class="btn btn-primary" type="button" onclick="shezhi()">设置(<?php echo $day[3]?>)</button>
</div>
<div class="page-header app_head" style="border:0px;margin:0px">
    <h1 class="text-center text-primary">&nbsp;<small></small></h1>
</div>
<div class="alert alert-danger" style="text-align:center;display:none " id="alert"></div>
<div class="alert alert-danger" style="display: none" id="alert222"></div>
<div style="float:left">
<span style='font-size:30px'>imei列表</span>
</div>
<div style="float:right">
	<button class="btn btn-primary" type="button" onclick="tianjia()">添加</button>
</div>
<br>
<?php


    $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'effectivepolicy-grid',
            'dataProvider'=>$data,
            'emptyText'=>'没有找到数据.',
            'nullDisplay'=>'',
            'columns'=>array(
                array(
                    'header'=>'imei',
                    'name'=>'imeicode',
                    // 'value'=>'ProductList::getname($data["pid"])',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'手机品牌',
                    'name'=>'brand',
                    // 'value'=>'$data["mac"]',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'手机型号',
                    'name'=>'model',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'用户名',
                    'name'=>'uid',
                    'value'=>'ProductList::getmemname($data["uid"])',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'封号日期',
                    'name'=>'createtime',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'操作人',
                    'value'=>'ProductList::imei($data["mid"])',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                // array(
                //     'header'=>'详细',
                //     'name'=>'id',
                //     // 'value'=>'ProductList::handle($data["id"])',
                //     'type'=>'raw',
                //     'htmlOptions'=>array('style'=>'text-align:center')
                // ),
               
                
            ),
        ));


?>
<style type="text/css">
	#test {
    width:100%;
    height:100%;
    background-color:#aaaaaa;
    position:absolute;
    top:0;
    left:0;
    z-index:2;
    opacity:0.3;
    /*兼容IE8及以下版本浏览器*/
    filter: alpha(opacity=30);
    display:none;
}
</style>

<!-- 弹出层 设置-->
<div id="test">
</div>
<div id="she_zhi" style="width:300px;height: 200px;background-color: #FFFFFF;position: fixed;left:43%;top:200px;z-index:3;border-radius: 5px;border:1px solid rgba(0, 0, 0, 0.3);box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);display:none ">
<div style="margin-top: 50px;margin-bottom: 50px">
	<span style="margin-left:10px">安装次数：</span><input style="height:34px" type="text" name="number_cha" id="number_cha"  value="">
</div>
<p style="text-align: center">
	<button class="btn btn-primary" type="button" onclick="cancel()">取消</button>
	<button style="margin-left: 50px" class="btn btn-primary" type="button" onclick='submiting()'>提交</button>
</p>
</div>
<!-- 结束设置 -->

<!-- 弹出层 添加 -->
<div id="tian_jia" style="width:400px;height: auto;background-color: #FFFFFF;position: fixed;left:37%;top:100px;z-index:3;border-radius: 6px;border:1px solid rgba(0, 0, 0, 0.3);display:none ;padding-left: 10px;box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);">
<p style="text-align: center;font-size:25px;margin-top:10px">添加imei</p>
<div style="margin-left: 29px;margin-bottom: 10px;"><span>imei：</span><input style="height:34px" type="text" name="imei" id="imei"  value=""><button style="margin-left:20px" class="btn btn-primary" type="button" onclick="search()">查询</button></div>
<div id="danger" class="alert alert-danger" style="display: none;margin-right:10px">数据库中没有该imei</div>
<div style="margin-bottom: 10px"><span>手机品牌：</span><input style="height:34px" type="text" name="brand" id="brand"  value=""></div>
<div style="margin-bottom: 10px"><span>手机型号：</span><input style="height:34px" type="text" name="model" id="model"  value=""></div>
<div style="margin-left: 15px;"><span>用户名：</span><input style="height:34px" type="text" name="username" id="username"  value=""></div>
<button style="margin-left:30%;margin-top:15%" class="btn btn-primary" type="button" onclick="tijiao()">提交</button>
<br><br><br><br><br><br>
<div style="position:relative;text-align: right;margin-right: 10px;padding-top:5px;margin-bottom:5px;border-top:1px solid #ccc">
<button  class="btn btn-primary" type="button" onclick="guanbi()">关闭</button>  
</div>
</div>
<!-- 结束添加 -->






<script>
/*查询按钮*/
function chaxun(id){
    var number=$('#an_ci').val();
    if(number==''){
        alert('请输入安装次数');
        return false;
    }
    if(id==0){   
        $.post(//查询
            '/dhadmin/productList/maxnum',
            {num:number},
            function(data){
                console.log(data);
                $('#alert').text('');
                $('#alert').append('根据条件安装次数大于'+number+'的查询结果是：'+data+'个手机<br>');
                $('#alert').show();
            }
        )
    }else{
        var a=confirm('确认导入黑名单吗？');
        if(a){
            $.post(//封号
                '/dhadmin/productList/maxnum',
                {num:number,handle:'black'},
                function(result){
                    $('#alert').text('');
                    $('#alert').append('<p>共导入黑名单'+result+'个手机</p>').show();
                    location.reload();
                }
            )
        }
    }
}
/*设置按钮*/
function shezhi(){
    $('#test').show();
    $('#she_zhi').show();
}
/*取消*/
function cancel(){
   $('#test').hide();
   $('#she_zhi').hide();
   $('#number_cha').val('');
}
/*关闭*/
function guanbi(){
    $('#test').hide();
    $('#tian_jia').hide();
    $('#tian_jia').find('input').val('');
    $('#danger').hide();
}
/*添加*/
function tianjia(){
    $('#test').show();
    $('#tian_jia').show();
}
/*添加页面-查询按钮*/
function search(){
    var imei=$('#imei').val();
    if(imei==''){
        alert('请输入imei');
        return false;
    }
    $.post(
        '/dhadmin/productList/feng',
        {imei:imei},
        function(data){
            if(data==''){
                $('#danger').text('数据库中没有该imei');
                $('#danger').show();
                $('#brand').val('');
                $('#model').val('');
                $('#username').val('');
            }else if(data[0]==1){
                $('#danger').text('该imei已经在黑名单中');
                $('#danger').show();
                $('#brand').val('');
                $('#model').val('');
                $('#username').val('');
            }else{
                $('#danger').hide();
                $('#brand').val(data.brand);
                $('#model').val(data.model);
                $('#username').val(data.uid); 
            }
        },
        'json'
    )
}
/*提交*/
function tijiao(){
    var imeicode=$('#imei').val();
    var brand=$('#brand').val();
    var model=$('#model').val();
    var username=$('#username').val();
    if(imeicode==''){
        alert('请输入imei');
        return false;
    }
    $.post(
        '/dhadmin/productList/feng',
        {imeicode:imeicode,brand:brand,model:model,username:username},
        function(data){
            if(data==1){
                location.reload();
            }else if(data==2){
                alert('数据库中没有该用户名，封号失败');
                location.reload();
            }else if(data==3){
                alert('该imei已经在黑名单中，操作失败');
                location.reload();
            }else{
                alert('封号失败');
            }
        }

    )

}
/*设置里面-提交*/
function submiting(){
    var number_cha=$('#number_cha').val();
    if(number_cha==''){
        alert('请输入安装次数');
        return false;
    }
    $.post(
        '/dhadmin/productList/dingshi',
        {number_cha,number_cha},
        function(data){
            if(data==1){
                alert('设置成功');
                cancel();
                location.reload();
            }else{
                alert('设置失败');
            }
        }

    )
}
</script>