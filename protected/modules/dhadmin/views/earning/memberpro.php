<div class="page-header app_head">
    <h1 class="text-center text-primary">用户开启业务列表</h1>
</div>
<div class="col-md-2" style="height:300px; margin-right:50px;">
    <div class="list-group">
        <li class="list-group-item active">数据管理</li>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/import/index");?>" class="list-group-item">导入数据</a>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/earning/index");?>" class="list-group-item">用户收益</a>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/earning/memberpro");?>" class="list-group-item">用户业务</a>
    </div>
</div>
<style type="text/css">
    .grid-view{width:1000px;float:left;}
    .btn-info{margin-top:-2px;}
    .input-small{height:30px;}
    .form_date{width:200px;float:left;}
    .items tr td{text-align:center;}
</style>
<?php
$this->breadcrumbs = array('用户开启业务列表');

echo CHtml::beginForm('index', 'get', array('class'=>'input-append')),
'&nbsp;<span>用户名</span>&nbsp;',
CHtml::textField('uname', $uname, array('class'=>'input-small')),
    '&nbsp;',
CHtml::button('确认', array_merge(array('onclick' => 'sel()'), array('class'=>'btn btn-info')));
CHtml::endForm();

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
     'columns' => array(
         'uid',
         array(
             'header' => '用户名',
             'value' => 'Member::model()->getById($data["uid"])->username',
         ),
         //'type',
         array(
             'header'=>'业务类型',
             'value'=>'$data->type',
         ),
     )
));
?>

<script type="text/javascript">
    function sel() {
        window.location.href = '<?php echo $this->createUrl('',array('uname'=>'')) ?>'
        + $("#uname").val();
    }
</script>
