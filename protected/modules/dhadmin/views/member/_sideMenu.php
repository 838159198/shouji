<div class="list-group">
    <li class="list-group-item active">用户系统</li>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/member/index");?>" class="list-group-item">用户列表</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/member/detail?id=".$data['id']);?>" class="list-group-item">用户资料</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/member/edit?id=".$data['id']);?>" class="list-group-item">修改基本信息</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/member/bank?id=".$data['id']);?>" class="list-group-item">修改银行信息</a>

    <?php if($data['status']==1){?>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/member/lock?id=".$data['id']);?>" class="list-group-item">锁定账号</a>
    <?php }else{?>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/member/unLock?id=".$data['id']);?>" class="list-group-item">解锁账号</a>
    <?php }?>
    <a href="javascript:if(confirm('确实重置密码吗？'))location='<?php echo Yii::app()->createUrl($this->getModule()->id."/member/resetPassword?id=".$data['id']);?>'"  class="list-group-item list-group-item-danger">重置密码</a>
    <!--<a href="<?php /*echo Yii::app()->createUrl($this->getModule()->id."/member/url?id=".$data['id']);*/?>" target="_blank" class="list-group-item">跳转到用户平台</a>-->
</div>