$(function () {
    $("#type").change(function () {
        window.location.replace(THIS_URL + '?type=' + this.value);
    });
});

function deleteall() {
    if (!confirm("是否确定要删除这些单价信息？")) return;
    var data = [];
    $("input[name='ids[]']:checkbox:checked").each(function () {
        data.push(this.value);
    });
    if (data.length > 0) {
        $.post(DELETE_ALL_URL, {ids: data}, function (data) {
            if (data && data === "success") {
                window.location.reload();
            } else {
                alert("删除错误，请重试");
            }
        });
    } else {
        alert("请选择要删除的数据");
    }
}