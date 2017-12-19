<div class="weui-panel">
    <div class="weui-panel__hd"><?=$data['dates']?>日收益明细</div>
    <div class="weui-panel__bd">
        <div class="weui-media-box weui-media-box_small-appmsg">
            <div class="weui-cells">
                <!-- 循环开始-->
                <?php foreach($adList as $k=>$v) {
                    if(empty($data[$k]))continue;
                        $prinfo = Product::model()->find('pathname=:pathname',array(':pathname'=>$k));
                ?>
                <a class="weui-cell weui-cell_access" href="javascript:;">
                    <div class="weui-cell__hd"></div>
                    <div class="weui-cell__bd weui-cell_primary">
                        <p><?=$v?></p>
                    </div>
                    <span class="weui-cell__ft"><?=$data[$k]?>元 / <?=$data[$k]/$prinfo["quote"]?>个</span>
                </a>
                <?php }?>
                <!-- 循环结束 -->
            </div>
        </div>
    </div>
</div>
