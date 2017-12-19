<?php
/* @var $this StatsController */
/* @var $data CArrayDataProvider */
/* @var $param array */

$this->breadcrumbs = array('平台数据统计');
//$dateOption = array_merge(Bs::cls(Bs::INPUT_SMALL), Bs::dateInput());
$dateOption = Bs::cls(Bs::INPUT_SMALL);
?>
<style type="text/css" rel="stylesheet">
    .dl-horizontal{width: 1400px;margin: 0 auto;}
    .dl-horizontal dd,.dl-horizontal dt{line-height: 40px;}
    .ui-dialog dd,.ui-dialog dt{line-height: 20px;}
    #income-grid{width: 1200px;margin: 0 auto;margin-top: 10px;}
    #Param_type{height: 28px;margin-top: 5px;}
</style>
<div class="page-header app_head"><h1 class="text-center text-primary">降量分析</h1></div>

<?php echo CHtml::beginForm($this->createUrl(''), 'get'); ?>
<dl class="dl-horizontal">
    <dt>资源类型</dt>
    <dd><?php echo CHtml::dropDownList('Param[type]', $param['type'], Ad::getAdList(false), array('onchange' => 'getTaskType()')); ?></dd>
    <input type='hidden' id='t_type' value=''>
    <dt>开始时间</dt>
    <dd>
        <?php //echo CHtml::textField('Param[firstDate][first]', $param['firstDate']['first'], $dateOption); ?><!-- --->

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

        <?php //echo CHtml::textField('Param[firstDate][last]', $param['firstDate']['last'], $dateOption); ?>
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

    </dd>

    <dt>结束时间</dt>
    <dd>
        <?php //echo CHtml::textField('Param[lastDate][first]', $param['lastDate']['first'], $dateOption); ?>
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

        <?php //echo CHtml::textField('Param[lastDate][last]', $param['lastDate']['last'], $dateOption); ?>
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
    </dd>


    <dt>&nbsp;</dt>
    <dd>
        <?php
        echo CHtml::submitButton('开始分析', Bs::cls(Bs::BTN_PRIMARY));
        if (Auth::check('stats_dropdataexcel')) {
            $url = 'dropdataexcel?'
                . 'Param[type]=' . $param['type']
                . '&Param[firstDate][first]=' . $param['firstDate']['first']
                . '&Param[firstDate][last]=' . $param['firstDate']['last']
                . '&Param[lastDate][first]=' . $param['lastDate']['first']
                . '&Param[lastDate][last]=' . $param['lastDate']['last'];
            //echo Bs::nbsp, CHtml::link('下载Excel', $url, Bs::cls(Bs::BTN_PRIMARY));
        }
        ?>
    </dd>
</dl>
<?php echo CHtml::endForm(); ?>

<?php
function getPerCent($data)
{
    $percent = $data['percent'];
    $icon = '<i class="icon-arrow-down"></i> ';
    if ($percent > 0) {
        $icon = '<i class="icon-arrow-up"></i> ';
    }
    return $icon . $percent . ' %';
}

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'income-grid',
    'dataProvider' => $data,
    'columns' => array(
        array(
            'class' => 'CLinkColumn',
            'labelExpression' => '$data["t_status"]',
        ),
        array(
            'selectableRows' => 2,
            'footer' => CHtml::button('发布任务', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'GetCheckbox(this)'))),
            'class' => 'CCheckBoxColumn',
            'headerHtmlOptions' => array('width' => '33px'),
            'checkBoxHtmlOptions' => array('name' => 'selectdsend'),
        ),
        array('name' => 'firstSum',
            'visible' => false,
            'value' => '$data["firstSum"]',
            'header' => '开始收入'),

        array('name' => 'username', 'value' => '$data["username"]', 'header' => '用户名'),
        array(
            'class' => 'CLinkColumn',
            'labelExpression' => 'Bs::ICON_SEARCH',
            'urlExpression' => '"javascript:show({$data["id"]})"',
        ),
        array(
            'class' => 'CLinkColumn',
            'label' => '用户类型',
            'labelExpression' => '$data["categoryName"].Bs::ICON_EDIT',
            'urlExpression' => '"javascript:category({$data["id"]},{$data["category"]})"',
        ),
        array(
            'class' => 'CLinkColumn',
            'label' => '曲线图',
            'urlExpression' => 'array("member/graphs", "uid" =>$data["id"])',
            'linkHtmlOptions' => array('target' => '_blank'),
            'visible' => Auth::check('member_graphs')
        ),
        array(
            'class' => 'CLinkColumn',
            'label' => '用户咨询记录',
            'urlExpression' => 'array("advisoryrecords/index", "uid" =>$data["id"])',
            'linkHtmlOptions' => array('target' => '_blank'),
            'visible' => Auth::check('advisoryrecords_index')
        ),
        array('name' => 'firstSum', 'value' => '$data["firstSum"]', 'header' => '开始收入'),
        array('name' => 'lastSum', 'value' => '$data["lastSum"]', 'header' => '结束收入'),
        array('name' => 'difference', 'value' => '$data["difference"]', 'header' => '差'),
        array('name' => 'percent', 'value' => 'getPerCent($data)', 'header' => '百分比', 'type' => 'html'),
    ),
));
?>

<div id="modalcategory" title="修改用户类别" style="display:none;">
    <?php echo CHtml::beginForm($this->createUrl('member/category'), 'post', Bs::cls(Bs::FORM_INLINE)),
    CHtml::label('用户类别：', 'm_category'),
    CHtml::dropDownList('m_category', '', MemberCategory::model()->getListToArray()),
    CHtml::hiddenField('m_uid'), Bs::nbsp,
    CHtml::submitButton('保存', Bs::cls(Bs::BTN_INFO)),
    CHtml::endForm();?>
</div>

<div id="ask_for_task" title="申请任务" style="display:none;">
<?php echo CHtml::label('申请任务：', 'm_category') ?>
		<input type='hidden' value='' id = 'hide_uid'>
		<select onchange="ask_for_task(this)" id = 'a_task'>
		<?php foreach ($model AS $tiem) { ?>
    			<option id="<?php echo $tiem['id'] ?>" a_id ='<?php echo $tiem['id'] ?>'  value = "<?php echo $tiem['name'] ?>">
    				<?php echo $tiem['name'] ?>
    			</option >
    	<?php } ?>
    	</select>
<?php echo CHtml::button('申请', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'ask_for_up()'))); ?>
</div>

<div id="modaltask" title="发布任务" style="display:none;">

    <label id="suname"></label>
    <dl class="dl-horizontal">
        <dt>标题（50字）：</dt>
        <dd><?php echo CHtml::textField('t_title') ?></dd>

        <dt>说明（200字）：</dt>
        <dd><?php echo CHtml::textArea('t_content') ?></dd>

        <dt>接收人：</dt>
        <dd>
            <select id='a_list'>
                <?php foreach ($model AS $list) { ?>
                    <option id='<?php echo $list['id'] ?>'
                            role='<?php echo $list['role'] ?>'><?php echo $list['name']; ?>(<?php echo $list['rname'] ?>
                        )
                    </option>
                <?php } ?>
            </select>
        </dd>
        <dt>&nbsp;</dt>
        <dd><?php echo CHtml::button('确认发布', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'makeSureAskTask()'))) ?></dd>
    </dl>
</div>


<script type="text/javascript">
    var MEMBER_INFO_URL = '<?php echo $this->createUrl('member/info') ?>';
</script>