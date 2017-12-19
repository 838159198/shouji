<?php
/* @var $this ProductController */
/* @var $data Resource */
/* @var $resourceStatus array */

$layout = false;
?>

<script type="text/javascript">
    var yy = eval(<?php $arr1['bool']=Yii::app()->user->getState("member_manage");echo json_encode($arr1);?>);
</script>
<div class=" alert alert-warning" style="padding: 0px;background-color: white;border-color: white">
    <div class="row-fluid">
        <div class="span12">
            <div class="container-fluid" style="padding: 0px">
                <div class="row-fluid">
                    <div class="span12">
                        <div class="tabbable" id="tabs-527327">
                            <ul class="nav nav-tabs">
                                <?php foreach ($arr as $k=>$vt): ?>
                                    <?php if ($k==0): ?>
                                        <li>
                                            <a style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;border-right: 1px solid #ddd;" data-toggle="tab" id=<?php echo $k;?> href="#panel-845765"><?php echo $vt;?></a>
                                        </li>
                                        <?php elseif($k==-1): ?>
                                        <li  class="active">
                                            <a style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;border-right: 1px solid #ddd;" data-toggle="tab" id=<?php echo $k;?> href="#panel-845765"><?php echo $vt;?></a>
                                        </li>
                                        <?php else: ?>
                                        <li>
                                            <a style="border-left: 1px solid #ddd;border-top: 1px solid #ddd;border-right: 1px solid #ddd;" data-toggle="tab" id=<?php echo $k;?> href="#panel-845765"><?php echo $vt;?></a>
                                        </li>
                                    <?php endif;?>
                                <?php endforeach;?>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane" id="panel-845765">
                                    <table class="table">
                                        <thead>
                                        <tr class="table-tr">
                                            <th style="width: 72px"></th>
                                            <th style="width: 30%">软件名称</th>
                                            <th>单价</th>
                                            <th>计费规则</th>
                                            <th>内置区域</th>
                                            <th style="width: 100px" class="special">操作</th>
                                        </tr>
                                        </thead>
                                            <tbody class="category-tbody">
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

