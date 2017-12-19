// JScript 文件
function TopAd()
{
    var strTopAd="";
	
	//定义小图片内容
    var topSmallBanner="<div style='height: 79px; overflow: hidden;'><a href=\"special/songqian\" target=_blank><img src=\"/style/topad/images/small.gif\" /></a></div>";
	
	//判断在那些页面上显示大图变小图效果，非这些地址只显示小图（或FLASH）
    if (location.hostname == "58xuntui.com" || location.hostname == "www.58xuntui.com")
    {

		//定义大图内容
        strTopAd="<div style='width:100%;background: #fd4e53;'><div id=adimage style=\"width:1000px;margin: 0 auto\">"+
                    "<div id='adBig' style='height: 396px;'><a href=\"/special/songqian\" " +
                    "target=_blank><img "+
                    "src=\"/style/topad/images/big.jpg\" " +
                    "border=0></A></div>"+
                    "<div id=adSmall style=\"display: none\">";
        //strTopAd+=  topFlash;
		strTopAd+=  topSmallBanner;
        strTopAd+=  "</div></div></div>";
    }
    else if (location.hostname == "www.gtwb8.com" || location.hostname == "gtwb8.com")
    {
        var topSmallBanner="<div style='height: 79px; overflow: hidden;'><a href=\"/lvcha/special/detail?pathname=songqian\" target=_blank><img src=\"/style/topad/images/small.gif\" /></a></div>";
        //定义大图内容
        strTopAd="<div style='width:100%;background: #fd4e53;'><div id=adimage style=\"width:1000px;margin: 0 auto\">"+
        "<div id='adBig' style='height: 396px;'><a href=\"/lvcha/special/detail?pathname=songqian\" " +
        "target=_blank><img "+
        "src=\"/style/topad/images/big.jpg\" " +
        "border=0></A></div>"+
        "<div id=adSmall style=\"display: none\">";
        //strTopAd+=  topFlash;
        strTopAd+=  topSmallBanner;
        strTopAd+=  "</div></div></div>";
    }
    else if (location.hostname == "wb.58xuntui.com")
    {
        var topSmallBanner="<div style='height: 79px; overflow: hidden;'><a href=\"/kongt/special/detail?pathname=songqian\" target=_blank><img src=\"/style/topad/images/small.gif\" /></a></div>";
        //定义大图内容
        strTopAd="<div style='width:100%;background: #fd4e53;'><div id=adimage style=\"width:1000px;margin: 0 auto\">"+
        "<div id='adBig' style='height: 396px;'><a href=\"/kongt/special/detail?pathname=songqian\" " +
        "target=_blank><img "+
        "src=\"/style/topad/images/big.jpg\" " +
        "border=0></A></div>"+
        "<div id=adSmall style=\"display: none\">";
        //strTopAd+=  topFlash;
        strTopAd+=  topSmallBanner;
        strTopAd+=  "</div></div></div>";
    }
    else
    {
        //strTopAd+=topFlash;
		strTopAd+=  topSmallBanner;
    }

    //strTopAd+="<div style=\"height:7px; clear:both;overflow:hidden\"></div>";
    return strTopAd;
}
document.write(TopAd());
$(function(){
	//过两秒显示 showImage(); 内容
    setTimeout("showImage();",2000);
    //alert(location);
});
function showImage()
{
    $("#adBig").slideUp(1000,function(){$("#adSmall").slideDown(1000);});
}

