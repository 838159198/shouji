<div class="page-header app_head">
    <h1 class="text-center text-primary"><?php echo Yii::app()->user->manage_name;?>,欢迎登陆管理平台！ <small></small></h1>
</div>
<table class="table table-bordered">
    <tr>
        <th width="150">注册会员：</th>
        <td><?php
            $role = Manage::model()->getRoleByUid(Yii::app()->user->manage_id);
            echo $role<5?$member_count:"";
            ?></td>
        <th width="150">正常人数：</th>
        <td><span class="label label-success"><?php echo $role<5?$member_ok_count:"";?></span></td>
        <th width="150">锁定人数：</th>
        <td><span class="label label-danger"><?php echo $member_fail_count;?></span></td>
    </tr>
    <tr>
        <th>业务数量</th>
        <td><?php echo $product_count;?></td>
        <th>开启：</th>
        <td><?php echo $product_ok_count;?></td>
        <th>关闭：</th>
        <td><?php echo $product_fail_count;?></td>
    </tr>
</table>
<!--
<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>
<h1><?php echo $this->uniqueId . $this->uniqueId . '/' . $this->action->id; ?></h1>

<p>
This is the view content for action "<?php echo $this->action->id; ?>".
The action belongs to the controller "<?php echo get_class($this); ?>"
in the "<?php echo $this->module->id; ?>" module.
</p>
<p>
You may customize this page by editing <tt><?php echo __FILE__; ?></tt>
</p>
-->
<?php
//echo strpos("Hello world!","or");
?>