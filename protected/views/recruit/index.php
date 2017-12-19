<style type="text/css">
    .jobst{
        margin: 0 auto; width: 1002px;
    }
    .rec{
        height: 40px;background: #515151;
        border-radius: 3px;
    }
    .nullst{width: 100px;height: 90px;}
    .recruit{
        width: 100%;
        height: 298px;
        background: url("/css/site/images/recruit-bg.jpg") 0 0 repeat-x;
    }
    .recruit-box{
        width:1002px;
        height: 298px;
        /*overflow: hidden;*/
        margin: 0 auto;
        background: url("/css/site/images/recruit.jpg") 0 0 no-repeat;
    }
   .rec li {
       height: 30px;
       float: left;
       color: white;
       background: #515151;
       line-height: 30px;
       text-align: center;
       margin-left: 20px;
       margin-top: 5px;
       padding-left: 5px;
       padding-right: 5px;
       border-radius: 5px;
   }
    .rec li:hover{
        background: #999999;
    }
    .rec-list{
        width: 1002px;
        margin: 0 auto;
        background: #616161;
        border-radius: 3px;
    }
    .rec-list li{
        /*width: 120px;*/
        height: 30px;
        background: #616161;
        display: inline-block;
        padding: 0 5px;
        margin-left: 50px;
        margin-top: 5px;
        margin-bottom: 5px;
        border-radius: 5px;
        line-height: 30px;
        color: white;
        text-align: center;
    }
    .rec-list li:hover{
        background: #999999;
    }
    .detail{
        width: 982px;
        overflow: hidden;
        margin: 10px auto;
        border: 0.1px dashed grey;
        background: lightgrey;
        padding: 50px;
        -webkit-box-sizing:border-box;
        -moz-box-sizing:border-box;
        -o-box-sizing:border-box;
        -ma-box-sizing:border-box;
        box-sizing:border-box;
        border-radius: 10px;
    }
    .detail p{
        margin-top: 5px;
    }
    .span-nine .p{
        margin-left: 24px;
    }
    .span-div{
        width: 450px;
        white-space: normal;
        font-size: 14px;
        line-height: 30px;
    }
    .span9-1{
        letter-spacing:1px;
    }
    .span10,.span11{
        white-space: pre-wrap;
        white-space: -moz-pre-wrap;
        /*white-space: -pre-wrap; */
        white-space: -o-pre-wrap;
        word-wrap: break-word;
        margin-left: 20px;
        font-size: 14px;
        line-height:30px;
        letter-spacing:1px;
    }
    .detail strong{
        font-size: 14px;
        line-height:30px;
    }
    .detail span{
        margin-left: 10px;
        font-size: 14px;
        line-height:30px;
    }
    .detail1, .detail2{
        width: 50%;float: left;
        font-size: 14px;
        line-height: 30px;
    }
