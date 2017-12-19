<!--<div class="page-header app_head">
    <h1 class="text-center text-primary"><?php /*echo $data['username'];*/?> <small>用户信息</small></h1>
</div>-->
<?php
//判断是否有提示信息
if(Yii::app()->user->hasFlash('pwd_status')):?>
    <div class="container-fluid">
        <div class="alert alert-success ">
            <b ><?php echo Yii::app()->user->getFlash('pwd_status');?></b>
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
                    <li class="active">用户信息</li>
                </ol>
            </div>
            <table class="table table-bordered">
                <thead>
                <tr class="info">
                    <th colspan="2">基本信息</th>
                </tr>
                </thead>
                <tbody>
<!--                <tr>
                    <td width="200">ID</td>
                    <td><?php /*echo $data['id'];*/?></td>
                </tr>-->

                <tr>
                    <td width="200">用户名</td>
                    <td><?php echo $data['username'];?></td>
                </tr>
                <tr>
                    <td width="200">手机号码</td>
                    <td><?php echo $data['tel'];?></td>
                </tr>
                <tr>
                    <td width="200">email</td>
                    <td><?php echo $data['mail'];?></td>
                </tr>
                <tr>
                    <td width="200">QQ</td>
                    <td><?php echo $data['qq'];?></td>
                </tr>
                <tr>
                    <td width="200">微信</td>
                    <td><?php echo $data['weixin_name'];?></td>
                </tr>
                </tbody>
                <thead>
                <tr class="info">
                    <th colspan="2"><b>财务信息</b></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td width="200">收款人</td>
                    <td><?php echo $data['holder'];?></td>
                </tr>
                <tr>
                    <td width="200">身份证号码</td>
                    <td><?php echo $data['xsfz'];?></td>
                </tr>
                <tr>
                    <td width="200">开户行</td>
                    <td><?php echo $data['bank'];?></td>
                </tr>
                <tr>
                    <td width="200">银行卡号</td>
                    <td><?php echo $data['bank_no'];?></td>
                </tr>
                <tr>
                    <td width="200">开户地址</td>
                    <td><?php echo $data['bank_site'];?></td>
                </tr>

                <tr>
                    <td width="200">地区</td>
                    <td><?php echo $data['Xprovince'];?> <?php echo $data['Xcity'];?> <?php echo $data['Xcounty'];?></td>
                </tr>
                <tr>
                    <td width="200">收款二维码</td>
                    <td><img src="<?php echo $data['qrcode'];?>" width="180"> </td>
                </tr>
                </tbody>
                <thead>
                <tr class="info">
                    <th colspan="2"><b>其他信息</b></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td width="200">注册时间</td>
                    <td><?php echo date("Y-m-d H:i:s",$data['jointime']);?></td>
                </tr>
                <tr>
                    <td width="200">最后登录时间</td>
                    <td><?php echo date("Y-m-d H:i:s",$data['overtime']);?></td>
                </tr>
                <tr>
                    <td width="200">最后登录IP</td>
                    <td><?php echo $data['login_ip'];?></td>
                </tr>
                </tbody>
            </table>


        </div>
    </div>
</div>