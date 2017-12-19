<?php
/**
 * 余额补入
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/5
 * Time: 11:00
 */
?>
<style type="text/css">
    .table th, .table td {line-height: 60px;padding:0px}
    .controls input{height: 20px}
    .controls .btn{height: 30px}
    .btn{height: 30px}
    .table-log thead tr th{
        text-align: center;
    }
    .table-log tbody tr td{
        text-align: center;
    }
    .table-log tbody tr td span{
        cursor: pointer;
    }
    .pag span{
        cursor: pointer;
    }
</style>
<div class="page-header app_head"><h1 class="text-center text-primary">余额补入</h1></div>
<div class="col-md-2" style="height: 400px;">
    <div class="list-group">
        <li class="list-group-item active">收益补入</li>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/mendIncome");?>" class="list-group-item">业务收益补入</a>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/surplus");?>" class="list-group-item">余额补入</a>
    </div>
</div>
<div class="col-md-10">
    <div class="control-group pull-left" style="margin: 20px">
        用户名：<input type="text" name="username" id="username" value="">
    </div>

    <div class="control-group pull-left" style="margin: 20px">
        添加余额：<input type="text" name="surmoney" id="surmoney" value="">
    </div>
    <div class="control-group pull-left" style="margin: 20px">
        备注：<input type="text" name="note" id="note" value="">
    </div>
    <div class="control-group pull-left" style="margin: 20px">
        <input class="btn btn-primary" type="submit" name="yt0" value="确认" onclick="return check()" />

    </div>

<div class="page-header app_head" style="margin-top: 150px;clear: both"><h1 class="text-center text-primary">操作记录</h1></div>
<div class="control-group pull-left" style="margin: 20px">
    用户名：<input type="text" name="username_log" id="username_log" value="">
</div>
<div class="control-group pull-left" style="margin: 20px">
    操作日期：
    <?php
    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
        'language'=>'zh_cn',
        'name'=>'date_log',
        'id'=>'date_log',
        'value'=>'',
        'options'=>array(
            'showAnim'=>'fold',
            'showOn'=>'focus',
//                        'minDate'=>date('Y-m-01'),
            'maxDate'=>date('Y-m-d'),//设置最大日期为前一天'-1D',当前日期为new Date()
            'dateFormat'=>'yy-mm-dd',
        ),
        'htmlOptions'=>array(
            'style'=>'height:30px',
            'maxlength'=>8,
        ),
    ));
    ?>
</div>

<div class="control-group pull-left" style="margin: 20px">
    <input class="btn btn-primary btn-log" type="submit" value="查询"/>
</div>
<div class="control-group pull-left" style="margin: 20px">
    相关结果<span class="related_results"></span>个&nbsp;&nbsp;
    耗时<span class="time_consum"></span>秒&nbsp;&nbsp;
    搜索时间:&nbsp;&nbsp;<span class="search_time"></span>
</div>
<div style="clear: both">
    <table  border="1" class="table table-hover table-striped table-bordered table-log">
        <thead>
        <tr style="background-color: lightgrey;text-align: center">
            <th>用户名</th>
            <th>渠道</th>
            <th>操作时间</th>
            <th>修改前余额</th>
            <th>补入余额</th>
            <th>修改后余额</th>
            <th>操作人</th>
            <th>操作</th>
            <th>记录</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <div class="pag" style="float: right">
        <button id="firstPage">首页</button>
        <button id="previous">上一页</button>
        <button id="currentPage">1</button>
        <button id="next">下一页</button>
        <span>到第<input type="number" class="gotopage" style="width: 40px" value=""/>页</span>
        <button id="gotopage_confirm">确认</button>
        <button id="last">尾页</button>
        共<span id="totalPage"></span>页
    </div>
</div>
</div>


<script type="text/javascript" src="/js/myalert.js"></script>
<script type="text/javascript" src="/js/manage/surplus.js"></script>
