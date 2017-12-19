/**
 * Created by Peng on 2017/2/7.
 */

/****************************************************************************/
$(document).ready(function(){

    $(".main_visual").hover(function(){
        $("#btn_prev,#btn_next").fadeIn()
    },function(){
        $("#btn_prev,#btn_next").fadeOut()
    });

    $dragBln = false;

    $(".main_image").touchSlider({
        flexible : true,
        speed : 200,
        btn_prev : $("#btn_prev"),
        btn_next : $("#btn_next"),
        paging : $(".flicking_con a"),
        counter : function (e){
            $(".flicking_con a").removeClass("on").eq(e.current-1).addClass("on");
        }
    });

    $(".main_image").bind("mousedown", function() {
        $dragBln = false;
    });

    $(".main_image").bind("dragstart", function() {
        $dragBln = true;
    });

    $(".main_image a").click(function(){
        if($dragBln) {
            return false;
        }
    });

//            timer = setInterval(function(){
//                $("#btn_next").click();
//            }, 5000);

//            $(".main_visual").hover(function(){
//                clearInterval(timer);
//            },function(){
//                timer = setInterval(function(){
//                    $("#btn_next").click();
//                },5000);
//            });
//
//            $(".main_image").bind("touchstart",function(){
//                clearInterval(timer);
//            }).bind("touchend", function(){
//                timer = setInterval(function(){
//                    $("#btn_next").click();
//                }, 5000);
//            });

});
/****************************************************************************/
$(function(){
    var widd = $(window).width();
    var heii = $(window).height();
    $(".img1").css('width',widd+'px');
    $('#btn_prev').css('top',heii/2+'px');
    $('#btn_next').css('top',heii/2+'px');
    $(".flicking_con").css('top',(heii-60)+'px')
    $(window).resize(function() {
        var wid = $(window).width();
        var hei = $(window).height();
        $(".img1").css('width',wid+'px');
        $('#btn_prev').css('top',hei/2+'px');
        $('#btn_next').css('top',hei/2+'px');
        $(".flicking_con").css('top',(hei-60)+'px')
    });
})
/*禁用浏览器右键,IE浏览器兼容*/
function iEsc(){ return false; }
function iRec(){ return true; }
function DisableKeys() {
    if(event.ctrlKey || event.shiftKey || event.altKey)  {
        window.event.returnValue=false;
        iEsc();}
}

document.ondragstart=iEsc;
document.onkeydown=DisableKeys;
document.oncontextmenu=iEsc;


