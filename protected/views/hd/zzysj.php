<script language="javascript" type="text/javascript">
    var interval = 1000;
    function ShowCountDown(year,month,day,divname)
    {
        var now = new Date();
        var endDate = new Date(year, month-1, day);
        var leftTime=endDate.getTime()-now.getTime();
        var leftsecond = parseInt(leftTime/1000);
//var day1=parseInt(leftsecond/(24*60*60*6));
        var day1=Math.floor(leftsecond/(60*60*24));
        var hour=Math.floor((leftsecond-day1*24*60*60)/3600);
        var minute=Math.floor((leftsecond-day1*24*60*60-hour*3600)/60);
        var second=Math.floor(leftsecond-day1*24*60*60-hour*3600-minute*60);
        var nMS = Math.floor(leftTime / 100) % 10;
        var cc = document.getElementById(divname);
        //cc.innerHTML = "距离报名截止日期"+year+"年"+month+"月"+day+"日还有："+day1+"天"+hour+"小时"+minute+"分"+second+"秒";
        if(leftTime > 0){
            cc.innerHTML = "距离报名截止日期还有<br><em>"+day1+"</em>天<em>"+hour+"</em>小时<em>"+minute+"</em>分<em>"+second+"</em>秒";
        }else{
            cc.innerHTML = "本期活动报名已结束";
        }

    }
    window.setInterval(function(){ShowCountDown(2016,10,25,'daojishi');}, interval);
</script>
<div class="jdd-top-bg">
    <div style="background: url(/css/hd/zzysj/images/zzysj.png) no-repeat center top; height: 323px;
            width: 100%;">
    </div>
</div>
<div class="jdd-hd-top">
    <div class="st_product">
        <div class="st_product_row">
            <div class="st_product_icon"><img src="/css/hd/zzysj/images/apk/wandoujia.png" border="0"/></div>
            <div class="st_product_text">
                <p>活动产品：豌豆荚</p>
                <p>活动单价：2.5元/台</p>
                <p>活动奖励：+20%的收益</p>
                <p>活动要求：安装在system区，联网首次激活</p>
                <p>报名时间：2016年10月20日 - 2016年10月24日</p>
                <p>活动周期：2016年10月21日 - 2016年10月27日</p>


            </div>
            <div class="st_product_r">
                <div class="st_product_btn"><a href="/member/product/campaignindex" ><input id="btnHB60" type="button" value="报名参加" class="jdd-hd-cd_lib" style="width:150px;font-family: Microsoft YaHei,Arial, Helvetica, sans-serif;" /></a></div>
                <div class="st_daojishi" id="daojishi"></div>
            </div>
        </div>
    </div>


    <!--<div class="jdd-hd-cd-a">
        <ul>
            <li class="jdd-hd-cd_lia"></li>
            <input id="btnHB20" type="submit" value="领取红包" class="jdd-hd-cd_lib" style="background-color: #b3b3b3;color: #fff;font-family: Microsoft YaHei,Arial, Helvetica, sans-serif;" disabled="disabled"/>
        </ul>
    </div>
    <div class="jdd-hd-cd-b">
        <ul>
            <li class="jdd-hd-cd_lia2"></li>
            <input id="btnHB60" type="submit" value="领取红包" class="jdd-hd-cd_lib" style="background-color: #b3b3b3;color: #fff;font-family: Microsoft YaHei,Arial, Helvetica, sans-serif;" disabled="disabled"/>
        </ul>
    </div>-->
</div>

<div class="jdd-hd-min">
    <div class="jdd-hd-min_tit">活动介绍</div>
    <div class="jdd-hd-min-texta">
        <p>为回馈长期以来在速推平台稳定推广的老客户，提高各位大大做包的积极性。</p>
        <p>即日起，联盟每周推出一款“新产品”或“人气产品”，活动期内可享受如下优惠：</p>
        <p>1、活动周期内，推广“活动产品”，联盟将额外奖励20%收益，奖励收益与活动产品收益同时发放到用户账户；</p>
        <p>2、活动周期内，累计激活数据排名前三位的用户即可获得联盟提供的价值不等的品牌手机使用权一周；</p>
    </div>
