<link rel="stylesheet" type="text/css" href="/css/member/product.css">
<script type="text/javascript" src="/js/member/product.js"></script>
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
                    <li class="active">产品列表</li>
                </ol>
            </div>
<!--<a href="/member/product/textMethod">按钮</a>-->
            <!--统计软件-->
            <div class="alert alert-success" >
                <h4 class="tongji" style="width: 200px; font-size: 30px;color:black;font-weight: bold"><span class="tongjisoft">统计软件</span></h4>
                <span class="tongji">下载统计软件stat.apk内置于ROM的system/app目录下,否则无法计费。<br></span>
                <div class="btn-group btn-group1" style="float: right;margin-top: -55px;margin-right: 14%">
                    <a  class="btn btn-primary btn-lg btn-success" style="float: left;border-radius: 5px" href="<?php if(!empty($appurl)) {echo $appurl;} else {echo Yii::app()->request->url.'/stat';}?>"><?php echo '下载统计软件';?></a>
                    <div class="btn-updata" style="float: left;margin-left: 10px;font-size: 14px">
                        更新日期:<span>2017-11-14</span><br>
                        版本:<span>V9.0</span>&nbsp;&nbsp;&nbsp;大小:<span>148KB</span>
                    </div>
                </div>
            </div>
            <!--温馨提示-->
            <div class="alert alert-warning">
                <span style="margin-top: 5px;font-size: 14px"><?php  if(!empty($prompt))   echo $prompt[0]['content'];?></span>
            </div>

            <?php
            $user = Yii::app()->user;
            $uid=$user->getState('member_uid');

            $manageid=$user->getState('member_manage');
            $member=Member::model()->find("id=$uid");
            $agent=$member["agent"];
            ?>

            <?php if($agent!=69 || ($agent==69 && $uid==69) || ($agent==69 && $uid==722) || $manageid==1)
            {
                $this->renderPartial('_view',array('data'=>$resourceList,'arr'=>$arr,'resourceStatus' => $resourceStatus));
            }

            ?>

        </div>
    </div>
</div>
<div class="pop">
    <div class="popMain">
        <div class="popimg"></div>
        <div class="poptitle" style="color: #fff;">业务包正在压缩，这个过程可能需要几分钟，请您耐心等待，压缩完成后自动下载……</div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $(".ibgstyle").parents('.alert-info').addClass("bgstyle");
    })

</script>