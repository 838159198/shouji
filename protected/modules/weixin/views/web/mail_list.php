<div class="weui-panel">
    <div class="weui-panel__hd">最新站内信</div>
    <div class="weui-panel__bd">
        <div class="weui-media-box weui-media-box_small-appmsg">
            <div class="weui-cells">
                <?php foreach ($data AS $_data):?>
                <a class="weui-cell weui-cell_access" href="<?php echo Yii::app()->createUrl("/weixin/web/mailDetail",array("id"=>$_data['id'],"userid"=>$userid))?>">
                    <div class="weui-cell__hd"><?php if($_data['status']==1){  ?><i class="weui-icon-info-circle"></i><?php }else{ ?><i class="weui-icon-success"></i><?php }?>
                    </div>
                    <div class="weui-cell__bd weui-cell_primary">
                        <p><?php echo $_data['title'];?></p>
                    </div>
                    <span class="weui-cell__ft"></span>
                </a>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>
