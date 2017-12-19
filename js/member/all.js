$(function () {
    $("#tipmail").qtip();
    $.get(MAIL_URL, function (data) {
        if (data) {
            var count = Number(data);
            if (count > 0) {
                var tip = $("#tipmail");
                tip.attr("title", "您有 " + count + " 条未读信息<br>请注意查看");
                tip.trigger("mouseover");
            }
        }
    });
});