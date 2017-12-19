<article class="weui-article">
    <h1><?php echo $data[0]->title;?></h1>
    <section>
        <section>
            <?php echo $data[0]->content;?>
        </section>
    </section>
    <h5>小编：速推   发布时间： <span><?php echo Weixin::time_tran($data[0]->lasttime);?></span></h5>
</article>