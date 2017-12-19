<div class="noticepic">
        <img src="/css/site/images/notice.png"/>
</div>

<div class="mainpage">
        <?php $this->renderPartial('/layouts/newleft'); ?>

        <div class="mainleft">
                <div class="maintit titwid1">
                        <span class="text">&nbsp;&nbsp;<?php echo $category['name'];?></span><span class="more"></span>
                </div>
                <div class="notelist">
                        <?php foreach($data as $row):?>
                        <?php $dates=date('Y-m-d H:i:s',$row['createtime']); echo "
                                <ul>
                                   <li class='note1of2'><a href='{$row['url']}' target='_blank'>".Common::substr($row['title'],33)."</a></li>
                                    <li class='note2of2'>{$dates}</li>
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
                        'maxButtonCount'=>5,
                        'htmlOptions'=>array("class"=>"pagination pagination-lg"),
                    )
                );
                ?>
        </div>

</div>

