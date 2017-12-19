<script type="text/javascript">
    function blur_bank(){
        //var bank = $("#Member_bank").find("option:selected").text();//开户行
        var bank = $("#Member_bank").val();//获取value
        var bank_num = $("#Member_bank_no").val();//卡号
        var bank_holder = $("#Member_holder").val();//开户人
        if(bank==undefined && bank_num==undefined){
            return true;
        }else{
            if(bank!='' || bank_num!=''){
                //判断开户行是否为空
                if(bank == ''){
                    alert("请选择开户行");
                    return false;
                }
                //判断银行卡号是否为空
                if(bank_num == ''){
                    alert('请填写【'+bank+'】银行卡号');
                    return false;
                }
                //判断开户人
                if(bank_holder == ''){
                    alert('请填写开户人');
                    return false;
                }


                //判断用户选择的是银行还是支付宝
                if(bank=="工商银行" || bank == "建设银行" || bank == "农业银行" || bank == "招商银行" || bank == "中国银行" || bank == "交通银行" || bank == "邮政储蓄" || bank == "广发银行" || bank == "光大银行" || bank == "平安银行" || bank == "华夏银行" || bank == "兴业银行" || bank == "浦发银行" || bank == "渤海银行"){
                    //判断银行卡长度16位或者19位
                    if(!(bank_num.length ==16 || bank_num.length == 19)){
                        alert('请填写正确的银行卡号，16位或19位！');
                        return false;
                    }else{
                        //判断是否是数字
                        if (isNaN(bank_num)) {
                            alert("银行卡号必须全为数字，不能有空格或其他字符");
                            return false;
                        }
                    }
                    //判断省市区
                    if($("#Member_province").val() == ''){
                        alert("请选择==>>省");
                        return false;
                    }
                    if($("#Member_city").val() == ''){
                        alert("请选择==>>市");
                        return false;
                    }
                    if($("#Member_county").val() == ''){
                        alert("请选择==>>区");
                        return false;
                    }

                }else if(bank=="支付宝"){
                    //判断是手机号码还是email
                    if(!isNaN(bank_num)){
                        //如果都是数字，说明是手机号码，判断：11位
                        if(bank_num.length==11){
                            var mobile = /^(((13[0-9]{1})|(15[0-9]{1})|(14[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
                            if(!mobile.test(bank_num))
                            {
                                alert('支付宝账号==>请输入有效的手机号码！');
                                return false;
                            }
                        }else{
                            alert("请填写正确支付宝账号");
                            return false;
                        }
                    }else{
                        //email判断
                        var email = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
                        if(!email.test(bank_num)){
                            alert('支付宝账号==>请输入正确的email地址！');
                            return false;
                        }
                    }
                }else{
                    alert("请选择正确的开户行");
                    return false;
                }
                //return false;
            }else{
                //判断开户行是否为空
                if(bank == ''){
                    alert("请选择开户行");
                    return false;
                }
                //判断银行卡号是否为空
                if(bank_num == ''){
                    alert('请填写【'+bank+'】银行卡号');
                    return false;
                }
            }
        }

    }
    function checkAll()
    {
        if(blur_bank()===false)
        {
            return false;
        }
        return true;
    }
    $(function()
        {
            $("#Member_bank").click(function(){
                var bank = $("#Member_bank").val();//获取value
                if(bank=="支付宝")
                {
                    $("#qrcodest").css('display','block');
                }
                else
                {
                    $("#qrcodest").css('display','none');
                }
            });

        }
    )
</script>
<script>
    KindEditor.ready(function(K) {
        var editor = K.editor({
            allowFileManager : true,
            uploadJson: '<?php echo "/{$this->getModule()->id}/kindedit/upload";?>'
        });
        K('#imageButton').click(function() {
            editor.loadPlugin('image', function() {
                editor.plugin.imageDialog({
                    imageUrl : K('#Member_qrcode').val(),
                    showRemote: false,
                    clickFn : function(url, title, width, height, border, align) {
                        K('#Member_qrcode').val(url);
                        editor.hideDialog();
                    }
                });
            });
        });
    });
</script>
<style type="text/css">
    .ke-container,.ke-container-default{display:none}
    #qrcodest{display: none;}
</style>
<!--<div class="page-header app_head">
    <h1 class="text-center text-primary"><?php /*echo $data['username'];*/?> <small>银行资料修改</small></h1>
</div>-->
<?php
//判断是否有提示信息
if(Yii::app()->user->hasFlash('status')):?>
    <div class="container-fluid">
        <div class="alert alert-success ">
            <b ><?php echo Yii::app()->user->getFlash('status');?></b>
        </div>
    </div>
<?php endif;?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <div class="row">
                <ol class="breadcrumb pull-left">
                    当前位置：<li><a href="/dealer">管理主页</a></li>
                    <li class="active">银行资料修改</li>
                </ol>
            </div>
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => false,
                'htmlOptions' => array('class' => "form-horizontal",'onsubmit'=>"if(!checkAll()){return false;}"),
            )); ?>
            <div class="alert alert-danger" style="line-height: 30px;margin-top: 20px;">
                *使用支付宝收款的用户请不要隐藏真实姓名，财务支付收益时将无法进行账号与姓名的双重验证，可能会将您的收益支付给他人的情况。凡因隐藏姓名造成财务打款出现问题者，速推平台不承担任何责任。【<a href="http://www.sutuiapp.com/notice/71" target="_blank">点击查看修改方法</a>】
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">开户人</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($data, 'holder', array('class'=>'form-control','placeholder' => '请填写开户人姓名')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'holder',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">身份证号码</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($data, 'id_card', array('class'=>'form-control','placeholder' => '请输入身份证号码')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'id_card',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">开户行</label>
                <div class="col-sm-5">
                    <?php //echo $form->textField($data, 'bank', array('class'=>'form-control','placeholder' => '请输入开户行')); ?>
                    <?php echo $form->dropDownList($data, 'bank', Member::getBankList(), array('class'=>"form-control",'empty' => '--请选择开户行--')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'bank',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">银行卡号</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($data, 'bank_no', array('class'=>'form-control','placeholder' => '请输入银行卡号')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'bank_no',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group" id="qrcodest">
                <label for="inputPassword3" class="col-sm-2 control-label">收款二维码</label>
                <div class="col-sm-5">
                    <div class="input-group">
                        <?php echo $form->textField($data, 'qrcode', array('class'=>'form-control','placeholder' => '请上传图片')); ?>
                        <span class="input-group-btn">
                                  <input class="btn btn-default" type="button" id="imageButton" value="选择图片" />
                        </span>
                        <span class="input-group-addon">
                            <a href="/notice/84" target="_blank">支付宝收款二维码教程</a>
                        </span>

                    </div><!-- /input-group -->
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'qrcode',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">开户地址</label>
                <div class="col-sm-5">
                    <?php //echo $form->textField($data, 'bank_site', array('class'=>'form-control','placeholder' => '请输入银行开户地址','readonly'=>'readonly')); ?>
                </div>
                <div class="col-md-5"></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">省</label>
                <div class="col-sm-5">
                    <?php
                    echo $form->dropDownList($data, 'province', Area::model()->getProvince(), array(
                        'class'=>'form-control',
                        'empty' => '-请选择-',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('area/child'),
                            'data' => array('fid' => 'js:this.value'),
                            'update' => '#Member_city'
                        ),
                    ));
                    ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'province',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">市</label>
                <div class="col-sm-5">
                    <?php
                    echo $form->dropDownList($data, 'city', Area::model()->getChild($data->province), array(
                        'class'=>'form-control',
                        'empty' => '-请选择-',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('area/child'),
                            'data' => array('fid' => 'js:this.value'),
                            'update' => '#Member_county'
                        ),
                    ));
                    ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'city',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">区县</label>
                <div class="col-sm-5">
                    <?php
                    echo $form->dropDownList($data, 'county', Area::model()->getChild($data->city), array(
                        'class'=>'form-control',
                        'empty' => '-请选择-',
                    ));
                    ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($data, 'county',array('class'=>"errorMessageTips"));?></div>
            </div>


            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">确认提交</button>
                </div>
            </div>
            <?php $this->endWidget(); ?>

            <?php $this->widget('ext.KEditor.KEditor',array(
                'model'=>$data,  //传入form model
                'name'=>'content', //设置name
                'properties'=>array(
                    'uploadJson'=>"/{$this->getModule()->id}/kindedit/upload",
                )
            )); ?>
        </div>
    </div>
</div>