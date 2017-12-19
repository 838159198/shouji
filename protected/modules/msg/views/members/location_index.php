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
                    当前位置：<li><a href="/msg">管理主页</a></li>
                    <li class="active">收货地址</li>
                </ol>
            </div>
            <div class="row-fluid" >
                <a href="/member/members/locationAdd" class="btn btn-info">添加地址</a>
            </div>
            <table class="table table-bordered" style="margin-top: 20px;">
                <tr>
                    <th>姓名</th>
                    <th>手机号码</th>
                    <th>详细地址</th>
                    <th>操作</th>
                </tr>
                <?php foreach($data as $row):?>
                    <tr>
                        <td><?php echo CHtml::encode($row['name']);?></td>
                        <td><?php echo CHtml::encode($row['tel']);?></td>
                        <td><?php echo CHtml::encode($row['address']);?></td>
                        <td><a href="<?php echo $this->createUrl("locationedit",array("id"=>$row['id']));?>" class="btn btn-info">编辑地址</a></td>
                    </tr>
                <?php endforeach;?>
            </table>

        </div>
    </div>
</div>
