<style type="text/css">
    body{ background: #f2f2f2;    font-family: "Microsoft YaHei","微软雅黑",SimSun,"宋体",Heiti,"黑体",sans-serif;color: #333;}
    #envon input[type="button"]{margin-left:114px;width:200px;font-size:16px;padding-left:11px;}
    #envon.reg-button{background: #7bbfee;}
    .reg-select { float: left;   width: 292px;line-height: 18px;   padding: 7px 10px;    border: 1px solid #ddd; }

</style>
<input type="button" value="&nbsp;" id="test" onclick="EV_modeAlert('envon')" style="display:none" /><br />

<div id="envon" style=" width:600px;height:70%; background-color:#8fc6df; border:1px solid #016fbb; padding:20px; overflow:auto; display:none;">
    <strong style="font-size:16px;background:#1b95ea;width:600px;height:35px;line-height:35px;float:left;text-align:center;color:#ffffff;letter-spacing:2px;margin-bottom:20px;margin-top:10px;"><span style="color:#3a7aa7">----</span>速推注册声明<span style="color:#3a7aa7">----</span></strong>
    <p style="font-size:14px; line-height:20px;"></br>
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

        11、您保证：您为合法从业者，是通过合法方式推广，遵守相关法律规定，若因违犯法律规定或侵害他人合法权利的，愿意独自承担一切法律责任，并接受本网站的处理决定。
        <br><br>
        <input style="margin-left: 33%" type="button" class="reg-button" value="同意以上内容，点击注册	" onclick="EV_closeAlert()">
    </p>
</div>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#test").click();
    })
</script>


<div class="reg-container">
    <div class="reg-header"><h3>欢迎注册速推APP推广联盟！</h3></div>
    <div class="reg-content">
        <div class="reg-content-l">
            <div class="reg-forminfo">
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'reg-form',
                    //'enableAjaxValidation'=>true,
                    'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                ));
                $datacome=array(9=>"ROM开发者",8=>"线下手机销售",4=>"批发商",5=>"微信/QQ/网站",6=>"广告合作",7=>"其它",);
                ?>
                <dl>
                    <dt><font color="#ff0000">*</font> 用户名:</dt>
                    <dd><?php echo $form->textField($model,'username',array("class"=>"reg-input")); ?></dd>
                    <div class="error"><?php echo $form->error($model,'username'); ?></div>
                </dl>
                <dl>
                    <dt><font color="#ff0000">*</font> 密码:</dt>
                    <dd><?php echo $form->passwordField($model,'password',array("class"=>"reg-input")); ?></dd>
                    <div class="error"><?php echo $form->error($model,'password'); ?></div>
                </dl>
                <dl>
                    <dt><font color="#ff0000">*</font> 确认密码:</dt>
                    <dd><?php echo $form->passwordField($model,'password2',array("class"=>"reg-input")); ?></dd>
                    <div class="error"><?php echo $form->error($model,'password2'); ?></div>
                </dl>
                <dl>
                    <dt><font color="#ff0000">*</font> 手机号码:</dt>
                    <dd><?php echo $form->textField($model,'tel',array("class"=>"reg-input")); ?></dd>
                    <div class="error"><?php echo $form->error($model,'tel'); ?></div>
                </dl>
                <dl>
                    <dt><font color="#ff0000">*</font> QQ号码:</dt>
                    <dd><?php echo $form->textField($model,'qq',array("class"=>"reg-input")); ?></dd>
                    <div class="error"><?php echo $form->error($model,'qq'); ?></div>
                </dl>
                <dl>
                    <dt> 微信号:</dt>
                    <dd><?php echo $form->textField($model,'weixin_name',array("class"=>"reg-input")); ?></dd>
                    <div class="error"><?php echo $form->error($model,'weixin_name'); ?></div>
                </dl>
                <dl>
                    <dt><font color="#ff0000">*</font> 用户类型:</dt>
                    <dd><?php echo $form->dropDownList($model, 'type', $datacome,array("empty"=>"","class"=>"reg-select")) ?></dd>
                    <div class="error"><?php echo $form->error($model,'type'); ?></div>
                </dl>

                <dl>
                    <dt> 邀请码:</dt>
                    <dd><?php echo $form->textField($model,'invitationcode',array("class"=>"reg-input")); ?></dd>
                    <div class="error"><?php echo $form->error($model,'invitationcode'); ?></div>
                </dl>
                <dl>
                    <dt><font color="#ff0000">*</font> 验证码:</dt>
                    <dd><?php if (CCaptcha::checkRequirements()) {
                            echo $form->textField($model, 'verifyCode', array('placeholder' => '验证码', 'class' => 'reg-input-verifyCode'));
                            ?>
                            <?php $this->widget('CCaptcha', array(
                                    'buttonLabel' => '',
                                    'id'=>'captcha',
                                    'clickableImage' => true,
                                    'imageOptions' => array('width' => '150', 'height' => '34'),
                                )
                            );}?></dd>
                    <div class="error"><?php echo $form->error($model,'verifyCode'); ?></div>
                </dl>
                <dl>
                    <dt> </dt>
                    <dd><input type="submit" class="reg-button" value="确认注册"></dd>
                </dl>
                <?php $this->endWidget(); ?>
            </div>
        </div>
        <!-- // 左侧结束 -->
        <div class="reg-content-r">
            <div class="reg-r-img"><img src="/css/site/images/login1.png" border="0"></div>
            <div class="reg-r-txt">
                <p>已有账号，<a href="/login">请登录</a></p>
                <p>如果注册发生问题，请与客服联系。</p>
            </div>
        </div>
        <!-- //右侧结束 -->
    </div>
    <!-- //reg-content__END//-->
