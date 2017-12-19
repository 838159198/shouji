<?php
/* @var $this ProductController */
/* @var $data Resource */
/* @var $resourceStatus array */
//var_dump($data);exit;
//如果项目为关闭状态，则不显示
if ($data->auth == Product::AUTH_CLOSED || empty($data->appurl) ) {
    return;
}
?>

<div class="alert alert-info" id="alert-infost">


    <div class="prolef">
        <h4><?php echo CHtml::encode($data->name); ?></h4>
        <?php echo CHtml::image($data->pic, '', array()) ?>
    </div>
    <p>价格：<b style="color: #ff6600; font-family: Arial, monospace;font-size:18px;"><?php echo $data->price; ?></b> 元/个<span style="color: red"><?php echo $data->under_instructions=="" ? "":"(".$data->under_instructions.")"; ?></span><br><?php echo $data->content; ?><br>要求：<a href="javascript:void(0)" title="<?php echo $data->install_instructions; ?>"><?php
        $char=mb_strlen($data->install_instructions);
        if($char>12)
        {
            echo Common::cut_str($data->install_instructions,10);
        }
        else
        {
            echo $data->install_instructions;
        }

        ?></a>
        <br>验证软件MD5值：<a href="javascript:void(0)" title="<?php echo $data->actrule; ?>"><?php  echo Common::cut_str($data->actrule,3);?></a>
    </p>
    <?php
    //var_dump($resourceStatus);exit;
    if (!empty($resourceStatus[$data->id]['closed'])) {
        echo CHtml::label($resourceStatus[$data->id]['closed'], '', array('class'=>'label label-warning'));
    } elseif ($resourceStatus[$data->id]['status'] == true) {
        $btn = '<div class="btn-group">';
        $btn .= CHtml::link('关闭业务', array('product/edit', 'type' => $data->pathname, 'status' => 0),  array('class'=>'btn btn-danger'));
        $btn .= '</div>';
        echo $btn;

        $btn1 = '<div class="btn-group btn-group1">';
        $btn1 .= CHtml::link('下载APP', array($data->appurl),  array('class'=>'btn btn-primary'));
        $btn1 .= '</div>';
        echo $btn1;
        if($resourceStatus[$data->id]['is_put']==0){
            $btn2 = '<div class="btn-group btn-group1">';
            $btn2 .= CHtml::link('开启投放', array('product/openIsPut', 'type' => $data->pathname,'uid'=>$uid),  array('class'=>'btn btn-primary'));
            $btn2 .= '</div>';
        }else{
            $btn2 = '<div class="btn-group btn-group1">';
            $btn2 .= CHtml::link('关闭投放', array('product/closeIsPut', 'type' => $data->pathname,'uid'=>$uid),  array('class'=>'btn btn-danger'));
            $btn2 .= '</div>';
        }
        echo $btn2;

    } else {
        switch ($data->auth) {
            case Product::AUTH_MANAGE:
                if(Yii::app()->user->getState("member_manage")==true)
                {
                    echo CHtml::link('开启业务', array('product/edit', 'type' => $data->pathname, 'status' => 1), array('class'=>'btn btn-primary'));
                }
                elseif (empty($resourceStatus[$data->id]['value'])) {
                    echo CHtml::link('开启业务', "javascript:alert('开启此业务，请联系客服');", array('class'=>'btn btn-primary'));
                } else {
                    echo CHtml::link('开启业务', array('product/edit', 'type' => $data->pathname, 'status' => 1), array('class'=>'btn btn-primary'));
                }
                break;
            case Product::AUTH_MEMBER:
                echo CHtml::link('开启业务', array('product/edit', 'type' => $data->pathname, 'status' => 1), array('class'=>'btn btn-info'));

                break;
            case Product::AUTH_CLOSED:
                echo CHtml::label('业务调整中，暂时关闭', '', array('class'=>'label label-important'));
                break;
        }
    }
    ?>
    <p style="margin-top: 5px;">
            <?php
            //var_dump($data);exit;
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



