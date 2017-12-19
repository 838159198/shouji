$(function () {
    $("#modalscore").dialog({modal: true, autoOpen: false, width: 500, height: 300});
    var s = $("#sliders");
    s.slider({
        value: 0,
        min: 0,
        max: 10,
        step: 1,
        slide: function (event, ui) {
            $("#amount").html("分数：" + ui.value);
            $("#t_score").val(ui.value);
        }
    });
    $("#amount").html("分数：" + s.slider("value"));
});

function score(id) {
    $("#t_id").val(id);
    $("#modalscore").dialog("open");
}

function done(type) {
    if (type === 1) {
        $("#type").val(1);
    }
    if (type === 2) {
        $("#type").val(2);
    }
    $("#doneform").submit();
}