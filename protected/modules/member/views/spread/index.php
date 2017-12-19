<link rel="stylesheet" href="/css/jQuery/css/style.css" type="text/css" />
<!--<script type="text/javascript" src="/css/jQuery/js/jquery-1.11.1.min.js"></script>-->
<script type="text/javascript" src="/css/jQuery/js/cropbox.js"></script>
<style type="text/css" >
    .jiathis_default_pos {top: 30%;  left: 80%;}
    table tr th,td{border: solid #000 1px;width: 220px;text-align: center}
</style>
<?php
/* @var $this SpreadController */
/* @var $member MemberInfo */

$title = '推广链接';
if ($this->type == Common::USER_TYPE_AGENT) {
    $title = '代理商链接';
}
$host = 'http://'.$_SERVER['HTTP_HOST'];
$this->breadcrumbs = array($title);
echo '<h4 class="text-center">' . $title . '</h4>';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <div class="row">
                <ol class="breadcrumb pull-left">
                    当前位置：<li><a href="/member">管理主页</a></li>
                    <li class="active">推广链接</li>
                </ol>
            </div>
            <div class="alert alert-success">
                <label for="url">  推广说明：</label>
                <p>1、将推广链接/专属二维码头像发送到QQ空间、QQ群、论坛、贴吧、微博；</p>
                <p>2、使用您的推广链接注册的会员，速推将额外奖励一定比例的推广佣金给您；</p>
                <p>3、成功邀请用户注册可获得下线3个月的佣金提成。</p>
                <p>4、推广佣金为速推平台发放，对您发展的下线收入没有影响。</p><br>

                <label for="url">  奖励方式计算：</label>
                <p>推广用户的月收益总和相加 乘以 对应的比例 就是您的推广奖励收益。</p>
                <p>例如：你推广的A用户11月收入是3000元，B用户11月收入是1万元，两个用户的收入相加是13000元，速推奖励给你的比例是3%，那么你的推广奖励就是13000 * 3% = 390元。</p><br>
                <label for="url">  奖励比例表：</label>
                <table style="border: solid #000 1px">
                    <thead>
                        <tr>
                            <th>下线月收益</th>
                            <th>奖励比例</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>0&lt;x &le;5000</td>
                            <td>1%</td>
                        </tr>
                        <tr>
                            <td>5000&lt;x&le;1万</td>
                            <td>2%</td>
                        </tr>
                        <tr>
                            <td>1万&lt;x &le;2万</td>
                            <td>3%</td>
                        </tr>
                        <tr>
                            <td>2万&lt;x&le;3万</td>
                            <td>4%</td>
                        </tr>
                        <tr>
                            <td>x>3万</td>
                            <td>5%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="alert alert-info" style="height: 100px">

                <div class="row" style="padding-left: 10;margin-top: 10px">
                    <?php
                    if (strlen($member->alias) == 32) {
                        $url = Common::getAppParam('web') . Yii::app()->createUrl('/domains/index', array('a' => $member->alias));
                        $url='http://www.sutuiapp.com'.$url;
                    } else {
                        $url = 'http://www.sutuiapp.com/domains/index?a=' . $member->alias;
                    }
                        $d = (int)$member->id+123456;
                        $url=$host.'/t/'.$d;
                    ?>
                    <label for="url">推广方式一：推广链接</label>&nbsp;&nbsp;
                    <input type="text" class="input-xxlarge" value="<?=$url?>" style="width: 60%;margin-top: 10px;" id="url">
                    <input class="btn btn-info" type="button" value="复制" onclick="copy()"/>
                    <span id="copy"></span><br><br>

                </div>
        </div><br/><br/>


            <!--图片剪切上传-->
            <?php
            if($qrurl==""){
                $qrurll="请上传图片";
            }else{
                $qrurll="<img src='$qrurl' style='width: 205px;height: 205px'/>";
            }
            ?>

            <div class="qrcodetext" style="float: left;width: 549px;margin-left: -10px;">
                <p class="alert alert-info" style="height:452px;width: 526px;margin-left: 10px;">

                    <strong class="alertlef">推广方式二：二维码</strong><br/>
                    <span class="alertrig">将二维码用作您的论坛头像、QQ头像，只要用户通过扫码注册账号并提现，您将得到推广奖励。<br/></span>
                    <strong class="alertlef" style="color: #737373">生成二维码方法：</strong><br/><span class="alertrig">第一步：请先上传图片<br/>第二步：通过+&nbsp;&nbsp;-号使图片缩放跟预览框大小相同  <br/>第三步：点击裁切生成二维码
                    </span>
                </p>
                <div style="height: 66px;float: left;margin-top: 60px;">
                    <div class="action">
                        <!-- <input type="file" id="file" style=" width: 200px">-->
                        <div class="new-contentarea tc"> <a href="javascript:void(0)" class="upload-img">
                                <label for="upload-file">上传图片</label>
                            </a>
                            <input type="file" class="" name="upload-file" id="upload-file" />
                        </div>
                        <input type="button" id="btnCrop"  class="Btnsty_peytonn" value="裁切生成二维码">
                        <input type="button" id="btnZoomIn" class="Btnsty_peyton" value="+"  >
                        <input type="button" id="btnZoomOut" class="Btnsty_peyton" value="-" >
                    </div>
                </div>
            </div>
            <div style="float: left;width:300px;margin-left: 0px;text-align: center;">
                <div class="boxborder"><div class="thumbBoxx" style="font-size: 20px;line-height: 300px;"><?php echo $qrurll ?></div></div>
                <div class="imageBox">
                    <div class="thumbBox"></div>
                    <div class="spinner" style="display: none">Loading...</div>
                </div>
            </div>

        </div>


    </div>

</div>
<script type="text/javascript">
    $(window).load(function() {
        var options =
        {
            thumbBox: '.thumbBox',
            spinner: '.spinner',
            imgSrc: '/css/jQuery/images/avatar.png'
        }
        var cropper = $('.imageBox').cropbox(options);
        $('#upload-file').on('change', function(){
            var reader = new FileReader();
            reader.onload = function(e) {
                options.imgSrc = e.target.result;
                cropper = $('.imageBox').cropbox(options);
            }
            reader.readAsDataURL(this.files[0]);
            this.files = [];
        })
        $('#btnCrop').on('click', function(){
            var img = cropper.getDataURL();
            $('.cropped').html('');
            $('.cropped').append('<img src="'+img+'" align="absmiddle" style="width:64px;margin-top:4px;box-shadow:0px 0px 12px #7E7E7E;" ><p>64px*64px</p>');
            $('.cropped').append('<img src="'+img+'" align="absmiddle" style="width:128px;margin-top:4px;border-radius:128px;box-shadow:0px 0px 12px #7E7E7E;"><p>128px*128px</p>');
            $('.cropped').append('<img src="'+img+'" align="absmiddle" style="width:180px;margin-top:4px;border-radius:180px;box-shadow:0px 0px 12px #7E7E7E;"><p>180px*180px</p>');

            var img = cropper.getDataURL();
            if(img==''){
                alert("请选择上传文件");
                return false;
            }
            $.ajax({
                type:"POST",
                url:"/member/spread/edit",
                data:{img:img},
                datatype: "json",
                success:function(data){
                    var jsonStr = eval("("+data+")");
                    if(jsonStr.status==403){
                        alert(jsonStr.message);
                        return false;
                    }else if(jsonStr.status==200){
                        alert(jsonStr.message);
                        location.replace(location.href);
                    }else{
                        alert("发生错误"+jsonStr.status);
                        return false;
                    }
                },
                error: function(){
                    alert("未知错误");
                }
            });
        })
        $('#btnZoomIn').on('click', function(){
            cropper.zoomIn();
        })
        $('#btnZoomOut').on('click', function(){
            cropper.zoomOut();
        })

    });
</script>
<script type="text/javascript">
    function copy(){
        var Url2=document.getElementById("url");Url2.select(); // 选择对象
        document.execCommand("Copy"); // 执行浏览器复制命令
       $("#copy").text('已复制好，可贴粘。').css({"color":"red","font-weight":"bold"});
    }
</script>