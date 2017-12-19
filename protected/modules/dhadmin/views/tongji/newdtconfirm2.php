<style type="text/css">
    .col-md-10 {width: 100%; }
    .input-append{padding-left: 15px;}
    .grid-view {float: none;}
    .btn-info{margin-top:-2px;}
    .input-small{height:30px;}
    .form_date{width:200px;float:left;}
    select{height: 30px;}
    .container {
         padding-right: 0px;
         padding-left: 0px;
    }
    .row-fluid{margin-top: 20px}
    .btn-danger{float: right}
</style>
<script type="text/javascript" src="/css/bootstrap/libs/js-xlsx/xlsx.core.min.js"></script>
<script type="text/javascript" src="/css/bootstrap/libs/FileSaver/FileSaver.min.js"></script>
<script type="text/javascript" src="/css/bootstrap/libs/jsPDF/jspdf.min.js"></script>
<script type="text/javascript" src="/css/bootstrap/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script type="text/javascript" src="/css/bootstrap/libs/html2canvas/html2canvas.min.js"></script>
<script type="text/javascript" src="/css/bootstrap/tableExport.js"></script>



<link rel="stylesheet" type="text/css" href="/css/bootstrap/css/bootstrap-table.css" />
<script type="text/javascript" src="/css/bootstrap/js/bootstrap-table.js"></script>
<script src="http://cdn.bootcss.com/bootstrap-table/1.9.1/locale/bootstrap-table-zh-CN.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap-table/1.11.1/extensions/export/bootstrap-table-export.js"></script>

<script src="/css/bootstrap/js/tableexport.js"></script>
<div class="page-header app_head">
    <h1 class="text-center text-primary">代理商-业务APP激活判定<small></small></h1>
</div>
<div class="alert alert-info" style="margin-left:16px; margin-right:14px;">
    日数据已判断激活，默认判断为选择日期当天和之前的数据集合--判断日期应为今天的前一天日期
</div>
<?php
echo CHtml::beginForm('newdtconfirm2', 'get', array('class'=>'input-append')),
'<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >
        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
        <input class = "input-small" id="date" name="date" size="10"  data-rule="required" type="text" value=',$date,'>&nbsp;&nbsp;</div>',
'用户名：<input class = "input-small" id="username" name="username" type="text" value=',$username,'>',
CHtml::submitButton('提交',  array('class'=>'btn btn-info')),'',
CHtml::endForm();
?>

<div class="col-md-10">
<div class="container" style="width: 100%">
    <table id="cusTable">
    </table>
