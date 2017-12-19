<style type="text/css" rel="stylesheet">
    .dl-horizontal{width: 1400px;margin: 0 auto;}
    .dl-horizontal dd,.dl-horizontal dt{line-height: 40px;}
    .ui-dialog dd,.ui-dialog dt{line-height: 20px;}
    #income-grid{width: 1200px;margin: 0 auto;margin-top: 10px;}
    #Param_type{height: 28px;margin-top: 5px;}
</style>

<div class="page-header app_head"><h1 class="text-center text-primary">安装降量分析</h1></div>
<?php echo CHtml::beginForm($this->createUrl(''), 'get'); ?>
<dl class="dl-horizontal">
    <dt>用户类型</dt>
    <dd><?php echo CHtml::dropDownList('Param[type]',$param['value'], $param['type']); ?></dd>
    <dt>开始时间</dt>
    <dd>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'attribute' => 'p_title',
            'value' => $param['firstDate']['first'], //设置默认值
            'name' => 'Param[firstDate][first]',
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd',
                'showOtherMonths'=> true,
                'selectOtherMonths'=> true
            ),
            'htmlOptions' => array(
                'maxlength' => 8,
            ),
        ));
        ?>

        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'attribute' => 'p_title',
            'value' => $param['firstDate']['last'], //设置默认值
            'name' => 'Param[firstDate][last]',
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd',
                'showOtherMonths'=> true,
                'selectOtherMonths'=> true
            ),
            'htmlOptions' => array(
                'maxlength' => 8,
            ),
        ));
        ?>
        <span id='tip' style="display:none;color:red"></span>
    </dd>
    <dt>结束时间</dt>
    <dd>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'attribute' => 'p_title',
            'value' => $param['lastDate']['first'], //设置默认值
            'name' => 'Param[lastDate][first]',
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd',
                'showOtherMonths'=> true,
                'selectOtherMonths'=> true
            ),
            'htmlOptions' => array(
                'maxlength' => 8,
            ),
        ));
        ?>

        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'attribute' => 'p_title',
            'value' => $param['lastDate']['last'], //设置默认值
            'name' => 'Param[lastDate][last]',
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'yy-mm-dd',
                'showOtherMonths'=> true,
                'selectOtherMonths'=> true
            ),
            'htmlOptions' => array(
                'maxlength' => 8,
            ),
        ));
        ?>
        <span id='tip2' style="display:none;color:red"></span>
    </dd>
    <dd>
		<?php echo CHtml::submitButton('开始分析', Bs::cls(Bs::BTN_PRIMARY));?>
    </dd>
</dl>
<?php echo CHtml::endForm(); ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'income-grid',
    // 'enablePagination' => false,
    // 'enableSorting' => false,
    // 'itemsCssClass' => Bs::TABLE,
    'itemsCssClass' =>'items',//分页表格的样式
    'dataProvider' => $param['dataProvider'],
    // 'filter' => $model,
    'columns' => array(
        array('name' => 'username',
            'header'=>'用户名',
            'value' => '$data["username"]',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
         array(
            'header'=>'所属客服 / 销售',
            'value' => 'Member::kefu($data["id"])',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array(
            'name' => 'begall',
            'header'=>'开始安装量',
            'value'=>'$data["begall"]',
            'htmlOptions'=>array('style'=>'text-align:center')
            
        ),
        array(
            'name'=>'endall',
            'header'=>'结束安装量',
            'value' => '$data["endall"]',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array(
            'name'=>'cha',
            'header'=>'差值',
            'value'=>'$data["cha"]',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array(
            'name'=>'bai',
            'header'=>'百分比（ % ）',
            'value'=>'$data["bai"]',
            'type'=>'raw',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        
    ),
)); ?>
<script>
 $('#Param_firstDate_first').change(function(){
    var Param_firstDate_last= $('#Param_firstDate_last').val();
    var Param_firstDate_first= $('#Param_firstDate_first').val();
    if(Param_firstDate_first > Param_firstDate_last){
        $('#Param_firstDate_first').val('');
        $('#tip').html(' 开始时间的时间节点不能大于初始时间    注：初始时间为前排显示时间。').show();
        $('input[type=submit]').attr('disabled','disabled');
    }else{
        $('#tip').hide();
        $('input[type=submit]').removeAttr('disabled');
    }

 })   

 $('#Param_firstDate_last').change(function(){
    var Param_firstDate_last= $('#Param_firstDate_last').val();
    var Param_firstDate_first= $('#Param_firstDate_first').val();
    if(Param_firstDate_first > Param_firstDate_last){
        $('#Param_firstDate_last').val('');
        $('#tip').html(' 开始时间的时间节点不能大于初始时间    注：初始时间为前排显示时间。').show();
        $('input[type=submit]').attr('disabled','disabled');
    }else{
        $('#tip').hide();
        $('input[type=submit]').removeAttr('disabled');
    }

 }) 

 $('#Param_lastDate_first').change(function(){
    var Param_lastDate_first= $('#Param_lastDate_first').val();
    var Param_lastDate_last= $('#Param_lastDate_last').val();
    if(Param_lastDate_first > Param_lastDate_last){
        $('#Param_lastDate_first').val('');
        $('#tip2').html(' 结束时间的时间节点不能小于初始时间    注：初始时间为前排显示时间。').show();
        $('input[type=submit]').attr('disabled','disabled');
    }else{
        $('#tip2').hide();
        $('input[type=submit]').removeAttr('disabled');
    }

 }) 

$('#Param_lastDate_last').change(function(){
    var Param_lastDate_first= $('#Param_lastDate_first').val();
    var Param_lastDate_last= $('#Param_lastDate_last').val();
    if(Param_lastDate_first > Param_lastDate_last){
        $('#Param_lastDate_last').val('');
        $('#tip2').html(' 结束时间的时间节点不能小于初始时间    注：初始时间为前排显示时间。').show();
        $('input[type=submit]').attr('disabled','disabled');
    }else{
        $('#tip2').hide();
        $('input[type=submit]').removeAttr('disabled');
    }

 }) 



</script>