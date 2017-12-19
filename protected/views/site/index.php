<script  type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/site/js/jquery.flexslider-min.js"></script>
<script  type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/site/js/jump.js"></script>
<script type="text/javascript">
    $(function(){
        $(".apppro img").each(function(k,img){
            new JumpObj(img,20);
            $(img).hover(function(){this.parentNode.parentNode.className="apppro"});
        });
        $('.flexslider').flexslider({
            animation: "fade",
            pauseOnAction: false,
            slideshowSpeed: 3000
        });
    });
</script>

<div class="flexslider">
    <ul class="slides">

        <!--<li style="background:url('/css/site/images/img5.png') 50% 0 no-repeat;background-color:#62568c;" onClick="window.open('http://www.sutuiapp.com/hd/zzysjs/3')"></li>-->

        <!--<li style="background:url('/css/site/images/img5.jpg') 50% 0 no-repeat;background-color:#820405;" onClick="window.open('http://www.sutuiapp.com/2017year')"></li>-->
        <li style="background:url('/css/site/images/img2.png') 50% 0 no-repeat;background-color:#c1eaf9;"></li>
        <li style="background:url('/css/site/images/img1.png') 50% 0 no-repeat; background-color:#473688;"></li>
        <li style="background:url('/css/site/images/img3.png') 50% 0 no-repeat;background-color:#fdce5a;"></li>
    </ul>
</div>

<div class="liuchst">
    <div class="liuchmain">
        <img src="/css/site/images/jiaru.png" usemap="#planetmap"/>
        <map name="planetmap" id="planetmap">
            <area shape="rect" coords="165,40,310,0" href ="/reg" target="_blank" />
            <area shape="rect" coords="330,40,475,0" href ="/product" target="_blank" />
            <area shape="rect" coords="497,40,643,0" href ="/question" target="_blank" />
            <area shape="rect" coords="663,40,810,0" href ="/question" target="_blank" />
            <area shape="rect" coords="833,40,978,0" href ="/question" target="_blank" />
        </map>
    </div>
</div>

<div class="mainpage">

    <?php $this->renderPartial('/layouts/newleft'); ?>

    <div class="mainleft">
        <div class="maintit titwid1">
            <span class="text">>> APP推广列表</span><span class="more"><a href="/product">更多</a></span>
        </div>
        <hr>
        <div class="applist">
            <?php foreach($data as $row):?>
                <?php echo "
                            <ul class='apppro'>
                                <li class='app1of3'><img src='{$row['pic']}''/></li>
                                <li class='app2of3'>{$row['name']}</li>
                                <li class='app3of3'>{$row['price']}元/个</li>
                            </ul>
                            ";
            ?><?php endforeach;?>
        </div>

        <div class="maintit titwid1">
            <span class="text">>> 合作伙伴</span><span class="more"></span>
        </div>
        <hr>
        <table class="tabst" cellpadding="100">
            <tr>
                <td><img src="/css/site/images/hezuo01.gif"/></td>
                <td><img src="/css/site/images/hezuo02.gif"/></td>
                <td><img src="/css/site/images/hezuo03.gif"/></td>
                <td><img src="/css/site/images/hezuo04.gif"/></td>
                <td><img src="/css/site/images/hezuo05.gif"/></td>
            </tr>
        </table>
    </div>

</div>
