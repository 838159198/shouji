<?php
    $articlehitone= Article::model()->find('`cid`=3 and `status`=1 order by hits desc');
    $articlelist= Article::model()->findAll('`cid`=3 and `status`=1 and id !='.$articlehitone["id"].' order by createtime desc limit 1');
    $postslist= Posts::model()->findAll('`cid`=1 and `status`=1 order by createtime desc limit 5');
    $campaign=new Campaign();
?>
<div class="mainreg" style="float:right">
    <div class="conusst" style="height:120px">
        <div class="contimg" style="margin-top: 20px"><img src="/css/site/images/qqhead.gif"/></div>
        <div class="context" style="padding-top:0px;padding-left:0px;width:210px">
            <!-- <strong>在线客服咨询</strong><br> -->
            <strong>客服电话：400-091-8058</strong><br>
            QQ工作时间：09:00-18:00(工作日)<br>
            <div class="contextqq">
        <span style="height:32px;">

                <span style="margin-left:10px;margin-right: 10px">在线客服咨询</span>
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=3135539730&site=qq&menu=yes" >
                    <img style="width:79px;height:25px" border="0" src="http://wpa.qq.com/pa?p=2:3488754157:51" alt="点击这里给我发消息" title="点击这里给我发消息"/>
                </a>
        </span>
        </div>
        </div>
        <!-- <div class="contextqq">
        <span style="height:32px;">

                <span style="margin-left:60px;margin-right: 10px">在线客服咨询</span>
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=3135539730&site=qq&menu=yes" >
                    <img style="width:79px;height:25px" border="0" src="http://wpa.qq.com/pa?p=2:3488754157:51" alt="点击这里给我发消息" title="点击这里给我发消息"/>
                </a>
        </span> -->
        <!-- <span style="height:32px">

                <span style="margin-right: 3px">速推然然</span>
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=3488754157&site=qq&menu=yes" >
                    <img style="width:79px;height:25px" border="0" src="http://wpa.qq.com/pa?p=2:3488754157:51" alt="点击这里给我发消息" title="点击这里给我发消息"/>
                </a>
        </span>
        <span style="height:32px;margin-left: 30px">

                <span style="margin-right: 3px">速推若琳</span>
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2478657917&site=qq&menu=yes" >
                    <img style="width:79px;height:25px" border="0" src="http://wpa.qq.com/pa?p=2:2478657917:51" alt="点击这里给我发消息" title="点击这里给我发消息"/>
                </a>
        </span>
        
            <span><span style="margin-right: 3px">在线客服</span><script charset="utf-8" type="text/javascript" src="http://wpa.b.qq.com/cgi/wpa.php?key=XzkzODAzODI0M180NjU2MDRfNDAwMDkxODA1OF8"></script></span>
            <span style="margin-left: 18px">

                <span style="margin-right: 3px">投诉建议</span>
                <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=3135539730&site=qq&menu=yes" >
                    <img style="width:79px;height:25px" border="0" src="http://wpa.qq.com/pa?p=2:3135539730:51" alt="点击这里给我发消息" title="点击这里给我发消息"/>
                </a>
            </span> -->
        <!-- </div> -->
    </div>


    <!--<div class="maintit titwid2" style="background-color: #ff2b10;height: 40px;line-height: 40px">
        <span class="text"> 周周赢手机 天天高收益</span><span class="more"><a href="/campaign/list">更多</a></span>
    </div>
    <hr>
    <div class="huodong">
        <?php /* $campaignStart=$campaign->getCampaignStart();//未开始或报名
            if($campaignStart){
                foreach($campaignStart as $v){
        */?>
        <div class="huodong_left"><a href='/hd/zzysjs/<?/*=$v->periods; */?>' target='_blank'><img src="<?/*=$v->p->pic; */?>"></a></div>
        <div class="huodong_right" style="float: right;">
            <ul>
                <li><a href='/hd/zzysjs/<?/*=$v->periods; */?>' target='_blank'>第<?/*=$v->periods; */?>期：<?/*=$v->p->name; */?></a></li>
                <li>活动价格：<?/*=sprintf("%.2f", ($v->p->price)*1.2); */?>元
                    <a href='/hd/zzysjs/<?/*=$v->periods; */?>' target='_blank'>
                        <span id='btn_status' style="padding-left: 5px;margin-right:0;text-align:center;background-color: #24a5ef"><?php /*echo date("Y-m-d H:i:s",time())>= $v->userstarttime?'火热报名': '未开始'*/?></span></a>
                    </li>
                <li>报名时间：<?/*=substr($v->userstarttime,5,2); */?>月<?/*=substr($v->userstarttime,8,2); */?>日-
                    <?/*=substr($v->userendtime,5,2); */?>月<?/*=substr($v->userendtime,8,2); */?>日</li>
            </ul>
        </div><hr>
        <?php /*}  }*/?>
        <?php /* $campaignList=$campaign->getCampaign();//正在进行
            if($campaignList){
        */?>
        <div class="huodong_start">
            <div class="huodong_left" ><a href='/hd/zzysjs/<?/*=$campaignList[0]->periods; */?>' target='_blank'><img src="<?/*=$campaignList[0]->p->pic; */?>"></a></div>
            <div class="huodong_right" style="float: right;">
                <ul>
                    <li ><a href='/hd/zzysjs/<?/*=$campaignList[0]->periods; */?>' target='_blank'>第<?/*=$campaignList[0]->periods; */?>期：<?/*=$campaignList[0]->p->name; */?></a></li>
                    <li>活动价格：<?/*=sprintf("%.2f", ($campaignList[0]->p->price)*1.2); */?>元<a href="/hd/zzysjs/<?/*=$campaignList[0]->periods; */?>" target="_blank"><span id='btn_status' style="padding-left: 5px;margin-right:0;background-color:#e14632;text-align:center">正在进行</span></a></li>
                    <li>活动时间：<?/*=substr($campaignList[0]->starttime,5,2); */?>月<?/*=substr($campaignList[0]->starttime,8,2); */?>日-
                        <?/*=substr($campaignList[0]->endtime,5,2); */?>月<?/*=substr($campaignList[0]->endtime,8,2); */?>日</li>
                </ul>
            </div>
        </div><hr>
        <?php /*}
            $campaignEnd=$campaign->getCampaignEnd();
            if($campaignEnd){
        */?>
        <div class="huodong_left"><a href='/hd/zzysjs/<?/*=$campaignEnd[0]->periods; */?>' target='_blank'><img src="<?/*=$campaignEnd[0]->p->pic;*/?>"></a></div>
        <div class="huodong_right" style="float: right;">
            <ul>
                <li ><a href='/hd/zzysjs/<?/*=$campaignEnd[0]->periods; */?>' target='_blank'>第<?/*=$campaignEnd[0]->periods; */?>期：<?/*=$campaignEnd[0]->p->name; */?></a></li>
                <li>活动价格：<?/*=sprintf("%.2f", ($campaignEnd[0]->p->price)*1.2); */?>元
                    <a href='/hd/zzysjs/<?/*=$campaignEnd[0]->periods; */?>' target='_blank'> <span id='btn_status' style="padding-left:5px;margin-right:0;text-align:center;background-color:#8b8b8b">已结束</span></a>
                   </li>
                <li style="margin-top: 6px">获奖用户：
                    <div  id="gundong" style="float: right;height: 20px;width: 140px;">
                        <?php
