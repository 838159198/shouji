<?php
/**
 * Created by PhpStorm.
 * User: Peng
 * Date: 2017/2/16
 * Time: 15:13
 */

$user = Yii::app()->user;
$uid=$this->uid;
$manageid=$user->getState('member_manage');
$member=Member::model()->find("id=$uid");
$agent=$member["agent"];
?>
<link rel="stylesheet" type="text/css" href="/css/newdt/style.css">
<section id="content_wrapper" style="padding-top: 40px;">
    <div id="content" class="">
        <div class="row tray-inner">
            <div class="tab-content">
                <div id="tab8_1" class="tab-pane active">
                    <?php if($manageid==1){?>
                    <div class="panel">
                        <div class="panel-son">
                            <img src="/css/newdt/image/zx.png">
                            <h3 class="press">高收益的软件套餐</h3>
                        </div>
                        <div class="panel-body pn">
                            <div class="table-responsive" style="background:#ffffff;overflow-x: inherit">
                                <ul class="Package_ul" style="width:100%;margin:0 auto 60px auto;border: none;padding: 0 0 30px 0">
                                    <?php  foreach ($romPackage as $vt): ?>
                                    <li id="<?php echo $vt['id']; ?>" class="Package_li">
                                        <div class="Package_box">
                                            <div class="Package_top">
                                                <div class="left">
                                                    <p class="Package_title"><?php echo $vt['package_name']?></p>
                                                </div>
                                                <div class="line"></div>
                                                <div class="right">
                                                    <p>共<?php echo $vt['num'];?>
                                                        款软件 <?php echo $vt['filesize']; ?>MB
                                                    </p>
                                                    <p>安装<?php echo $vt['install_bill'];?>元+到达<?php echo $vt['arrive_bill'];?>元</p>
                                                </div>
                                                <div class="Package_clearfix"></div>
                                            </div>

                                            <div class="Package_bottom">
                                                <div>
                                                    <div class="Package_close">
                                                        <i>∧</i>
                                                        <span>收起</span>
                                                    </div>
                                                    <div class="Package_close Package_close2">
                                                        <i>∨</i>
                                                        <span>展开查看套餐详情</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="sanjiao"><em></em></div>
                                        </div>

                                    </li>
                                    <?php endforeach;?>

                                    <div class="clearfix">
                                    </div>
                                </ul>
                                <div class="Package_sub">
                                </div>
                                <div class="Package_clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <?php }?>
                    <script src="/css/newdt/msg.js"></script>
                    <div class="panel androidSoft">
                        <div class="panel-son">
                            <img src="/css/newdt/image/zx.png">
                            <h3 class="press">高收益的安卓软件</h3>
                        </div>
                        <div class="panel-body pn">
                            <div class="table-responsive">
                                <table class="table table-bordered" cellpadding="0" cellspacing="0" class="rom-down">
                                    <thead >
                                    <tr class="success">
                                        <th>应用名称</th>
                                        <th>版本号</th>
                                        <th>价格</th>
                                        <th>激活规则</th>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php  foreach ($dataP as $vt): ?>
                                    <tr>
                                        <td><img style="float: left" title="user" src="<?php echo $vt['pic'];?>" class="img-responsive ib mr10">
                                            <div style="float:left;margin-top: 28px"><?php echo $vt['name'];?></div>
                                        </td>
                                        <td><?php echo $versionArr[$vt['id']] ?></td>
                                        <td>
                                            <strong><?php
                                                $price= AgentPrice::findPrice($vt['id'],$agent);echo $price==99999?$vt['price']:$price;?></strong>元/台
                                        </td>
                                        <td><?php echo $vt['activate_instructions'];?></td>

                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

        $(function () {
            // 判断是否是IE浏览器
            if (window.ActiveXObject || "ActiveXObject" in window){

                $(".panel-son .press").css({
                    'color':'#337ab7',
                    'background-color':'#ffffff'
                });
            }
            if(isFirefox=navigator.userAgent.indexOf("Firefox")>0){
                $(".panel-son .press").css({
                    'color':'#337ab7',
                    'background-color':'#ffffff'
                });
            }
        });

</script>