</style>
<script type="text/javascript">
   $(function () {
       $('.rec li').click(function () {
          var p = $(this).attr('classid');
          $(this).css('background','#999999').siblings().css('background','#515151');
           $('.detail').css('display','none');
          $.ajax({
               type:'post',
               dataType:"json",
               url :"/recruit/job",
               data:{
                   'classid':p
               },
               success:function (json) {
                       if ($('.rec-list').is(':empty')) {
                           $.each(json.val, function (index, data) {
                               var lib = "<li  id='" + data.id + "'>" + data.jobname + "</li>";
                               $('.rec-list').append(lib);
                           })
                       } else {
                           $('.rec-list >li').remove();
                           $.each(json.val, function (index, data) {
                               var lib = "<li id='" + data.id + "'>" + data.jobname + "</li>";
                               $('.rec-list').append(lib);
                           })
                       }
                   console.log(json);
               },
               error:function () {
               }
           })
       })

       // 显示数据
       $('.rec-list li').live('click',function () {
           $(this).css('background','#999999').siblings().css('background','#616161');
          var id = $(this).attr('id');

           $.ajax({
               type:'post',
               dataType:"json",
               url :"/recruit/resume",
               data:{
                   'id':id
               },
               success:function (data) {
                   if (data.val[0]) {
                       $(".detail").css('display', 'block');
                       $('.detail .span1').text(data.val[0]['classname']);
                       $('.detail .span2').text(data.val[0]['jobname']);
                       if (data.val[0]['age'] == 1) {
                           $('.detail .span3').text('30岁以下');
                       } else if (data.val[0]['age'] == 2){
                           $('.detail .span3').text('30岁以上');
                       }else {
                           $('.detail .span3').text('不限');
                       }
                       if (data.val[0]['sex'] == 1) {
                           $('.detail .span4').text('男');
                       } else if((data.val[0]['sex'] == 2)){
                           $('.detail .span4').text('女');
                       }else {
                           $('.detail .span4').text('不限');
                       }
                       if (data.val[0]['age'] == 1) {
                           $('.detail .span5').text('1人');
                       } else if (data.val[0]['age'] == 2) {
                           $('.detail .span5').text('2人');
                       } else {
                           $('.detail .span5').text('若干');
                       }
                       if (data.val[0]['education'] == 1) {
                           $('.detail .span6').text('不限');
                       } else if (data.val[0]['education'] == 2) {
                           $('.detail .span6').text('大专及以上');
                       } else {
                           $('.detail .span6').text('本科及以上');
                       }

                       if (data.val[0]['experience'] == 1) {
                           $('.detail .span7').text('不限,可接受应届毕业生');
                       } else if (data.val[0]['experience'] == 2) {
                           $('.detail .span7').text('一年以下');
                       } else if (data.val[0]['experience'] == 3) {
                           $('.detail .span7').text('一年到两年');
                       } else {
                           $('.detail .span7').text('两年到三年');
                       }
                       if (data.val[0]['salary'] == 1) {
                           $('.detail .span8').text('2k-4k');
                       } else if (data.val[0]['salary'] == 2) {
                           $('.detail .span8').text('4k-6k');
                       } else if (data.val[0]['salary'] == 3) {
                           $('.detail .span8').text('6k-8k');
                       } else {
                           $('.detail .span8').text('面议');
                       }
                       var arr = {};
                       var str;
                       arr[1] = '六险一金';
                       arr[2] = '带薪年假';
                       arr[3] = '周末双休';
                       arr[4] = '年底双薪';
                       arr[5] = '全勤奖';
                       arr[6] = '员工旅游';
                       // 职位两点
                       var paydata = $.parseJSON(data.val[0].pay);
                       if (paydata['content']) {
                           $('.span9-1').parent().css('display', 'block');
                           $('.span9-1').text(paydata['content']);
                       } else {
                           $('.span9-1').parent().css('display', 'none');
                       }
                       $.each(paydata, function (index, dom) {
                           str = str + arr[dom] + ' ';

                       })
                       str = str.replace(/undefined/g, ' ')
                       $('.span9').text(str);
                       var strduty = data.val[0]['duty'];
                       var pp = strduty.split('d:');

                       $('.detail .span10').text($.parseJSON(data.val[0]['duty']));
                       $('.detail .span11').text($.parseJSON(data.val[0]['job_require']));
                       $('.detail .span12').text($.parseJSON(data.val[0]['working_place']));

                       $('.detail .span13').text(data.val[0]['tel_num']);
                       $('.detail .span14').text(data.val[0]['mail']);
                   }else {
                       $(".detail").css('display', 'none');
                   }
               },
               error:function () {
                   console.log('1111');
               }
           })
       })
   })

    
</script>
<div class="recruit">
    <div class="recruit-box"></div>
</div>
<div class="jobst">
    <div class="aboutus-title">
        <h3>企业招聘</h3>
    </div>
    <div class="aboutus-lmjs-txt">
        <p><strong>公司全称：</strong> 大连晟平网络科技有限公司</p>
        <p><strong>公司行业：</strong> 计算机/互联网/通信/电子-计算机软件</p>
        <p><strong>公司规模：</strong> 10-50职员</p>
        <p><strong>公司性质：</strong> 民营公司</p>
    </div>
    <ul class="rec">
        <?php foreach($data as $vt):?>
            <?php echo"
        <li classid='{$vt['classid']}'>{$vt['classname']}</li>
        " ?>
        <?php endforeach;?>
    </ul>
    <?php echo "
        <ul class='rec-list'></ul>"
    ?>

    <div class="detail" style="display: none">
        <div class="detail1">
            <p><strong>类&nbsp;&nbsp;别:</strong><span class="span1"></span></p>
            <p><strong>年&nbsp;&nbsp;龄:</strong><span class="span3"></span></p>
            <p><strong>人&nbsp;&nbsp;数:</strong><span class="span5"></span></p>
            <p><strong>工作经验:</strong><span class="span7"></span></p>
        </div>
        <div class="detail2">
            <p><strong>职&nbsp;&nbsp;位:</strong><span class="span2"></span></p>
            <p><strong>性&nbsp;&nbsp;别:</strong><span class="span4"></span></p>
            <p><strong>学&nbsp;&nbsp;历:</strong><span class="span6"></span></p>
            <p><strong>薪&nbsp;&nbsp;资:</strong><span class="span8"></span></p>
        </div>
        <div class="span-nine"><p><strong>职位亮点:</strong><span class="span9"></span></p>
            <p class="p" style="display: none"><strong>其他:</strong><span class="span9-1"></span></p></div>
        <p><strong>岗位职责:</strong><span class="span-div"><pre class="span10"></pre></span></p>
        <p><strong>任职要求:</strong><span class="span-div"><pre class="span11"></pre></span></span></p>
        <p><strong>工作地点:</strong><span class="span12"></span></p>
        <p><strong>联系电话:</strong><span class="span13"></span></p>
        <p><strong>邮&nbsp;&nbsp;箱:</strong><span class="span14"></span></p>
    </div>
</div>

<div class="nullst">&nbsp;</div>