/*                        $sortArr=CampaignSort::model()->findByPeriods($campaignEnd[0]->periods);
                        if(!empty($sortArr))
                        {
                            foreach($sortArr as $v)
                            {
                                echo '<span style="float:right;padding-right: 10px;width:130px;height: 20px;padding: 0px;">'.$v.'</span>';
                            }
                        }
                        */?>

                    </div>

                </li>

            </ul>
        </div><hr>
        <?php /*}*/?>
    </div> 活动结束-->

    <div class="maintit titwid2">
        <span class="text">>> 最新公告</span><span class="more"><a href="/notice">更多</a></span>
    </div>
    <hr>
    <ul>
        <?php foreach($postslist as $row):?>
            <?php echo "<li><a href='{$row['url']}' target='_blank'>".Common::substr($row['title'],18)."</a></li>";?>
        <?php endforeach;?>

    </ul>

</div>
<script type="text/javascript">
    $(function(){
        var _wrap=$('#gundong');
        var _interval=1000;
        var _moving;
        _wrap.hover(function(){
            clearInterval(_moving);
        },function(){
            _moving=setInterval(function(){
                var _field=_wrap.find('span:first');
                var _h=_field.height()
                _field.animate({marginTop:-_h+'px'},600,function(){
                    _field.css('marginTop',0).appendTo(_wrap);
                })
            },_interval)
        }).trigger('mouseleave');
    });
</script>