</div>
<div class="jdd-hd-min">
    <div class="jdd-hd-min_tit">活动奖品</div>
    <div class="jdd-hd-min-texta">
        <strong>第一名可选奖品（一周使用权）：</strong><br />
        <table width="100%" border="1" bordercolor="#a95d12" style="text-align: center;">
            <tr>
                <th width="200">品牌</th>
                <th>型号</th>
                <th width="200">价值</th>
            </tr>
            <tr>
                <td>OPPO</td>
                <td>A59M</td>
                <td>2000 元</td>
            </tr>
        </table>
        <br />
        <strong>第二名可选奖品（一周使用权）：</strong><br />
        <table width="100%" border="1" bordercolor="#a95d12" style="text-align: center;">
            <tr>
                <th width="200">品牌</th>
                <th>型号</th>
                <th width="200">价值</th>
            </tr>
            <tr>
                <td>酷派</td>
                <td>C106</td>
                <td>1500 元</td>
            </tr>
            <tr>
                <td>三星</td>
                <td>G900L S5韩版</td>
                <td>1500 元</td>
            </tr>
        </table>
        <br />
        <strong>第三名可选奖品（一周使用权）：</strong><br />
        <table width="100%" border="1" bordercolor="#a95d12" style="text-align: center;">
            <tr>
                <th width="200">品牌</th>
                <th>型号</th>
                <th width="200">价值</th>
            </tr>
            <tr>
                <td>华为</td>
                <td>5S TAG-AL00全网通</td>
                <td>1000 元</td>
            </tr>
            <tr>
                <td>努比亚</td>
                <td>Z9 mini NX511J</td>
                <td>1000 元</td>
            </tr>
            <tr>
                <td>乐视</td>
                <td>X500</td>
                <td>1000 元</td>
            </tr>
            <tr>
                <td>魅族</td>
                <td>MX 5</td>
                <td>1000 元</td>
            </tr>
            <tr>
                <td>摩托罗拉</td>
                <td>Nexus 6</td>
                <td>1000 元</td>
            </tr>
            <tr>
                <td>中兴</td>
                <td>红牛 X9180</td>
                <td>1000 元</td>
            </tr>
            <tr>
                <td>小米</td>
                <td>MI 3联通版</td>
                <td>1000 元</td>
            </tr>
            <tr>
                <td>小米</td>
                <td>红米note</td>
                <td>1000 元</td>
            </tr>
            <tr>
                <td>联想</td>
                <td>k3 note(K50-T5 )</td>
                <td>1000 元</td>
            </tr>
            <tr>
                <td>联想</td>
                <td>K5 note(K52t38)</td>
                <td>1000 元</td>
            </tr>
        </table>
        <br />
        <font color="#cc0000"> * 温馨提醒：第一名可选第二名或第三名的奖品；第二名可选第三名的奖品。</font>
    </div>
</div>
<div class="jdd-hd-min">
    <div class="jdd-hd-min_tit">
        活动规则</div>
    <div class="jdd-hd-min-texta">
        1、活动产品只在活动推广周期内额外奖励收益20%，活动结束后将不再享受额外奖励收益；<br>
        2、提交报名申请后会由系统综合审核，报名成功的用户会在1个工作日内站内信以及短信通知，请及时查看报名结果；<br>
        3、活动产品统一于2016年10月21日自动开启，报名成功的用户可以直接下载推广，无需联系客服手动开启业务；<br>
        4、在平台注册时间低于30天的用户报名将提示报名不成功；<br>
        5、在平台未成功提现的用户报名将提示报名不成功；<br>
        6、系统审核主要以其他业务的推广质量以及留存情况为参考；<br>
        7、活动激活数据排名指的是活动周期内累计激活数据；<br>
        8、兑换手机使用权用户将冻结手机价值资金一个月，待测试机返回余额可与下个月打款一起打到用户账户；<br>
        9、报名成功的用户将优先参加后期其他活动；<br>
        10、对联盟此次活动有任何建议和意见请联系客服，一经采纳将有精美礼品相送；<br>
        11、本活动最终解释权归速推联盟所有。
    </div>
    <!--<div class=" jdd-hd-gg"></div>-->
</div>

<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"《周周赢手机 天天高收益》","bdDesc":"各位机友请注意：我正在速推APP推广联盟参加《周周赢手机 天天高收益》活动，您也来一起参加吧！","bdMini":"2","bdMiniList":false,"bdPic":"http://www.sutuiapp.com/css/hd/zzysj/images/apk/wandoujia.png","bdStyle":"0","bdSize":"32"},"slide":{"type":"slide","bdImg":"5","bdPos":"right","bdTop":"100"}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>