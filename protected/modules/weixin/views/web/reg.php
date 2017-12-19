<style>
    #me:after{
        border-top:0;
    }
</style>
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
<div class="weui-cells__title">确认密码</div>
<div class="weui-cells">
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <input class="weui-input" type="password" name="password2" id="password2" placeholder="请输入确认密码"/>
        </div>
    </div>
</div>
<div class="weui-cells__title">手机号码</div>
<div class="weui-cells">
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <input class="weui-input" type="text" name="mobile" id="mobile" placeholder="请输入手机号码"/>
        </div>
    </div>
</div>
<div class="weui-cells__title">QQ号码</div>
<div class="weui-cells">
    <div class="weui-cell">
        <div class="weui-cell__bd">
            <input class="weui-input" type="text" name="qq" id="qq" placeholder="请输入QQ号码"/>
        </div>
    </div>
</div>
<div class="weui-cells__title">用户类型（单选）</div>
<div class="weui-cells">
    <div class="weui-cell weui-cell_select">
        <div class="weui-cell__bd">
            <select class="weui-select" name="select1" id="type">
                <option selected="" value="0">请选择用户类型</option>
                <option  value="9">ROM开发者</option>
                <option value="3">线下手机销售(手机批发商，维修商暂不支持推广)</option>
                <option value="8">其它</option>
            </select>
        </div>
    </div>
</div>

<div class="weui-btn-area">
    <a class="weui-btn weui-btn_primary" href="javascript:" id="showDialog">注册并绑定</a>
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
<div class="js_dialog" id="iosDialog1" style="opacity: 1;">
            <div class="weui-mask"></div>
            <div class="weui-dialog" style="max-width:80%;position:fixed;text-align: left;top:50%;height:500px; overflow:auto">
                <div class="weui-dialog__hd" style="text-align: center;"><strong class="weui-dialog__title">速推公众号注册声明</strong></div>
                <div class="weui-dialog__bd" style="height:auto">

                    欢迎您关注速推APP推广联盟公众号，您在本公众号注册视为您在www.sutuiapp.com注册，以下为www.sutuiapp.com注册声明，请仔细阅读：<br>
                    1、本网站仅和ROM开发者、手机经销商、手机维修个体或公司合作，如果您为上述合法从业者，欢迎您来注册。<br>
                    2、本网站是专业服务于rom开发者刷机包推广app、手机门店的平台。<br>
                    3、本网站业务仅支持通过合法方式推广的用户，如果您通过自己安装点击，强制安装点击，程序安装点击等其他非法违规推广方式推广app，所造成的一切法律后果自负，与本网站无关。<br>
                    4、所有可能对用户体验造成损害、对速推app推广联盟合作秩序造成扰乱、对平台产品或相关品牌造成不良影响、对平台构成不正当竞争或侵犯平台合法权益的行为，本网站会将记录您的IP以及操作记录交予相关机关协助调查。<br>
                    5、在此声明，本网站程序仅支持后台自主下载，如果您从其他渠道获得或以速推旗帜的客服名义发送，所造成的一切法律后果自负，与本网站无关。<br>
                    6、您在注册帐号过程中，需要填写一些必要的信息，请保持这些信息的真实、准确、合法、有效并注意及时更新，以便本网站向您提供及时有效的帮助，或更好地为您提供服务。根据相关法律法规和政策，请您填写真实的身份信息。若您填写的信息不完整或不准确，则可能无法使用本服务或在使用过程中受到限制。<br>
                    7、您理解并同意，因业务发展需要，本网站保留单方面对本服务的全部或部分服务内容在任何时候不经任何通知的情况下变更、暂停、限制、终止或撤销的权利，您需承担此风险。<br>
                    8、您理解并同意，如果本网站发现或收到他人投诉用户存在违法或侵害他人合法权利的行为，本网站有权限制或禁止使用帐号部分或全部功能，并协助相关部门进行处理。<br>
                    9、您理解并同意，本网站有权依合理判断对违反有关法律法规的行为进行处罚，对违法违规的任何人士采取适当的法律行动，并依据法律法规保存有关信息向有关部门报告等，您应独自承担由此而产生的一切法律责任。<br>
                    10、您理解并同意，因您违反本声明或相关服务条款的规定，导致或产生第三方主张的任何索赔、要求或损失，您应当独立承担责任；本网站因此遭受损失的，您也应当一并赔偿。<br>
                    11、您保证：您为合法从业者，是通过合法方式推广，遵守相关法律规定，若因违犯法律规定或侵害他人合法权利的，愿意独自承担一切法律责任，并接受本网站的处理决定。<br>


                </div>
                <div class="weui-dialog__ft" id="me" style="text-align: center;margin-bottom: 10px">
                    <a href="javascript:;" class="weui-btn weui-btn_primary" onclick="content()">同意以上内容，点击注册</a>
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
            var password2 = $("#password2").val();
            var mobile = $("#mobile").val();
            var qq = $("#qq").val();
            var type = $("#type").val();
            //var type = $("input[name=type]:checked").val();
            //var type = $(".weui-cells_radio").find("input[type=radio]");

            if($.trim(username)=="" || username.length<5 || username.length > 15 || !checkUsername(username)){
                $("#dialog_title").text("请填写用户名");
                $("#dialog_text").text("用户名长度大于5位小于15位的英文或数字");
                $dialog.fadeIn(200);
                return false;
            }else if(password == "" || password.length < 6 || password.length > 20){
                $("#dialog_title").text("发生错误");
                $("#dialog_text").text("请填写密码，长度大于6位小于20位");
                $dialog.fadeIn(200);
                return false;
            }else if(password != password2){
                $("#dialog_title").text("发生错误");
                $("#dialog_text").text("密码和确认密码不一致");
                $dialog.fadeIn(200);
                return false;
            }else if(mobile.length != 11 || !checkMobile(mobile)){
                $("#dialog_title").text("发生错误");
                $("#dialog_text").text("请填写正确的手机号码");
                $dialog.fadeIn(200);
                return false;
            }else if(isNaN(qq) || qq.length > 11 || qq.length < 5){
                $("#dialog_title").text("发生错误");
                $("#dialog_text").text("请填写正确的QQ号码");
                $dialog.fadeIn(200);
                return false;
            }else if(type== undefined || isNaN(type) || type==0){
                console.log(type);
                $("#dialog_title").text("发生错误");
                $("#dialog_text").text("请选择用户类型");
                $dialog.fadeIn(200);
                return false;
            }else{
                //ajax对账号密码判断
                $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->createUrl("/weixin/web/ajaxReg",array("userid"=>$user['openid']));?>',
                    // data to be added to query string:
                    data: { username: username,
                        password:password,
                        mobile:mobile,
                        qq:qq,
                        type:type
                    },
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
                            window.location.href = '<?php echo Yii::app()->createUrl("/weixin/web/regOk",array("userid"=>$user['openid']));?>';
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
    function checkMobile(tel) {
        var reg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
        if (reg.test(tel)) {
            return true;
        }else{
            return false;
        };
    }
    function checkUsername(username){
        var reg = /^[a-z0-9A-Z]+$/;
        if (reg.test(username)) {
            return true;
        }else{
            return false;
        };
    }
    //注册账号
    function reg_account() {
        $("#dialog_title").text("注册账号");
        $("#dialog_text").text("微信公众号暂未开发，请与我们客服人员联系");
        $dialog.fadeIn(200);
    }
    //速推公众号注册声明
    function content(){
        $('#iosDialog1').hide();
    }
</script>
