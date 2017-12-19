<div class="page-header app_head">
    <h1 class="text-center text-primary"><?php echo $data['name'];?> <small>管理员账号信息</small></h1>
</div>
<?php
//判断是否有提示信息
if(Yii::app()->user->hasFlash('status')):?>
    <div class="container-fluid">
        <div class="alert alert-success ">
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            <b ><?php echo Yii::app()->user->getFlash('status');?></b>
        </div>
    </div>
<?php endif;?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("_sideMenu",array('data'=>$data));?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <table class="table table-bordered">
                <thead>
                <tr class="info">
                    <th colspan="2">基本信息</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td width="200">ID</td>
                    <td><?php echo $data['id'];?></td>
                </tr>
                <tr>
                    <td width="200">状态</td>
                    <td><?php echo $data['xstatus'];?></td>
                </tr>
                <tr>
                    <td width="200">账号</td>
                    <td><?php echo $data['username'];?></td>
                </tr>
                <tr>
                    <td width="200">姓名</td>
                    <td><?php echo $data['name'];?></td>
                </tr>
                <tr>
                    <td width="200">性别</td>
                    <td><?php echo $data['xsex'];?></td>
                </tr>
                <tr>
                    <td width="200">电话号码</td>
                    <td><?php echo $data['phone'];?></td>
                </tr>
                <tr>
                    <td width="200">有效证件号码</td>
                    <td><?php echo $data['idcard'];?></td>
                </tr>
                <tr>
                    <td width="200">QQ</td>
                    <td><?php echo $data['qq'];?></td>
                </tr>
                <tr>
                    <td width="200">email</td>
                    <td><?php echo $data['mail'];?></td>
                </tr>

                <tr>
                    <td width="200">备注信息</td>
                    <td><?php echo $data['remark'];?></td>
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
                    <td width="200">注册IP</td>
                    <td><?php echo $data['joinip'];?></td>
                </tr>
                <tr>
                    <td width="200">最后登录时间</td>
                    <td><?php echo date("Y-m-d H:i:s",$data['overtime']);?></td>
                </tr>
                <tr>
                    <td width="200">最后登录IP</td>
                    <td><?php echo $data['overip'];?></td>
                </tr>
                </tbody>
            </table>


        </div>
    </div>
</div>