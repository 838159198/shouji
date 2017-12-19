<?php
    $this->breadcrumbs = array(
        '群发站内信'=>array('createMailToUidList'),
    '站内信历史记录'=>array('logmail'),
    '站内信详情',
);

?>

<a href="/dhadmin/mail/logmail"><<站内信历史记录</a>
<h3><?php  echo $data[0]['title']; ?></h3>
<?php
echo $data[0]['content'].'<br><br><br>';
?>


<button class="btn btn-info" onclick="read('')">显示所有</button> <button class="btn btn-info" onclick="read(1)">显示已读</button> <button class="btn btn-info" onclick="read(0)">显示未读</button> <?php if (Auth::check('mail_delall')){
        echo '<button class="btn btn-info" onclick="del()">批量撤回</button>';
        }?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'agent-grid',
    // 'enablePagination' => false,
    // 'enableSorting' => false,
    // 'itemsCssClass' => Bs::TABLE,
    'itemsCssClass' =>'items',//分页表格的样式
    'dataProvider' => $dataProvider,
    // 'filter' => $model,
    'columns' => array(
        array(
            'header'=>'<input onclick="qaunxuan()" type="checkbox" name="all" id="all">',
            'value' => 'MailContent::input($data->id,$data->status)',
            'type'=>'raw',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array(
            'name'=>'recipient',
            'header'=>'收件人',
            'value'=>'MailContent::member($data->recipient)',
            'htmlOptions'=>array('style'=>'text-align:center'),
            
        ),
         array('name' => 'jointime',
            'header'=>'发送时间',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array(
            'name'=>'status',
            'header'=>'用户状态',
            'type'=>'html',
            'value'=>'MailContent::status($data->status)',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array(
            'header'=>'操作',
            'value'=>'MailContent::chehui($data->id,2)',
            // 'value'=>'"<a class=\'delete\' title=\'删除\' href=\'/manage/mail/updel?id=$data->id\'><img src=\'/assets/1d0e9428/gridview/delete.png\' alt=\'删除\'></a>"',
            'type'=>'raw',
            // 'value'=>'',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
    ),
)); ?>
<script type="text/javascript">
// 全选
// $('[name=all]').click(function(){
//     if($('[name=all]').is(':checked')){
//         $('[name=checkbox]').attr('checked','checked');
//     }else{
//         $('[name=checkbox]').removeAttr('checked');
//     }


// })

function qaunxuan(){
    if($('[name=all]').is(':checked')){
            $('[name=checkbox]').prop('checked','checked');
            
        }else{
            $('[name=checkbox]').removeAttr('checked');
            
        }
}
//颜色
// $('.btn.btn-info').click(function(){
//     $(this).attr('style','background:#00688B');
//     $(this).siblings().removeAttr('style');

// })
//批量删除
function del(){
    var select=false;
    var id='';
    $('[name=checkbox]:checked').each(function(i){
        if(typeof($(this).attr('key'))=='undefined'){

        }else{
            id+=$(this).attr('key')+',';
            select=true;
        }
        
    })
    if(select){
        var a=confirm('是否确定要撤回');
        if(a){
            $.post(
                '/dhadmin/mail/delall',
                {id:id},
                function(data){
                    console.log(data);
                    if(data==1){
                        alert('撤回成功');
                        location.reload();
                    }
                    else if(data==2){
                        alert('撤回失败');
                    }
                }

           ) 
        }
    }else{
        alert('请选择要撤回的数据');
    }


}
//获取url中的参数
function GetQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}
//全选或已读或未读
function read(num){
    location.href='/dhadmin/mail/look?id='+GetQueryString("id")+'&status='+num;
    // var dlform = document.createElement('form');
    // dlform.style = "display:none;";
    // dlform.method = 'post';
    // dlform.action = 'look';
    // // dlform.target = 'callBackTarget';
    // var hdnFilePath = document.createElement('input');
    // hdnFilePath.type = 'hidden';
    // hdnFilePath.name = 'status';
    // hdnFilePath.value = num;
    // dlform.appendChild(hdnFilePath);
    // document.body.appendChild(dlform);
    // dlform.submit();
    // document.body.removeChild(dlform);

}
</script>