<style type="text/css">
    .btn-group1{}
    .tongji{color:#008800; font-family: Arial, monospace;font-size:18px;font-weight:bold}
    .alert-success{width: 97%;min-height: 230px;height: auto;overflow:hidden}
    .alert-info{width: 32%;margin-right:10px;display: inline-block;}
    .alert-info p{font-family: "Microsoft YaHei" ! important;font-size: 13px;min-width: 320px;margin-right: -10px;margin-top: 20px;}
    .alert-info p a:hover{text-decoration: none;}
    .img-rounded{padding-right:20px;}

    .prolef{float: left;text-align: center; min-width: 20%;padding-right: 15px;}
    .prolef h4,img{font-family: "Microsoft YaHei" ! important;font-size: 14px;font-weight: bold;}
    .prolef h4{margin-bottom: 15px;margin-top: 20px;}
    .prolef img{width: 72px; height: 72px;}
    .bgstyle{background:url("/css/images/new.png") no-repeat top right;  background-color: #d9edf7;}
    .tongji-left{width: 40%;float: left;}
    .tongji-rig{width: 48%;float: left;margin-left: 2%}
    .tongji-rig2{width: 10%;float: left;}
</style>
<?php
    $user = Yii::app()->user;
    $uid=$this->uid;
    $manageid=$user->getState('member_manage');
    $member=Member::model()->find("id=$uid");
    $agent=$member["agent"];
    $urom=RomSoftpak::model()->find('status=0 and uid='.$uid);
    $uinfo=Member::model()->find('status=1 and invitationcode!="" and invitationcode like "st%" and id='.$uid);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <div class="row">
                <ol class="breadcrumb pull-left">
                    当前位置：<li><a href="/msg">管理主页</a></li>
                    <li class="active">产品列表</li>
                </ol>
            </div>



<!--            --><?php //if($agent==99 || $agent==96 || $manageid==1)
//            {
                $this->renderPartial('_view',array('romPackage'=>$romPackage,'dataP'=>$dataP,'versionArr'=>$arr));

//            }


            ?>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $(".ibgstyle").parents('.alert-info').addClass("bgstyle");
        $(".clicktask").click(function(){
            alert("请下载921手机助手使用");
        });
    });

</script>
<script type="text/javascript">
    function aa(){
        $.ajax({
            type:"POST",
            url:"/msg/product/ajaxOnoff",
            data: {install : <?php echo $member['launcher_install']?>},
            datatype: "json",
            success:function(data){
                var jsonStr = eval("("+data+")");
                if(jsonStr.status==500){
                    alert(jsonStr.msg);
                    return false;
                }else if(jsonStr.status==200){
                    alert(jsonStr.msg);
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