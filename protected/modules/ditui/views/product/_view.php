<?php
/* @var $this ProductController */
/* @var $data Resource */
/* @var $resourceStatus array */

//如果项目为关闭状态，则不显示
if (($data->auth == Product::AUTH_CLOSED) ||(empty($data->appurl))) {
    return;
}
?>

<div class="alert alert-info" id="alert-infost">
    <div class="prolef">
        <h4><?php echo CHtml::encode($data->name=="PP助手精装版"?"PP助手":$data->name); ?></h4>
        <?php echo CHtml::image($data->pic, '', array()) ?>
    </div>
    <p>价格：<b style="color: #ff6600; font-family: Arial, monospace;font-size:18px;"><?php echo $data->price; ?></b> 元/个<br>
        <?php
        if($data->pathname=="shsp" || $data->pathname=="sjzs360" || $data->pathname=="aqws360")
        {
            echo"说明：每两周返回数据";
        }
        else
        {
            echo"说明：每周三返回上周数据";
        }
        ?>
        </a>
    </p>
    <?php
    $btn1 = '<div class="btn-group btn-group1">';
    $btn1 .= CHtml::link('下载APP', "",  array('class'=>'btn btn-primary clicktask'));
    $btn1 .= '</div>';
    echo $btn1;

    if (!empty($resourceStatus[$data->id]['closed'])) {
        echo CHtml::label($resourceStatus[$data->id]['closed'], '', array('class'=>'label label-warning'));
    } elseif ($resourceStatus[$data->id]['status'] == true) {
/*        $btn = '<div class="btn-group">';
        $btn .= CHtml::link('关闭业务', array('product/edit', 'type' => $data->pathname, 'status' => 0),  array('class'=>'btn btn-danger'));
        $btn .= '</div>';
        echo $btn;*/




    } else {
        switch ($data->auth) {
            case Product::AUTH_MANAGE:
                if(Yii::app()->user->getState("member_manage")==true)
                {
                    //echo CHtml::link('开启业务', array('product/edit', 'type' => $data->pathname, 'status' => 1), array('class'=>'btn btn-primary'));
                }
                elseif (empty($resourceStatus[$data->id]['value'])) {
                   // echo CHtml::link('开启业务', "javascript:alert('开启此业务，请联系客服');", array('class'=>'btn btn-primary'));
                } else {
                   // echo CHtml::link('开启业务', array('product/edit', 'type' => $data->pathname, 'status' => 1), array('class'=>'btn btn-primary'));
                }
                break;
            case Product::AUTH_MEMBER:
                //echo CHtml::link('开启业务', array('product/edit', 'type' => $data->pathname, 'status' => 1), array('class'=>'btn btn-info'));

                break;
            case Product::AUTH_CLOSED:
                echo CHtml::label('业务调整中，暂时关闭', '', array('class'=>'label label-important'));
                break;
        }
    }
    ?>
    <p style="margin-top: 5px;">
            <?php
                $newday=date('Y-m-d H:i:s',strtotime('+10 Day',strtotime($data->enrollment)));
                $today=date('Y-m-d H:i:s');
                if(strtotime($today)>strtotime($newday))
                {
                    echo "<b style='color: green;font-family: Arial, monospace;font-size:14px;font-weight: normal;'>".$data->updatetime."--".substr($data->enrollment,0,10)."</b>";
                }
                else
                {
                    echo "<b class='ibgstyle' style='color: red; font-family: Arial, monospace;font-size:14px;font-weight: normal;'>".$data->updatetime."--".substr($data->enrollment,0,10)."</b>";
                }
            ?>
        </p>
</div>



