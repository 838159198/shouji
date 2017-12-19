<style>
    #color:after{
        content: " ";
    display: inline-block;
    height: 10px;
    width: 10px;
    border-width: 2px 2px 0 0;
    border-color: #C8C8CD;
    border-style: solid;
    /*-webkit-transform: matrix(0.71, 0.71, -0.71, 0.71, 0, 0);*/
    transform: matrix(0.71, 0.71, -0.71, 0.71, 0, 0);
    /*position: relative;*/
    /*top: -2px;*/
    position: absolute;
    top: 50%;
    margin-top: -4px;
    right: 5%;

    }

</style>
<div class="weui-panel" style="margin-top: 0px">
    <div class="weui-panel__hd">历史收益</div>
    <div class="weui-panel__bd" >
        <?php foreach ($data AS $k=>$v):?>
            <a style="padding:0;height:50px;background-color: #F2F2F2;padding-left:5px" class="weui-cell weui-cell_access" href="<?php echo Yii::app()->createUrl("/weixin/web/earndetail",array("date"=>$v['dates'],"scale"=>$scale,'userid'=>$userid))?>">
                <div class="weui-cell__bd">
                    <h4 style="margin-bottom: 0" class="weui-media-box__title"><?php echo $v['dates']?>&nbsp;&nbsp;&nbsp;&nbsp;您的收益：<?php echo $v['amount']?>元</h4>
                </div>
                <div id="color" >
                </div>
            </a>
        <?php endforeach;?>
    </div>
</div>