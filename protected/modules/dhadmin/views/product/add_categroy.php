<style type="text/css">
    .row .col-md-2{
        float: left;
    }
    .col-md-10{
        float: left;
        margin-top: -120px;
        margin-left: 40%;
    }
    .col-md-10 .btn{
        display: block;
        margin-top: 20px;
        margin-left:100px;
        background-color: #337ab7;
        color: white;
    }
</style>

<script type="text/javascript">

    $(function () {

        $('.btn').click(function () {
            var name =$('.new-category').val();
            if (!name){
                alert('请输入类名');
                return;
            }
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/dhadmin/product/ajax",
                data: {
                    "name":name
                },
                success: function(data){
                    if (parseInt(data.val) == 0){
                        alert('类名已存在,请重新输入');
                    }else if(parseInt(data.val) == 1){
                        window.location.href = '/dhadmin/product';
                    }
                },
                error : function() {

                }
            })
        })

    })



</script>



<div class="page-header app_head">
    <h1 class="text-center text-primary">添加分类<small></small></h1>
</div>
<div class="container-fluid">
    <div class="row" >
        <div class="col-md-2">
            <div class="list-group">
                <li class="list-group-item active">业务产品</li>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/product");?>" class="list-group-item">返回列表</a>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/product/create");?>" class="list-group-item">重新添加</a>
            </div>
        </div>
        </div>
    <div class="col-md-10">
        <label>新增类名</label>
        <input type="text" class="new-category" />
        <button type="submit" class="btn">提交</button>
        </div>
    </div>