<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8" />
    <?php Yii::app()->clientScript->registerCoreScript('jquery',CClientScript::POS_HEAD);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/bootstrap/js/bootstrap.min.js");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/bootstrap/css/bootstrap.min.css");?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/member/member.css");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/qtip/jquery.qtip.css");?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/qtip/jquery.qtip.js");?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/site/js/jquery.ui.datepicker-zh-CN.min.js");?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/site/js/jquery-ui-1.10.2.custom.js");?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/site/js/jquery-ui-1.10.2.custom.min.js");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/site/js/jquery.ui.1.10.0.ie.css");?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/site/js/jquery-ui-1.10.0.custom.css");?>
    <?php if (Common::isMobile()) {Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/member/member-mobile.css");} ?>

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
    <?php //Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/css/respond/respond.min.js");?>
    <title>管理系统</title>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/css/respond/respond.min.js"></script>
    <script type="text/javascript" src="/css/html5shiv/html5shiv.min.js"></script>
    <![endif]-->
</head>
<body>
<!--[if lt IE 10]>
<style type="text/css">
    #ie6-warning{
        background:rgb(255,255,225) no-repeat scroll 3px center;
        position:absolute;
        top:0;
        left:0;
        font-size:14px;
        color:#ff0000;
        width:100%;
        height: 60px;
        line-height: 60px;

        text-align:center;
        z-index: 9999999;
        border-bottom:#FFA0A2 solid 2px;
        font-weight: bold;
    }
    #ie6-warning a {
        text-decoration:none;
    }
</style>
<div id="ie6-warning">尊敬的用户您好！我们发现您正在使用IE浏览器版本太低，为了更好的浏览本站。建议您升级到 <a href="http://windows.microsoft.com/zh-cn/internet-explorer/download-ie" target="_blank">Internet Explorer 10</a> 或使用以下浏览器：<a href="http://se.360.cn/" target="_blank">360安全浏览器</a>、<a href="http://chrome.360.cn/" target="_blank">360极速浏览器</a> 、<a href="http://liulanqi.baidu.com/" target="_blank">百度浏览器</a>、<a href="http://ie.sogou.com/" target="_blank">搜狗浏览器</a>.</div>
<script type="text/javascript">
    function position_fixed(el, eltop, elleft){
// check if this is IE6
        if(!window.XMLHttpRequest)
            window.onscroll = function(){
                el.style.top = (document.documentElement.scrollTop + eltop)+"px";
                el.style.left = (document.documentElement.scrollLeft + elleft)+"px";
            }
        else el.style.position = "fixed";
    }
    position_fixed(document.getElementById("ie6-warning"),0, 0);
</script>
<![endif]-->

<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->baseUrl.'/'?>';
    var MAIL_URL = '<?php echo $this->createUrl('/member/mail/count') ?>';
</script>
<style type="text/css">
    .qtip{
        margin-top:8px;
        margin-left:14px;
        color:red;
    }
    #infocount{color:#ff0000; margin-left:-25px;}
</style>
<script type="text/javascript">
    $(function () {
        $("#tipmail").qtip({
            position: 'bottomLeft'
        });
        $.get(MAIL_URL, function (data) {
            if (data) {
                var count = Number(data);
                if (count > 0) {
                    $("#infocount").text("("+count+")");
                    var tip = $("#tipmail");
                    tip.attr("title", "您有 " + count + " 条重要信息，请注意查看");
                    tip.trigger("mouseover");
                }
            }
        });
    });
</script>
<div class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">
                <img width="166" height="37" alt="Brand" src="<?php echo Yii::app()->request->baseUrl;?>/css/member/images/logo.png">
            </a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="<?php echo Yii::app()->createUrl("/member");?>">首页</a></li>
                <li><a href="<?php echo Yii::app()->createUrl("/member/product");?>">产品列表</a></li>
                <li>
                    <?php echo CHtml::link('消息', array('mail/index'), array('id' => 'tipmail', 'title' => '没有新消息')) ?>
                </li>
                <li><a href="<?php echo Yii::app()->createUrl("/member/mail/index");?>" id="infocount"></a></li>
                <li><a href="/">服务中心</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo Yii::app()->user->member_username;?> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/site/logout">安全退出</a></li>
                    </ul>
                </li>
            </ul>
            <div class="navbar-text navbar-right"><h3 style="margin: 0;"><span class="label label-danger" style="font-family: '微软雅黑', '宋体', Arial, sans-serif; font-weight: normal;">联系电话：0411-39585580</span></h3></div>
        </div><!-- /.navbar-collapse -->

    </div><!-- /.container-fluid -->
</div>
<!--导航结束-->
<div class="container-fluid">
    <?php echo $content; ?>
</div>
<div class="container-fluid">
    <div class="footer">
        <p>速推手机广告联盟 &copy; 版权所有 <?php //echo date("Y",time());?> 联系电话：0411-39585580</p>
    </div>
</div>
<!--右下角-->
<style>
    #yxj_pop{background:#fff;width:300px; height:282px;font-size:12px;position:fixed;right:1px;bottom:1px;z-index: 9999;}
    #yxj_popHead{line-height:32px;background:#f6f0f3;border-bottom:1px solid #e0e0e0;font-size:12px;padding:0 0 0 10px;}
    #yxj_popHead h2{font-size:14px;color:#666;line-height:32px;height:32px; margin:0; font-weight: bold}
    #yxj_popHead #yxj_popClose{position:absolute;right:10px;top:1px;}
    #yxj_popHead a#yxj_popClose:hover{color:#f00;cursor:pointer;}
