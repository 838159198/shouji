<style type="text/css">
    .btn-group1{margin-left:20px;}
    .tongji{color:#008800; font-family: Arial, monospace;font-size:18px;font-weight:bold}
    .alert-success{width: 98%;}
    .alert-info{width: 48%;margin-right:10px;display: inline-block;}
    .alert-info p{font-family: "Microsoft YaHei" ! important;font-size: 13px;min-width: 320px;margin-right: -10px;}
    .alert-info p a:hover{text-decoration: none;}
    .img-rounded{padding-right:20px;}

    .prolef{float: left;text-align: center; min-width: 20%;padding-right: 15px;}
    .prolef h4,img{font-family: "Microsoft YaHei" ! important;font-size: 14px;font-weight: bold;}
    .prolef h4{margin-bottom: 15px;margin-top: 20px;}
    .prolef img{width: 72px; height: 72px;}
    .protext{height: 96px;line-height: 25px;color: #ff0000;background-color: #ffffe7;border: 1px solid #ffcd66;margin-bottom: 20px;width: 98%;padding: 10px;color: #f89406}
    .protext strong{color: darkorange}
    .bgstyle{background:url("/css/images/new.png") no-repeat top right;  background-color: #d9edf7;}
    #alert-infost a{/*margin-left: 20px;*/}
    #alert-infost p{text-decoration:none;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;}
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
                    当前位置：<li><a href="/dealer">管理主页</a></li>
                    <li class="active">VIP产品区</li>
                </ol>
            </div>
            <div class="protext" id="mp">
                <strong>开启业务方法</strong><br>
                第一步：选择需要预装的APP应用点击开启业务-下载APP-预装到ROM(data区，平台标明需要内置system区的除外)<br>
                第二步：下载统计软件，内置至system区；（必须安装，否则无法统计数据）
            </div>
            <div class="alert alert-success">
                <h4 class="tongji" style=" color:#4A515B">统计软件</h4>
                <img style="float:left;" class="img-rounded" src="/css/site/images/sutuiapp.png" alt="">
                <p style="margin-top: -15px;"><b class="tongji">下载说明：<br>
                        <span style="font-weight:normal;font-size:14px; height: 100px;">
                        1. 自定义统计软件也必须放在system/app/文件夹下；<br>
                        2. 统计软件实际打包大小可能会有差异，不影响正常数据统计。<br>
                            <?php
                            $newday=date('Y-m-d H:i:s',strtotime('+10 Day',strtotime('2016-10-14 00:00:00')));
                            $today=date('Y-m-d H:i:s');
                            if(strtotime($today)>strtotime($newday))
                            {
                                echo "<b style='color: green;font-family: Arial, monospace;font-size:16px;font-weight:bold;'>V7.0--2016-10-14</b>";
                            }
                            else
                            {
                                echo "<b style='color: red; font-family: Arial, monospace;font-size:16px;font-weight: bold;'>V7.0--2016-10-14</b>";
                            }
                            ?>

                    </span>
                    </b></p>
                <div class="btn-group btn-group1" style="margin-left:-5px; margin-top:10px;">
                    <!--<a class="btn btn-primary" href="<?php /*echo $appurl;*/?>"><?php /*echo $appurlmsg;*/?></a>-->
                    <a class="btn btn-primary" href="<?php if(!empty($appurl)) {echo $appurl;} else {echo Yii::app()->request->url."?uidt=".$this->uid;}?>"><?php echo $appurlmsg;?></a>
                </div>
            </div>

            <?php
            $user = Yii::app()->user;
            $uid=$this->uid;
            $manageid=$user->getState('member_manage');
            $member=Member::model()->find("id=$uid");
            $agent=$member["agent"];
            ?>

            <?php if($agent!=69 || ($agent=69 && $uid==69) || ($agent=69 && $uid==722) || $manageid==1)
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
    })

</script>