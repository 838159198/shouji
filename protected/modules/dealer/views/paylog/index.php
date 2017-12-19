<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <div class="row">
                <ol class="breadcrumb pull-left">
                    当前位置：<li><a href="/dealer">管理主页</a></li>
                    <li class="active">付款记录</li>
                </ol>
            </div>
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $data,
                'columns' => array(
                    'sums',
                    array(
                        'name' => 'ask_time',
                        'value' => <<<VALUE
    date('Y-m-d',strtotime(\$data->ask_time))
VALUE
                    ),
                    array(
                        'name' => 'answer_time',
                        'value' => <<<VALUE
    \$data->answer_time=='0000-00-00 00:00:00'?'':date('Y-m-d',strtotime(\$data->answer_time))
VALUE
                    ),
                    array(
                        'name' => 'status',
                        'value' => <<<VALUE
    \$data->status==0?((date('Y-m')==date('Y-m',strtotime(\$data->ask_time)))?'等待支付':'申请成功'):'支付成功'
VALUE
                    )
                )
            ));?>


        </div>
    </div>
</div>

