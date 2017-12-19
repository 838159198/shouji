<div class="weui-panel">
    <div class="weui-panel__hd">最近公告</div>
    <div class="weui-panel__bd">
        <?php foreach ($data AS $_data):?>
            <a class="weui-cell weui-cell_access" href="<?php echo Yii::app()->createUrl("/weixin/web/noticeDetail",array("id"=>$_data['id']))?>">
                <div class="weui-media-box weui-media-box_text">
                    <h4 class="weui-media-box__title"><?php echo $_data['title'];?></h4>
                    <p class="weui-media-box__desc"><?php echo Weixin::truncate_utf8_string($_data['content'],80);?></p>
                    <ul class="weui-media-box__info">
                        <li class="weui-media-box__info__meta"><?php echo Weixin::time_tran($_data['lasttime']);?></li>
                    </ul>
                </div>
            </a>
        <?php endforeach;?>
    </div>
    <div class="weui-panel__hd" style="border-top: 1px solid #E5E5E5;">*只显示最新20条公告</div>
</div>