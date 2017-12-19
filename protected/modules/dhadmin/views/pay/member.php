
<?php
/* @var $this PayController */
/* @var $data CActiveDataProvider */
/* @var $status int */

$this->breadcrumbs = array(
    '财务管理' => 'index',
);
?>

<div class="page-header app_head">
    <h1 class="text-center text-primary">月提现申请与支付</h1>
</div>
<div class="col-md-2">
    <div class="list-group">
        <li class="list-group-item active">财务管理</li>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/index");?>" class="list-group-item">财务说明</a>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/stats");?>" class="list-group-item">统计上月收益至余额</a>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/member?status=0");?>" class="list-group-item">未支付记录</a>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/member?status=1");?>" class="list-group-item">已支付记录</a>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/pay/statement");?>" class="list-group-item">月财务统计</a>
    </div>
</div>
<div class="col-md-10">
    <script type="text/javascript">
        var PAY_URL = '<?php echo $this->createUrl('updates') ?>';
    </script>
    <div class="row-fluid">
        <div class="app_button">

    <?php
if ($status == MemberPaylog::STATUS_FALSE) {
    echo CHtml::button('批量支付', array_merge(
        array('class'=>'btn btn-success'),
        array('id' => 'payAll')
    ));
    echo '&nbsp;' . CHtml::link('导出Excel', 'excel', array('class'=>'btn btn-primary'));
}?>
        </div>
        <?php echo CHtml::beginForm() ?>
        <label>
            选择查看日期：
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'attribute' => 'visit_time',
                'language' => 'zh_cn',
                'name' => 'dates',
                'value' => $date,
                'options' => array(
                    'dateFormat' => 'yy-mm',
                ))); ?>

        </label>
        <?php echo CHtml::endForm() ?>
    </div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'admin-grid',
    'dataProvider' => $data,
    'columns' => array(
        array(
            'name' => '全选',
            'value' => '$data->id',
            'selectableRows' => 2,
            'header' => CHtml::checkBox('all', false),
            'class' => 'CCheckBoxColumn',
            'checkBoxHtmlOptions' => array('name' => 'rid[]'),
            'visible' => $status == MemberPaylog::STATUS_FALSE,
        ),
        array(
            'name' => '用户',
            'value' => '$data->member->username'
        ),
        'sums',
        'ask_time',
        array(
            'name' => 'payee',
            'value' => '$data->payee'
        ),
        array(
            'name' => 'bank',
            'value' => '$data->bank'
        ),
        array(
            'name' => 'bank_num',
            'value' => '$data->bank_num'
        ),
        array(
            'name' => 'bank_site',
            'value' => '$data->bank_site'
        ),
        array(
            'name' => '二维码',
            'type'=>'raw',
            'value'=>'$data->member->qrcode==""?"":"<a href=".$data->member->qrcode." target=_blank>查看</a>"',

        ),
        'answer_time',
        array(
            'class' => 'CButtonColumn',
            'buttons' => array(
                'update' => array(
                    'label' => '支付',
                    'imageUrl' => false,
                )
            ),
            'visible' => $status == MemberPaylog::STATUS_FALSE,

            'template' => '{update}',
        ),
/*        array(
            'class' => 'CButtonColumn',
            'buttons' => array(
                'income' => array(
                    'label' => '详情<br>',
                    'imageUrl' => false,
                    'options' => array('target' => '_blank'),
                    'url' => "Yii::app()->createUrl('manage/earning/count',array('uid'=>\$data->member->id))",
                ),

            ),
            'template' => '{income}',
        ),*/
    ),

));
echo "<br>总计：".$sumdata[0]["su"];
?>
<script type="text/javascript">
    $(function () {
        $("#all").click(function () {
            $(":checkbox").attr("checked", this.checked);
        });

        $("#payAll").click(function () {
            var ids = [];
            $(":checkbox[name='rid[]']").each(function () {
                if (this.checked) {
                    ids.push(this.value);
                }
            });

            if (ids.length > 0) {
                $.post(PAY_URL, {'rid[]': ids}, function (data) {
                    if (data && data === "success") {
                        document.location.reload();
                    } else {
                        $("#modal").html("支付出错，请重试").dialog({autoOpen: true, modal: true, width: 400});
                    }
                });
            }
        });
    });
</script>
    <script type="text/javascript">
        $('#dates').change(function(){
            window.location.replace('<?php echo $this->createUrl('',array('status'=>$status)) ?>'  + '&date=' + this.value);
        })

    </script>
    </div>