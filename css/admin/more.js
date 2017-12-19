/**
 * Created by Administrator on 2017/6/28.
 */
$(function () {

    var wid = parseInt($(window).width());
    if (wid < 1778 && wid>768){
        $(".multi-level").empty();
        var aa =Math.ceil((1778-wid)/90);
        for (i=11;i>=(11-aa);i--){
            $(".navbar-nav>li:eq("+i+")").hide();
            $obj = $(".navbar-nav>li:eq("+i+")").clone(true).show();
            if ($obj.has('ul').length!=0){
                $obj.find(".dropdown-toggle").children().remove('span');
                $obj.attr('data-stopPropagation','true');
                $(".multi-level").append($obj.addClass('dropdown-submenu'));
            }else {
                $(".multi-level").append($obj.clone());
            }
        }
    }else {
        $(".multi-level").empty();
        $(".navbar-nav>li").css('display','block');
        $(".dropdown-more").css('display','none');
    }

    $(window).resize(function() {
        var width = parseInt($(window).width());
        $(".navbar-nav>li").show()
        if (width<1778 && width>768){
            $(".multi-level").empty();
            var a =Math.ceil((1778-width)/90);
            for (i=11;i>=(11-a);i--){
                $(".navbar-nav>li:eq("+i+")").hide();
                $obj = $(".navbar-nav>li:eq("+i+")").clone(true).show();
                if ($obj.has('ul').length!=0){
                    $obj.find(".dropdown-toggle").children().remove('span');
                    $obj.attr('data-stopPropagation','true');
                    $(".multi-level").append($obj.addClass('dropdown-submenu'));
                }else {
                    $(".multi-level").append($obj.clone());
                }
            }
        }else {
            $(".multi-level").empty();
            $(".navbar-nav>li").css('display','block');
            $(".dropdown-more").css('display','none');
        }
    });
})

$(function () {
    $("ul.dropdown-menu").on("click", "[data-stopPropagation]", function(e) {
        e.stopPropagation();
    });

})
