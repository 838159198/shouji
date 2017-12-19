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
</style>
<script type="text/javascript">
    var img_id_upload=new Array();//初始化数组，存储已经上传的图片名
    var i=0;//初始化数组下标
    $(function() {
        $('#file_upload').uploadify({
            'auto'     : false,//关闭自动上传
            'removeTimeout' : 1,//文件队列上传完成1秒后删除
            'swf'      : '/js/uploadify/uploadify.swf',
            'uploader' : '<?php echo Yii::app()->createUrl("dhadmin/boxdesk/moreUpload")?>',
            'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
            'buttonText' : '选择文件',//设置按钮文本
            'multi'    : true,//允许同时上传多张图片
            'uploadLimit' : 10,//一次最多只允许上传10张图片
            'fileTypeExts':'*.apk',
            'fileSizeLimit' : '20000KB',//限制上传的图片不得超过200KB
            'onSelect' : function(file){
                var parten =/^launcherV+(\d)+\.(\d)+\(+([1-9][0-9]{5})\)\.apk$/;
                var r = (file.name).match(parten);
                if(r==null){
                    alert(file.name+'文件不匹配，请删除');
                    $('#file_upload').uploadify('cancel', file.id)//删除不匹配的
                }

            },
            'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
                img_id_upload[i]=data;
                i++;
                if(data !='上传成功')
                    alert(data);
            },
            'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
                document.location.reload();
                // if(img_id_upload.length>0)
                // alert('成功上传的文件有：'+encodeURIComponent(img_id_upload));
            }
            // Put your options here
        });
    });
</script>
<?php
$this->breadcrumbs = array('盒子桌面列表');
$this->menu = array(
    array('label' => '盒子列表', 'url' => array('softbox/index')),
    array('label' => '盒子文件', 'url' => array('boxdata/index')),
    array('label' => '盒子桌面', 'url' => array('boxdesk/index')),
);
?>

<h1 class="text-center text-primary">盒子桌面列表</h1>
<?php
$this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));?>

<div class="row-fluid">
    <div class="app_button"  style="margin-top: 30px;">
        <a href="<?php echo $this->createUrl("boxdesk/create");?>" class="btn btn-success">单个软件上传</a>
    </div>
    <div style="width: 100%;height: 3px;background-color: green;margin-top: 20px;margin-bottom: 20px;"></div>
    <div>

        <p><a href="javascript:$('#file_upload').uploadify('settings', 'formData', {'typeCode':document.getElementById('id_file').value});$('#file_upload').uploadify('upload','*')" class="btn btn-success">批量上传</a>
            <a href="javascript:$('#file_upload').uploadify('cancel','*')" class="btn btn-success">取消</a>
        </p><input type="file" name="file_upload" id="file_upload" />
        <input type="hidden" value="1215154" name="tmpdir" id="id_file">
    </div>
</div>
<?php
//判断是否有提示信息
if(Yii::app()->user->hasFlash('status')):?>
    <div class="alert alert-success">
        <b><?php echo Yii::app()->user->getFlash('status');?></b>
    </div>
<?php endif;?>
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
            'name'=>'id',
            'value'=>'$data->id',
            'htmlOptions'=>array('style'=>'text-align:center;width:100px;'),
        ),
        array(
            'name'=>'uid',
            'value'=>'$data->uid',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        'filename',
        'md5',
        'downloadurl',
        'version',
        array(
            'name'=>'createtime',
            'value'=>'empty($data->createtime)?"--": date("Y-m-d H:i:s",$data->createtime)',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'updatetime',
            'value'=>'empty($data->updatetime)?"--": $data->updatetime',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'header'=>'操作',
            'class' => 'CButtonColumn',
            'updateButtonUrl'=>'Yii::app()->createUrl("dhadmin/boxdesk/edit",array("id"=>$data->id));',
            'template'=>'{update}',// {delete}
            'buttons'=>array(
                /*演示代码测试
                 * 'print'=>array(
                    'label'=>'更新',
                    'url'=>'Yii::app()->controller->createUrl("print", array("id"=>$data->id))',
                    'options'=>array("target"=>"_blank","onclick"=>"return del()"),
                ),*/
                /*'delete'=>array(
                    'options'=>array("onclick"=>"return del()"),
                    'click'=>"none",
                ),*/
            ),

        ),
    ),
));
?>

