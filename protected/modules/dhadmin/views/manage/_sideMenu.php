<div class="list-group">
    <li href="#" class="list-group-item active ">管理员系统</li>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/manage/index");?>" class="list-group-item">管理员列表</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/manage/detail?id=".$data['id']);?>" class="list-group-item">资料查看</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/manage/edit?id=".$data['id']);?>" class="list-group-item">修改基本信息</a>
    <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/manage/auth?id=".$data['id']);?>" class="list-group-item">权限设置</a>
    <a href="javascript:if(confirm('确实重置密码吗？'))location='<?php echo Yii::app()->createUrl($this->getModule()->id."/manage/password?id=".$data['id']);?>'"  class="list-group-item list-group-item-danger">重置密码</a>
    <?php if($data['status']==1){?>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/manage/lock?id=".$data['id']);?>" class="list-group-item">锁定账号</a>
    <?php }else{?>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/manage/unLock?id=".$data['id']);?>" class="list-group-item">解锁账号</a>
    <?php }?>
</div>