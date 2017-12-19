function show(id) {
    $.get(MEMBER_INFO_URL + "?mid=" + id, function (data) {
        if (data) {
            var modal = $("#clickst").eq(0);
            modal.attr("title", "用户详细信息");
            modal.html(data);
            modal.dialog({show: "blind",hide: "explode", resizable: true,modal: true, width: 510,height:700, buttons: {
                "关闭": function () {
                    $(this).dialog("close");
                }
            }});
        }
    });
}