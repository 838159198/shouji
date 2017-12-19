function exclude(type, exclude) {
    var modal = $("#modal");
    modal.find("input[name='exclude']").val(exclude);
    modal.find("input[name='type']").val(type);

    if (exclude == 'url') {
        modal.find("h3").html("排除网址列表");
        modal.find("label").html("说明：排除网址多个网址域名换行，保持一行一个域名或网址。<br /> (请填写正确的网址格式) 以下示例可以删除");
    }
    if (exclude == 'ips') {
        modal.find("h3").html("排除IP列表");
        modal.find("label").html("说明：多个IP地址请换行。" +
            "<br /> (请填写正确的网址格式) 以下示例可以删除"
        );
    }
    if (exclude == 'black') {
        modal.find("h3").html("抢首页黑名单");
        modal.find("label").html("说明：如果首页被抢，把在抢的网址输入到黑名单中，客户端会再次抢回来" +
            "<br />一行输入一个网址，支持多行。请填写正确的网址格式" +
            "<br>注：此功能只支持新版锁首页，需要新版锁首页工具请与客服联系"
        );
    }
    $.get(getUrl, {exclude: exclude, type: type}, function (data) {
        if (data.length == 0) {
            var str = '';
            if (exclude == 'ips') {
                str = "127.0.0.1";
            } else {
                str = "http://www.baidu.com/index.php?+tn=-url=";
            }
            $("#excludeval").val(str);
        } else {
            $("#excludeval").val(data);
        }
        $("#modal").modal();
    });
}

function setZmtb2() {
    $.get(SET_ZMTB2_URL, function (data) {
        if (data) {
            $('<div style="overflow:hidden;"></div>').append('<iframe style="width:800px;height:700px;" src="' + data + '"></iframe>').dialog(
                {
                    modal: true, width: 800, height: 700, title: "桌面图标2设置",
                    buttons: {
                        "关闭": function () {
                            $(this).dialog("close");
                        }
                    }
                }
            );
        } else {
            alert("没有正在使用的桌面图标2业务");
        }
    });
}

function setYxj() {
    $("#excludeval").hide();
    var select = $("#yxj");
    select.show();

    var modal = $("#modal");
    modal.find("input[name='exclude']").val("yxj");
    modal.find("input[name='type']").val("ycggyxj");
    modal.find("h3").html("右下角升窗设置");
    modal.find("label").html("说明：设置右下角升窗弹出的频率"
    );
    modal.modal();
    $.get(getUrl, {exclude: "yxj", type: "ycgg"}, function (data) {
        if (data) {
            select.val(data);
        }
    });
}