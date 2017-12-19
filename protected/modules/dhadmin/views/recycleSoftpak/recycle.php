<style type="text/css">
    .item_select select,input{
        display: inline-block;
        padding: 4px;
        height: 30px;
        width: 100px;
        font-size: 13px;
        line-height: 18px;
        color: #808080;
        border: 1px solid #ccc;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }
    .checkbox-column input {
        height: 20px;
        width: 20px;
    }
</style>
<?php
$this->breadcrumbs = array('用户路由列表');
$this->menu = array(
    array('label' => '统计软件回收', 'url' => array('recycleSoftpak/recycle')),
    array('label' => '回收记录', 'url' => array('recycleSoftpak/index')),
);
?>
<h4 class="text-center">统计软件回收</h4>
<?php
$this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));
$template = '';
//$template .= Auth::check('manage.admin.view') ? ' {view}' : '';

$template .= Auth::check('manage.admin.update') ? ' {update}' : '';
$template .= Auth::check('manage.admin.delete') ? ' {delete}' : '';?>
<?php
//判断是否有提示信息
if(Yii::app()->user->hasFlash('status')){?>
    <div class="alert alert-success">
        <b><?php echo Yii::app()->user->getFlash('status');?></b>
    </div>
<?php }?>
<div class="alert alert-info" >
    门店：共有<?=$data_dt['count']?>个统计软件，已分配<?=$data_dt['count1']?>个，剩余<?=$data_dt['count2']?>个<br>
    门店：共有<?=$data_desk['count']?>个桌面，已分配<?=$data_desk['count1']?>个，剩余<?=$data_desk['count2']?>个<br>
    ROM：共有<?=$data_rom['count']?>个统计软件，已分配<?=$data_rom['count1']?>个，剩余<?=$data_rom['count2']?>个<br>
</div>
<div style="margin-left: 30px" class="item_select">
    <span>零收益：</span>
    <select id="income">
        <option value=">" <?php if(isset($_GET['income']) && $_GET['income']=='>'){?> selected<?php }?>>大于</option>
        <option value="<" <?php if(isset($_GET['income']) && $_GET['income']=='<'){?> selected<?php }?>>小于</option>
        <option value="=" <?php if(isset($_GET['income']) && $_GET['income']=='='){?> selected<?php }?>>等于</option>
        <option value=">=" <?php if(isset($_GET['income']) && $_GET['income']=='>='){?> selected<?php }?>>大于等于</option>
        <option value="<=" <?php if(isset($_GET['income']) && $_GET['income']=='<='){?> selected<?php }?>>小于等于</option>
    </select>
    <input type="text" name="income_day" id="income_day"  value="<?=isset($_GET['income_day'])?$_GET['income_day']:0?>">天&nbsp;&nbsp;&nbsp;&nbsp;
    <span>零安装：</span>
    <select id="install">
        <option value=">" <?php if(isset($_GET['install']) && $_GET['install']=='>'){?> selected<?php }?>>大于</option>
        <option value="<" <?php if(isset($_GET['install']) && $_GET['install']=='<'){?> selected<?php }?>>小于</option>
        <option value="=" <?php if(isset($_GET['install']) && $_GET['install']=='='){?> selected<?php }?>>等于</option>
        <option value=">=" <?php if(isset($_GET['install']) && $_GET['install']=='>='){?> selected<?php }?>>大于等于</option>
        <option value="<=" <?php if(isset($_GET['install']) && $_GET['install']=='<='){?> selected<?php }?>>小于等于</option>
    </select>
    <input type="text" name="install_day" id="install_day"  value="<?=isset($_GET['install_day'])?$_GET['install_day']:0?>">天&nbsp;&nbsp;&nbsp;&nbsp;
    <span>未登录：</span>
    <select id="logou">
        <option value=">" <?php if(isset($_GET['logou']) && $_GET['logou']=='>'){?> selected<?php }?>>大于</option>
        <option value="<" <?php if(isset($_GET['logou']) && $_GET['logou']=='<'){?> selected<?php }?>>小于</option>
        <option value="=" <?php if(isset($_GET['logou']) && $_GET['logou']=='='){?> selected<?php }?>>等于</option>
        <option value=">=" <?php if(isset($_GET['logou']) && $_GET['logou']=='>='){?> selected<?php }?>>大于等于</option>
        <option value="<=" <?php if(isset($_GET['logou']) && $_GET['logou']=='<='){?> selected<?php }?>>小于等于</option>
    </select>
    <input type="text" name="logout_day" id="logout_day"  value="<?=isset($_GET['logout_day'])?$_GET['logout_day']:0?>">天
