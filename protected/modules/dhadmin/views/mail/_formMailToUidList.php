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

    <div class="control-group">
        <?php echo '用户名*（以英文逗号分隔，非逗号结尾）'; ?>
        <div class="controls">
            <input type="text" name="username" id="username" maxlength="10000000" value="<?php echo $suser; ?>"/>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model, 'title', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'title'); ?>
            <?php echo $form->error($model, 'title',  array('class'=>'label label-important')); ?>
        </div>
    </div>

    <div class="control-group">
        选择在做业务用户
        <div class="controls">
            <select id="template" name="group" onchange="addTemplate(this)">
                <option value="">== 请选择 ==</option>
                <?php
                    foreach(Ad::getAdList() as $key=>$val)
                    {
                        echo "<option value=".$key.">".$val."</option>";
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="control-group">
        所有会员通知
        <div class="controls">
            <select id="template" name="selected" onchange="addTemplate2(this)">
                <option value="">== 请选择 ==</option>
                <option value="1">全体会员</option>
                <option value="2">线下人员</option>
                <option value="4">ROM开发者</option>
                <option value="5">经销商</option>
                <option value="3">VIP测试人员</option>
            </select>
        </div>
    </div>


    <div class="control-group">
        <?php echo $form->labelEx($model, 'content',  array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textArea($model, 'content',  array('style'=>'width:500px; height:300px;')); ?>
            <?php echo $form->error($model, 'content',  array('class'=>'label label-important')); ?>
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            <?php echo CHtml::submitButton('发送', array('class'=>'btn btn-primary btn-large')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
    function addTemplate(oSel) {
        if (oSel.value == "") return;
        var sval = oSel.options[oSel.selectedIndex].value;
        location.replace('/dhadmin/mail/CreateMailToUidList?osel='+sval);
    }
    function addTemplate2(oSel2) {
        if (oSel2.value == "") return;
        var sval = oSel2.options[oSel2.selectedIndex].value;
        location.replace('/dhadmin/mail/CreateMailToUidList?osel2='+sval);
    }

    /**
     * zlb
     * 优化
     * 2017-09-13
     */
    //获取url中参数
    function getQueryString(name) {
        var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
        var r = window.location.search.substr(1).match(reg);
        if (r != null) {
            return unescape(r[2]);
        }
        return null;
    }
    $(function(){
        $('[name=selected]').val(getQueryString('osel2')).attr('selected','selected');
        $('[name=group]').val(getQueryString('osel')).attr('selected','selected');

    })
</script>