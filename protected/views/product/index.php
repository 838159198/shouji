<style type="text/css">
    .pagination {
        margin-left: 12px;
    }
</style>
<div class="productpic">
    <img src="/css/site/images/appbg.png"/>
</div>

<div class="mainpage">
    <?php $this->renderPartial('/layouts/newleft'); ?>
    <div class="mainleft">
        <div class="maintit titwid1">
            <span class="text">&nbsp;&nbsp;APP列表</span><span class="more"><a href=""></a></span>
        </div>
        <div class="appslist">
            <ul class="apptextst">
                <li>图标</li>
                <li>APP名称</li>
                <li>单价</li>
                <li class='app4of4'>要求</li>
            </ul>
            <hr>
            <?php foreach($data as $row):?>
                <?php echo "
                        <ul class='appdatast'>
                           <li><img src='{$row['pic']}'/> </li>
                           <li class='app2of4'>{$row['name']}</li>
                            <li class='app3of4'>{$row['officialprice']}元</li>
                            <li class='app4of4'>{$row['activate_instructions']}</li>
                        </ul>
                        ";?>
            <?php endforeach;?>
        </div>
        <?php
        $this->widget('CLinkPager',array(
                'header'=>'',
                'cssFile'=>false,
                'firstPageLabel' => '首页',
                'lastPageLabel' => '末页',
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
                'pages' => $pages,
                'maxButtonCount'=>8,
                'htmlOptions'=>array("class"=>"pagination pagination-lg","style"=>"margin-left:12px"),
            )
        );
        ?>
    </div>

</div>

