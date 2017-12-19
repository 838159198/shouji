<div class="list-head">
    <div class="list-head-content">
        <h3>帮助文档</h3>
        <div class="list-head-description">帮您解答疑问，如有疑问请联系在线客服。</div>
    </div>
</div>
<div class="list-wrap">
    <div class="list-notice">
        <ul>

            <h2 class="first"><a href="javascript:void(0);">帮助文档</a></h2>
            <?php foreach($data as $row):?>
                <li>
                    <h3><?php echo date("m-d",$row['createtime'])?><span><?php echo date("Y",$row['createtime'])?></span></h3>
                    <dl>
                        <dt><a href="<?php echo $row['url'];?>" target="_blank"><?php echo CHtml::encode($row['title'])?></a></dt>
                        <dd><?php echo Helper::truncate_utf8_string($row['content'],100)?></dd>
                    </dl>
                </li>
            <?php endforeach;?>
        </ul>
    </div>
    <div class="list-page">
        <?php $this->widget('CLinkPager',array(
                'cssFile'=>false,
                'header'=>'',
                'firstPageLabel' => '首页',
                'lastPageLabel' => '末页',
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
                'pages' => $pages,
                'maxButtonCount'=>8,
                'htmlOptions'=>array("id"=>"xtlistpage",'class'=>false)
            )
        );?>
    </div>
</div>