<style type="text/css">
    .btn-group1{}
    .tongji{color:#008800; font-family: Arial, monospace;font-size:18px;font-weight:bold}
    .alert-success{width: 98%;height: 230px;}
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
    .tongji-rig{width: 50%;float: left;}
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
                    当前位置：<li><a href="/ditui">管理主页</a></li>
                    <li class="active">产品列表</li>
                </ol>
            </div>
            <div class="alert alert-success" id="mlist">
                <h4 class="tongji" style=" color:#4A515B">921手机助手<hr style="margin-top: 6px;"></h4>
                <div class="tongji-left">
                    <h4 class="tongji" style="color: #0077b3;padding-left: 13px;">PC版</h4>
                    <img style="float:left;" class="img-rounded" src="/css/site/images/sutuiapp.png" alt="">
                    <p style="margin-top: -15px;"><b class="tongji">使用电脑安装方法：<br>
                        <span style="font-weight:normal;font-size:14px; height: 100px;">
                下载921手机助手，登陆账号；<br>
                连接手机，选择组合包下载或自由下载APP并安装；<br>
                            <?php
                            $newday=date('Y-m-d H:i:s',strtotime('+10 Day',strtotime('2016-10-09 00:00:00')));
                            $today=date('Y-m-d H:i:s');
                            if(strtotime($today)>strtotime($newday)) {echo "<b style='color: green;font-family: Arial, monospace;font-size:16px;font-weight:bold;'>V1.16.908.16575--2016-10-09</b>";}
                            else {echo "<b style='color: red; font-family: Arial, monospace;font-size:16px;font-weight: bold;'>V1.16.908.16575--2016-10-09</b>";}
                            ?>

                    </span>
                        </b></p>
                    <div class="btn-group btn-group1" style="margin-left:-5px; margin-top:1px;" id="mh2">
                        <?php
                        if($manageid==1 || !empty($urom) || !empty($uinfo))
                        {
                            echo '<a class="btn btn-primary" href="/uploads/pczs/stzs.exe">点击下载</a>';
                        }
                        else
                        {
                            echo '<a class="btn btn-primary" href="javascript:alert(\'请联系客服开启\')">点击下载</a>';
                        }
                        ?>
                    </div>
                </div>
                <div class="tongji-rig">
                    <h4 class="tongji" style=" color:#0077b3;padding-left: 7px;">安卓版</h4>
                    <img style="float:left;" class="img-rounded" src="/css/site/images/app_icon_72.png" alt="">
                    <p style="margin-top: -15px;"><b class="tongji">使用手机安装方法：<br>
                        <span style="font-weight:normal;font-size:14px; height: 100px;">
                       点击“获取专属APP”，预装手机浏览器输入：<?php if(!empty($appurl)) {echo "sutuiapp.com/".$appurl;} else{echo "XXXXXXXXXXXXXXX";} ?>，<br>或扫描二维码下载921助手安卓版，通过921助手下载安装软件。<br>
                            <?php
                            $newday=date('Y-m-d H:i:s',strtotime('+10 Day',strtotime('2017-01-04 00:00:00')));
                            $today=date('Y-m-d H:i:s');
                            if(strtotime($today)>strtotime($newday)) {echo "<b style='color: green;font-family: Arial, monospace;font-size:16px;font-weight:bold;'>V8.0--2017-01-04</b>";}
                            else {echo "<b style='color: red; font-family: Arial, monospace;font-size:16px;font-weight: bold;'>V8.0--2017-01-04</b>";}
                            ?>

                    </span>
                        </b></p>
                    <div class="btn-group btn-group1" style="margin-left:-19px; margin-top:1px;" id="mh3">
                        <a class="btn btn-primary" href="<?php if(!empty($appurl)) {echo "javascript:void(0)";} elseif(empty($appurl) && ($manageid==1 || !empty($urom) || !empty($uinfo))) {echo Yii::app()->request->url."?uidt=".$this->uid;} else {echo "javascript:alert('请联系客服开启')";}?>">
                            <?php  echo $appurlmsg; ?>
                        </a>
                    </div>
                </div>
                <div class="tongji-rig2">
                    <h4 class="tongji" style=" color:#0077b3;padding-left: 11px;">二维码下载</h4>
                    <img style="float:left;width:116px;height:116px;" src="<?php echo $codeurl; ?>" >
                </div>



            </div>

            <?php if($agent!=69 || ($agent=69 && $uid==69) || $manageid==1)
            {
                $this->widget('zii.widgets.CListView', array(
                    'dataProvider' => $data,
                    'itemView' => '_view',
                    'summaryText' => '',
                    'viewData' => array('resourceStatus' => $resourceStatus,),
                ));
            } ?>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $(".ibgstyle").parents('.alert-info').addClass("bgstyle");
        $(".clicktask").click(function(){
            alert("请下载921手机助手使用");
        });
    })

</script>