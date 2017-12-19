<script>
    <?php $max_num = intval($member['credits']/$goods['credits'])?>
    $(document).ready(function() {
        $("#add").click(function () {
            var n = $("#num").val();
            var num = parseInt(n) + 1;
            if (num == 0) {
                alert("再减下去就没啦 -_-|||");
            }
            if (num ><?php echo $max_num?>) {
                alert("别装土豪啦，你的积分已经不够啦！");
                $("#num").val(<?php echo $max_num?>);
                return;
            }
            $("#num").val(num);
        });
        $("#jian").click(function () {
            var n = $("#num").val();
            var num = parseInt(n) - 1;
            if (num == 0) {
                alert("再减下去就没啦 -_-|||");
                return
            }
            $("#num").val(num);
        });
        $('#button_submit').click(function(){
            var goods_id = <?php echo $goods['id']?>;
            var goods_num = $("#num").val();
            var location = $('input:radio[name=location]:checked').val();
            var remarks = $("#remarks").val();
            //检测数量是否是数字
            if(isNaN(goods_num)){
                alert("请填写正确数量");
                return false;
            }
            if(location===null||location==undefined){
                alert("请选择收货地址");
                return false;
            }
            //检测备注长度
            if(remarks.length>100){
                alert("备注内容太长了，不能超过100个字符！");
                return false;
            }
            //return false;
            $.ajax({
                type: "POST",
                url: "/member/shop/buyok",
                //,goods_num:goods_num,location:location,remarks:remarks
                data: {goods_id:goods_id,goods_num:goods_num,location:location,remarks:remarks},
                dataType: "json",
                success: function(data){
                    if(data.msg=="ok"){
                        //成功，跳转到用户订单界面
                        alert(data.message);
                        window.location.href="/member/shop/order";
                        return false;
                    }else if(data.msg=="error"){
                        alert(data.message);
                        return false;
                    }else{
                        alert("兑换失败，请重新提交");
                        return false;
                    }
                },
                error : function() {
                    alert("发生异常，请重新提交！");
                }
            });
        });
    });
</script>
<?php
$this->breadcrumbs = array(
    '产品订购',
);
?>
<style>
    #num{ width:40px; height:16px; line-height:16px; border:1px solid #CCC; text-align:center;  float:left; margin:0 5px;}
    #add,#jian{ width:26px; height:20px; float:left; border:1px solid #ccc; background:#eee; cursor:pointer;}
    .divrow{ width: 800px; margin:0;padding: 20px 0;overflow: hidden;}
    .divrow-l{ width: 80px;  float: left;}
    .divrow-r{ width: 680px; float: left;}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <div class="row">
                <ol class="breadcrumb pull-left">
                    当前位置：<li><a href="/member">管理主页</a></li>
                    <li class="active">订单查询</li>
                </ol>
            </div>
            <div class="divrow">
                <div class="divrow-l"> </div>
                <div class="divrow-r"><img src="<?php echo $goods['previewimage'];?>"></div>
            </div>
            <div class="divrow">
                <div class="divrow-l">产品名称：</div>
                <div class="divrow-r"><?php echo CHtml::encode($goods['title']);?></div>
            </div>
            <div class="divrow">
                <div class="divrow-l">积分：</div>
                <div class="divrow-r"><?php echo CHtml::encode($goods['credits']);?></div>
            </div>
            <div class="divrow">
                <div class="divrow-l">数量选择：</div>
                <div class="divrow-r">
                    <input type="button" id="jian" value="-" />
                    <input type="text" id="num" value="1" readonly="readonly" />
                    <input type="button" id="add" value="+" />
                </div>
            </div>
            <div class="divrow">
                <div class="divrow-l">地址选择：</div>
                <div class="divrow-r">
                    <p><a href="/member/members/location">创建收货地址</a> </p>
                    <?php foreach($memberLocation as $row):?>
                    <label><input type="radio" name="location" id="location" value="<?php echo $row['id']?>"> <?php echo $row['name']." ".$row['tel']." ".$row['address']."</label>";?>
                        <?php endforeach;?>
                </div>
            </div>
            <div class="divrow">
                <div class="divrow-l">备注：</div>
                <div class="divrow-r">
                    <textarea cols="20" rows="5" name="remarks" id="remarks" style="width: 600px;"></textarea>
                </div>
            </div>
            <div class="divrow">
                <div class="divrow-l">&nbsp;</div>
                <div class="divrow-r">
                    <input type="button" value="我要兑换" id="button_submit" />
                </div>
            </div>
        </div>
    </div>
</div>