</div>

<div style="margin-left: 30px;margin-bottom: 20px;margin-top: 10px" class="item_select">
    <span>渠&nbsp;&nbsp;&nbsp;&nbsp;道：</span>
    <select id="agent">
        <option value="tongji" <?php if(isset($_GET['agent']) && $_GET['agent']=='tongji'){?> selected<?php }?>>ROM</option>
        <option value="newdt" <?php if(isset($_GET['agent']) && $_GET['agent']=='newdt'){?> selected<?php }?>>线下</option>
    </select>&nbsp;&nbsp;&nbsp;&nbsp;

    用户名：<input type="text" name="username" id="username"  value="<?=isset($_GET['username'])?$_GET['username']:''?>">&nbsp;&nbsp;&nbsp;&nbsp;
    统计ID：<input type="text" name="serial_number" id="serial_number"  value="<?=isset($_GET['serial_number'])?$_GET['serial_number']:''?>">
        <button style="margin-left: 5px;margin-right:10px" class="btn btn-primary" type="button" onclick="chaxun()">查询</button>
    <!--<a href="http://sutui.me/dtApi/GetRecycleData" class="btn btn-success">更新</a>-->
    <button style="margin-left: 5px;margin-right:10px" class="btn btn-success" type="button" onclick="updata()" id="update_data">更新</button>
    <?php
    //判断是否有提示信息
    if(Yii::app()->user->hasFlash('status_sum')){?>
            <b><?php echo Yii::app()->user->getFlash('status_sum');?></b>
    <?php }?>
</div>

<div>
    <button style="margin-left: 5px;margin-right:10px" class="btn btn-primary" type="button"  name="admin-grid_c0_all" id="all">全选</button>
    <button style="margin-left: 5px;margin-right:10px" class="btn btn-primary" type="button" id="payAll">批量回收</button>
