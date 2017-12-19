<?php
/**
 * Created by PhpStorm.
 * User: Peng
 * Date: 2017/1/16
 * Time: 9:46
 */
?>
<link rel="stylesheet" type="text/css" href="/css/appdataretention.css">
<script type="text/javascript" src="/js/manage/appdataretention.js"></script>
<div class="page-header app_head">
    <h1 class="text-center text-primary">数据留存<small></small></h1>
</div>
<div class="retention_wrap">
        <div class='input-group date form_date' data-date='' data-date-format='yyyy-mm-dd' >
       <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
       <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>
       <input class = 'input-small-date' id='date' name='date' size='10'  data-rule='required' type='text' value="<?php echo $date;?>">
        </div>
        <?php $arr_yw = Ad::getAdList();$arr_yw = Array_merge(array('qxzyw'=>'请选择'),$arr_yw);?>
        <?php $arr_fz = Ad::getAdUsergroup();$arr_fz = Array_merge(array('qxzyw'=>'请选择'),$arr_fz);?>
        业务:<?php echo CHtml::dropDownList('type_yw', 'qxzyw', $arr_yw).'&nbsp;&nbsp;&nbsp;&nbsp;'?>
        分组:<?php echo CHtml::dropDownList('type_fz', 'qxzyw', $arr_fz).'&nbsp;&nbsp;&nbsp;&nbsp;'?>
        MD5和版本:<select style="width: 200px" id="type_md5_version">
        <option value="qxzyw"selected="selected">已选择</option>
        </select>&nbsp;&nbsp;&nbsp;&nbsp;
        留存周期:<?php echo CHtml::dropDownList('type_zq', '7', Ad::getAdDataretention()).'&nbsp;&nbsp;&nbsp;&nbsp;'?>
        留存分割:<?php echo CHtml::dropDownList('type_fg', '30', Ad::getAdRetentidonivision()).'&nbsp;&nbsp;&nbsp;&nbsp;'?>
        用户名：<input class = 'input-username' id='username' name='username' type='text' value=''>&nbsp;&nbsp;&nbsp;&nbsp;
        <input class="check-sign" type="checkbox">是否单日&nbsp;&nbsp;&nbsp;&nbsp;
        <input class="all-user" type="checkbox">是否显示所有用户&nbsp;&nbsp;&nbsp;&nbsp;
        <button type="button" class="btn btn-primary btn-deal">处理数据</button>
        <button type="button" class="btn btn-primary btn-retention" style="display: none">提交</button>
</div>

<div class="retention_table">
    <table border="1" class="table table-hover table-striped table-bordered" contenteditable="true">
        <thead>
        <tr>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<div class="zzt" id="container"></div>

<style>
    .pop{
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 99999;
    }
    .pop .popMain{ width: 600px;height: auto; background: #fff; position: absolute; left: 50%; top: 30%; margin-left: -300px; border-radius: 10px;padding-top: 20px }
    .pop .popTop{ position: absolute; top: 0; left: 0; width: 100%; height: 50px; text-align: center;background: gainsboro;border-radius: 10px 10px 0 0;}
    .pop .popTop .x-cancel{width: 32px;height: 32px;background: url("/images/x-cancel.png") no-repeat;position: absolute;right: 3px;top: 3px;}
    .pop .popBottom{ position: absolute; bottom: 0; left: 0; width: 100%; height: 60px; text-align: center;background: gainsboro;border-radius: 0 0 10px 10px;}
    .pop .popBottom div{text-align: center; font-size: 16px; color: #fff;background: #488ACC; cursor: pointer;float: right;margin-left: 20px;margin-top: 10px;border-radius: 5px;}
    .pop .popBottom .select-all{ width: 60px;height: 40px;line-height: 40px;margin-right: 20px}
    .pop .popBottom .confirm{ width: 60px;height: 40px;line-height: 40px;margin-right: 40px}
</style>
<div class="pop">
    <div class="popMain" style="height: auto">
        <div class="popTop" style="margin-bottom: 10px">
            <div style="width: 150px;font-size: 25px;margin: 7.5px auto;">MD5和版本</div>
<!--            <div style="width: 16px;height: 16px;background: red;margin-top: -48px;margin-left: 97%"></div>-->
            <div class="x-cancel"></div>
        </div>
        <div style="margin-top:30px;margin-bottom:70px;width: 100%;padding-left: 50px" id="md5_version"></div>
        <div class="popBottom">

            <div class="confirm">确认</div>
            <div class="select-all">全选</div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
<script src="http://cdn.hcharts.cn/highcharts/modules/data.js"></script>
<script src="http://cdn.hcharts.cn/highcharts/modules/exporting.js"></script>
<script type="text/javascript">
    $(".popBottom").on('click', 'div', function(event) {
        event.preventDefault();
        if($(this).hasClass('confirm')){
            var inputchecked = $('#md5_version input:checked');
            var arr = new Array();
            $(inputchecked).each(function () {
                arr.push($(this).val());
            });

            if(arr.length==0){
                alert('请选择MD5');
            }else {
                 $("#type_md5_version>option").not(":first").remove();
                for (var i = 0;i<arr.length;i++){
                    var opt = "<option value='"+arr[i]+"'>"+arr[i]+"</option>";
                    $('#type_md5_version').append(opt);
                }
                $(".pop").fadeOut();
            }

        }else {
            var txt = $(".select-all").text();
            if (txt=='全选'){
                $("#md5_version input").prop("checked", true);
                $(".select-all").text('取消');
            }else {
                $("#md5_version input").prop("checked", false);
                $(".select-all").text('全选');
            }

        }
    });

    $(".x-cancel").on('click', function() {
        $(".pop").fadeOut();
        $(".select-all").text('全选');
    })
</script>

