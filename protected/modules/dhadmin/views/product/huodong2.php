<style>
    .input-append{margin-bottom: 10px;}
    select{height: 33px;}
    tr th,tr td{text-align: center}
    thead tr{background-color: rgba(88, 121, 128, 0.45)
</style>
<?php ;?>
<div class="page-header app_head">
    <h1 class="text-center text-primary"><span style="color:red ">第<?=$periods?>期：<?=$_GET['name']?></span> 报名中心 <small></small></h1>
    <span style="float: right;padding-top: 20px;padding-right: 20px;padding-bottom: 10px;"><?php echo "共".$count."条";?></span>
</div>
<div class="row-fluid">
    <div class="app_button">
        <a href="/dhadmin/product/huodong" class="btn btn-primary">活动中心</a>
        <a href="/dhadmin/product/hddata?periods=<?=$_GET['periods']?>&name=<?=$_GET['name']?>" class="btn btn-warning">活动数据</a>
        <a href="/dhadmin/product/hdorder?periods=<?=$_GET['periods']?>&name=<?=$_GET['name']?>" class="btn btn-success">活动排名</a>
    </div>
<div style="height: 30px;width: 100px;">&nbsp;</div>

</div>

<?php
function getLoginUrl($id, $host = 'local')
{
    $loginID = base64_encode($id . '_userid' . time());
    $url = '';
    switch ($host) {
        case 'local':
            $url = Yii::app()->createUrl('site/mlogin', array('uid' => $loginID));
            break;
    }
    return $url;
}
?>
<div class="row-fluid">
    <?php
/*    $caminfo=Campaign::model()->findAll(array(
        'select' =>array('title,id,periods'),
        'order' => 'periods DESC',
    ));
    $pcamlog_data = array();
    foreach($caminfo as $k=>$t)
    {
        $pcamlog_data[$t['periods']]= $t['title'];
    }

    echo CHtml::beginForm('huodong', 'post', array('class'=>'input-append')),
        ''.CHtml::dropDownList('title', '', $pcamlog_data).'
 &nbsp;&nbsp;',
    CHtml::submitButton('提交',  array('class'=>'btn btn-info')),
    CHtml::endForm();
    */?>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th><input type='checkbox' id='member-info-grid-all'>全选</th>
            <th>序号</th>
            <th>用户名</th>
            <!--<th>手机号</th>-->
            <th>QQ</th>
            <th>当前状态</th>
            <th>申请时间</th>

            <th width="75">操作</th>
        </tr>
    </thead>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <tbody>
        <?php foreach($data as $row):?>
        <tr>
            <td>
                <input type='checkbox' name='selectdsend[]' value='<?php echo $row['username']; ?>'>
            </td>
            <td><?php echo $row['id'];?></td>
            <td>
                <?php
                $member=Member::model()->find('username="'.$row['username'].'"');
                if (Auth::check('member_mlogin')) {
                     echo CHtml::link($member->username, getLoginUrl($member->id), array('target' => '_blank'));
                } else {
                    echo $row['username'];
                }
                ?>
            </td>
            <!--<td><?php /*echo $row['tel'];*/?></td>-->
            <td><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $row['qq'];?>&site=qq&menu=yes">
                    <img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $row['qq'];?>:51" alt="点击这里给我发消息" title="点击这里给我发消息"/></a> <?php echo $row['qq'];?> </td>
            <td title="<?=$row['bak']?>"><?php if( $row['statusname']=="未审核"){
                    echo "<span style='color: red;font-weight: 800'>".$row['statusname']."</span>";
                }else{
                    echo $row['statusname'];
                }
                ?>
            </td>
            <td><?php echo $row['createtime'];?></td>

            <td><a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/product/huodongedit/id/".$row['id']);?>" class="label label-primary"><span class="glyphicon glyphicon-header" aria-hidden="true"></span></a></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
</div>
<div class="row-fluid text-center">
    <nav>
        <?php
        $this->widget('CLinkPager',array(
                'header'=>'',
                'cssFile'=>false,
                'firstPageLabel' => '首页',
                'lastPageLabel' => '末页',
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
                'pages' => $pages,
                'maxButtonCount'=>8,
                'htmlOptions'=>array("class"=>"pagination pagination-lg"),
            )
        );
        ?>
    </nav>

</div>

<div><a href="javascript:show()" class="btn btn-danger">获取用户名</a></div>

<!-- 获取用户名弹出框 -->
<div class="modal fade" id="tt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
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
        var _url="/dhadmin/product/usernames?usernames="+usernames;
        $('#tt').removeData("bs.modal");
        $("#tt").modal({remote: _url});
    }
</script>
