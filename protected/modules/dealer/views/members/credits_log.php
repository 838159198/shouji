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
                    <li class="active">积分记录</li>
                </ol>
            </div>
            <div class="alert alert-success">
                可使用积分：<?php $member = Member::model()->findByPk($this->uid);echo $member['credits'];?>
            </div>
            <table class="table table-bordered">
                <tr>
                    <th>序号</th>

                    <th>积分变化</th>
                    <th>备注</th>
                    <th>记录时间</th>
                </tr>
                <?php $i=1; foreach($data as $row):?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td><b><?php echo ($row['credits']>0)?"<font color=#009900>+{$row['credits']}</font>":"<font color=#ff0000>{$row['credits']}</font>";?></b></td>

                        <td><?php echo $row['remarks'];?></td>
                        <td><?php echo date("Y-m-d H:i:s",$row['create_datetime']);?></td>

                    </tr>
                    <?php $i++; endforeach;?>
            </table>
            <div class="pager">
                <?php
                $this->widget('CLinkPager',array(
                        'header'=>'',
                        'firstPageLabel' => '首页',
                        'lastPageLabel' => '末页',
                        'prevPageLabel' => '上一页',
                        'nextPageLabel' => '下一页',
                        'pages' => $pages,
                        'maxButtonCount'=>8,
                    )
                );
                ?>
            </div>

        </div>
    </div>
</div>
<?php
$this->breadcrumbs = array(
    '积分记录',
);
?>
