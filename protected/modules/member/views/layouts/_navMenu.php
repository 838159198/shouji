<?php
    $user = Yii::app()->user;
    $uid=$this->uid;
    $manageid=$user->getState('member_manage');
    $member=Member::model()->find("id=$uid");
    $agent=$member["agent"];
?>
<style>
    @media (min-width:1274px)and(max-width: 1551px){
     
        #divv{margin-left: 0px;width:167px;}
        #ppp{
            text-align: left;margin-left:12px 
        }
        #pic{
            text-align: left;
        }
        
    }

    /*@media(max-width: 1274px){
        #zixun{
            margin-left:10px;
        }
    }*/

    @media (min-width: 992px){
        .col-md-10{
        width:none;
        float: none;
        }
        .col-md-2{
            width:100%;
        }   
    }

    @media(min-width:1274px){
        #divv{margin-left: 20%;width:167px;}
        #pic{
            text-align:center;
        }
        #ppp{
        
        }

    }
    @media (min-width:1274px){.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9{float:left}.col-md-12{width:100%}.col-md-11{width:91.66666667%}.col-md-10{width:83.33333333%}.col-md-9{width:75%}.col-md-8{width:66.66666667%}.col-md-7{width:58.33333333%}.col-md-6{width:50%}.col-md-5{width:41.66666667%}.col-md-4{width:33.33333333%}.col-md-3{width:25%}.col-md-2{width:16.66666667%}.col-md-1{width:8.33333333%}.col-md-pull-12{right:100%}.col-md-pull-11{right:91.66666667%}.col-md-pull-10{right:83.33333333%}.col-md-pull-9{right:75%}.col-md-pull-8{right:66.66666667%}.col-md-pull-7{right:58.33333333%}.col-md-pull-6{right:50%}.col-md-pull-5{right:41.66666667%}.col-md-pull-4{right:33.33333333%}.col-md-pull-3{right:25%}.col-md-pull-2{right:16.66666667%}.col-md-pull-1{right:8.33333333%}.col-md-pull-0{right:auto}.col-md-push-12{left:100%}.col-md-push-11{left:91.66666667%}.col-md-push-10{left:83.33333333%}.col-md-push-9{left:75%}.col-md-push-8{left:66.66666667%}.col-md-push-7{left:58.33333333%}.col-md-push-6{left:50%}.col-md-push-5{left:41.66666667%}.col-md-push-4{left:33.33333333%}.col-md-push-3{left:25%}.col-md-push-2{left:16.66666667%}.col-md-push-1{left:8.33333333%}.col-md-push-0{left:auto}.col-md-offset-12{margin-left:100%}.col-md-offset-11{margin-left:91.66666667%}.col-md-offset-10{margin-left:83.33333333%}.col-md-offset-9{margin-left:75%}.col-md-offset-8{margin-left:66.66666667%}.col-md-offset-7{margin-left:58.33333333%}.col-md-offset-6{margin-left:50%}.col-md-offset-5{margin-left:41.66666667%}.col-md-offset-4{margin-left:33.33333333%}.col-md-offset-3{margin-left:25%}.col-md-offset-2{margin-left:16.66666667%}.col-md-offset-1{margin-left:8.33333333%}.col-md-offset-0{margin-left:0}}
    @media (max-width: 1551px){
        #divv{margin-left: 0px;width:167px;}
        #ppp{
            text-align: left;margin-left:12px 
        }
        #pic{
            text-align: left;
        }
        .panel-body{
            padding:15px 0px;
        }
        
    }
     @media(width:1440px){
         #divv{margin-left: 20px;width:167px;}
         .panel-body{
            padding:15px 0px;
         }
    }
    @media(width:1366px){
         #divv{margin-left: 20px;width:167px;}
         .panel-body{
            padding:15px 0px;
         }
    }
</style>
<div class="list-group" style="min-width:203px">
    <?php $a =$this->getId()."_".$this->getAction()->id;?>
    <div  class="list-group-item activea "><b>业务管理</b></div>
    <a href="<?php echo Yii::app()->createUrl("/member/product");?>" class="list-group-item <?php if($a=="product_index"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> 产品列表</a>
