<style type="text/css">
    #btn_status{width: 90px;height: 25px;background-color: #24a5ef;border-radius: 5px;line-height: 25px;display: block;float: right;margin-right: 10px;color: #ffffff;text-align: center}
</style>
<div class="hangyepic">
</div>
<div class="mainpage">
    <?php $this->renderPartial('/layouts/newleft'); ?>

    <div class="mainleft">
        <div class="maintit titwid1"  style="background-color: #ff2b10;height: 40px;line-height: 40px">
            <span class="text">&nbsp;&nbsp;周周赢手机 天天高收益</span><span class="more"></span>
        </div>
        <div class="hangyelist">
            <div class="nonehei">&nbsp;</div>

            <?php foreach($data as $row):?>
                <?php echo "
                    <ul>
                        <li class='hyleftst'></li>
                        <li>
                            <a href='/hd/zzysjs/{$row->periods}' target='_blank'><img src='{$row->p->pic}' style='width: 80px;height: 80px;margin-top: 10px'/></a>
                            <ul>
                                <li class='hylist1of3'><a href='/hd/zzysjs/{$row->periods}' target='_blank'>".Common::substr($row['title'],28 )."</a></li>
                                <li>活动产品：{$row->p->name}<a href='/hd/zzysjs/{$row->periods}' target='_blank'><span>{$row->xstatus}</span></a></li>
                                <li>活动介绍：<span title='{$row->instruction}'>".Common::substr($row['instruction'],35 )."</span></li>
                                <li>活动时间：".Common::substr($row['starttime'],10)." 至 ".Common::substr($row['endtime'],10)."</li>
                            </ul>
                        </li>
                        <li class='hybottom'></li>
                    </ul>";?>
            <?php endforeach;?>
        </div>
<span ></span>
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


