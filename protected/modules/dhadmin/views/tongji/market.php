<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15
 * Time: 15:38
 */


?>


<style type="text/css">
    /*td a{ color: #333; text-decoration: none; }*/
    /*td a:hover{ color: #333; text-decoration: none; }*/
    .btn-info{margin-left: 10px}
</style>

<div class="page-header app_head">
    <h1 class="text-center text-primary">地推数据</h1>
</div>


<?php
echo CHtml::beginForm('market', 'get', array('class'=>'form-inline')),
CHtml::label('选择月份', 'm'),
CHtml::dropDownList('date',$date , Common::getDateMonthList()),
'用户名：<input class = "input-small" id="username" name="username" type="text" value="'.$username.'" >',
CHtml::submitButton('查询', array('class'=>'btn btn-info')),
CHtml::endForm();
?>
<?php

    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'serach-info-grid',
        'dataProvider'=>$dataProvider,
       // 'filter'=>$model,
        'emptyText'=>'没有找到数据.',
        'nullDisplay'=>'-',
        'columns'=>array(
            array(
                'name'=>'ID',
                'value'=>'$data["uid"]'
            ),
            array(
                'name'=>'用户名',
                'value'=>'$data["username"]'
            ),
            array(
                'name'=>'昨日收益',
                'value'=>'$data["y_income"]'
            ),
            array(
                'header'=>'本月收益',
                'value'=>'$data["m_income"]'
            ),
            array(
                'header'=>'余额',
                'value'=>'$data["surplus"]=="0"?"0.00":$data["surplus"]'
            ),
        )
    ));
?>
<div style="background: #337ab7;width: 150px;height: 30px;line-height: 30px;text-align: center;color: #fff;border-radius: 5px">
本月收益合计:<?php echo $sum;?></div>

