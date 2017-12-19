<style type="text/css">
    .add-category{
        width: 100px;
        height: 40px;
        text-align: center;
        font-size: 20px;
        color: white;
        line-height: 40px;
        background: #3385ff;
        border: 1px solid transparent;
        border-radius: 5px;
        margin-top: 100px;
        margin-bottom: 5px;
        margin-left: 30px;
        cursor: pointer;
    }
    .main-category{
        margin-left: 80px;
    }
    .edit-category,.del-category{
        cursor: pointer;
        color: #0e509e;
    }
    .table{
        width: 1113px;
    }
</style>

<script type="text/javascript">
    $(function () {
        $('.edit-category').click(function () {
           var p= $(this).parent().parent().find('.classid').text();
            window.location.href = 'edit?id='+p;
        })

        $('.del-category').click(function () {
           var id = $(this).parent().parent().find('.classid').text();
            $.ajax({
                type:'post',
                dataType:"json",
                url:"/dhadmin/recruitmanage/del",
                data:{
                    'classid':id
                },
                success:function (data) {
                    console.log('2222');
                },
                error:function () {
                    console.log('1111');
                }
            })
            // 移除点击删除所在的行
                $(this).parent().parent().remove();
        })

        $('.add-category').click(function () {
            window.location.href = 'edit';
        })

    })




</script>

<div class="container-fluid">
    <div class="row">
        <!--左侧-->
        <div class="col-md-10 main-category">
            <div class="add-category">新增类别</div>
            <div>
            <table class="table table-bordered" style="text-align: center">
                <thead>
                <tr class="info">
                    <th style="text-align: center">编号</th>
                    <th style="text-align: center">招聘类别</th>
                    <th style="text-align: center">类别编码</th>
                    <th style="text-align: center">创建时间</th>
                    <th style="text-align: center">操作</th>
                </tr>
                </thead>
                <?php foreach ($data as $vt):?>
                <?php
                    $timeP= date('Y-m-d H:i',$vt['createtime']);
                    echo "
             
                <tr class='p'>
                    <td width='200'>{$vt['id']}</td>
                    <td width='200'>{$vt['classname']}</td>
                    <td class='classid' width='200'>{$vt['classid']}</td>
                    <td width='200'>{$timeP}</td>
                    <td width='200'><span class='edit-category'>编辑</span>/<span class='del-category'>删除</span></td>
                </tr>
              
                "?>
                <?php endforeach;?>
            </table>
        </div>
    </div>
</div>