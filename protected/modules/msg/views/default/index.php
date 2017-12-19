<!--<div class="page-header app_head">
    <h1 class="text-center text-primary"><?php /*echo $data['username'];*/?> <small>用户信息</small></h1>
</div>-->
<script type="text/javascript">
    $(function () {
        var id=<?php echo $this->uid;?>;

        var url='/msg/default/info';
        $.get(url + "?mid=" + id, function (data) {
            var data = (new Function("return " + data))();
            if (data) {
                var modal = $(".tabag");
                modal.attr("title", ""+data.title);
                modal.html(data.content+"<br><span>"+data.jointime+"</span>");
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
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("/layouts/_navMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <div class="row">
                <ol class="breadcrumb pull-left">
                    当前位置：<li><a href="/msg">管理主页</a></li>
                    <li class="active">7天收益 - <?php echo $data['dates'] ?></li>
                </ol>
            </div>
            <table class="table table-bordered">
                <tr class="tabag">
                    <th>类别</th>
                    <th>总激活量</th>
                    <th>总收入</th>
                </tr>
                <?php
                $tr = '';
                $jhcounts=0;
                $membera=Member::model()->getById($this->uid);
                foreach ($adList as $k => $v) {
                    if(!empty($data[$k]))
                    {
                        if($membera["agent"]!=69)
                        {
                            $prinfo = Product::model()->find('pathname=:pathname',array(':pathname'=>$k));
                            $jhcounts+=$data[$k]/$agentprice[$k]['price'];
                        }
                        else
                        {
                            //代理商69子用户特殊单价
                            if($k=="2345sjzs")
                            {
                                $jhcounts+= $data[$k]/2.5;

                            }
                            elseif($k=="yyzx")
                            {
                                $jhcounts+= $data[$k]/2.5;
                            }
                            else
                            {
                                $prinfo = Product::model()->find('pathname=:pathname',array(':pathname'=>$k));
                                $jhcounts+=$data[$k]/$agentprice[$k]['price'];
                            }
                        }

                    }


                }
                ?>
                <tr>
                    <td><strong>合计</strong></td>
                    <td><?php echo $jhcounts ?>个</td>
                    <td><?php echo $data['amount'] ?>元</td>
                </tr>
            </table>
            <?php  if (isset($data1)&& isset($data2) && count($data1)>0 && count($data2)>0): ?>
                <a href="/fotoplace/index" target="_blank"><div style="width: 100%;height: 200px;background: url(/css/fotoplace/images/780_back.png) repeat"><img style="margin: 0 auto;display: block;" src="/css/fotoplace/images/780.jpg"></div></a>
            <?php endif;?>
        </div>
    </div>
</div>