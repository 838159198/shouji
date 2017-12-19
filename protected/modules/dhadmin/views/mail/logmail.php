<a href="/dhadmin/mail/CreateMailToUidList"><<群发站内信</a>

    <h4 class="text-center">站内信历史记录</h4>


<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'agent-grid',
    // 'enablePagination' => false,
    // 'enableSorting' => false,
    // 'itemsCssClass' => Bs::TABLE,
    'itemsCssClass' =>'items',//分页表格的样式
    'dataProvider' => $dataProvider,
    // 'filter' => $model,
    'columns' => array(
        array('name' => 'id',
            'header'=>'站内信ID',
            // 'value' => 'DataInter::username($data->username)',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array('name' => 'send',
            'header'=>'发件人',
            'value'=>'MailContent::manage($data->id)',
            'htmlOptions'=>array('style'=>'text-align:center'),
            
        ),
        array('name' => 'title',
            'header'=>'标题',
            // 'value' => 'DataInter::hashrate($data->username,1,$data->date)',
            'htmlOptions'=>array('style'=>'text-align:left'),
        ),
        array('name' => 'numpeopel',
            'header'=>'发送人数',
            'value'=>'MailContent::total($data->id)',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array('name' => 'time',
            'header'=>'发送时间',
            'value'=>'MailContent::sendtime($data->id)',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array(
            'header'=>'浏览',
            'value'=>'"<a class=\'view\' title=\'查看\' href=\'/dhadmin/mail/look?id=$data->id&status=\'><img src=\'/images/view.png\' alt=\'查看\'></a>"',
            'type'=>'raw',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array(
            'header'=>'操作',
            'value'=>'MailContent::chehui($data->id,1)',
            // 'value'=>'"<a class=\'delete\' title=\'删除\' href=\'/manage/mail/del?id=$data->id\'><img src=\'/assets/1d0e9428/gridview/delete.png\' alt=\'删除\'></a>"',
            'type'=>'raw',
            // 'value'=>'',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
    ),
)); ?>