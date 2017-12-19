
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.7.2/jquery.min.js"></script>
<script  type="text/javascript" src="/js/core/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript" src="/css/yearendDraw/js/awardRotate.js"></script>
<script type="text/javascript" src="/css/yearendDraw/yearendDraw.js"></script>
<link rel="stylesheet" type="text/css" href="/css/yearendDraw/yearendDraw.css" />

<div class="draw-banner">
    <div class="draw-banner-bg"></div>
</div>
<div class="draw-container">
    <div class="draw-container-top">
        <div class="draw-left-img"></div><div class="draw-container-topimg"><?php echo Yii::app()->user->name;?></div><div class="draw-right-img"></div><div class="draw-container-topprompt">您好！&nbsp;&nbsp;&nbsp;&nbsp;每人只有一次抽奖机会哦！</div>
    </div>
    <div class="draw-container-content">
        <div class="turntable-bg">
            <!--<div class="mask"><img src="images/award_01.png"/></div>-->
            <div class="pointer"><img src="/css/yearendDraw/images/pointer33.png" alt="pointer"/></div>
            <div class="rotate" ><img id="rotate" src="/css/yearendDraw/images/turntable4.png" alt="turntable"/></div>
        </div>
        <div style="text-align:center;"></div>
    </div>
    <div class="draw-container-side">
        <div class="draw-jilu">
            <div class="draw-jilu-title">获奖榜单</div>
            <div class="draw-jilu-content">
                <ul>
                <?php $this->widget('application.components.widget.draw.DrawWidget',array("num" => 20)); ?>
                </ul>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(".draw-jilu").slide({
                mainCell:".draw-jilu-content ul",
                autoPlay:true,
                effect:"topMarquee",
                vis:12,
                interTime:50,
                trigger:"click"});
        </script>
    </div>
</div>
<div class="draw-spread">
    <div class="draw-spread-backimg">
        <?php
        $spread_link= '';
        if (Yii::app()->user->isGuest){
            $spread_link = 'http://sutuiapp.com/2017year';
        }else{
            $userInfo = Yii::app()->user;
            if (empty($userInfo->member_uid)){
                $spread_link = 'http://sutuiapp.com/2017year';
            }else{
                $spread_link = 'http://sutuiapp.com/2017year?id='.$userInfo->member_uid;
            }
        }
        ?>
       <div class="draw-spread-div"> <input type="text" value="<?php echo $spread_link;?>"/></div>
        <div class="bdsharebuttonbox">
            <a href="#" class="bds_tsina" data-cmd="tsina"></a>
            <a href="#" class="bds_qzone" data-cmd="qzone"></a>
            <a href="#" class="bds_tieba" data-cmd="tieba"></a>
            <a href="#" class="bds_weixin" data-cmd="weixin"></a>
            <a href="#" class="bds_copy" data-cmd="copy"></a>
            <a href="#" class="bds_more" data-cmd="more"></a>
        </div>
    </div>
    <script>window._bd_share_config=
        {"common":
        {"bdSnsKey":{},"bdText":"《新年礼包抽抽》活动","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"32","bdUrl":"<?php echo $spread_link;?>",
            "bdDesc":"速推正在举办《2017年感恩回馈 年货免费带回家》活动，真的是100%中奖哦，你也一起来参加吧！"},
            "share":{}
        };
        with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
    </script>
</div>


<div class="draw-good">
    <div class="draw-good-title"></div>
    <div class="draw-good-img1"></div>
    <div class="draw-good-img2"></div>
    <div class="draw-good-img3"></div>
</div>
<div class="draw-status">
    <div class="draw-status-img"></div>


<div class="draw-status-main">
    <table>
        <tr>
        <td width="200">用户名</td>
        <td width="200" >地址</td>
        <td width="280">奖品</td>
        <td width="120">状态</td>
        <td width="180">日期</td>
        </tr>
    </table>
    <div class="draw-status-list">
        <div class="draw-obtain-main">
            <div class="draw-obtain-list">
                <div class="draw-obtain-list-content">
                    <ul>
                        <?php $this->widget('application.components.widget.draw.DrawStatusWidget',array("num" => 20)); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(".draw-obtain-list").slide({
        mainCell:".draw-obtain-list-content ul",
        autoPlay:true,
        effect:"topMarquee",
        vis:5,
        interTime:50,
        trigger:"click"
    });
</script>
</div>

<div class="pop">
    <div class="popMain">
        <div class="popTop"></div>
        <div class="popMiddle">
            <div><img src="/css/yearendDraw/images/gx.png" width="120px"></div>
            <p>恭喜你抽中速推2017感恩回馈</p>
            <p class="pop_draw" style="color: red"></p>
           
        </div>
        <div class="popBottom">
            <div class="cancel">取消</div>
        </div>
    </div>
</div>

