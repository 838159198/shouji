<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-8-7
 * Time: 上午10:43
 */

?>

<div class="alert alert-block">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4>客服权限</h4>
    <p>客服权限分为 &nbsp;
        <a onclick="tasks_type(5)">见习客服</a>，
        <a onclick="tasks_type(5)">普通客服</a>，
        <a onclick="tasks_type(5)">高级客服</a>，
        <a onclick="tasks_type(5)">见习客服主管</a>，
        <a onclick="tasks_type(5)">客服主管</a>共5个等级，对应每个等级有不同的工资底薪与提成比例</p>
</div>

<div class="alert alert-error">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4>见习客服</h4>
    无法申请任务，
    <a onclick="tasks_type(5)">只能接收客服主管</a>，
    <a onclick="tasks_type(5)">见习主管</a>所发布的
    <a onclick="tasks_type(5)">回访任务</a>。
</div>

<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4>普通客服</h4>
    <p>可以申请接收所有类型的任务，与高级客服相同，
    不过<a onclick="tasks_type(5)">新用户任务</a>，
    与<a onclick="tasks_type(5)">降量任务</a>
    <a onclick="tasks_type(5)">提成比例</a>较低。</p>
    <p>回访任务没有收益，但是可以选择跟进此回访任务，将此回访任务变更为新用户任务或降量任务。</p>
    <p>周任务不计算收益。</p>
</div>

<div class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4>高级客服!</h4>
    可以申请接收所有类型的任务。
    <a onclick="tasks_type(5)">新用户任务</a>，
    <a onclick="tasks_type(5)">降量任务</a>，
    <a onclick="tasks_type(5)">回访任务</a>，
    <a onclick="tasks_type(5)">周任务</a>。
</div>
<hr>
</br>

<div class="alert alert-block">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4>如何晋级客服等级</h4>
    <p>见习客服工作满1-3个月，经客服主管与经理批准，则升级为<a onclick="tasks_type(5)">普通客服</a></p>
    <p>普通客服从个人用户池中申请的周任务，连续两周，每周超过3个周任务合格，则系统弹出消息框，可选择升级为高级客服。
        升级时间为下个月的第一天开始生效<a onclick="tasks_type(5)"></a></p>
</div>
<hr>
</br>

<div class="alert alert-error">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4>如何计算任务收益--降量任务</h4>

    <p></p>
    <p>(<br>&nbsp;&nbsp;&nbsp;&nbsp;(任务上报当前时间-24小时，至&nbsp;&nbsp;任务上报当前时间-24小时再前移30天任务用户的收益的总和)
        <br>&nbsp;&nbsp;减去&nbsp;&nbsp;
        <br>&nbsp;&nbsp;&nbsp;&nbsp;(任务申请当天，此任务关联的用户所有开启的业务，所获收益的总和，乘以30。)
        <br>)
        <br>&nbsp;&nbsp;乘以&nbsp;&nbsp;
        <br>提成比例
    </p>
    <hr>
    <p>data_1 = 当前时间 - 24&nbsp;小时 </p>
    <p>data_2 = 申请时间 - 24&nbsp;小时-30*24&nbsp;小时 </p>
    <p>data_3 = 任务申请/发布时间 任务用户当天所获得的收益</p>
    <p>pay    = 你的提成比例（普通客服 %5，高级客服 %7）</p>
    <p>data_old = data_3  *  30 </p>
    <p>data_new = data_1  至 data_2 时间段内，任务用户所获得的收益总和</p>
    <p>pay_back = (data_new - data_old)  * pay</p>
    <p style = 'color: #000000'>(备注：如果申请任务与上报任务间隔未满30天，则 data2 = 任务申请/发布时间+24小时)</p>

</div>

