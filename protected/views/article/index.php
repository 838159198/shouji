<div class="hangyepic">
</div>

<div class="mainpage">
    <?php $this->renderPartial('/layouts/newleft'); ?>

    <div class="mainleft">
        <div class="maintit titwid1">
            <span class="text">&nbsp;&nbsp;文章中心</span><span class="more"></span>
        </div>
        <div class="hangyelist">
            <div class="nonehei">&nbsp;</div>

            <?php foreach($data as $row):?>
                <?php $dates=date("Y-m-d",$row["createtime"]); echo "
                    <ul>
                        <li class='hyleftst'></li>
                        <li>
                            <a href='{$row['url']}' target='_blank'><img src='{$row['image']}' /></a>
                            <ul>
                                <li class='hylist1of3'><a href='{$row['url']}' target='_blank'>".Common::substr($row['title'],28 )."</a></li>
                                <li class='hylist2of3'>".Common::substr($row['content'],63)."</li>
                                <li class='hylist3of3'>发布时间：{$dates}</li>
                            </ul>
                        </li>
                        <li class='hybottom'></li>
                    </ul>";?>
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


