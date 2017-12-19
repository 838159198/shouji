<?php
/* @var $this ProductController */
/* @var $data Resource */
/* @var $resourceStatus array */
//如果项目为关闭状态，则不显示
if (($data->auth == Product::AUTH_CLOSED) || ($data->pathname=="yyb") || ($data->pathname=="aqws360") || ($data->pathname=="zmtq") || ($data->pathname=="txsp") || ($data->pathname=="txxw") || ($data->pathname=="ucllq") || ($data->pathname=="wdj") || ($data->pathname=="zysck") || ($data->pathname=="ppzs") || ($data->pathname=="yysd") || ($data->pathname=="jiuyou") || ($data->pathname=="2345ysdq") || ($data->pathname=="bdsjzs") || ($data->pathname=="2345tqw") || ($data->pathname=="2345ydw") || ($data->pathname=="jyyxzx") || ($data->pathname=="llq360")) {
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
      $btn = '<div class="btn-group">';
        if($data->default_app==1){
            $btn.= CHtml::link('关闭业务', "javascript:alert('此业务为默认开启，如有疑问请联系客服');", array('class'=>'btn btn-danger'));
        }else{
            $btn .= CHtml::link('关闭业务', array('product/edit', 'type' => $data->pathname, 'status' => 0),  array('class'=>'btn btn-danger'));
        }
        $btn .= '</div>';
        echo $btn;
        $uid=$this->uid;
        if($resourceStatus[$data->id]['is_put']==0 ){
            $btn2 = '<div class="btn-group btn-group1">';
            $btn2 .= CHtml::link('开启投放', array('product/openIsPut', 'type' => $data->pathname,'uid'=>$uid),  array('class'=>'btn btn-primary'));
            $btn2 .= '</div>';
        }else{
            $btn2 = '<div class="btn-group btn-group1">';
            if($data->default_app==1){
                $btn2.= CHtml::link('关闭投放', "javascript:alert('此业务为默认开启，如有疑问请联系客服');", array('class'=>'btn btn-danger'));
            }else{
                $btn2 .= CHtml::link('关闭投放', array('product/closeIsPut', 'type' => $data->pathname,'uid'=>$uid),  array('class'=>'btn btn-danger'));
            }
            $btn2 .= '</div>';
        }
        echo $btn2;


    } else {
        switch ($data->auth) {
            case Product::AUTH_MANAGE:
                if(Yii::app()->user->getState("member_manage")==true)
                {
                    //echo CHtml::link('开启业务', array('product/edit', 'type' => $data->pathname, 'status' => 1), array('class'=>'btn btn-primary'));
                } else {
                    if($data->default_app==1){
                        //在MemberResource表创建默认pc版的app
                        $uid=$this->uid;
                        $MemberResource = MemberResource::model();
                        $memberResource=$MemberResource->find("uid=:uid and type=:type",array(":uid"=>$uid,":type"=>$data->pathname));
                        if(is_null($memberResource)){
                            $MemberResource = MemberResource::model()->createDeResource($uid,$data->pathname);
                        }

                    }else{
                        echo CHtml::link('开启业务', array('product/edit', 'type' => $data->pathname, 'status' => 1), array('class'=>'btn btn-primary'));
                    }

                }
                break;
            case Product::AUTH_MEMBER:
                if($data->default_app==0){
                    echo CHtml::link('开启业务', array('product/edit', 'type' => $data->pathname, 'status' => 1), array('class'=>'btn btn-info'));
                }


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



