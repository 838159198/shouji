/**
 * Created by Peng on 2016/12/29.
 */

/*************************************************************************************   _campview  **********************************************************************************************/
function clickCampview(event) {
    var str = event.attr('id');
    var strType = event.attr('type')+'campaign';
    var arr = ['closePsign','managesign','othersign','memebersign'];
    var index = arr.indexOf(str);
    var type;
    var status;
    switch (index) {
        case 0:
            type=strType;
            status = 0;
            break;
        case 1:
            type=strType;
            status = 1;
            break;
        case 2:
            type=strType;
            status = 1;
            break;
        case 3:
            alert('开启此业务，请联系客服');
            break;
    }
    if (index !=3){
        operation(type,status,event);
    }else {
        return;
    }

}
function operation(type,status,event) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/member/product/edit",
        data: {
            "type": type,
            "status":status
        },
        success: function (data) {
            switch (parseInt(data.val)){
                case 0:
                    alert('数据错误');
                    break;
                case 1:
                    alert('没有此广告类型或此类型广告不允许修改');
                    break;
                case 2:
                    alert('此项目已关闭');
                    break;
                case 3:
                    alert('此项目只有客服可以开启');
                    break;
                case 4:
                    alert('该业务已没有可使用的ID，请联系客服');
                    break;
                case 5:
                    alert('修改状态出现错误');
                    break;
                default:
                    if(event.text() == '关闭业务'){
                        event.text('开启业务');
                        if (xx['bool']==true){
                            event.attr('id','managesign');
                        }else {
                            event.attr('id','memebersign');
                        }
                        event.css({'background-color':'#337ab7','border-color':'#204d74'});
                        $(event).hover(function(){
                                $(this).css('background-color','#204d74');
                            },
                            function(){
                                $(this).css('background-color','#337ab7');
                            });
                        event.parent().parent().find('.btn-group1').remove();
                    }else if (event.text() == '开启业务'){
                        event.text('关闭业务');
                        event.attr('id','closePsign');
                        event.css({'background-color':'#d9534f','border-color':'#d43f3a'});
                        $(event).hover(function(){
                                $(this).css('background-color','#d43f3a');
                            },
                            function(){
                                $(this).css('background-color','#d9534f');
                            });
                        location.reload();
                    }
                    break;
            }
        },
        error: function(data){
        },
    })
}