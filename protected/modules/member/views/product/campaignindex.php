<style type="text/css">
    .btn-group1{}
    .tongji{color:#008800; font-family: Arial, monospace;font-size:18px;font-weight:bold}
    .alert-success{width: 98%;}
    .alert-info{width: 98%;margin-right:10px;display: inline-block;}
    .alert-info p{font-family: "Microsoft YaHei" ! important;font-size: 13px;min-width: 320px;margin-right: -10px;}
    .alert-info p a:hover{text-decoration: none;}
    .img-rounded{padding-right:20px;}

    .prolef{float: left;text-align: center; min-width: 20%;padding-right: 15px;}
    .prolef h4,img{font-family: "Microsoft YaHei" ! important;font-size: 14px;font-weight: bold;}
    .prolef h4{margin-bottom: 15px;margin-top: 20px;}
    .prolef img{width: 72px; height: 72px;}
    th p img{width: 96px; height: 96px;}
    .protext{height: 96px;line-height: 25px;color: #ff0000;background-color: #ffffe7;border: 1px solid #ffcd66;margin-bottom: 20px;width: 98%;padding: 10px;color: #f89406}
    .protext strong{color: darkorange}
    .bgstyle{background:url("/css/images/new.png") no-repeat top right;  background-color: #d9edf7;}
    #alert-infost p{text-decoration:none;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;}
    .grid-view{width: 98%}
    .grid-view tr{height: 35px;}
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
                    当前位置：<li><a href="/member">管理主页</a></li>
                    <li class="active">活动产品区</li>
                </ol>
            </div>
            <div class="protext" id="mp" style="height: 230px;">
                <h3 style="color:#B57340;font-weight: bold;">活动说明</h3>
                <p>为回馈长期以来在速推平台稳定推广的老客户，提高各位大大做包的积极性。</p>
                <p>即日起，联盟每周推出一款“新产品”或“人气产品”，活动期内可享受如下优惠：</p>
                <p>1、活动周期内，推广“活动产品”，联盟将额外奖励20%收益，奖励收益与活动产品收益同时发放到用户账户；</p>
                <p>2、活动周期内，累计激活数据排名前三位的用户即可获得联盟提供的价值不等的品牌手机使用权一周；</p>
            </div>


            <?php

            $user = Yii::app()->user;
            $uid=$user->getState('member_uid');
            $manageid=$user->getState('member_manage');
            $member=Member::model()->find("id=$uid");
            $agent=$member["agent"];
            ?>
            <span style="font-weight: bold;font-size: 16px;color: #009999;margin-top: 10px;float: left; margin-bottom: 10px;">活动列表</span>
            <?php
            /* @var $dataProvider CArrayDataProvider */
/*            $columns[] = array('name' => "序号", 'value' => '$data[\'id\']');
            $columns[] = array('name' => "活动产品", 'value' => '$data[\'createtime\']');
            $columns[] = array('name' => "标题", 'value' => '$data[\'title\']');
            $columns[] = array('name' => "活动开始时间", 'value' => '$data[\'starttime\']');
            $columns[] = array('name' => "活动截止时间", 'value' => '$data[\'endtime\']');
            $columns[] = array('name' => "报名开始时间", 'value' => '$data[\'userstarttime\']');
            $columns[] = array('name' => "报名截止时间", 'value' => '$data[\'userendtime\']');
            $columns[] = array('name' => "说明", 'value' => '$data[\'instruction\']');
            $columns[] = array('name' => "有效状态", 'headerHtmlOptions' => array('width'=>'8%'),'value' => '$data->status=="0"?"<span style=font-weight:bold;color:red;>无效</span>":"<span style=color:darkgreen;font-weight:bold;>有效</span>" ','type'=>'html');
            $columns[] = array('name' => "操作状态", 'headerHtmlOptions' => array('width'=>'8%'),'value' => '$data[\'temp\']','type'=>'html');
            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $campaign,
                'columns' => $columns,
            ));*/
            ?>


            <?php
                $this->widget('zii.widgets.CListView', array(
                    'dataProvider' => $data,
                    'itemView' => '_campview',
                    'summaryText' => '',
                    'viewData' => array('resourceStatus' => $resourceStatus),
                ));
             ?>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $(".ibgstyle").parents('.alert-info').addClass("bgstyle");
        $("#xiazaist").click(function(){
            alert("活动未到开始时间");
        });
    })

</script>