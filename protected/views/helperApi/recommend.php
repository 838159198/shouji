<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta charset="UTF-8">
    <title>app</title>
    <style type="text/css">
        html,body{overflow-x: hidden; overflow-y: hidden;background: #ffffff;}
        body{width: 990px;height: 570px;margin: 0px; padding: 0px;}
        .container{margin: 10px;height: 540px;width: 960px;overflow: hidden;}
        .myButton{float:left;-moz-box-shadow:inset 0px 1px 0px 0px #bbdaf7;-webkit-box-shadow:inset 0px 1px 0px 0px #bbdaf7;box-shadow:inset 0px 1px 0px 0px #bbdaf7;background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #79bbff), color-stop(1, #378de5));background:-moz-linear-gradient(top, #79bbff 5%, #378de5 100%);background:-webkit-linear-gradient(top, #79bbff 5%, #378de5 100%);background:-o-linear-gradient(top, #79bbff 5%, #378de5 100%);background:-ms-linear-gradient(top, #79bbff 5%, #378de5 100%);background:linear-gradient(to bottom, #79bbff 5%, #378de5 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#79bbff', endColorstr='#378de5',GradientType=0);background-color:#79bbff; -moz-border-radius:6px; -webkit-border-radius:6px;     border-radius:6px;border:1px solid #84bbf3;display:inline-block;cursor:pointer;color:#ffffff;font-family:Arial;font-size:13px;font-weight:bold;padding:3px 4px;text-decoration:none;text-shadow:0px 1px 0px #528ecc;	}
        .myButton:hover{background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #378de5), color-stop(1, #79bbff));background:-moz-linear-gradient(top, #378de5 5%, #79bbff 100%);background:-webkit-linear-gradient(top, #378de5 5%, #79bbff 100%);		background:-o-linear-gradient(top, #378de5 5%, #79bbff 100%);		background:-ms-linear-gradient(top, #378de5 5%, #79bbff 100%);		background:linear-gradient(to bottom, #378de5 5%, #79bbff 100%);		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#378de5', endColorstr='#79bbff',GradientType=0);		background-color:#378de5;	}
        .myButton:active{position:relative;top:1px;}
        .myButtone{
            background-color: #eaf0fd;border: 1px solid #8da4d2; padding: 0.01px 25px;text-shadow: 0px 1px 0px #bab1b1;  }
        .myButtone:hover{background-color:#d6e1f8;}
        .myButtons{ float: left;  width:80px; height:23px; border: 1px solid #c4c1c0; background-color: #f8f3f1; -moz-border-radius: 5px;/* Gecko browsers */ -webkit-border-radius: 5px;   /* Webkit browsers */ border-radius:5px;/* W3C syntax */ }
        .myButtons:hover{background-color: #6899fe;color: white;cursor: pointer;border: 1px solid #6f6c6c;}
        button:focus {  outline:none; }
        .m_appgroup{height: 163px;width: 990px;overflow: hidden;}

        .m_appgroup ul{width: 960px;height: 150px;padding: 0px;margin:0;list-style:none;padding:0;margin-top: 0px; border-left:1px solid #cccccc;overflow: hidden;}
        .m_appgroup ul li{list-style: none;list-style-type: none;width:191.5px;float: left;}
        .m_appgroup ul li .appgroup_tit{border: solid 1px #cccccc;background:#f5f5f5;border-left:none; text-indent: 35px;font-size: 14px;height: 30px;line-height: 30px;font-weight: bold;color: #444444;}
        .m_appgroup ul li .appgroup_tit img{width: 20px; height: 20px;float: right;margin-top: 4px;margin-right: 16px;}
        .m_appgroup ul li .appgroup_con{border: solid 1px #cccccc;border-left:none;border-top: none;padding-left: 35px;font-size: 12px;line-height: 30px;height: 100px;color: #666666;}
        /*.m_appgroup ul li:last-child .appgroup_con{border: solid 1px #cccccc;border-top: none;width: 163px;}*/

        .m_applist{height: 328px;scrollbar-3dlight-color:#ffffff; /*- 最外左 D4D0C8-*/
            scrollbar-highlight-color:#fff; /*- 左二 -*/
            scrollbar-face-color:#E4E4E4; /*- 面子 -*/
            scrollbar-arrow-color:#666; /*- 箭头 -*/
            scrollbar-shadow-color:#ffffff; /*- 右二 808080-*/
            scrollbar-darkshadow-color:#ffffff; /*- 右一 D7DCE0-*/
            scrollbar-base-color:#D7DCE0; /*- 基色 -*/
            scrollbar-track-color:#ffffff;/*- 滑道 -*/}
        .m_applist ul{list-style: none;list-style-type: none;width: 960px;height: 328px;padding: 0px;margin: 0px;border: solid 1px #cbddf0;overflow-y:auto}
        .m_applist ul li{width:118px;padding-left:10px; padding-top:15px;/*padding:13px;*/ margin-bottom:10px;text-align:center;float: left; overflow: hidden; }
        .m_applist ul li .qian{ width: 15px; height: 15px; background: url("/images/icon_money.png") 0 0 no-repeat; position: absolute;top:49px;left: 0; z-index: 999;}
        .m_applist ul li .img01{width: 64px; height: 64px; margin:0 auto; }
        .m_applist ul li .img01 img{width: 64px; height: 64px; }
        .m_applist ul li span{ height: 44px;font-size: 12px;width:116px;float: left;text-align: center;line-height: 20px;margin-left: 20px;}
        .m_applist ul li span nobr{float:left;width:80px;height:20px;text-align: center;line-height: 20px;overflow:hidden;text-overflow:ellipsis}
        .m_title{width: 1160px;height: 30px;font-size: 16px;font-weight: bold;line-height: 30px;letter-spacing: 0.1em;}
        .m_title img{width: 18px; height: 18px;padding-top: -30px;margin-right: 2px;}

        .group_applist_box{width: 965px;overflow: hidden;position: relative;}
        .group_applist{width: 960px;overflow: hidden;display: block;background:#eaf4fe;border: solid 1px #b3daff;margin-top: 14px; height: 260px; overflow-y:auto }
        .group_arrow_icon{ position: absolute; width: 20px; height: 14px; background:url("/images/icon_arrow.png") 0 0 no-repeat;  z-index: 9999;}
        .group_arrow_0{  top: 0px; left: 86px; }
        .group_arrow_1{  top: 0px; left: 277px; }
        .group_arrow_2{  top: 0px; left: 469px; }
        .group_arrow_3{  top: 0px; left: 660px; }
        .group_arrow_4{  top: 0px; left: 851px; }
        .group_arrow_5{  top: 0px; left: 1042px; }
        .group_arrow_6{  top: 0px; left: 1233px; }
        .group_applist ul{padding: 0px;margin: 0px;}
        .group_applist ul li{list-style: none;list-style-type: none;width:118px;padding-left:10px; padding-top:15px;/*padding:13px;*/ margin-bottom:10px;text-align:center;float: left;overflow: hidden;}
        .group_applist ul li .qian{ width: 15px; height: 15px; background: url("/images/icon_money.png") 0 0 no-repeat; position: absolute;left:0;top: 49px; z-index: 999;}
        .group_applist ul li .img01{width: 64px; height: 64px;margin:0 auto; /*position: relative;*/}
        .group_applist ul li .img01 img{width: 64px; height: 64px; }

        .group_applist ul li span{ height: 44px;font-size: 12px;width:116px;float: left;text-align: center;line-height: 20px;margin-left: 20px;}
        .group_applist ul li span nobr{float:left;width:80px;height:20px;text-align: center;line-height: 20px;overflow:hidden;text-overflow:ellipsis}

    </style>
    <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.7.2/jquery.min.js" ></script>
    <script>
        function changeButton(id) {
            $("button[name='"+id+"']").text("安装");
        }
        function changeButtonOut(id,valuea) {
            $("button[name='"+id+"']").text(valuea);
        }
        function abc(id) {
            var id = id;
            var ida = "#group_content_"+id;
            for (var i=0;i<6;i++){
                if(i==id){
                    $(ida).toggle();
                }else{
                    $("#group_content_"+i).hide();
                }
            }
            //$("#group_content_1").hidden();
        }
    </script>
</head>
<body scroll="no">
<div class="container">
    <div class="m_appgroup">
        <div class="m_title" style="color: #444444"><img style="float: left;"  src="http://www.sutuiapp.com/images/pc/ico/icong.png" /><span style="float: left;margin-top: -4px;">套餐组合</span></div>
        <ul>
            <?php $group_t_id=0;foreach ($groupData AS $row):?>
            <li>
                <div class="appgroup_tit"><img src="http://www.sutuiapp.com/images/pc/ico/icongt.png" onclick="abc(<?php echo $group_t_id;?>)"/><?php echo $row['name'];?> </div>
                <div class="appgroup_con">
                    评级<?php echo Common::getStarSymbol($row['star'])?><br>
                    共<?php echo $row['num'];?>款<br>
                    <button id="appgroup<?php echo $row['id']?>" class="myButton myButtone" type="button"><?php echo Common::convertFileSize($row['size'],1);?></button>
                </div>
            </li>
            <?php $group_t_id++;endforeach;?>
        </ul>
    </div>
    <div class="group_applist_box">
    <?php $group_id=0; foreach ($groupData AS $row):?>
        <div class="group_applist" style="display: none;" id="group_content_<?php echo $group_id?>">
            <div class="group_arrow_icon group_arrow_<?php echo $group_id?>"></div>
            <ul>
            <?php foreach ($row['apkData'] AS $subRow):?>
                <li>

                    <?php if($subRow['app_sutui']==1){?>
                        <div class="img01"><img src="<?php echo $subRow['app_previewimage'];?>"/></div>
                        <span><nobr><font color="#ff0000"><?php echo CHtml::encode($subRow['app_name']);?></font></nobr><button onmousemove="changeButton('button_a_<?php echo $subRow['app_id']?>')" onmouseout="changeButtonOut('button_a_<?php echo $subRow['app_id']?>','<?php echo Common::convertFileSize($subRow['app_size'],1);?>')" name="<?php echo "button_a_".$subRow['app_id']?>" id="<?php echo CHtml::encode($subRow['app_name']);?>||<?php echo $subRow['app_id'];?>||<?php echo $subRow['app_version'];?>||<?php echo $subRow['app_file'];?>||<?php echo $subRow['app_sutui'];?>||<?php echo $subRow['app_sutui_id'];?>" class="myButtons" type="button"><?php echo Common::convertFileSize($subRow['app_size'],1);?></button></span>
                    <?php }else{?>
                        <div class="img01"><img src="<?php echo $subRow['app_previewimage'];?>"/></div>
                        <span><nobr><?php echo CHtml::encode($subRow['app_name']);?></nobr><button onmousemove="changeButton('button_a_<?php echo $subRow['app_id']?>')" onmouseout="changeButtonOut('button_a_<?php echo $subRow['app_id']?>','<?php echo Common::convertFileSize($subRow['app_size'],1);?>')" name="<?php echo "button_a_".$subRow['app_id']?>" id="<?php echo CHtml::encode($subRow['app_name']);?>||<?php echo $subRow['app_id'];?>||<?php echo $subRow['app_version'];?>||<?php echo $subRow['app_file'];?>||<?php echo $subRow['app_sutui'];?>||0" class="myButtons" type="button"><?php echo Common::convertFileSize($subRow['app_size'],1);?></button></span>
                    <?php }?>

                </li>
            <?php endforeach;?>
            </ul>
        </div>
    <?php $group_id++; endforeach;?>
    </div>

    <div class="m_applist">
        <div class="m_title" style="color: #2176cd;margin-top: 17px;"><img style="float: left;" src="http://www.sutuiapp.com/images/pc/ico/iconl.png" /><span style="float: left;margin-top: -4px;">应用列表</span></div>
        <ul>
            <?php foreach ($data AS $row):?>
            <li>

                <?php if($row['app_sutui']==1){?>
                    <div class="img01"><img src="<?php echo $row['app_previewimage'];?>"/></div>
                    <span><nobr><font color="#ff0000"><?php echo CHtml::encode($row['app_name']);?></font></nobr><button  onmousemove="changeButton('button_b<?php echo $row['app_id']?>')" onmouseout="changeButtonOut('button_b<?php echo $row['app_id']?>','<?php echo Common::convertFileSize($row['app_size'],1);?>')" name="<?php echo "button_b".$row['app_id']?>" id="<?php echo CHtml::encode($row['app_name']);?>||<?php echo $row['app_id'];?>||<?php echo $row['app_version'];?>||<?php echo $row['app_file'];?>||<?php echo $row['app_sutui'];?>||<?php echo $row['app_sutui_id'];?>" class="myButtons" type="button"><?php echo Common::convertFileSize($row['app_size'],1);?></button></span>
                <?php }else{?>
                    <div class="img01"><img src="<?php echo $row['app_previewimage'];?>"/></div>
                    <span><nobr><?php echo CHtml::encode($row['app_name']);?></nobr><button onmousemove="changeButton('button_b<?php echo $row['app_id']?>')" onmouseout="changeButtonOut('button_b<?php echo $row['app_id']?>','<?php echo Common::convertFileSize($row['app_size'],1);?>')" name="button_b<?php echo $row['app_id']?>" id="<?php echo CHtml::encode($row['app_name']);?>||<?php echo $row['app_id'];?>||<?php echo $row['app_version'];?>||<?php echo $row['app_file'];?>||<?php echo $row['app_sutui'];?>||0" class="myButtons" type="button"><?php echo Common::convertFileSize($row['app_size'],1);?></button></span>
                <?php }?>

            </li>
            <?php endforeach;?>
        </ul>
    </div>
</div>
</body>
</html>

