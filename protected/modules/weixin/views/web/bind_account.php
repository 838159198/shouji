<div class="weui-panel weui-panel_access">
    <div class="weui-panel__hd">微信账号信息</div>
    <div class="weui-panel__bd">
        <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg">
            <div class="weui-media-box__hd">
                <img class="weui-media-box__thumb" src="<?php echo $user['headimgurl'];?>/96" alt="">
            </div>
            <div class="weui-media-box__bd">
                <h4 class="weui-media-box__title"><?php echo $user['nickname'];?></h4>
                <p class="weui-media-box__desc">绑定速推账号后，可在微信公众号查询收益情况。</p>
            </div>
        </a>
    </div>
</div>
<div class="weui-cells__title">用户名</div>
<div class="weui-cells">
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <input class="weui-input" type="text" name="username" id="username" placeholder="请输入速推用户名"/>
        </div>
    </div>
</div>
<div class="weui-cells__title">密码</div>
<div class="weui-cells">
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <input class="weui-input" type="password" name="password" id="password" placeholder="请输入密码"/>
        </div>
    </div>
</div>
<div class="weui-btn-area">
    <a class="weui-btn weui-btn_primary" href="javascript:" id="showDialog">提交绑定</a>

</div>
<div class="weui-footer" style="margin-top: 50px;">
    <p class="weui-footer__links">
        <a href="<?php echo Yii::app()->createUrl("/weixin/web/reg",array("userid"=>$userid))?>" class="weui-footer__link" onclick="reg_account()">注册账号</a><a href="#" id="forgot_password" class="weui-footer__link">忘记密码(?)</a>
    </p>
</div>
<div class="weui-footer">
    <p class="weui-footer__text">&nbsp;</p>
</div>
<div id="dialogs">
    <div class="js_dialog" style="display: none;" id="dialog">
        <div class="weui-mask"></div>
        <div class="weui-dialog">
            <div class="weui-dialog__hd"><strong class="weui-dialog__title" id="dialog_title">弹窗标题</strong></div>
            <div class="weui-dialog__bd" id="dialog_text">弹窗内容，告知当前页面信息等</div>
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
            var username =$("#username").val();
            var password =$("#password").val();
            if($.trim(username)=="" || password == ""){
                $("#dialog_title").text("发生错误");
                $("#dialog_text").text("账号或密码不得为空");
                $dialog.fadeIn(200);
                return false;
            }else{
                //ajax对账号密码判断
                $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->createUrl("/weixin/web/ajaxBindAccount",array("userid"=>$user['openid']));?>',
                    // data to be added to query string:
                    data: { username: username,password:password },
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
                            window.location.href = '<?php echo Yii::app()->createUrl("/weixin/web/bindOk",array("userid"=>$user['openid']));?>';
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
        //忘记密码
        $('#forgot_password').on('click', function(){
            $("#dialog_title").text("忘记密码");
            $("#dialog_text").text("如您已经忘记密码，请与我们客服人员联系");
            $dialog.fadeIn(200);
        });
    });

    //注册账号
    function reg_account() {
        $("#dialog_title").text("注册账号");
        $("#dialog_text").text("微信公众号暂未开发，请与我们客服人员联系");
        $dialog.fadeIn(200);
    }
</script>
