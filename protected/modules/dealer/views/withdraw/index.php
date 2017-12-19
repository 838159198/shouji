<?php
/* @var $this WithdrawController */
/* @var $modelMember Member */
/* @var $modelBill MemberBill */
/* @var $commission float */

$this->breadcrumbs = array(
    '财务提现',
);
?>
<style type="text/css">

    .alert-info ul li{ line-height:30px;}
    .alert-danger{}
    .alert-info ul li{list-style:none;list-style-type:none;}
    .alert-info h4{font-weight:bold;}
</style>
<?php /*Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . "/css/member.css");*/?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <style type="text/css">
            .a_title{ margin: 0; padding: 0; font-size: 20px; font-family: "Microsoft YaHei", "微软雅黑", SimSun, "宋体", Heiti, "黑体", sans-serif;
             }
            .panel-default>.panel-heading{background: #e9e9e9;}
            .money{ font-size: 24px; font-family: arial, helvetica, sans-serif; color: #ff6600; margin-right: 5px;}
        </style>
        <div class="col-md-10">
            <div class="row">
                <ol class="breadcrumb pull-left">
                    当前位置：<li><a href="/dealer">管理主页</a></li>
                    <li class="active">财务提现</li>
                </ol>
            </div>
            <div class="row">
                    <?php
                    //判断用户财务信息是否填写
                    if($modelMember->holder==""||$modelMember->bank==""||$modelMember->bank_no==""||$modelMember->bank_site==""){
                        $bank_status = false;
                    }else{
                        $bank_status = true;
                    }
                    ?>
                    <div class="panel panel-success">
                        <div class="panel-heading"><b>财务信息</b><span style="color: #ff6600;">（联盟每月8-10日发布上个月预估收益，其它时间请在收益明细查看）</span></div>
                        <div class="panel-body">
                            本月预估收益：<span class="money"><?php echo $modelBill->cy;?></span>元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您的余额：<span class="money"><?php echo $modelBill->surplus ?></span>元
<!--                            --><?php
/*                            $m=Member::model()->getById($this->uid);
                            $money=0;
                            if($m["type"]==1)
                            {
                                echo '(含上月代理商提成'.$money.'元)';
                            }
                            */?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;已支付金额：<span class="money"><?php echo $modelBill->paid ?></span>元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;未支付金额：<span class="money"><?php echo $modelBill->nopay;?></span>元
                        </div>
                    </div>
                <?php if($bank_status==false){?>
                    <div class="panel panel-danger">
                        <div class="panel-heading"><b>提现</b></div>
                        <div class="panel-body">
                            <div class="alert alert-danger" role="alert">请先填写财务信息，才可以提现哦 ^_^ <a href="/dealer/info/bank" class="lable">现在就去填写财务信息</a> </div>
                        </div>
                    </div>
                <?php }else{?>
                    <div class="panel panel-success">
                        <div class="panel-heading"><b>提现</b></div>
                        <div class="panel-body">
                                 <?php echo CHtml::beginForm(array('save'), 'post', array('id' => 'formWithdraw','class'=>'form-inline')) ?>
                                <input type="hidden" name="surplus" id="surplus" value="<?php echo $modelBill->surplus ?>"/>
                                <div class="input-group">
                                    <span class="input-group-addon">金额</span>
                                    <input type="text" class="form-control" name="price" id="price" placeholder="0.00">
                                  <span class="input-group-btn">
                                    <button class="btn btn-success" type="button" onclick="define()">确认提现!</button>
                                  </span>
                                </div><!-- /input-group -->
                                <div class="alert alert-danger" style="line-height: 30px;margin-top: 20px;">*每次您的提现金额必须 ≥ 50元
                                    <br>*使用支付宝收款的用户请不要隐藏真实姓名，财务支付收益时将无法进行账号与姓名的双重验证，可能会将您的收益支付给他人的情况。凡因隐藏姓名造成财务打款出现问题者，速推平台不承担任何责任。【<a href="http://www.sutuiapp.com/notice/71" target="_blank">点击查看修改方法</a>】
                                </div><?php echo CHtml::endForm() ?>
                        </div>
                    </div>
                <?php }?>

                <!-- //左侧结束 -->

                    <div class="panel panel-success">
                        <div class="panel-heading"><b>提现说明</b></div>
                        <div class="panel-body">
                            联盟每月11-15日为提现期，16-20日统一打款。
                        </div>
                    </div>
                <?php if($bank_status==false){?>
                    <div class="panel panel-danger">
                        <div class="panel-heading"><b>收款人信息</b></div>
                        <div class="panel-body">
                            <div class="alert alert-danger" role="alert">请先填写财务信息，才可以提现哦 ^_^ <a href="/dealer/info/bank" class="lable">现在就去填写财务信息</a> </div>
                        </div>
                    </div>
                <?php }else{?>
                    <div class="panel panel-success">
                        <div class="panel-heading"><b>收款人信息</b></div>
                        <div class="panel-body">
                            <p>收款人： <?php echo $modelMember->holder ?></p>
                            <p>开户银行： <?php echo $modelMember->bank ?></p>
                            <p>开户帐号： <?php echo $modelMember->bank_no ?></p>
                            <p>开户地区： <?php echo $modelMember->bank_site ?></p>
                            <p>联系电话： <?php echo $modelMember->tel ?></p>
                            <p style="display: none;"><?php echo CHtml::link('修改提现人信息', array('info/bank'), array('class'=>'btn btn-danger')) ?></p>
                        </div>
                    </div>
                <?php }?>

            </div>
    </div>
</div>
<script type="text/javascript">
    /** withdraw/index */
    function define() {
        if (!verify()) {
            return;
        }
        var form = $("#formWithdraw");
        var sum = Number($("#price").val());
        var tax = sum * 0.01; //税率
        var fee = (sum - tax) * 0.01;
        if (fee <= 5) {
            fee = 5;
        } else if (fee >= 50) {
            fee = 50;
        }
//    if (confirm('提现' + sum + '元的手续费为' + fee + '元，实际到账为' + (sum - fee) + '元，是否确认？')) {
        if (confirm('提现' + sum + '元，是否确认？')) {
            form.submit();
        }
    }

    function verify() {
        var date = new Date();
        var day = date.getDate();
        if (day < 11) {
            alert("请在每月11-15号申请支付");
            return false;
        }
        if (day > 15) {
            alert("请在每月11-15号申请支付");
            return false;
        }

        var sum = Number($("#price").val());
        var money = Number($("#surplus").val());

        if (sum > money) {
            alert('提现数超过余额');
            return false;
        }
        if (isNaN(sum)) {
            alert('必须为数字');
            return false;
        }
        if (sum == '' || sum < 50) {
            alert('金额不能为空或小于50');
            return false;
        }
        return true;
    }

</script>