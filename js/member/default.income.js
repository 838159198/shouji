$("#month").change(function () {
    location.replace(SELECT_MONTH_REPLACE_URL + this.value);
});
$(".zmtb2detail").click(function () {
    var title = this.title;
    $.get(ZMTB2_DETAIL_URL + "?date=" + title, function (data) {
        if (data) {
            var modal = $("#modal");
            modal.attr("title", "桌面图标2收入详细信息");
            modal.html(data);
            modal.dialog({modal: true, width: 500, buttons: {
                "关闭": function () {
                    $(this).dialog("close");
                }
            }});
        }
    });
});