/**
 * site/index
 */
/*function editStatus(obj) {
 $("#loginMenu li").attr("class", "");
 obj.parent("li").attr("class", "active");
 }

 function clearText() {
 $("#loginForm input[type='text']").val("");
 $("#loginForm input[type='password']").val("");
 }*/

function login() {
    $("#login-form").submit();
}

$(function () {
    $(".marquee").marquee();
});

/*
 var current = $("#LoginForm_type").val();
 var panels = $("#loginMenu li a");
 $.each(panels, function (i, o) {
 var obj = $(o);
 if (obj.html() === current) {
 editStatus(obj);
 }
 });
 panels.click(function () {
 clearText();
 var me = $(this);
 editStatus(me)
 $("#LoginForm_type").val(me.html());
 });

 */
