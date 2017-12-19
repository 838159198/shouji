$(function () {
    $("#all").click(function () {
        $(":checkbox").attr("checked", this.checked);
    });

    $("#payAll").click(function () {
        var ids = [];
        $(":checkbox[name='rid[]']").each(function () {
            if (this.checked) {
                ids.push(this.value);
            }
        });

        if (ids.length > 0) {
            $.post(PAY_URL, {'rid[]': ids}, function (data) {
                if (data && data === "success") {
                    document.location.reload();
                } else {
                    $("#modal").html("支付出错，请重试").dialog({autoOpen: true, modal: true, width: 400});
                }
            });
        }
    });
});