<!--    --><?php
/*    $mbill=MemberBill::model()->find('uid='.$uid);
    if($mbill['paid']>=500 || $mbill['surplus']>=500 || $mbill['nopay']>=500)
    {
        echo '<a href="'.Yii::app()->createUrl("/member/product/newindex").'" class="list-group-item "><span class="glyphicon glyphicon-list" aria-hidden="true"></span> VIP产品区(<span style="color:red">new</span>)</a>';
    }
    */?>
    <?php if($agent==0): ?>
        <a href="<?php echo Yii::app()->createUrl("/member/product/campaignindex");?>" class="list-group-item <?php if($a=="product_campaignindex"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> <span style="color:red;font-weight: bold;">活动产品区</span></a>
    <?php endif; ?>

    <a href="<?php echo Yii::app()->createUrl("/member/datashow/productupdate");?>" class="list-group-item <?php if($a=="datashow_productupdate"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> 产品更新状况</a>
    <a href="<?php echo Yii::app()->createUrl("/member/datashow/extmodel");?>" class="list-group-item <?php if($a=="datashow_extmodel"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> ROM测试</a>
    <a href="<?php echo Yii::app()->createUrl("/member/datashow/instalcheck");?>" class="list-group-item <?php if($a=="datashow_instalcheck"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> 安装量分析</a>
    <a href="<?php echo Yii::app()->createUrl("/member/datashow/activatop");?>" class="list-group-item <?php if($a=="datashow_activatop"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> 应用激活排行</a>
    <a href="<?php echo Yii::app()->createUrl("/member/datashow/modeltop");?>" class="list-group-item <?php if($a=="datashow_modeltop"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> 热门机型排行</a>
    <a href="<?php echo Yii::app()->createUrl("/member/datashow/instruction");?>" class="list-group-item <?php if($a=="datashow_instruction"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> 业务说明</a>




    <div  class="list-group-item activea "><b>财务管理</b></div>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/default/income");?>" class="list-group-item <?php if($a=="default_income"):?>list-group-item-infoA<?php endif;?>" target="_blank"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> 收益明细</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/withdraw/index");?>" class="list-group-item <?php if($a=="withdraw_index"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 财务提现</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/paylog/index");?>" class="list-group-item <?php if($a=="paylog_index"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-yen" aria-hidden="true"></span> 付款记录</a>
    <?php
        $m=Member::model()->getById($this->uid);
        if($m["type"]==1)
        {
            echo '<a href="/member/incomes/index" class="list-group-item"><span class="glyphicon glyphicon-yen" aria-hidden="true"></span> 子用户收益</a>';
        }
    ?>
    <div  class="list-group-item  activea "><b>个人信息</b></div>
    <a href="<?php echo Yii::app()->createUrl("/member/info");?>" class="list-group-item <?php if($a=="info_index"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> 我的信息</a>
    <a href="<?php echo Yii::app()->createUrl("/member/info/edit");?>" class="list-group-item <?php if($a=="info_edit"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> 资料修改</a>
    <?php if($agent!=69 ||($agent==69 && $uid==69) || $manageid==1): ?>
    <a href="<?php echo Yii::app()->createUrl("/member/info/bank");?>" class="list-group-item <?php if($a=="info_bank"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span> 财务信息修改</a>
    <?php endif; ?>
    <a href="<?php echo Yii::app()->createUrl("/member/info/password");?>" class="list-group-item <?php if($a=="info_password"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> 密码修改</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/spread/index");?>" class="list-group-item  <?php if($a=="spread_index"):?>list-group-item-infoA<?php endif;?>" ><span class="glyphicon glyphicon-file" aria-hidden="true"></span> 推广链接</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/members/index");?>" class="list-group-item  <?php if($a=="members_index"):?>list-group-item-infoA<?php endif;?>" ><span class="glyphicon glyphicon-file" aria-hidden="true"></span> 推广用户</a>
    <div  class="list-group-item activea "><b>商城管理</b></div>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/members/location/index");?>" class="list-group-item <?php if($a=="location_index"):?>list-group-item-infoA<?php endif;?>" ><span class="glyphicon glyphicon-file" aria-hidden="true"></span> 收货地址</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/shop/order/index");?>" class="list-group-item <?php if($a=="order_index"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 订单查询</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/members/creditslog");?>" class="list-group-item <?php if($a=="credits_log"):?>list-group-item-infoA<?php endif;?>"><span class="glyphicon glyphicon-yen" aria-hidden="true"></span> 积分记录</a>
    <a href="<?php echo Yii::app()->createUrl("/site/logout");?>" class="list-group-item list-group-item"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> 安全退出</a>

</div>



<div class="panel panel-info" style="min-width: 203px;overflow: hidden">
    <div class="panel-heading">
        <h3 class="panel-title"><b>在线客服</b></h3>
    </div>
    <div class="panel-body" style="">
        <div style="text-align: center">
        <div id="divv" >
         <!--  <p style="padding-left:px">

                <span>速推然然</span>
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=3488754157&site=qq&menu=yes" >
                    <img border="0" src="http://wpa.qq.com/pa?p=2:3488754157:51" alt="点击这里给我发消息" title="点击这里给我发消息"/>
                </a>
        </p>
         <!--  <p style="padding-left:px">

                <span>速推然然</span>
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=3488754157&site=qq&menu=yes" >
                    <img border="0" src="http://wpa.qq.com/pa?p=2:3488754157:51" alt="点击这里给我发消息" title="点击这里给我发消息"/>
                </a>
        </p>
          <!--  <p style="padding-left:px">

                <span>速推然然</span>
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=3488754157&site=qq&menu=yes" >
                    <img border="0" src="http://wpa.qq.com/pa?p=2:3488754157:51" alt="点击这里给我发消息" title="点击这里给我发消息"/>
                </a>
        </p>
        <p style="padding-left:px">

                <span>速推若琳</span>
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2478657917&site=qq&menu=yes" >
                    <img border="0" src="http://wpa.qq.com/pa?p=2:2478657917:51" alt="点击这里给我发消息" title="点击这里给我发消息"/>
                </a>
        </p>
        

            <div style="float: left;margin-left:10px;margin-right:5px">在线客服</div><span><script charset="utf-8" type="text/javascript" src="http://wpa.b.qq.com/cgi/wpa.php?key=XzkzODAzODI0M180NjU2MDRfNDAwMDkxODA1OF8"></script></span>

            <p style="padding-left:px;margin-top:5px">

                <span>投诉建议</span>
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=3135539730&site=qq&menu=yes" >
                    <img border="0" src="http://wpa.qq.com/pa?p=2:3135539730:51" alt="点击这里给我发消息" title="点击这里给我发消息"/>
                </a>
            </p> -->
             <p style="padding-left:px;margin-top:5px">

                <span id="zixun">在线客服咨询</span>
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=3135539730&site=qq&menu=yes" >
                    <img border="0" src="http://wpa.qq.com/pa?p=2:3135539730:51" alt="点击这里给我发消息" title="点击这里给我发消息"/>
                </a>
            </p>
            <p id="ppp" style="">官方微信：sutuiapp</p>
        </div>
        
            <p id="pic"><img src="/css/site/images/sutuiapp.jpg" width="200" height="190"/></p>
            
        </div>
    </div>
</div>