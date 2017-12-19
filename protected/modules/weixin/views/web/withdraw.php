<div class="weui-panel weui-panel_access">
    <div class="weui-panel__hd">可提现金额</div>
    <div class="weui-panel__bd">
        <div class="weui-media-box weui-media-box_text">
            <h4 class="weui-media-box__title" id="surplus" valll="<?=$surplus?>"><?=$surplus?>元</h4>
            <p class="weui-media-box__desc">联盟每月11-15日为提现期，16-20日统一打款</p>
        </div>
    </div>
</div>

<div class="weui-cells__title">提现金额</div>
<div class="weui-cells">
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <input class="weui-input" type="text" name="money" id="money" placeholder="请输入提现金额"/>
        </div>
    </div>
</div>
<div class="weui-btn-area">
    <a class="weui-btn weui-btn_primary" href="javascript:" id="showDialog">确认提现</a>

</div>

<div class="weui-footer">
    <p class="weui-footer__text">&nbsp;</p>
</div>
<div id="dialogs">
    <div class="js_dialog" style="display: none;" id="dialog">
        <div class="weui-mask"></div>
        <div class="weui-dialog">
            <div class="weui-dialog__hd"><strong class="weui-dialog__title" id="dialog_title"></strong></div>
            <div class="weui-dialog__bd" id="dialog_text"></div>
            <div class="weui-dialog__ft">
                <a href="javascript:;" class="weui-dialog__btn weui-dialog__btn_primary" >确定</a>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        var $dialog = $('#dialog');

        $('#dialogs').on('click', '.weui-dialog__btn', function(){
            $(this).parents('.js_dialog').fadeOut(200);
        });

        $('#showDialog').on('click', function(){
            var money =$("#money").val();
            var surplus=$("#surplus").attr('valll');
            if($.trim(money)=="" || money == "" || money ==0){
                $("#dialog_title").text("发生错误");
                $("#dialog_text").text("请填写提现金额");
                $dialog.fadeIn(200);
                return false;
            }else if(isNaN(money) || !checkMoney(money)){
                $("#dialog_title").text("发生错误");
                $("#dialog_text").text("请填写正确的提现金额");
                $dialog.fadeIn(200);
                return false;
            }else if(parseFloat(money) > parseFloat(surplus)){
                $("#dialog_title").text("发生错误");
                $("#dialog_text").text("提现金额不能大于可提现金额");
                $dialog.fadeIn(200);
                return false;
            }else if(money<50){
                $("#dialog_title").text("发生错误");
                $("#dialog_text").text("提现金额输入错误，提现金额必须≥50元");
                $dialog.fadeIn(200);
                return false;
            }else{
                //ajax对账号密码判断
                $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->createUrl("/weixin/web/ajaxWithdraw",array("userid"=>$user['openid']));?>',
                    // data to be added to query string:
                    data: { money: money},
                    // type of data we are expecting in return:
                    dataType: 'json',
                    async:false,
                    timeout: 300,
                    context: $('body'),
                    success: function(data){
                        // Supposing this JSON payload was received:
                        //   {"project": {"id": 42, "html": "<div>..." }}
                        // append the HTML to context object.
                        //this.append(data.project.html)
                        if(data.status==200){
                            //$("#dialog_title").text(data.title);
                            //$("#dialog_text").text(data.msg);
                            //$dialog.fadeIn(200);
                            //增加跳转成功页面
                            window.location.href = '<?php echo Yii::app()->createUrl("/weixin/web/WithdrawOk",array("userid"=>$user['openid']));?>';
                            return true;
                        }else{
                            $("#dialog_title").text(data.title);
                            $("#dialog_text").text(data.msg);
                            $dialog.fadeIn(200);
                            return false;
                        }

                    },
                    error: function(xhr, type){
                        $("#dialog_title").text("请求失败");
                        $("#dialog_text").text("请再试一次或联系客服人员");
                        $dialog.fadeIn(200);
                        return false;
                    }
                })
            }
            //alert(username);
        });

    });
    function checkMoney(money) {
        //var num = '21022332.05';
        var exp = /^\d+(\.\d{1,2})?$/;
        if(exp.test(money)){
            return true;
        }else{
            return false;
        }
    }


</script>
