/**
 * Created by Administrator on 2017/12/12.
 */
function check() {
    var username = $('#username').val().replace(/\s/g,'');
    var surmoney = $('#surmoney').val();
    var note = $('#note').val();
    if (username==''){
        alert('用户名不能为空');
        return false;
    }
    if (surmoney==''|| surmoney==0){
        alert('补入余额不能为空,且不能为0');
        return false;
    }
    var r = /^\+?[0-9][0-9]*$/;　　//判断是否为正整数
    if ( !r.test(surmoney)){
        alert("补入余额只能为正整数");
        return false;
    }

    var txt =  "<div style='width: 200px;height: 100%;margin: 0 auto;text-align: left'>" +
        "用户名:"+username+"<br>" +
        "补入金额:"+surmoney+"<br>" +
        "</div>";

    zdconfirm('确认补入信息',txt,function (r) {
        if (r) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/dhadmin/pay/surplusAjax",
                data: {
                    'username': username,
                    'surmoney': surmoney,
                    'note': note
                },
                success: function (data) {
                    var val = data.val
                    if (val == 'u') {
                        alert('用户名不存在');
                        return false;
                    } else if (val == 'f') {
                        alert('该用户已被封号');
                        return false;
                    } else if (val == 'z') {
                        alert("补入余额只能为正整数");
                        return false;
                    } else if (val == 'error') {
                        alert("出现未知错误,请刷新重试");
                        return false;
                    } else if (val == 'success') {
                        alert("补入成功");
                        location.reload();
                    }
                },
                error: function () {
                }
            });
        }
    })
}


var pageSize = "25";//每页行数
var currentPage = "1";//当前页
var totalPage = "1";//总页数
var rowCount = "0";//总条数
var params="";//参数

