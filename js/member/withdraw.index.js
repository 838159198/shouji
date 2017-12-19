/** withdraw/index */
function define() {
    if (!verify()) {
        return;
    }
    var form = $("#formWithdraw");
    var sum = Number($("#price").val());
    var tax = sum * 0.01; //税率
    var fee = (sum - tax) * 0.01;
    if (fee <= 5) {
        fee = 5;
    } else if (fee >= 50) {
        fee = 50;
    }
//    if (confirm('提现' + sum + '元的手续费为' + fee + '元，实际到账为' + (sum - fee) + '元，是否确认？')) {
    if (confirm('提现' + sum + '元，是否确认？')) {
        form.submit();
    }
}

function verify() {
    var date = new Date();
    var day = date.getDate();
    if (day > 16) {
//            alert("请在每月1-10号申请支付");
//            return false;
    }

    var sum = Number($("#price").val());
    var money = Number($("#surplus").val());

    if (sum > money) {
        alert('提现数超过余额');
        return false;
    }
    if (isNaN(sum)) {
        alert('必须为数字');
        return false;
    }
    if (sum == '' || sum < 50) {
        alert('金额不能为空或小于50');
        return false;
    }
    return true;
}