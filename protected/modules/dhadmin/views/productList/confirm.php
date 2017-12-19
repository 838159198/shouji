<div class="page-header app_head">
    <h1 class="text-center text-primary">业务对外展示</h1>
</div>
<?php


    $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'effectivepolicy-grid',
            'dataProvider'=>$data1,
            'emptyText'=>'没有找到数据.',
            'nullDisplay'=>'',
            'columns'=>array(
                array(
                    'header'=>'业务名',
                    'name'=>'pid',
                    'value'=>'ProductList::getname($data["pid"])',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'分组',
                    'name'=>'agent',
                    // 'value'=>'$data["mac"]',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                	'header'=>'业务包名',
                	'name'=>'pakname',
                	'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                	'header'=>'渠道号',
                	'name'=>'pakid',
                	'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                	'header'=>'上传时间',
                	'name'=>'createtime',
                	'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                	'header'=>'版本号',
                	'name'=>'version',
                	'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                	'header'=>'md5值',
                	'name'=>'sign',
                	'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                	'header'=>'操作',
                	'value'=>'ProductList::handle($data["id"])',
                	'type'=>'raw',
                	'htmlOptions'=>array('style'=>'text-align:center')
                ),
               
                
            ),
        ));


?>
<script>
function handle(id){
	var a=confirm('确认显示该业务包？');
	if(a){
		$.post(
			'/dhadmin/productList/confirm',
			{id:id},
			function(data){
				if(data==1){
					location.reload();
				}
			}

		)
	}
}
</script>



<div class="page-header app_head">
    <h1 class="text-center text-primary">业务对外展示记录</h1>
</div>
<?php


    $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'admin-grid',
            'dataProvider'=>$data2,
            'emptyText'=>'没有找到数据.',
            'nullDisplay'=>'',
            'columns'=>array(
                array(
                    'header'=>'业务名',
                    'name'=>'pid',
                    'value'=>'ProductList::getname($data["pid"])',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                    'header'=>'分组',
                    'name'=>'agent',
                    // 'value'=>'$data["mac"]',
                    'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                	'header'=>'业务包名',
                	'name'=>'pakname',
                	'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                	'header'=>'渠道号',
                	'name'=>'pakid',
                	'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                	'header'=>'版本号',
                	'name'=>'version',
                	'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                	'header'=>'md5值',
                	'name'=>'sign',
                	'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                	'header'=>'展示时间',
                	'name'=>'createtime',
                	'htmlOptions'=>array('style'=>'text-align:center')
                ),
                array(
                	'header'=>'操作人',
                	'value'=>'ProductList::manage($data["mid"])',
                	// 'type'=>'raw',
                	'htmlOptions'=>array('style'=>'text-align:center')
                ),

               
                
            ),
        ));


?>