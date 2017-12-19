/**
 * Created by Peng on 2016/12/27.
 */
/*********************************************************   index   ******************************************************************/
$(function () {
    $('.abtn').click(function () {
        var link = $(this).attr('href');
        if (link.indexOf('/uploads/tongji/')>=0){
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/member/product/download",
                data: {
                    "downLogo":'1'
                },
                success: function(data){
                },
                error : function() {
                }
            })
        }else {
        }
    })
})
/*********************************************************   _view   ******************************************************************/
$(function () {
    // 点击按钮
    $('.nav li').click(function () {
        var index = $(this).children().attr('id');

        $('.table-tr .batch-tr').remove();
        $('#panel-845765 .batch-down').remove();
        dataProcess(index);
    });
});
// 页面刚加载显示全部,传入数据0
window.onload = function(){
    var index =-1;
    var r = "#"+index;
    $(r).attr('aria-expanded','true');
    $('#panel-845765').addClass('active');

    dataProcess(index);
};
// var yy = eval(<?php $arr1['bool']=Yii::app()->user->getState("member_manage");echo json_encode($arr1);?>);
function dataProcess(index) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/member/product/textAjax",
        data: {
            "index": index
        },
        success: function (data) {
            var arr = data.val;
            $('.category-tbody').children().remove();
            if (index == -1 && (arr.length==0 || arr==null)){
                var ndiv ="<div class='wkqyw' style='width:300px;height: 100px;background-color: white;color: red;font-weight: bold;line-height: 100px'>" +
                    "<p style='margin-top: 5px;'>您还没有开启业务,请开启！</p> </div>";
                $(".category-tbody").append(ndiv);
            }

            for (var i=0;i<arr.length;i++){
                //|| (arr[i]['pathname']=='jd') || (arr[i]['pathname']=='dzdp') || (arr[i]['pathname']=='kxxxl') || (arr[i]['pathname']=='tq') || (arr[i]['pathname']=='zssq') || (arr[i]['pathname']=='ayd') || (arr[i]['pathname']=='zhwnl')
                if ((arr[i]['auth']==2)/*|| (arr[i]['pathname']=='shsp')*/){
                    continue;
                }
                var p = "";
                var str = arr[i]['install_instructions'];
                if (str.indexOf('system')>=0 && str.indexOf('data')>=0){
                    p = 'system或data';
                }else if (str.indexOf('system')>=0){
                    p = 'system';
                }else if (str.indexOf('data')>=0){
                    p = 'data';
                }

                // 下架说明
                var under_instructions;
                if (arr[i]['under_instructions']){
                    under_instructions = '('+ arr[i]['under_instructions']+')';
                }else {
                    under_instructions = '';
                }

                var htm = "<tr class='success' id='success"+i+"' pid="+arr[i]['id']+">" +
                    "<td>" +
                    "<img alt='' style='width: 72px;height:72px' src='"+arr[i]['pic']+"'/></td>" +
                    "<td><div style='height: 100%;margin-top: 5px;margin-left: 3%'><div class='pic-sign'><span class='softTitle' id='title"+arr[i]['id']+"' style='margin-left: 0px;color: #0000ff;font-weight: bold;cursor: pointer'>"+arr[i]['name']+"</span><span style='color: red'>"+under_instructions+"</span>" +
                    "</div><span class='list-content' style='font-size: 12px;'>"+arr[i]['content']+"</span></div> " +
                    "</td>" +
                    "<td><span style='color:red;font-weight: bold'>"+arr[i]['price']+"</span><span class='list-content'>元/台</span></td>" +
                    "<td class='list-content'><span><nobr>"+arr[i]['activate_instructions']+"</nobr></span></td>" +
                    "<td class='list-content'>"+p+"</td>";
                $(".category-tbody").append(htm);
                var arrSign = arr[i]['sign'];
                var tt = '#success'+i;
                $(tt).find('.pic-sign').children('img').remove();
                if(arrSign && arrSign.length>0){
                    for (var j=0;j<arrSign.length;j++){
                        if (arrSign[j] == 1){
                            var imgSign = "<img title='推荐' style='margin-left: 5px' src='/images/tj2.png'>";
                        }else if (arrSign[j]==2){
                            var imgSign = "<img title='热门' style='margin-left: 5px' src='/images/hot.gif'>";
                        }else if (arrSign[j]==3){
                            var imgSign = "<img title='新品' style='margin-left: 5px' src='/images/new1.gif'>";
                        }
                        $(tt).find('.pic-sign').append(imgSign);
                    }
                }
                var obj =data.val1;
                var row = arr[i];
                var r = row['auth'];
                var btn='';
                if(index!=-1)
                {
                    $('.special').css('display','block');
                    if (obj[row['id']]['closed']) {
                        btn = "<td><label class='label label-warning'>"+obj[row['id']]['closed']+"</label></td>";
                    }else if (obj[row['id']]['status'] == true){
                        btn = "<td><div class='btn-group'><a class='btn btn-danger' id='closePsign'  type='"+arr[i]['pathname']+"' onclick='clickOperation($(this))'>关闭业务</a></div></td>";
                    }else{
                        switch (parseInt(row['auth'])) {
                            case 1:
                                if(yy['bool']==true)
                                {
                                    btn = "<td class='managetd'><a class='btn btn-primary' id='managesign' type='"+arr[i]['pathname']+"' onclick='clickOperation($(this))'>开启业务</a></td>";
                                }else if (!obj[row['id']]['value']) {
                                    btn = "<td><a class='btn btn-primary' id='contactsign' type='"+arr[i]['pathname']+"' onclick='clickOperation($(this) )'>开启业务</a></td>";
                                }else {
                                    btn = "<td><a class='btn btn-primary' id='othersign' type='"+arr[i]['pathname']+"' onclick='clickOperation($(this))'>开启业务</a></td>";
                                }
                                break;
                            case 0:
                                btn = "<td><a class='btn btn-info' id='memebersign' type='"+arr[i]['pathname']+"' onclick='clickOperation($(this))'>开启业务</a></td>";
                                break;
                            case 2:
                                btn = "<td><label class='label label-important'>业务调整中，暂时关闭</label></td>";
                                break;
                        }
                    }
                }
                else
                {
                    $('.special').css('display','none');
                }

                $(tt).append(btn);
                // 已开启业务中的复选框
                if(index == -1){
                    var h = '#success'+i;
                    var sel = "<td><input class='product-all' name='product-all' type='checkbox' /></td>";
                    $(h).prepend(sel);
                }
            }
            // 我已开启业务
            if(index == -1){
                var open ="<th class='batch-tr' style='width: 60px'><input class='input-all' type='checkbox'/>全选</th>";
                $(".table-tr").prepend(open);
                var batch = "<div class='batch-down'> " +
                    "<span style='line-height: 70px;margin-left: 10px'><input class='input-all' type='checkbox'>&nbsp;全选</span> " +
                    "<span style='margin-left: 5%'>已选择<span class='have_selected'>0</span>个软件,总大小<span class='total_size'>0</span>MB</span> " +
                    "<button style='float: right;width: 100px;height: 40px;margin-top: 15px;margin-right: 5%'>批量下载</button>" +
                    "</div>";
                $('#panel-845765').append(batch);
                select_all();
                downloadButton();
                scrollBatch();
            }
            touchTitle();
            $('.success td').css('background-color','white');
          
        },
        error: function () {
        }
    })
}
// 触摸标题浮动
function touchTitle() {
    $('.softTitle').hover(function () {
       var titleNum = $(this).attr('id');
        var Num = titleNum.replace('title','');
        var idd = "#"+titleNum;
        $(idd).off('hover');
        // alert(idd);
       $.ajax({
           type: "POST",
           dataType: "json",
           url: "/member/product/titlePopover",
           data: {
               "num":Num
           },
           success: function(data){
              var dataP = data.val;
              var dataL = data.valList;
              var createtime = dataL['createtime'].substr(0,10);
               var p ='';
               var str = dataP['install_instructions'];
               if (str.indexOf('system')>=0 && str.indexOf('data')>=0){
                   p = 'system或data';
               }else if (str.indexOf('system')>=0){
                   p = 'system';
               }else if (str.indexOf('data')>=0){
                   p = 'data';
               }
               var ppp ;
               if ($.trim(dataP['content']) !=''){
                   ppp = dataP['content']+"<br>";
               }else {
                  ppp = '';
               }

               $(idd).popover({
                   trigger:'manual',
                   placement : 'right', //placement of the popover. also can use top, bottom, left or right
                   title : "<div style='text-align:left; color:black;font-size:18px;font-weight: bold'>应用详情</div>", //this is the top title bar of the popover. add some basic css
                   html: 'true', //needed to show html of course
                   content : "<div id='popOverBox'><div style='float: left'><img src='"+dataP['pic']+"' width='72' height='72'/></div>" +
                   "<div style='float: left;margin-left: 20px;margin-top: 5px'><div><span style='font-weight: bold'>"+dataP['name']+"</span></div>" +
                   "<div style='margin-top: 20px'><span style='font-weight: bold'>更新时间:</span><span>"+createtime+"</span></div></div>  " +
                   "<div style='clear:both;overflow: hidden;padding-top: 10px'>" +
                   "<span class='content-ppover'>"+ppp+"</span>" +
                   "<span style='font-weight: bold'>单价:</span><span>"+dataP['price']+"</span>元<br>" +
                   "<span style='font-weight: bold'>版本号:</span><span>"+dataL['version']+"</span><br>" +
                   "<span style='font-weight: bold'>软件大小:</span><span>"+dataL['filesize']+"</span><br>" +
                   "<span style='font-weight: bold'>MD5:</span><span>"+dataL['sign']+"</span><br>" +
                   "<span style='font-weight: bold'>计费规则:</span><br><span>"+dataP['activate_instructions']+"</span><br>" +
                   "<span style='font-weight: bold'>内置区域:</span><span>"+p+"</span><br>" +
                   "</div> </div>", //this is the content of the html box. add the image here or anything you want really.
                   animation: false
               }).on('mousemove', function () {
                   var _this = this;

                   $(this).popover("show");
                   $(this).siblings(".popover").on("mouseleave", function () {
                       $(_this).popover('hide');
                   });

               }).on("mouseleave", function () {
                   var _this = this;
                   setTimeout(function () {
                       if (!$(".popover:hover").length) {
                           $(_this).popover("hide")
                       }
                   }, 100);
               });
           },
           error : function() {
           }
       })
    })
}
// 下载栏浮动
function scrollBatch(){
    var hei = $(window).height()-70;
    var width = $('.row-fluid').width();
    $(".batch-down").css('top',hei+'px').css('width',width+'px');
    $(window).resize(function() {
        var hei1 = $(window).height()-70;
        var width = $('.row-fluid').width();
        $(".batch-down").css('top',hei1+'px').css('width',width+'px');
    });
    window.onscroll=function() {
        var winHeight = $(window).height();
            //获取滚动条的滑动距离
            var scroH = $(this).scrollTop();
            var topH = winHeight - 70;
            var topHLast = winHeight- 70;
            if (scroH >= 0) {
                //当滚动条滚动时
                $(".batch-down").css("top", topH + "px");
            }
            //滚动条到底底部
            if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
                $(".batch-down").css("top", topHLast + "px");
            }
        }
}
// 全选
function select_all() {
    $('.input-all').change(function () {
        if ($(this).is(':checked')){
            $('.input-all').each(function () {
                $(this).prop('checked',true);
            });
            $('.product-all').each(function () {
                $(this).prop('checked',true);
            });
            softwareInfo();
        }else {
            $('.input-all').each(function () {
                $(this).prop('checked',false);
            });
            $('.product-all').each(function () {
                $(this).prop('checked',false);
            });
            softwareInfo();
        }
    });
    $('.product-all').change(function () {
        if ($('.batch-tr .input-all').is(':checked')){
            $('.input-all').each(function () {
                $(this).prop('checked',false);
            });
        }
        softwareInfo();
    })
}
// 选中的软件个数,和总大小
function softwareInfo() {
    $('.have_selected').text($('.product-all:checked').length);
    if ($('.product-all:checked').length !=0){
        var array = [];
        $('.product-all:checked').each(function () {
            array.push($(this).parent().parent().attr('pid'));
        });
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/member/product/softwareSize",
            data: {
                "array":array
            },
            success: function(data){
                $('.total_size').text(data.val);
            },
            error : function() {
            }
        })
    }else {
        $('.total_size').text('0');
    }
}
// 下载按钮
function downloadButton() {
    $('.batch-down button').click(function () {
        var aid = $('.active a').attr('id');
        var arr = [];
        if (aid ==-1){
            if ($('.batch-tr .input-all').is(':checked')){
                $('.success').each(function () {
                    arr.push($(this).attr('pid'));
                });
            }else {
                $('.product-all').each(function () {
                    if ($(this).is(':checked')){
                        arr.push($(this).parent().parent().attr('pid'));
                    }
                })
            }
        }
        if (arr.length ==0){
            return;
        }
        $('.pop').css('display','block');
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/member/product/goodDownload",
            data: {
                "arr":arr
            },
            success: function(data){
                if (data.val && data.val!='error'){
                    $('.pop').css('display','none');
                    var chars =data.val.indexOf("/upload");
                    var zipurl = data.val.substring(chars);
                    window.location.href = zipurl;
                }else {
                    alert('下载失败，请刷新后重试');
                    $('.pop').css('display','none');
                }

            },
            error : function() {

            }
        })
    })
}
// 点击按钮之后的一系列操作
function clickOperation (event){
    var aid = $('.active a').attr('id');
    if (aid ==-1){
        // 点击关闭时二次确认
        var a = window.confirm("请确认是否关闭业务");
        if (a==true){
            $(event).parent().parent().parent().remove();
        }else {
            return;
        }
        if ($('.product-all').length == 0){
            var ndiv ="<div class='wkqyw' style='width:300px;height: 100px;background-color: white;color: red;font-weight: bold;line-height: 100px'>" +
                "<p style='margin-top: 5px;'>您还没有开启业务,请开启！</p> </div>";
            $(".category-tbody").append(ndiv);
        }
        softwareInfo();
    }
    var str = event.attr('id');
    var arr = ['closePsign','managesign','contactsign','othersign','memebersign'];
    var index = arr.indexOf(str);
    var type;
    var status;
    switch (index) {
        case 0:
            type=event.attr('type');
            status = 0;
            break;
        case 1:
            type=event.attr('type');
            status = 1;
            break;
        case 2:
            alert('开启此业务，请联系客服');
            break;
        case 3:
            type=event.attr('type');
            status = 1;
            break;
        case 4:
            alert('开启此业务，请联系客服');
            break;
    }
    if (aid == -1) {
        if (index !=2 || index !=4){
            ajaxTool(type,status,event);
        }
    }else {
        if (index != 0 && index != 2 && index!=4) {
            ajaxTool(type, status, event);
        } else if (index == 0) {
            // 点击关闭时二次确认
            var b = window.confirm("请确认是否关闭业务");
            if (b == true) {
                ajaxTool(type, status, event);
            } else {
                return;
            }
        }
    }
}
// ajax传值改变按钮状态
function ajaxTool(type,status,event) {
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
                        if (yy['bool']==true){
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
                    }
                    break;
            }
        },
        error: function(data){
        },
    })
}

