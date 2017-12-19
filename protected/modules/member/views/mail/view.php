<?php
/* @var $this MailController */
/* @var $model Mail */

$this->breadcrumbs = array(
    '系统通知' => array('mail/index'),
    '查看信息'
);
?>

<div class="hero-unit">
    <h3><?php echo $model->MailContent->title ?></h3>

    <div class="alert alert-info"><?php echo $model->MailContent->content ?></div>

    <div class="btn-group">
        <?php echo CHtml::link('返回列表', array('index')) ?>
        <?php echo CHtml::link('删除信息', 'javascript:del(' . $model->id . ');') ?>
    </div>
</div>


<script type="text/javascript">
    var DELETE_URL = '<?php echo $this->createUrl('delete') ?>';
    function del(id) {
        if (window.confirm('是否确认删除此信息？')) {
            location.replace(DELETE_URL + '?id=' + id);
        }
    }
</script>