</div>

<script language="JavaScript" type="text/javascript">
    var EV_MsgBox_ID="";
    function EV_modeAlert(msgID){
        //创建大大的背景框
        var bgObj=document.createElement("div");
        bgObj.setAttribute('id','EV_bgModeAlertDiv');
        document.body.appendChild(bgObj);
        //背景框满窗口显示
        EV_Show_bgDiv();
        //把要显示的div居中显示
        EV_MsgBox_ID=msgID;
        EV_Show_msgDiv();
    }

    //关闭对话窗口
    function EV_closeAlert(){
        var msgObj=document.getElementById(EV_MsgBox_ID);
        var bgObj=document.getElementById("EV_bgModeAlertDiv");
        msgObj.style.display="none";
        document.body.removeChild(bgObj);
        EV_MsgBox_ID="";
    }

    //窗口大小改变时更正显示大小和位置
    window.onresize=function(){
        if (EV_MsgBox_ID.length>0){
            EV_Show_bgDiv();
            EV_Show_msgDiv();
        }
    }

    //窗口滚动条拖动时更正显示大小和位置
    window.onscroll=function(){
        if (EV_MsgBox_ID.length>0){
            EV_Show_bgDiv();
            EV_Show_msgDiv();
        }
    }

    //把要显示的div居中显示
    function EV_Show_msgDiv(){
        var msgObj   = document.getElementById(EV_MsgBox_ID);
        msgObj.style.display  = "block";
        var msgWidth = msgObj.scrollWidth;
        var msgHeight= msgObj.scrollHeight;
        var bgTop=EV_myScrollTop();
        var bgLeft=EV_myScrollLeft();
        var bgWidth=EV_myClientWidth();
        var bgHeight=EV_myClientHeight();
        var msgTop=bgTop+Math.round((bgHeight-msgHeight)/2);
        var msgLeft=bgLeft+Math.round((bgWidth-msgWidth)/2);
        msgObj.style.position = "fixed";
        msgObj.style.top      = 100+"px";
        msgObj.style.left     = msgLeft+"px";
        msgObj.style.zIndex   = "10001";

    }
    //背景框满窗口显示
    function EV_Show_bgDiv(){
        var bgObj=document.getElementById("EV_bgModeAlertDiv");
        var bgWidth=EV_myClientWidth();
        var bgHeight=EV_myClientHeight();
        var bgTop=EV_myScrollTop();
        var bgLeft=EV_myScrollLeft();
        bgObj.style.position   = "fixed";
        bgObj.style.top        = 0+"px";
        bgObj.style.left       = 0+"px";
        bgObj.style.bottom        = 0+"px";
        bgObj.style.right       = 0+"px";
        // bgObj.style.width      = bgWidth + "px";
        // bgObj.style.height     = bgHeight + "px";
        bgObj.style.zIndex     = "10000";
        bgObj.style.background = "#777";
        bgObj.style.filter     = "progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=60,finishOpacity=60);";
        bgObj.style.opacity    = "0.6";
    }
    //网页被卷去的上高度
    function EV_myScrollTop(){
        var n=window.pageYOffset
            || document.documentElement.scrollTop
            || document.body.scrollTop || 0;
        return n;
    }
    //网页被卷去的左宽度
    function EV_myScrollLeft(){
        var n=window.pageXOffset
            || document.documentElement.scrollLeft
            || document.body.scrollLeft || 0;
        return n;
    }
    //网页可见区域宽
    function EV_myClientWidth(){
        var n=document.documentElement.clientWidth
            || document.body.clientWidth || 0;
        return n;
    }
    //网页可见区域高
    function EV_myClientHeight(){
        var n=document.documentElement.clientHeight
            || document.body.clientHeight || 0;
        return n;
    }
</script>