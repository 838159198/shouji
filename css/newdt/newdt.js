/**
 * Created by Peng on 2017/2/22.
 */
$('.Package_li').click(function () {
    $(this).siblings('li').children('.Package_box').removeClass('Package_box_active');
    $(this).children('.Package_box').addClass('Package_box_active');

    var index = $(this).attr('id');
    var idd = '#package'+index;
    if (idd != '#'+$('.Package_sub').attr('id')){
        $('.Package_sub').hide();
        $('.Package_ul li .sanjiao ').hide();
        $('.Package_sub').children().remove();
    }
    $('.Package_sub').attr('id','package'+index);

    if ($(idd).css('display') == 'block'){
        $(idd).hide('fast','easeOutQuint');
        $('.Package_ul li .sanjiao ').hide('fast','easeOutQuint');
        $('.Package_box').removeClass('Package_box_active');
        $('.Package_sub').children().remove();
    }else {
        dataajax(index);
        $('.Package_ul li .Package_box_active .sanjiao').show('fast','easeInQuint');
        $(idd).show('fast','easeInQuint');
    }

})


function dataajax(index) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/newdt/product/getProductData",
        data: {
            "index": index
        },
        success: function (data) {
            var arr = data.val;
            $('.Package_sub').children().remove();
            for (var i=0;i<arr.length;i++){
                var htm = "<div class='Package_sub_box'>" +
                    "<img src='"+arr[i]['pic']+"' alt=''> " +
                    "<p class='title'>"+arr[i]['name']+"</p> " +
                    "<p class='size'>大小："+arr[i]['filesize']+"MB</p>" +
                    "</div>"
                $('.Package_sub').append(htm);
            }
            var cle = "<div class='clear' style='clear: both'></div>";
            $('.Package_sub').append(cle);
        },
        error: function () {
        },
    });
}
