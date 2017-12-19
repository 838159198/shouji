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
            var content =$('.prompt-content').val();
            if (!content){
                alert('请输入内容');
                return;
            }
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/dhadmin/product/prompt",
                data: {
                    "content":content
                },
                success: function(data){
                    if (parseInt(data.val) == 1){
                        window.location.href = '/dhadmin/product';
                    }else{
                       alert('数据更新失败,请重新更新');
                    }
                },
                error : function(data) {
                }
            })
        })

    })
</script>



<div class="page-header app_head">
    <h1 class="text-center text-primary">温馨提示<small></small></h1>
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
        <label>提示內容:</label><br>
        <textarea type="text" class="prompt-content" style="margin-left: 40px;width: 400px;height: 200px"><?php echo $data[0]['content'];?>></textarea>
        <button type="submit" class="btn">提交</button>
    </div>
</div>