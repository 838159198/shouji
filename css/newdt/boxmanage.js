/**
 * Created by Peng on 2017/2/22.
 */
 $(function () {
    pop_ajaxupdate();

});



function pop_ajaxupdate(){

    $("tbody tr").each(function () {
       var txt = $(this).find("td:nth-child(2)").text();
        if (txt=="MDAZRJ"){
            $(this).find('.button-column').find('.btn').css({'background':'gray','border-color':'gray'});
        }
    })
    
$('td.button-column').click(function () {

    // $.ajaxSetup({ cache: false });
    var txt =$(this).parent().find("td:nth-child(2)").text();
    if (txt=="MDAZRJ"){
       return;
    }
    $('.box_label').attr('id',$(this).parent().find('td:first').text());
    $('.box_label').text($(this).parent().find('td:nth-child(2)').text());
    var boxcode = $('.box_label').text();
    if (boxcode=="MDAZRJ"){
        return;
    }
    var tc=$('#tc').attr('val');
    $.ajax({
        type: "POST",
        dataType: "json",
        cache: false,
        async:false,
        url: "/newdt/datashow/ajaxPackage",
        data: {
            'boxcode':boxcode,
            'cate':tc
        },
        success: function(data){
            var arr = data.val;
            var packid = data.val1;
            $('.package_select').children().remove();
            var op = "<option id='0'>请选择</option>";
            $('.package_select').append(op);
            for (var i=0;i<arr.length;i++){
                if (parseInt(packid)==0){
                    var opt = "<option id='"+arr[i]['id']+"'>"+arr[i]['package_name']+"</option>";
                    $('.package_select').append(opt);
                }else {
                    if (parseInt(packid) == arr[i]['id']){
                        var opt = "<option id='"+arr[i]['id']+"' selected='selected'>"+arr[i]['package_name']+"</option>";
                    }else {
                        var opt = "<option id='"+arr[i]['id']+"'>"+arr[i]['package_name']+"</option>";
                    }
                    $('.package_select').append(opt);
                }

            }
        },
        error : function() {
        }
    });

    $(".pop").fadeIn('fast');
});



$(".popBottom").on('click', 'div', function(event) {
    event.preventDefault();
    // $.ajaxSetup({ cache: false });
    if($(this).hasClass('confirm')){
        var packageid = $('.package_select option:selected').attr('id');
        var tc=$('#tc').attr('val');
        if(parseInt(packageid)==0){
            alert('请选择套餐');
        }else {
            var boxcode = $('.box_label').text();
            $.ajax({
                type: "POST",
                dataType: "json",
                // cache: false,
                async:false,
                url: "/newdt/datashow/getAjax",
                data: {
                    'packageid':packageid,
                    'boxcode':boxcode,
                    'cate':tc
                },
                success: function (data) {
                    if (data.val=='fail'){
                        alert('出现异常,请刷新后重试!');
                    }
                },
                error: function () {
                }
            });

            $(".pop").fadeOut();
            location.reload();
        }
    }else{
        $(".pop").fadeOut();

    }
});
}