</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'admin-grid',
    'dataProvider' =>$dataProvider,
    'pager'=>array(
        'class'=>'CLinkPager',//定义要调用的分页器类，默认是CLinkPager，需要完全自定义，还可以重写一个，参考我的另一篇博文：http://blog.sina.com.cn/s/blog_71d4414d0100yu6k.html
        // 'cssFile'=>false,//定义分页器的要调用的css文件，false为不调用，不调用则需要亲自己css文件里写这些样式
        'header'=>'转往分页：',//定义的文字将显示在pager的最前面
        // 'footer'=>'',//定义的文字将显示在pager的最后面
        'firstPageLabel'=>'首页',//定义首页按钮的显示文字
        'lastPageLabel'=>'尾页',//定义末页按钮的显示文字
        'nextPageLabel'=>'下一页',//定义下一页按钮的显示文字
        'prevPageLabel'=>'前一页',//定义上一页按钮的显示文字
        //关于分页器这个array，具体还有很多属性，可参考CLinkPager的API
    ),
    'emptyText'=>'没有发现用户',
    //'filter' => $model,
    'columns' => array(
        array(
            'name' => '全选',
            'value' => '$data["serial_number"]."*".$data["username"]',
            'selectableRows' => 2,
            'header' => CHtml::checkBox('all', false),
            'class' => 'CCheckBoxColumn',
            'checkBoxHtmlOptions' => array('name' => 'rid[]'),
            'visible' =>true,
        ),
        array('header'=>'序号',
            'value'=>'++$row',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'header'=>'用户名',
            'name'=>'username',
            'value'=>'$data["username"]',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'header'=>'统计ID',
            'name'=>'serial_number',
            'value'=>'$data["serial_number"]',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'header'=>'未登录(天)',
            'name'=>'logout_day',
            'value'=>'$data["logout_day"]',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'header'=>'零收益(天)',
            'name'=>'income_day',
            'value'=>'$data["income_day"]',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'header'=>'零安装(天)',
            'name'=>'install_day',
            'value'=>'$data["install_day"]',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'header'=>"操作",
            'value'=>'"<a class=\"label label-danger\" href=\'javascript:view(".$data["serial_number"].",\"".$data["username"]."\")\'><span class=\"glyphicon glyphicon-remove-sign\" aria-hidden=\"true\"></span> 回收</a>"',
            'htmlOptions'=>array("style"=>"text-align:center;width:70px;"),
            'type'=>'raw',
            'filter'=>false,
        ),
    ),
));
?>
<script type="text/javascript">
    $(function () {
        $("#all").click(function () {
            $("input[name='rid[]']").each(function(){
                $(this).prop('checked',true);
            })

        });

        $("#payAll").click(function () {

            var ids = [];
            var name='';
            $(":checkbox[name='rid[]']").each(function () {
                if (this.checked) {
                    var str=this.value;
                    var num =str.indexOf('*');
                    name+=str.substr(num+1)+'、';
                    ids.push(str.substr(0,num));
                }
            });
            if(confirm('是否对用户'+name+'的统计ID进行回收？'))
            {
                if (ids.length > 0) {
                     $.ajax({
                         type:"POST",
                         url:"/dhadmin/recycleSoftpak/recycleAll",
                         data:{id:ids},
                         datatype: "json",
                             success:function(data){
                             var jsonStr = eval("("+data+")");
                             if(jsonStr.status==400){
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
            }
            return false;
        });

    });
</script>
<script TYPE="TEXT/JAVASCRIPT">
    /*查询按钮*/
    function chaxun(){
        var income=$('#income').val();
        var income_day=$('#income_day').val();
        if(income==''){
            alert('请选择零收益查询条件');
            return false;
        }
        if(income_day !=''){
            if(isNaN(income_day)){
                alert('零收益天数不为数字');
                return false;
            }
        }

        var install=$('#install').val();
        var install_day=$('#install_day').val();
        if(install==''){
            alert('请选择零安装查询条件');
            return false;
        }
        if(install_day !=''){
            if(isNaN(install_day)){
                alert('零安装天数不为数字');
                return false;
            }
        }

        var logou=$('#logou').val();
        var logout_day=$('#logout_day').val();
        if(install==''){
            alert('请选择零安装查询条件');
            return false;
        }
        if(logout_day==''){
            if(isNaN(logout_day)){
                alert('未登录天数不为数字');
                return false;
            }
        }
        var agent=$('#agent').val();
        if(agent==''){
            alert('请选择渠道类型');
            return false;
        }
        var username=$('#username').val();
        var serial_number=$('#serial_number').val();
       window.location.href="/dhadmin/recycleSoftpak/recycle?income="+income+"&income_day="+income_day+"&install="+install+"&install_day="
       +install_day+"&logou="+logou+"&logout_day="+logout_day+"&agent="+agent+"&username="+username+"&serial_number="+serial_number;
//        $.ajax({
//            type:"GET",
//            url:"/dhadmin/recycleSoftpak/recycle",
//            data:{income:income,income_day:income_day,install:install,install_day:install_day,logou:logou,logout_day:logout_day,agent:agent},
//            datatype: "json",
//            success:function(data){
//                var jsonStr = eval("("+data+")");
//                if(jsonStr.status==400){
//                    alert(jsonStr.message);
//                    return false;
//                }else if(jsonStr.status==200){
//                    alert(jsonStr.message);
//                    location.replace(location.href);
//                }else{
//                    alert("发生错误"+jsonStr.status);
//                    return false;
//                }
//            },
//            error: function(){
//                alert("未知错误");
//            }
//        });
    }
    function view(tid,name){
        {
            if(confirm('是否对用户'+name+'的统计ID'+tid+'进行回收？'))
            {
                $.ajax({
                    type:"POST",
                    url:"/dhadmin/recycleSoftpak/recycleTj",
                    data:{tid:tid},
                    datatype: "json",
                    success:function(data){
                        var jsonStr = eval("("+data+")");
                        if(jsonStr.status==400){
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
            return false;
        }
    }
    function updata(){

        //2.让提交按钮失效，以实现防止按钮重复点击
        $("#update_data").attr('disabled', 'disabled');

         //3.给用户提供友好状态提示
        $("#update_data").text('更新中...');
        $.ajax({
            type:"POST",
            url:"/dhadmin/recycleSoftpak/UpdateRecycle",
            datatype: "json",
            success:function(data){
                var jsonStr = eval("("+data+")");
                if(jsonStr.status==400){
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