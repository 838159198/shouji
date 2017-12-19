function provisitetask(id) {
    var url = MT_STAFFPROVISITE;
    var modal = $("#DelTaskMsg");
    modal.dialog("open");
    var maxlength = base_parm.MAX_TOTAL_INSERT;
    var id = id;
    $('#reply_task_msg').click(function () {
        var msg = $('#reply_msg').val();
        if (msg.length > maxlength) {
            asyncbox.alert(data_back_msg.DATA_ERROR_TOOLONG, title.TITLE_ERROR);
            return false;
        } else {
            $.post(url, {id: id, msg: msg}, function (data) {
                var obj = (new Function("return " + data))();
                if (obj.msg == data_back.DATA_SUCCESS) {
                    asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                    location.reload();
                } else if (obj.msg == data_back.DATA_ERROR) {
                    asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                    return false;
                }
            })
        }

    })

}
















