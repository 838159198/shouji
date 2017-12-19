<!--<div class="page-header app_head">
    <h1 class="text-center text-primary"><?php /*echo $data['username'];*/?> <small>用户信息</small></h1>
</div>-->
<script type="text/javascript">
    $(function () {
        var id=<?php echo $this->uid;?>;

        var url='/member/default/info';
        $.get(url + "?mid=" + id, function (data) {
            var data = (new Function("return " + data))();
            if (data) {
                var modal = $(".tabag");
                modal.attr("title", ""+data.title);
                modal.html(data.content+"<br><span>"+data.jointime+"</span>");
                if(ismobile()==true)
                {
                    modal.dialog({modal: true, width: 250,height:250, buttons: {
                        "关闭": function () {
                            $(this).dialog("close");
                            location.reload();
                        }
                    }});
                }
                else
                {
                    modal.dialog({modal: true, width: 500,height:500, buttons: {
                        "关闭": function () {
                            $(this).dialog("close");
                            location.reload();
                        }
                    }});
                }

            }
        });

    });
function ismobile()
{
    var ua = navigator.userAgent;
    var ipad = ua.match(/(iPad).*OS\s([\d_]+)/),
        isIphone = !ipad && ua.match(/(iPhone\sOS)\s([\d_]+)/),
        isAndroid = ua.match(/(Android)\s+([\d.]+)/),
        isMobile = isIphone || isAndroid;
    if(isMobile) {
        return true;
    }else {
        return false;
    }
}

</script>
<style type="text/css">
    .tabag{background-color:#b6bec5; }
    .ui-dialog-title{text-align: center;}
    #ui-id-1{line-height: 25px;}
    #ui-id-1 span{float: right;padding-right: 20px;padding-top: 10px;font-weight:bold;}
    .ui-dialog-titlebar{margin-bottom: 30px;}
</style>
<?php $member=Member::model()->findByPk($this->uid);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <div class="row">
                <ol class="breadcrumb pull-left">
                    当前位置：<li><a href="/member">管理主页</a></li>
                    <li class="active">历史收益 <?php //echo $data['dates'] ?></li>
                </ol>
            </div>
           <!--  <table class="table table-bordered">
                <tr class="tabag">
                    <th>类别</th>
                    <th>激活量</th>
                    <th>单价</th>
                    <th>收入</th>
                </tr> -->
                <?php
                // $tr = '';

                // $membera=Member::model()->getById($this->uid);
                // foreach ($adList as $k => $v) {
                //     if(!empty($data[$k]))
                //     {
                //         if($membera["agent"]!=69)
                //         {
                //             $prinfo = Product::model()->find('pathname=:pathname',array(':pathname'=>$k));
                //             $tr .= '<tr>';
                //             $tr .= '<td>' . $v . '</td>';
                //             $tr .= '<td>'.$data[$k]/$prinfo["quote"].'</td>';
                //             $tr .= '<td>'.$prinfo["quote"].'</td>';
                //             $tr .= '<td>' . $data[$k] . '元</td>';
                //             $tr .= '</tr>';
                //         }
                //         else
                //         {
                //             //代理商69子用户特殊单价
                //             if($k=="2345sjzs")
                //             {
                //                 $tr .= '<tr>';
                //                 $tr .= '<td>' . $v . '</td>';
                //                 $tr .= '<td>'.$data[$k]/2.5.'</td>';
                //                 $tr .= '<td>2.50</td>';
                //                 $tr .= '<td>' . $data[$k] . '元</td>';
                //                 $tr .= '</tr>';
                //             }
                //             elseif($k=="yyzx")
                //             {
                //                 $tr .= '<tr>';
                //                 $tr .= '<td>' . $v . '</td>';
                //                 $tr .= '<td>'.$data[$k]/2.5.'</td>';
                //                 $tr .= '<td>2.50</td>';
                //                 $tr .= '<td>' . $data[$k] . '元</td>';
                //                 $tr .= '</tr>';
                //             }
                //             else
                //             {
                //                 $prinfo = Product::model()->find('pathname=:pathname',array(':pathname'=>$k));
                //                 $tr .= '<tr>';
                //                 $tr .= '<td>' . $v . '</td>';
                //                 $tr .= '<td>'.$data[$k]/$prinfo["quote"].'</td>';
                //                 $tr .= '<td>'.$prinfo["quote"].'</td>';
                //                 $tr .= '<td>' . $data[$k] . '元</td>';
                //                 $tr .= '</tr>';
                //             }
                //         }

                //     }


                // }
                // echo $tr;
                ?>
           <!--      <tr>
                    <td><strong>合计</strong></td>
                    <td colspan="3"><?php //echo $data['amount'] ?>元</td>
                </tr>
            </table> -->


            <!-- 七天历史收益，业务流id=3146 ，2017-10-27 start-->
            <table class="table table-bordered">
            <?php
                    $adList = Product::model()->getKeywordList();//全部业务
                    $sql="select date from `app_system_log` where type='UPLOAD' order by id desc limit 1 ";
                    $result=yii::app()->db->createCommand($sql)->queryAll();
                    $date=$result[0]['date'];
                    for($i=0;$i<7;$i++){//七天
                        $array[]=date('Y-m-d', strtotime('-'.$i.' day', strtotime($date)));
                    }
                    foreach ($array as $key => $value) {
                        //判断业务隐藏开关
                        // $datetime=SystemLog::getLogDate($value);
                        $model=SystemLog::model()->findAll("type=:type and date =:date and status=1 and is_show=0",
                            array(":type"=>SystemLog::TYPE_UPLOAD,":date"=>$value));
                        if($model){
                            foreach($model as $v){
                                $key=strtolower($v->target);
                                unset($adList[$key]);
                            }
                        }
                        $day=date('Y-m-d',strtotime('-1 day',strtotime($value)));
                        $data1[] = MemberIncome::getDataProviderByDate($this->uid, $day, $member->scale, $adList);
                    }
                    
                    foreach ($data1 as $key => $value) {
                        echo "<tr>";
                        echo "<td>".$value['dates']."</td>";
                        echo "<td>".$value['amount']."元</td>";
                        echo "</tr>";
                    }


            ?>

            </table>
<!-- end 2017-10-27 -->
            <?php  if (isset($data1)&& isset($data2) && count($data1)>0 && count($data2)>0): ?>
                <a href="/fotoplace/index" target="_blank"><div style="width: 100%;height: 200px;background: url(/css/fotoplace/images/780_back.png) repeat"><img style="margin: 0 auto;display: block;" src="/css/fotoplace/images/780.jpg"></div></a>
            <?php endif;?>
            <!--<a href="http://www.sutuiapp.com/2017year?user" target="_blank"><img src="/images/2017year.jpg"/></a>-->

<!--            <a href="/t/<?/*=$uid+123456*/?>?utm_source=2" title="Rom大师评选" target="_blank">
                <div style="margin-top: 50px;background-color: #0b98df;">
                    <div style="background: url(/css/rom/images/banner1.jpg) no-repeat center top; height: 200px;margin: 0 auto">
                    </div>
                </div>
            </a>-->

        </div>
    </div>
</div>