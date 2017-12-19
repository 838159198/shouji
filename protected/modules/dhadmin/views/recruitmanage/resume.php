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
    .col-main{
        margin-left: 100px;
    }
    .edit-category,.del-category{
        cursor: pointer;
        color: #0e509e;
    }
    .table{
        width: 1050px;
    }

</style>

<script type="text/javascript">
    $(function () {
        $('.edit-category').click(function () {
            var linkid= $(this).parent().parent().find('.linkid').text();
            var id= $(this).parent().parent().find('.num').text();
            window.location.href = 'addresume?linkid='+linkid+'&id='+id;
        })

        $('.del-category').click(function () {
            var id = $(this).parent().parent().find('.num').text();
            var is_select = $(this).parent().parent().find('input').val();
            $.ajax({
                type:'post',
                dataType:"json",
                url:"/dhadmin/recruitmanage/delResume",
                data:{
                    'id':id,
                    'is_select':is_select,
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
            window.location.href = 'addresume';
        })
        
        // 是否前台显示
        $('.check input').click(function () {
            if ($(this).val() == 0){
                $(this).attr('value','1');
                $(this).attr('checked','checked');
            }else {
                $(this).attr('value','0');
            }
            var id = $(this).parent().parent().find('.num').text();
            var show = $(this).val();
            
            $.ajax({
                type:'post',
                dataType:"json",
                url:"/dhadmin/recruitmanage/show",
                data:{
                    'id':id,
                    'show':show
                },
                success:function (data) {
                    var arr = {};
                    $.each(data.val, function (index, did) {
                        if (id != did['id']) {
                            arr[index] = did['id'];
                        }
                    })
                    console.log(id);
                    console.log(arr);
                    $('.p .num').each(function (index, dom) {
                        $.each(arr, function (ind, vt) {
                            if ($(dom).text() == vt){
                                $(dom).parent().find('.check input').removeAttr('checked');
                                $(dom).parent().find('.check input').attr('value',0);
                            }
                        })
                    })
                },
                error:function () {
                    console.log('1111');
                }
            })
        })
    })
</script>

<div class="container-fluid">
    <div class="row">
        <!--左侧-->
        <div class="col-md-10 col-main">
            <div class="add-category">新增职位</div>
            <div>
                <table class="table table-bordered" style="text-align: center">
                    <thead>
                    <tr class="info">
                        <th style="text-align: center">编号</th>
                        <th style="text-align: center">招聘类别</th>
                        <th style="text-align: center">招聘职位</th>
                        <th style="text-align: center">类别编码</th>
                        <th style="text-align: center">创建时间</th>
                        <th style="text-align: center">是否显示</th>
                        <th style="text-align: center">操作</th>
                    </tr>
                    </thead>
                    <?php foreach ($data as $vt):?>
                        <?php if ($vt['is_show'] == 1):?>
                        <?php
                        $timeP= date('Y-m-d H:i',$vt['createtime']);
                        echo "
                <tr class='p'>
                    <td class='num' width='200'>{$vt['id']}</td>
                    <td width='200'>{$vt['classname']}</td>
                    <td width='200'>{$vt['jobname']}</td>
                    <td class='linkid' width='200'>{$vt['link_id']}</td>
                    <td width='200'>{$timeP}</td>
                    <td class='check'  width='200'><input type='checkbox' value='1' checked='checked'></td>
                    <td width='200'><span class='edit-category'>编辑</span>/<span class='del-category'>删除</span></td>
                </tr>
              
                "?>
                     <?php endif;?>
                     <?php if($vt['is_show'] == 0):?>
                       <?php
                        $timeP= date('Y-m-d H:i',$vt['createtime']);
                        echo "
                <tr class='p'>
                    <td class='num' width='200'>{$vt['id']}</td>
                    <td width='200'>{$vt['classname']}</td>
                    <td width='200'>{$vt['jobname']}</td>
                    <td class='linkid' width='200'>{$vt['link_id']}</td>
                    <td width='200'>{$timeP}</td>
                    <td class='check' width='200'><input type='checkbox' value='0'></td>
                    <td width='200'><span class='edit-category'>编辑</span>/<span class='del-category'>删除</span></td>
                </tr>
                "?>
                     <?php endif;?>
                <?php endforeach;?>
                </table>

            </div>
        </div>
    </div>