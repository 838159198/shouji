<?php
/* @var $this ProductController */
/* @var $data Resource */
/* @var $resourceStatus array */

//如果项目为关闭状态，则不显示
if ($data["pauth"] == Product::AUTH_CLOSED) {
    return;
}
?>
<script type="text/javascript">
    var xx = eval(<?php $arr1['bool']=Yii::app()->user->getState("member_manage");echo json_encode($arr1);?>);
</script>
<script type="text/javascript" src="/js/member/campview.js"></script>
<table class="table table-striped table-hover table-bordered" style="width: 98%">
    <thead>
        <tr>
            <td style="text-align: center;">产品</td>
            <td><?php echo $data["title"]; ?>（<a style="color: #ff0000;font-weight: bold;" href="http://www.sutuiapp.com/hd/zzysjs/<?php echo $data["periods"]; ?>" target="_blank">活动详情>>></a>）</td>
            <td style="text-align: center;width: 120px;">报名状态</td>
            <td style="text-align: center;width: 120px;">操作</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th width="140" align="center">
                <p style="text-align: center"><?php echo CHtml::image($data["ppic"], '', array()) ?></p>
                <p style="text-align: center;"><?php echo CHtml::encode($data["pname"]); ?></p>
                <p style="text-align: center">版本号：<?php echo $data["pversion"]; ?></p>
            </th>
            <td>
                <p>价格：<b style="color: #ff6600; font-family: Arial, monospace;font-size:18px;"><?php echo $data["pprice"]+$data["pprice"]*0.2; ?></b> 元/个<span style="color: red;font-weight: bold;">（活动期间额外奖励20%收益）</span>
                    <span style="color: red"><?php echo $data["punder_instructions"]=="" ? "":"(".$data["punder_instructions"].")"; ?></span><br>
                    数据：<?php echo $data["pcontent"]; ?><br>
                    要求：<a href="javascript:void(0)" title="<?php echo $data["pinstall_instructions"]; ?>">
                        <?php echo $data["pinstall_instructions"];?></a>
                    <br>验证软件MD5值：<a href="javascript:void(0)" title="<?php echo $data["pactrule"]; ?>"><?php  echo $data["pactrule"];?></a>
                    <br>
                </p>
            </td>
            <td  width="120" style="text-align: center;">
                    <?php echo $data["temp"];?>
            </td>

            <td width="120" align="center">
                <?php
                if (!empty($resourceStatus[$data["pid"]]['closed'])) {
                    echo CHtml::label($resourceStatus[$data["pid"]]['closed'], '', array('class'=>'label label-warning'));
                } elseif ($resourceStatus[$data["pid"]]['status'] == true) {
                    $btn = "<div class='btn-group'><a class='btn btn-danger' id='closePsign'  type='".$data['ppathname']."' onclick='clickCampview($(this))'>关闭业务</a></div>";
                    echo $btn;

                    $btn1 = '<div class="btn-group btn-group1">';
                    $caminfo=Campaign::model()->find('id='.$data["id"]);
                    if(date("Y-m-d H:i:s",time())>=$caminfo["starttime"] && date("Y-m-d H:i:s",time())<=$caminfo["endtime"])
                    {
                        $btn1 .="<a class='btn btn-primary' id='down".$data['pid']."'  type='".$data['ppathname']."' href='".$data['pappurl']."'>下载APP</a>";
                    }
                    else
                    {
                        $btn1 .='<div class="btn-group"><a class="btn btn-primary" id="xiazaist1"  href="javascript:alert(\'不在活动时间范围内\')">下载APP</a></div>';
                    }

                    $btn1 .= '</div>';
                    echo $btn1;


                } else {
                    switch ($data["pauth"]) {
                        case Product::AUTH_MANAGE:
                            if(Yii::app()->user->getState("member_manage")==true)
                            {
                                echo "<a class='btn btn-primary' id='managesign' type='".$data['ppathname']."' onclick='clickCampview($(this))'>开启业务</a>";
                            }
                            elseif (empty($resourceStatus[$data["pid"]]['value'])) {
                                echo '<a class="btn btn-primary" id="contactsign" type="'.$data['ppathname'].'" href="javascript:alert(\'开启此业务，请联系客服\')">开启业务</a>';
                            } else {
                                echo '<a class="btn btn-primary" id="othersign" type="'.$data['ppathname'].'" onclick="clickCampview($(this))">开启业务</a>';
                            }
                            break;
                        case Product::AUTH_MEMBER:
                            echo '<a class="btn btn-info" id="memebersign" type="'.$data['ppathname'].'" onclick="clickCampview($(this))">开启业务</a>';
                            break;
                        case Product::AUTH_CLOSED:
                            echo '<label class="label label-important" style="color:red;">业务调整中，暂时关闭</label>';
                            break;
                    }
                }
                ?>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4"><?php echo '<span class="label label-success">活动开始时间：</span>'.$data["starttime"].'&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info">活动截止时间：</span><span style="color:red;">'.$data["endtime"].'</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-success">用户报名开始时间：</span>'.$data["userstarttime"].'&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info">用户报名截止时间：</span><span style="color:red;">'.$data["userendtime"].'</span>' ?></td>
        </tr>
    </tfoot>
</table>

