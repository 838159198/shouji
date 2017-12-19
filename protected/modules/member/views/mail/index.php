<?php
/* @var $this MailController */
/* @var $data IDataProvider */

$this->breadcrumbs = array(
    '系统通知',
);

$this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $data,
        'columns' => array(
            array(
                'header' => '标题',
                'value' => 'Mail::model()->getTitleById($data->id)',
            ),
            array(
                'name' => 'status',
                'value' => '$data->status==0?"未读":"已读"'
            ),
            array(
                'name' => 'jointime',
                'type' => 'date'
            ),
            array(
                'class' => 'CButtonColumn',
                'header' => '管理',
                'headerHtmlOptions' => array('width' => '200'),
                'template' => '{view}&nbsp;&nbsp;{delete}',
                'buttons' => array(
                    'view' => array(
                        'label' => '查看详细',
                        'imageUrl' => '/css/images/mail-info.png',
                    ),
                    'delete' => array(
                        'label' => '删除',
                        'imageUrl' => '/css/images/delete.png',
                    )
                )
            )
        )
    )
);