<?php
/* @var $this MailController */
/* @var $model Mail */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'mail-form',
        'enableAjaxValidation' => false,
    )); ?>

    <div class="alert">带（*）的内容是必须填写的</div>

    <?php echo Bs::formErrorSummary($form, $model, Bs::ALERT_ERROR); ?>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'title', Bs::cls(Bs::CONTROL_LABEL)); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'title'); ?>
            <?php echo $form->error($model, 'title', Bs::cls(Bs::LABEL_IMPORTANT)); ?>
        </div>
    </div>

    <div class="control-group">
        信息模板
        <div class="controls">
            <select id="template" onchange="addTemplate(this)">
                <option value="">== 请选择 ==</option>
                <option value="0">搜狗导航封号</option>
                <option value="1">搜狗搜索封号</option>
                <option value="2">金山导航封号</option>
                <option value="3">金山导航无效</option>
                <option value="4">隐藏广告封号</option>
                <option value="5">搜狗导航封号2</option>
                <option value="6">云增值价格恢复至原单价</option>
            </select>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'content', Bs::cls(Bs::CONTROL_LABEL)); ?>
        <div class="controls">
            <?php echo $form->textArea($model, 'content', Bs::textArea()); ?>
            <?php echo $form->error($model, 'content', Bs::cls(Bs::LABEL_IMPORTANT)); ?>
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            <?php echo CHtml::submitButton('发送', Bs::cls(Bs::BTN_PRIMARY, Bs::BTN_LARGE)); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->


<script type="text/javascript">
    var msgs = new Array(
        "尊敬的A+用户你好，系统从上游数据反馈到，您的搜狗导航业务因为涉嫌作弊，被搜狗官方封禁，此项业务将不会再结算，如存在异议，请马上与我们在线客服联系。",
        "尊敬的A+用户你好，系统从上游数据反馈到，您的搜狗搜索业务因为涉嫌作弊，被搜狗官方封禁，此项业务将不会再结算，如存在异议，请马上与我们在线客服联系。",
        "尊敬的A+用户你好，系统从上游数据反馈到，您的金山导航业务因为涉嫌作弊，被金山官方封禁，此项业务将不会再结算，如存在异议，请马上与我们在线客服联系。",
        "尊敬的A+用户，您的金山导航数据在官方的无效数据中，不能被计入收益。请尽快联系客服解决锁主页问题。",
        "尊敬的A+用户你好，系统从上游数据反馈到，您的隐藏广告业务因长时间数据质量严重异常，被官方封禁，此项业务将不会再结算，如存在异议，请马上与我们在线客服联系。",
        "尊敬的A+用户你好，系统从上游数据反馈到，您的搜狗导航业务被官方封禁，此项业务将不会再结算。目前客服给您的处理是向搜狗导航官方递交了申诉请求，在申诉期间不要撤量，因为官方需要看您封号之后的数据质量作为是否解封的重要依据，如果解封数据都会恢复，此id可继续使用。如需客服帮助，请及时联系我们。",
        "亲爱的A+大大，受市场原因影响，云增值项目价格恢复至原单价，即20元/天/1000台机器。感谢您的支持与陪伴！"
    );
    function addTemplate(oSel) {
        if (oSel.value == "") return;
        var title = oSel.options[oSel.selectedIndex].text;
        title = title.replace(/\d/, "");
        $("#MailContent_title").val(title + "通知");
        $("#MailContent_content").val(msgs[oSel.value]);
    }
</script>