<?php
/**
 * Created by PhpStorm.
 * User: 业务收益修改
 * Date: 2017/3/31
 * Time: 16:47
 */
$types=Ad::getAdList();
?>
<style type="text/css">
    .table th, .table td {line-height: 60px;padding:0px}
    .controls input{height: 20px}
    .controls .btn{height: 30px}
    .btn{height: 30px}
    #type{height: 30px}
    #type_log{height: 30px}
    .wrap{
        width: 100%;
        min-height: 200px;
        border: 1px solid #000;
        clear: both;
        font-size: 16px;
    }
    .wrap .wrap-content{
        width: 60%;
        min-height: 160px;
        margin: 20px auto;
    }
    .wrap .wrap-content .wrap-content-line{
        min-height: 50px;
        margin-bottom: 5px;
        line-height: 50px;
    }
    .wrap-content-line label{
        /*width: 15%;*/
        margin-left: 5%;
    }
    .wrap-note{
        width: 460px;
        height: 30px;
    }
    .wrap-content .third{
        text-align: center;
    }
    .wrap-content-line .btn-sum{
        height: 30px;
        width: 90px;
        line-height: 30px;
        font-size: 15px;
        margin-top: 10px;
    }
    .second .wrap-num{
        width: 50px;
        height: 30px;
    }
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
<div class="page-header app_head"><h1 class="text-center text-primary">业务收益补入</h1></div>
<div class="col-md-2" style="height: 400px;">
    <div class="list-group">
        <li class="list-group-item active">收益补入</li>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/mendIncome");?>" class="list-group-item">业务收益补入</a>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/surplus");?>" class="list-group-item">余额补入</a>
    </div>
</div>
<div class="col-md-10">
    <form id="admin-form" action="/dhadmin/pay/mendincome" method="get" class="input-append">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'admin-form',
            'enableAjaxValidation' => false,
            'htmlOptions' =>array('class'=>""),
        ));?>
        <div class="control-group pull-left" style="margin: 20px">
            用户名：<input type="text" name="username" id="username" value="<?php echo $username;?>">
        </div>
        <div class="control-group pull-left" style="margin: 20px">
           请选择业务：
                <select name="type" id="type">
                    <?php foreach($types as $k=>$v): if($type==$k){?>

                        <option value="<?php echo $k;?>" selected><?php echo $v;?></option>
                    <?php }else{?>
                        <option value="<?php echo $k;?>" ><?php echo $v;?></option>
                    <?php } endforeach?>
                </select>
        </div>
        <div class="control-group pull-left" style="margin: 20px">
           日期：
                <!--            --><?php //echo $form->textField($model, 'createtime',array('lang'=>"date")); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                    'language'=>'zh_cn',
                    'name'=>'date',
                    'id'=>'date',
                    'value'=>$date,
                    'options'=>array(
                        'showAnim'=>'fold',
                        'showOn'=>'focus',
//                        'minDate'=>date('Y-m-01'),
                        'maxDate'=>'-1D',//设置最大日期为前一天,当前日期为new Date()
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
            <input class="btn btn-primary" type="submit" name="yt0" value="查询" onclick="return check()" />

        </div>
        <?php $this->endWidget(); ?>
        <div class="control-group pull-left" style="margin: 20px">
            <?php
            if (!empty($type)){
                echo $types[$type];
            }
            ?>
            收益状态：
            <?php
                if ($i==0){
                    echo "未导入";
                }elseif ($i==1){
                    echo "已导入";
                }else{

                }
            ?>
            &nbsp;&nbsp;
            <?php
                if ($a==0){
                    echo "未封账";
                }elseif($a==1){
                    echo "已封账";
                }else{

                }
            ?>
        </div>
    </form>

<?php  if ($i==1 && $a==0):?>
    <div class="wrap">
        <div class="wrap-content">
            <div class="wrap-content-line first">
                <label>用户名:<span class="wrap-username" style="color:#f0070c"><?php echo $username;?></span></label>
                <label>业务名称:<span class="wrap-type" style="color:#f0070c" wraptype = "<?php echo $type;?>"><?php echo $types[$type]; ?></span></label>
                <label>补入日期:<span class="wrap-date" style="color:#f0070c"><?php echo $date;?></span></label><br>
                <label>备注:</label><input type="text" class="wrap-note" name="note" value=""/>
            </div>
            <div class="wrap-content-line second">
                <label>补入业务个数:<input type="text" class="wrap-num" name="num" value=""></label>
                <label>补入金额:<span class="wrap-money" style="color:#f0070c">0</span>元</label>

            </div>
            <div class="wrap-content-line third">
                <button class="btn-sum">确定</button>
            </div>

        </div>

    </div>
<?php endif;?>


<!--<div style="clear: both;width: 100%;height: 100px"></div>-->
<div class="page-header app_head" style="margin-top: 150px;clear: both"><h1 class="text-center text-primary">操作记录</h1></div>
    <div class="control-group pull-left" style="margin: 20px">
        用户名：<input type="text" name="username_log" id="username_log" value="">
    </div>
    <div class="control-group pull-left" style="margin: 20px">
        请选择业务：
        <select name="type_log" id="type_log">
            <option value="moren" selected>请选择</option>
            <?php foreach($types as $k=>$v):?>
                <option value="<?php echo $k;?>" ><?php echo $v;?></option>
            <?php endforeach; ?>
        </select>
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
                'maxDate'=>date('Y-m-d'),//设置最大日期为前一天,当前日期为new Date()
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
        <th>业务名</th>
        <th>操作时间</th>
        <th>修改前收益</th>
        <th>补入金额</th>
        <th>修改个数</th>
        <th>修改后收益</th>
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
<script src="/js/manage/mendincome.js"></script>
<script type="text/javascript">
    var price = <?php echo $price;?>;
    var arr = <?php echo $arr;?>;// 获取所有封账的月份
</script>
