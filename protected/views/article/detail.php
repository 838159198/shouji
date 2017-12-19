<div class="hangyepic">
</div>

<div class="mainpage">
    <?php $this->renderPartial('/layouts/newleft'); ?>

    <div class="mainleft">
        <div class="maintit titwid1 hangyetext">
            <span>当前位置：<a href="/"> 首页 </a>> <a href="/article/<?php echo $data->category->pathname;?>"> <?php echo $data->category->name;?></a></span><span class="more"><a href=""></a></span>
        </div>
        <div class="article">

                <div class='article_title'><?php echo Common::substr($data['title'],30); ?></div>
                <div class='article_info'>小编：速推&nbsp;&nbsp;&nbsp;发布时间：<?php echo date("Y-m-d H:i:s",$data["createtime"]); ?></div>
                <div class='hybottom'></div>

                <div class='article_content'><?php echo $data['content']; ?></div>
                <div class='article_page'>
                    <strong>上一篇：</strong>
                    <?php if(empty($datap)) {echo "没有了";} else { ?>
                    <a href="/article/<?php echo $datap[0]['id']; ?>"> <?php echo $datap[0]['title']; ?> </a>
                    <?php } ?>
                    </br>
                    <strong>下一篇：</strong>
                    <?php if(empty($datan)) {echo "没有了";} else { ?>
                        <a href="/article/<?php echo $datan[0]['id']; ?>"> <?php echo $datan[0]['title']; ?> </a>
                    <?php } ?>
                </div>
        </div>

    </div>

</div>
