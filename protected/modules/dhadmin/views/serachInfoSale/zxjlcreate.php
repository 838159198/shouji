<?php
/* @var $this SerachInfoSaleController */
/* @var $dataProvider CActiveDataProvider */
/* @var $model SerachinfoRecords */

$this->breadcrumbs = array(
    '销售录入信息管理' => array('serachInfoSale/admin'),
    '咨询记录',
);
?>
<style type="text/css">
    .form,.h-view{width: 900px; margin: 0 auto;}
    ul,li{list-style: none;list-style-type: none;}
    .input-lg{width: 880px;}
    .btn{margin-top: 20px;}
</style>
<div class="page-header app_head">
    <h1 class="text-center text-primary">添加咨询记录</h1>
</div>

<?php $this->renderPartial('_zxjlform', array('model' => $model)); ?>

<h4 class="text-center" style="font-weight: bold;margin-top: 40px;">用户咨询记录</h4>
<div class="h-view">
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider' => $dataProvider,
        'itemView' => '_zxjlview',
        'htmlOptions' => array(
            'class' => 'bs-docs-example',
        ),
    )); ?>
</div>
