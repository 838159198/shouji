/**
 * Created by Peng on 2017/1/13.
 */
$(function (){

    var rotateTimeOut = function (){
        $('#rotate').rotate({
            angle:0,
            animateTo:2160,
            duration:8000,
            callback:function (){
                alert('网络超时，请检查您的网络设置！');
            }
        });
    };
    var bRotate = false;


    $('.pointer').click(function (){

        if(bRotate)return;

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/yearendDraw/drawAjax",
            data: {
            },
            success: function(data){
                if (data.val =='fail'){
                    alert(data.message);
                }else if(data.val =='1'){
                    alert(data.message);
                }else {
                    var arr = data.val;
                    if (arr[2].indexOf('积分')>0){

                    }else {
                        var que = "<div class='confirm'>填写地址</div>";
                        $('.popBottom').append(que);
                    }
                    rotateFn(arr[0], arr[1],arr[2]);
                    clickBottom(data.link);
                }
            },
            error : function() {
            }
        });

    });


    var rotateFn = function (angles, txt1,txt2){
        bRotate = !bRotate;
        $('#rotate').stopRotate();
        $('#rotate').rotate({
            angle:0,
            animateTo:angles+3600,
            duration:8000,
            callback:function (){
                $('.popMiddle .pop_draw').text(''+txt1+',奖品为:'+txt2+'');
                $(".pop").fadeIn('fast');
                bRotate = !bRotate;
            }
        })
    };

    // 确认和取消按钮
    function clickBottom($link) {
        $(".popBottom").on('click', 'div', function(event) {
            event.preventDefault();
            if($(this).hasClass('confirm')){
                if ($link=='/'){
                    alert('出现错误');
                }else {
                    window.location.href=$link;
                }
                $(".pop").fadeOut();
            }else{
                $(".pop").fadeOut();
                location.reload();
            }
        });
    }


});

// 产生随机数,没有用到
function rnd(n, m){
    return Math.floor(Math.random()*(m-n+1)+n)
}