</style>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/member/yxj.js");?>
<!--<div id="yxj_pop" style="display:none;">
    <div id="yxj_popHead">
        <a id="yxj_popClose" title="关闭">关闭</a>
        <h2>精彩活动</h2>
    </div>
    <div id="yxj_popContent">
        <a href="http://www.sutuiapp.com/hd/zzysjs/3" target="_blank"><img src="/images/zzysj_yxj.gif"></a>
    </div>
</div>-->
<script>
    var popad=new Pop();
</script>
<!--中奖公告漂浮-->
<style type="text/css">
    .float_notice_conent{width:150px;height:120px;position:absolute;top:20px;font-size:12px;font-family:"微软雅黑";color:#666; z-index: 99999;}
    .float_notice_conent .float_notice_sition{width:150px;height:120px;position:relative; }
    .float_notice_conent .float_notice_sition .float_notice_c_header{width:150px;height:25px;line-height:25px;text-align:center;cursor:move;position:absolute;top:0px;left:0px; border:2px solid #ccc; border-radius:5px; color:#ff0000;font-weight:600;font-size:14px; background: #fff;}
    .float_notice_conent .float_notice_sition .float_notice_c_header .float_notice_txt{width:90px;color:#ff0000;font-weight:600;font-size:14px; height:25px;line-height:25px; border:2px solid #666; /*box-shadow:1px 1px 1px 1px #000;text-shadow:1px 1px 1px #000;*/ overflow:hidden;}
    .float_notice_conent .float_notice_sition .float_notice_c_adver{width:90px;height:90px;background:url("/images/no.gif") no-repeat;cursor:pointer;position:absolute;top:20px;left:30px;}
    .float_notice_conent .float_notice_sition .float_notice_sign{width:0px;height:10px;border:2px solid #ccc;display:block;position:absolute;top:25px;left:76px;}
</style>
<!--<div class="float_notice_conent">
    <div class="float_notice_sition">
        <a href="http://www.sutuiapp.com/notice/72" target="_blank">
            <div class="float_notice_c_header float_notice_txt"><marquee hspace="0" vspace="0" align="absmiddle" style="margin:0;padding:0;" >《周周赢手机 天天高收益》第3期获奖名单公布啦~~</marquee></div>
            <div class="float_notice_c_adver"></div>
            <i class="float_notice_sign"></i>
        </a>
    </div>
</div>-->
<script type="text/javascript">

    $(function(){
        var timer=null;/*定时器*/
        var _left=200;/*默认left距离*/
        var _top=200;/*默认top距离*/
        var top_folg=false;/*控制高度-锁*/
        var left_folg=true;/*控制宽度-锁*/
        var win_width=$(window).width()-$(".float_notice_conent").width()-30;/*获取并计算浏览器初始宽度*/
        var win_height=$(window).height()-$(".float_notice_conent").height();/*获取并计算浏览器初始高度*/
        action();/*执行走动*/
        /*$(".conent").mouseover(function(){
         clearInterval(timer);
         $(this).find(".c_adver").css({"background":"url('images/no.gif')","bakcground-repeat":"no-repeat"});
         $(this).find(".txt").text("点击查看!");

         }).mouseout(function(){
         action();
         $(this).find(".txt").text("《周周赢手机 天天高收益》第1期豌豆荚获奖名单，第一名：abc 第二名def 第三名 ghi</marquee>");
         $(this).find(".c_adver").css({"background":"url('images/back.gif')","bakcground-repeat":"no-repeat"});
         });*/

        $(window).resize(function(){
            conobj=$(".float_notice_conent");
            win_width=$(window).width()-conobj.width();
            win_height=$(window).height()-conobj.height();
        });

        function action(){
            timer=setInterval(function(){
                if(!top_folg){
                    _top++;
                    if(_top>=win_height){top_folg=true;};
                }else{
                    _top--;
                    if(_top<=0){top_folg=false;};
                };
                if(left_folg){
                    _left++;
                    if(_left>=win_width){left_folg=false;};
                }else{
                    _left--;
                    if(_left<=0){left_folg=true;};
                };
                $(".float_notice_conent").animate({
                    left:_left,
                    top:_top
                },3);
            },15);
        };

        $(".float_notice_conent .float_notice_c_adver").dblclick(function(){
            $(this).parents(".float_notice_conent").slideUp(500,function(){
                $(this).remove();
                clearInterval(timer);
            });
        });

        var state;/*拖动锁*/
        $(".float_notice_c_header").mousedown(function(event){
            state=false;
            var x=event.clientX;
            var y=event.clientY;
            var obj=$(this).parents(".float_notice_conent");
            var c_left=obj.offset().left;
            var c_top=obj.offset().top;
            $(document).mousemove(function(e){
                if(!state){
                    var x1=e.clientX;
                    var y1=e.clientY;
                    var action_left=x1-x+c_left;
                    var action_top=y1-y+c_top;
                    if(action_left<=0){action_left=0;};
                    if(action_top<=0){action_top=0;}
                    if(action_left>=win_width){action_left=win_width;};
                    if(action_top>=win_height){action_top=win_height;};
                    obj.css({top:action_top,left:action_left});
                    _left=action_left;
                    _top=action_top;
                };
            }).mouseup(function(){
                state=true;
            });
        });
    });
</script>
<!--中奖漂浮广告结束//-->
</body>
</html>