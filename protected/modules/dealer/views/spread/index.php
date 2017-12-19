<?php
/* @var $this SpreadController */
/* @var $member MemberInfo */

$title = '推广链接';
if ($this->type == Common::USER_TYPE_AGENT) {
    $title = '代理商链接';
}

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
                    当前位置：<li><a href="/dealer">管理主页</a></li>
                    <li class="active">个人信息</li>
                </ol>
            </div>

            <div class="alert alert-info">
                <?php echo ($this->type == Common::USER_TYPE_AGENT) ? '' : '<p class="label label-info">推荐其他用户在该链接注册并获得的收益，您将得到相应奖励。</p>' ?>
                <div class="row">
                    <label for="url"><?php echo $title ?>：</label>
                    <?php
                    if (strlen($member->alias) == 32) {
                        $url = Common::getAppParam('web') . Yii::app()->createUrl('/domains/index', array('a' => $member->alias));
                        $url='http://www.sutuiapp.com'.$url;
                    } else {
                        $url = 'http://www.sutuiapp.com/domains/index?a=' . $member->alias;
                    }
                    ?>
                    <input type="text" class="input-xxlarge" value="<?=$url?>" style="width: 60%;margin-top: 10px;" id="url">
                    <input class="btn btn-info" type="button" value="复制" onclick="copy()"/>
                    <span id="copy"></span>
                </div>

            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    function copy(){
        var Url2=document.getElementById("url");Url2.select(); // 选择对象
        document.execCommand("Copy"); // 执行浏览器复制命令
       $("#copy").text('已复制好，可贴粘。').css({"color":"red","font-weight":"bold"});
    }
</script>