<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4>如何计算任务收益--新用户任务</h4>
    <p></p>
    <p>(<br>&nbsp;&nbsp;&nbsp;&nbsp;(任务上报当前时间-24小时，至&nbsp;&nbsp;
        任务上报当前时间-24小时再前移30天任务用户的收益的总和)
        <br>&nbsp;&nbsp;减去&nbsp;&nbsp;
        <br>&nbsp;&nbsp;&nbsp;&nbsp;(任务申请当天，至 任务申请当天前移30天，此任务用户收益的总和。)
        <br>)
        <br>&nbsp;&nbsp;乘以&nbsp;&nbsp;
        <br>提成比例
    </p>
    <hr>
    <p>data_1 = 当前时间 - 24&nbsp;小时 </p>
    <p>data_2 = 当前时间 - 24&nbsp;小时-30*24&nbsp;小时 </p>
    <p>data_3 = 任务申请/发布时间 任务用户当天所获得的收益</p>
    <p>data_4 = 任务申请/发布时间 -30*24 &nbsp;小时</p>
    <p>pay    = 你的提成比例（普通客服 %10，高级客服 %15）</p>
    <p>data_old = data_3  至 data_4 时间段内，任务用户所获得的收益总和 </p>
    <p>data_new = data_1  至 data_2 时间段内，任务用户所获得的收益总和</p>
    <p>pay_back = (data_new - data_old)  * pay</p>
    <p style = 'color: #000000'>(备注：如果申请任务与上报任务间隔未满30天，则 data2 = 任务申请/发布时间+24小时)</p>
</div>

<div class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4>如何计算任务收益--周任务</h4>
    <p>普通客服可以申请周任务，单没有周任务收益或失败惩罚</p>
    <p>每周可申请20个周任务（默认为每周周一）</p>
    <p>因节假日等原因，管理员可以变更周任务的结束的时间</p>
    <p>每周周一下午14:00前，可申请本周的周任务（默认为每周周一，时间会随着周任务实际开始结束时间更改）</p>
    <p>每周周一下午14:00后，可申请下周的周任务（默认为每周周一，时间会随着周任务实际开始结束时间更改）</p>
    <p>每周周一计算上一周周任务的任务收益</p>
    <hr>
    <h4><p>如何判断单条周任务是否合格有效</p></h4>
    <p>(1):周任务单条任务有效收益最小值 大于 5(pay>5)</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;data1 = 周任务开始时间的两天前的用户收益<br>
       &nbsp;&nbsp;&nbsp;&nbsp;data2 = 周任务结束时间的两天前的用户收益<br>
       &nbsp;&nbsp;&nbsp;&nbsp;pay    = data2 - data1
    </p>
    <p>(2):周任务单条任务有效收益增长率最小值 大于 20 ( y > 20)</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;y = (&nbsp;(&nbsp;data2&nbsp;-&nbsp;data1&nbsp;)&nbsp;/&nbsp;data1&nbsp;)*100
    </p>
    <hr>
    <h4><p>周任务有效率计算</p></h4
    <p>周任务合格率 =  ( 单条有效周任务数量总和 / 周任务总数量 )*100; &nbsp;&nbsp;(保留小数点后三位)</p>
    <p>周任务合格率 >= 15(每周20条周任务，>=15%即 每周至少3条周任务合格) </p>
    <hr>
    <h4><p>周任务收益计算</p></h4
    <p>周任务合格率 >= 15(即 至少3条周任务合格)</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;3条任务合格：当月工资加0元<br>
        &nbsp;&nbsp;&nbsp;&nbsp;4条任务合格：当月工资加100元<br>
        &nbsp;&nbsp;&nbsp;&nbsp;5条任务合格：当月工资加200元<br>
        &nbsp;&nbsp;&nbsp;&nbsp;大于等于6条任务合格：当月工资加300元
    </p>
    <p>周任务合格率 < 15(即 小于3条周任务合格)</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;本周周任务全部判定为不合格.<br>
       &nbsp;&nbsp;&nbsp;&nbsp;有且只有2条周任务合格，当月工资扣款100元。<br>
       &nbsp;&nbsp;&nbsp;&nbsp;有且只有1条周任务合格，当月工资扣款200元。<br>
       &nbsp;&nbsp;&nbsp;&nbsp;没有周任务合格，当月工资扣款300元。<br>
    </p>
</div>