</div>
    <div class="row-fluid">
        <div class="app_button">

            <?php

            echo CHtml::button('判定手机', array_merge(
                array('class'=>'btn btn-info'),
                array('id' => 'payAll')
            ));
            echo "&nbsp;&nbsp;&nbsp;&nbsp;".CHtml::button('手机封号', array_merge(
                    array('class'=>'btn btn-danger'),
                    array('id' => 'nopayAll')
                ));
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
var PAY_URL = '<?php echo $this->createUrl('subupdate') ?>';
var NOPAY_URL = '<?php echo $this->createUrl('nosubupdate') ?>';
var PAY_URLL = '<?php echo $this->createUrl('newdtconfirm3',array("date"=>isset($_GET['date'])?$_GET['date']:'',"username"=>isset($_GET['username'])?$_GET['username']:'')) ?>';
var DATE_TIME ='<?php echo strtotime(Yii::app()->getRequest()->getQuery('date'));?>';
$(function(){

    //表格
    var TableInit = function () {
        var oTableInit = new Object();
        　
        oTableInit.Init = function () {
            //先销毁表格
            $('#cusTable').bootstrapTable('destroy');
            //初始化表格,动态从服务器加载数据
            $('#cusTable').bootstrapTable({
                method: "get", //请求方法
                striped: true, //是否显示行间隔色
                sortable: true, //是否启用排序
                sortOrder: "asc",  //排序方式
                url:PAY_URLL,
                dataType: "json",
                pagination: true,    // 显示页码等信息
                showColumns: true,  // 选择显示的列
                clickToSelect: false, //在点击行时，自动选择rediobox 和 checkbox
                pageNumber: 1,         //首页页码
                pageSize: 50,           // 当前分页值
                pageList: [50,100,150,200],  // 分页选页
                detailView: false,  //父子表
                queryParams: queryParams,//传递参数（*）
                sidePagination: 'client',   // //分页方式：client 客户端分页，server 服务端分页
                cache: false, // 不缓存
                striped: true, // 隔行加亮
                showRefresh: true,                  //是否显示刷新按钮
                showToggle:true,  //是否显示详细视图和列表视图的切换按钮
                search: true,        //是否显示表格搜索，此搜索是客户端搜索，不会进服务端，所以，个人感觉意义不大
                //strictSearch: true,
                showExport: true,
                exportDataType: "all",              //basic', 'all', 'selected'.
                onCheck:function (rom) {
                    $(".fixed-table-toolbar .pull-labels").remove();
                    var a = $('#cusTable').bootstrapTable('getSelections');//所选行数
                    var htm= "<div class='pull-right pull-labels' style='line-height: 54px;margin-right: 10px'><span class='pagination-select'>已选中"+a.length+" 行</span></div>";
                    $(".fixed-table-toolbar").append(htm);

                },
                onUncheck:function (rom) {
                    $(".fixed-table-toolbar .pull-labels").remove();
                    var a = $('#cusTable').bootstrapTable('getSelections');//所选行数
                    var htm= "<div class='pull-right pull-labels' style='line-height: 54px;margin-right: 10px'><span class='pagination-select'>已选中"+a.length+" 行</span></div>";
                    $(".fixed-table-toolbar").append(htm);
                },
                onCheckAll: function (rows) {
                    $(".fixed-table-toolbar .pull-labels").remove();
                    var htm= "<div class='pull-right pull-labels' style='line-height: 54px;margin-right: 10px'><span class='pagination-select'>已选中"+rows.length+" 行</span></div>";
                    $(".fixed-table-toolbar").append(htm);
                },
                onUncheckAll: function (rows) {
                    $(".fixed-table-toolbar .pull-labels").remove();
                    var htm= "<div class='pull-right pull-labels' style='line-height: 54px;margin-right: 10px'><span class='pagination-select'>已选中0行</span></div>";
                    $(".fixed-table-toolbar").append(htm);
                },
                columns: [
                    {
                        checkbox: true
                    },
                    {
                        title: '用户名',
                        field: 'username',
                        sortable:true,
                        align: 'center'
                    },
                    {
                        title: 'imeicode码',
                        field: 'imeicode',
                        sortable:true,
                        align: 'center'

                    },
                    {
                        title: '首次安装时间',
                        field: 'createtime',
                        sortable:true,
                        align: 'center'

                    },
                    {
                        title: '安装软件数量',
                        field: 'install',
                        sortable:true,
                        align: 'center'

                    },
                    {
                        title: '卸载数量',
                        field: 'uninstall',
                        sortable:true,
                        align: 'center'

                    },
                    {
                        title: '激活数量',
                        field: 'activation',
                        sortable:true,
                        align: 'center'

                    },
                    {
                        title: '剩余数量',
                        field: 'yu',
                        sortable:true,
                        align: 'center'

                    },
                    {
                        title: '统计次数',
                        field: 'report_count',
                        sortable:true,
                        align: 'center'

                    },

                    {
                        title: '留存百分比',
                        field: 'percent',
                        sortable:true,
                        align: 'center'
//                        formatter: function (value, row, index) {
//                            return   accDiv(value,100);
//                        }
                    },
                    {
                        title: '操作',
                        align: 'center',
                        formatter:function(value,row,index){
                            var rid =row['uid']+'-'+row['imeicode']+'-'+row['install']+'-'+row['activation']+'-'+row['uninstall']+'-'+DATE_TIME;
                            var e;
                            e = '<a href="subupdate?rid='+rid+'" class="btn btn-info " onclick="return view()">判定手机</a> &nbsp;&nbsp;&nbsp;&nbsp;'+
                            '<a href="nosubupdate?rid='+rid+'" class="btn btn-success " onclick="return view()">手机封号</a> '
                            ;
                            return e;

                        }
                    }
                ]
            });
        };
        return oTableInit;
    };
    //1.初始化Table
    var oTable = new TableInit();
    oTable.Init();
    //点击查询
    $(".dosearch").click(function(){
        $('#cusTable').bootstrapTable('refresh');
    });
    //得到查询的参数  模糊查询条件参数放到里面
    function queryParams(params){
        return {
            pageSize: params.limit, //页面大小
            pageNo: (params.offset)/10+1 //页码


        };
    };
    function queryParamschild(params){
        return {
            pageSize: params.limit, //页面大小
            pageNo: (params.offset)/10+1 //页码


        };
    };
    //格式化数据
    /*var html*/

    /*function onLoadSuccess(){
     $('.pagination-detail').append(html)
     }*/
    function responseHandler(sourceData) {
        if (sourceData.code == "0000") {
            var pageData = sourceData.prepareIncvoiceOut.prepareIncvoiceOutArray;




            return {
                "total": sourceData.prepareIncvoiceOut.totalList, //总条数
                "rows": pageData //返回的数据格式
            };
        } else {
            return {
                "total": 0,
                "rows": []
            };
        };
    }
    function responseHandlerchild(sourceData) {
        if (sourceData.code == "0000") {
            var pageData = sourceData.list;




            return {
                "total": sourceData.prepareIncvoiceOut.totalList, //总条数
                "rows": pageData //返回的数据格式
            };
        } else {
            return {
                "total": 0,
                "rows": []
            };
        };
    }
    //除法函数
    function accDiv(arg1, arg2) {
        var t1 = 0, t2 = 0, r1, r2;
        try {
            t1 = arg1.toString().split(".")[1].length;
        }
        catch (e) {
        }
        try {
            t2 = arg2.toString().split(".")[1].length;
        }
        catch (e) {
        }
        with (Math) {
            r1 = Number(arg1.toString().replace(".", ""));
            r2 = Number(arg2.toString().replace(".", ""));
            return (r1 / r2) * pow(10, t2 - t1);
        }
    }

})
function view(){
    {
        if(confirm('确定要执行此操作吗?'))
        {
            return true;
        }
        return false;
    }
}
</script>
<script type="text/javascript">
    $(function () {
        $("#payAll").click(function () {
            if(confirm('确定要执行此操作吗?'))
            {
                var a= $('#cusTable').bootstrapTable('getSelections');
                var ids = [];
                for (var i = 0; i < a.length; i++) {
                    var str=a[i]['uid']+'-'+a[i]['imeicode']+'-'+a[i]['install']+'-'+a[i]['activation']+'-'+a[i]['uninstall']+'-'+DATE_TIME;
                    ids.push(str);
                };

                if (ids.length > 0) {
                    $.post(PAY_URL, {'rid[]': ids}, function (data) {
                        if (data && data === "success") {
                            alert("完成激活");
                            document.location.reload();
                        } else {
                            alert("激活失败");
                            //$("#modal").html("请重试").dialog({autoOpen: true, modal: true, width: 400});
                        }
                    });
                }
            }
            return false;


        });

        $("#nopayAll").click(function () {
            if(confirm('确定要执行此操作吗?'))
            {
                var a= $('#cusTable').bootstrapTable('getSelections');
                var ids = [];
                for (var i = 0; i < a.length; i++) {
                    var str=a[i]['uid']+'-'+a[i]['imeicode']+'-'+a[i]['install']+'-'+a[i]['activation']+'-'+a[i]['uninstall']+'-'+DATE_TIME;
                    ids.push(str);
                };

                if (ids.length > 0) {
                    $.post(NOPAY_URL, {'rid[]': ids}, function (data) {
                        if (data && data === "success") {
                            alert("完成封号");
                            document.location.reload();
                        } else {
                            alert("封号失败");
                        }
                    });
                }
            }
            return false;


        });


    });
</script>
<script type="text/javascript">
    $(function () {
        //日期控件
        $('.form_date').datetimepicker({
            language:'zh-CN', weekStart:1,todayBtn:1,
            autoclose:1,
            todayHighlight:1,
            startView:2,
            minView:2,
            forceParse:0
        });
    });
</script>
