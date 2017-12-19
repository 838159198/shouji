
$(function () {

    $('#menu_list li ul').hide();

    $('#menu_list li span').addClass('label label-info');

    $('#menu_list li span').css({"margin":"10px 0","padding":"10px","display":"block"});

    //鼠标移入
    var count = $('#menu_list span').length;

    for(var i = 0; i < count; i++)
    {
         //   移入表单
        $("#menu_list span:eq("+i+")").bind("click", function()//mouseenter
        {
            var showthis = $(this).next("ul");

            showthis.show("fast"); //显示list

            showthis.bind("click", function()//mouseenter
            {
                showthis.show("fast"); //显示list
            });

            showthis.bind("mouseleave", function()//mouseleave
            {
                showthis.hide("fast"); //显示list
            });

        });

        $("#menu_list ul:eq("+i+") ").bind("mouseleave", function()//mouseleave
        {
            $(this).hide("fast"); //显示list
        });
    }
    $("#menu_list").bind("mouseleave", function()//mouseleave
    {
        $(this).next("ul").hide("fast"); //显示list
    });

    $(".well").bind("mouseleave", function()//mouseleave
    {
        $('#menu_list li ul').hide("fast");
    });

});

