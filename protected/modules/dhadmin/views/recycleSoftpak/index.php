<?php
$this->breadcrumbs = array('用户路由列表');
$this->menu = array(
    array('label' => '统计软件回收', 'url' => array('recycleSoftpak/recycle')),
    array('label' => '回收记录', 'url' => array('recycleSoftpak/index')),
);
?>
<style type="text/css">
    select {
        display: inline-block;
        padding: 4px;
        font-size: 13px;
        line-height: 18px;
        color: #808080;
        border: 1px solid #ccc;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }
    div p{margin:0 auto;width:400px;}
</style>
<h4 class="text-center">统计软件回收记录</h4>
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
    <h5 class="text-center">回收记录</h5>
    <p>ROM统计软件：<?php echo isset($data[0])?$data[0]:'0'?>个 &nbsp;&nbsp;
        门店统计软件：<?php echo isset($data[1])?$data[1]:'0'?>个 &nbsp;&nbsp;
        门店桌面：<?php echo isset($data[3])? $data[3]:'0'?>个</p>
</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(

    'id' => 'admin-grid',
    'dataProvider' => $model->search(),
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
    'filter' => $model,
    'columns' => array(
        array(
            'name'=>'type',
            'value'=>'$data->xtype',
            'filter'=>CHtml::listData(RecycleSoftpakLog::model()->listDataType,'key','value'),
            'type'=>"html",
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'uid',
            'value'=>'$data->user->username',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'serial_number',
            'value'=>'$data->serial_number',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'createtime',
            'value'=>'$data->createtime',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'reply_date',
            'value'=>'$data->reply_date',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
       /* array(
            'name'=>'mid',
            'value'=>'$data->m->name',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),*/
        array(
            'header'=>"操作",
            'value'=>'($data->status==0)?"<a class=\"label label-danger\" href=\'javascript:rollbackTj(".$data->id.",".$data->serial_number.",\"".$data->user->username."\")\'><span class=\"glyphicon glyphicon-remove-sign\" aria-hidden=\"true\"></span> 恢复</a>":"已恢复"',
            'htmlOptions'=>array("style"=>"text-align:center;width:70px;"),
            'type'=>'raw',
            'filter'=>false,
        ),
    ),
));
?>
<script type="text/javascript">
        //恢复回收的统计
        function rollbackTj(id,tid,name){
            if(confirm('是否对用户'+name+'的统计ID'+tid+'进行恢复？')){
                var id =id;
                $.ajax({
                    type:"POST",
                    url:"/dhadmin/recycleSoftpak/reply",
                    data:{id:id},
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
        }
</script>
