/**
 * Created by Peng on 2017/2/28.
 */

$(function () {
    $('.button-column a').hover(function () {
        var contenttabel='';
        var datestr = $(this).parent().parent().find('td:first').text();
        var _event = $(this);
        var p = datestr.split('-');
        var dateindex = p[0]+p[1]+p[2];
        var dateT = p[0]+'-'+p[1];
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/newdt/default/ajaxCache",
            data: {
                "date":dateT,
                "index":dateindex
            },
            success: function(data){
                var arr = data.val;
                var tr='';
                for (var i=0;i<arr.length;i++){
                    var tr =tr+ "<tr><td>"+arr[i]['name']+"</td><td>"+arr[i]['count']+"</td><td>"+arr[i]['bill']+"元</td></tr>";
                }
                contenttabel = "<div id='popOverBox'><table class='poptable'  border='1'>" +
                    "<tr>" +
                    "<th style='background: #D9D9D9;color: #000'>产品</th>" +
                    "<th style='background: #D9D9D9;color: #000'>激活量</th>" +
                    "<th style='background: #D9D9D9;color: #000'>收益</th>" +
                    "</tr>" +tr+
                    "</table></div>";
                getpopover(contenttabel,_event);

            },
            error : function() {

            }
        });

    });
})

function getpopover(contenttabel,$obj) {
    $obj.popover({
        trigger: 'manual',
        placement: 'left', //placement of the popover. also can use top, bottom, left or right
        html: 'true',//needed to show html of course
        title : "<div style='text-align:left; color:black;font-size:18px;font-weight: bold'>收益明细</div>", //this is the top title bar of the popover. add some basic css
        content: contenttabel, //this is the content of the html box. add the image here or anything you want really.
        animation: false
    }).on('mousemove', function () {
        var _this = this;

        $(this).popover("show");
        $(this).siblings(".popover").on("mouseleave", function () {
            $(_this).popover('hide');
        });

    }).on("mouseleave", function () {
        var _this = this;
        setTimeout(function () {
            if (!$(".popover:hover").length) {
                $(_this).popover("hide")
            }
        }, 100);
    });

    $obj.find('.popover-title').text('收益明细');

}