$(function () {
    queryForPages();
    $(".btn-log").click(function () {
        currentPage = "1";
        queryForPages();
    })
    function queryForPages() {
        // 获取参数
        var username_log = $("#username_log").val();
        var date_log = $("#date_log").val();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/dhadmin/pay/mendLog",
            data: {
                'sign': 2,
                'pageSize':pageSize,
                'currentPage':currentPage,
                'username_log':username_log,
                'date_log':date_log
            },
            success: function (data) {
                var info = data.val;
                totalPage = data.totalpage;
                rowCount = data.counts;
                var stamp = data.stamp;
                var datetime = data.datetime;
                $('.related_results').text(rowCount);
                $('.time_consum').text(stamp/1000);
                $('.search_time').text(datetime);
                $('#currentPage').text(currentPage);
                $('#totalPage').text(totalPage);
                clearDate();
                fillTable(info);
                revoke();
                details(info);
            },
            error: function () {
            }
        });
    }


    //填充数据
    function fillTable(info)
    {
        if(info.length>=1)
        {
            for (var i = 0; i < info.length; i++) {
                if (info[i]['status']==1){
                    var htm = "<tr>" +
                        "<td>" + info[i]['username'] + "</td>" +
                        "<td>" + info[i]['channel'] + "</td>" +
                        "<td>" + format(info[i]['stamptime']) + "</td>" +
                        "<td>" + info[i]['pre_data'] + "</td>" +
                        "<td>" + info[i]['mend_data'] + "</td>" +
                        "<td>" + info[i]['after_data'] + "</td>" +
                        "<td>" + info[i]['manager'] + "</td>" +
                        "<td><button class='revoke' data-id=" + info[i]['id'] + ">撤回</button></td>" +
                        "<td><button class='details' data-id="+info[i]['id']+">详情</button></td>" +
                        "</tr>";
                }else{
                    var htm = "<tr>" +
                        "<td>" + info[i]['username'] + "</td>" +
                        "<td>" + info[i]['channel'] + "</td>" +
                        "<td>" +format(info[i]['stamptime'])+ "</td>" +
                        "<td>" + info[i]['pre_data'] + "</td>" +
                        "<td>" + info[i]['mend_data'] + "</td>" +
                        "<td>" + info[i]['after_data'] + "</td>" +
                        "<td>" + info[i]['manager'] + "</td>" +
                        "<td><span class='revoke0' data-id=" + info[i]['id'] + ">已撤回</span></td>" +
                        "<td><button class='details' data-id="+info[i]['id']+">详情</button></td>" +
                        "</tr>";
                }
                $(".table-log tbody").append(htm);

            }
        }
        else
        {
            $(".table-log tbody").html("");
        }
    }


    // 撤回操作
    function revoke() {
        $(".revoke").click(function () {
            var id = $(this).attr('data-id');
            zdconfirm('二次确认','确定执行撤回操作吗?',function (r) {
                if (r) {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "/dhadmin/pay/revoke",
                        data: {
                            'sign': '2',
                            'id': id
                        },
                        success: function (data) {
                            var val = data.val;
                            if (val == 'success') {
                                queryForPages();
                            }else if (val=='notenough'){
                                alert('余额不足,操作失败');
                            }
                        },
                        error: function () {
                        }
                    });
                }
            })
        })
    }

    // 详情
    function details(info) {
        var arr=[];
        $.each(info,function (index,item) {
            arr[item['id']]=item;
        });
        $(".details").click(function () {
            var id=$(this).attr('data-id');
            console.log(arr[id]['username']);

            var det="<div style='width: 100%;min-height: 200px;padding-bottom: 10px'>" +
                "<div><label style='width: 150px;text-align: left'>用户名:  "+arr[id]['username']+"</label><label style='width: 150px;text-align: left'>操作时间:  "+arr[id]['operatime']+"</label></div>" +
                "<div><label style='width: 150px;text-align: left'>修改前金额:  "+arr[id]['pre_data']+"元</label><label style='width: 150px;text-align: left'>修改后余额:  "+arr[id]['after_data']+"</label></div>" +
                "<div><label style='width: 150px;text-align: left'>补入余额:  "+arr[id]['mend_data']+"元</label><label style='width: 150px;text-align: left'>所属门店:  "+arr[id]['channel']+"</label></div>" +
                "<div><label style='width: 300px;text-align: left;'>操作人:  "+arr[id]['manager']+"</label></div>" +
                "<div><label style='width: 300px;text-align: left;'>操作时间:  "+format(arr[id]['stamptime'])+"</label></div>" +
                "<div><label style='width: 300px;text-align: left;'>是否撤回:  "+pzlrevoke(arr[id]['status'])+"</label></div>" +
                "<div><label style='width: 300px;text-align: left;'>撤回操作时间:  "+format(arr[id]['revoketime'])+"</label></div>" +
                "<div><label style='width: 300px;text-align: left;'>撤回操作人:  "+arr[id]['revokeman']+"</label></div>" +
                "<div><label style='width: 300px;text-align: left;'>备注:</label><textarea  readonly='readonly' rows='3' cols='30'>"+arr[id]['note']+"</textarea></div>" +
                "</div>";
            zdalert('操作记录详情',det);

        })
    }



    //清空数据
    function clearDate()
    {
        $(".table-log tbody").html("");
    }
    //搜索活动
    $("#searchActivity").click(function(){
        queryForPages();
    });
    //首页
    $("#firstPage").click(function(){
        currentPage="1";
        queryForPages();
    });
    //上一页
    $("#previous").click(function(){
        if(currentPage>1)
        {
            currentPage-- ;
        }
        queryForPages();
    });
    //下一页
    $("#next").click(function(){
        if(currentPage<totalPage)
        {
            currentPage++ ;
        }
        queryForPages();
    });
    //尾页
    $("#last").click(function(){
        currentPage = totalPage;
        queryForPages();
    });


    // 跳转到指定页面
    $("#gotopage_confirm").click(function () {
        currentPage =  $(".gotopage").val();
        if (currentPage<=totalPage){
            queryForPages();
        }
    });

});


// 时间戳转化
function add0(m){return m<10?'0'+m:m }
function format(stamp)
{
    if (stamp==0){
        return '';
    }
    //shijianchuo是整数，否则要parseInt转换
    var shijianchuo = parseInt(stamp)*1000;
    var time = new Date(shijianchuo);
    var y = time.getFullYear();
    var m = time.getMonth()+1;
    var d = time.getDate();
    var h = time.getHours();
    var mm = time.getMinutes();
    var s = time.getSeconds();
    return y+'-'+add0(m)+'-'+add0(d)+' '+add0(h)+':'+add0(mm)+':'+add0(s);
}

// 判断是否撤回
function pzlrevoke(status) {
    if (status==0){
        return '是';
    }else {
        return '否';
    }
}