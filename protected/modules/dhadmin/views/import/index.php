<style type="text/css">
    .breadcrumb{margin-left:310px;margin-right:60px;}
    .breadcrumb  li{padding-left:40px;}
    .breadcrumb  li a{ color: #fff;  background-color: #337ab7;border-color: #2e6da4; display: inline-block;padding: 6px 12px;  margin-bottom: 0;font-size: 14px;
        font-weight: 400;line-height: 1.42857143;text-align: center;white-space: nowrap;vertical-align: middle;
        -ms-touch-action: manipulation; touch-action: manipulation; cursor: pointer;-webkit-user-select: none;  -moz-user-select: none;-ms-user-select: none;user-select: none;
        background-image: none; border: 1px solid transparent;
        border-radius: 4px; }
    .breadcrumb  li a:hover{text-decoration:none;}
</style>
<?php
/** @var $this ImportController */
/** @var $type string */
/** @var $success bool */
/** @var $need string */
/** @var $date string */

$this->breadcrumbs = array('数据导入');
$this->menu = array(
    array('label' => '清理已导入的业务数据','class' => 'btn btn-primary', 'url' => array('clear')),
);
echo '	<div class="page-header app_head">
		<h1 class="text-center text-primary">数据导入<small></small></h1>
	</div>';
$this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));
?>
<div class="col-md-2" style="height:800px; margin-right:50px;">
    <div class="list-group">
        <li class="list-group-item active">数据管理</li>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/import/index");?>" class="list-group-item">导入数据</a>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/earning/index");?>" class="list-group-item">用户收益</a>
        <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/earning/memberpro");?>" class="list-group-item">用户业务</a>
    </div>

</div>

<div class="h-input-list" style="overflow: hidden;">

    <?php echo CHtml::radioButtonList('type', $type, Ad::getAdList(false), array(
        'template' => '<span style="display:block;width:14em;float:left">{input} {label}</span>',
        'separator' => '',
        'encode' => false,
        'onclick' => 'sel(this.value,\'\')',
    )) ?>

</div>
<div class="h-clear"></div></br></br>
<div class="hero-unit h-hero h-gainadvert">
    <?php
    echo '<h4><p style="color:dodgerblue;">导入<strong style="color:mediumslateblue;">', Ad::getAdNameById($type), '</strong>数据</p>',
        '<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" >
    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
    <input lang="date" class = "input-small" id="importdate" onchange = "sel(\'\',this.value)" name="importdate" size="10"  data-rule="required" type="text" value=',$date,'  onblur="checkDateInput(this)">
</div></br>',

    '</h4>';

    if ($success) {
        if($show==1){
            echo '<div class="alert alert-success" style="margin-left:284px; margin-right:50px;">数据已导入   <a href="/dhadmin/import/show?type='.$type.'&date='.$date.'&show='.$show.'"class="btn btn-success">收益已显示</a></div>';
        }else{
            echo '<div class="alert alert-success" style="margin-left:284px; margin-right:50px;">数据已导入   <a href="/dhadmin/import/show?type='.$type.'&date='.$date.'&show='.$show.'"class="btn btn-default">收益已隐藏</a></div>';
        }

    } else {
        switch ($need) {
            case Income::TYPE_SELF:
                /*if ($type == Ad::TYPE_Fh123)
                {
                    echo CHtml::beginForm(array(Income::TYPE_SELF), 'post', array('enctype' => 'multipart/form-data')), CHtml::fileField('excel');
                }
                else
                {
                    echo CHtml::beginForm(array(Income::TYPE_SELF));
                }*/
                echo CHtml::beginForm(array(Income::TYPE_SELF));

                break;
        }


        foreach (Ad::getAdList() as $adk => $adv)
        {
            if ($type == $adk) {
                echo '<div class="form-inline">';
                echo '<label>选项：</label>';
                echo CHtml::radioButtonList($adk.'ParamSel', 'param', array('param' => ''), array('separator' => ''));
                echo '</div>';
                echo '<div id=".$adk."Param" class="none">';
                echo '<h4><p style="color:dodgerblue">填写激活扣量后数量</p></h4>';
                $incomen="Income".ucwords($adk);
                foreach ($incomen::getDeductUsers() as $key => $values) {
                    foreach ($values as $k => $v) {
                        echo '<div class="input-prepend"><span class="add-on">'. $v["val"] .' -  (';
                        if(is_numeric($v["status"]) && strlen($v["status"])<5){echo "分组";}
                        //分组88导入数据默认为0 ****date****2017-09-14
                        if(is_numeric($v['status']) && $v['status']== 88){
                            echo  $v["status"] .')</span>
                              <input type="hidden" name="DeductId[]" value="' . $v["uid"] . '" />
                              <input class="span6" type="text" name="DeductNum[]" value="0.001">
                            </div><br>';
                        }else{
                            echo  $v["status"] .')</span>
                                  <input type="hidden" name="DeductId[]" value="' . $v["uid"] . '" />
                                  <input class="span6" type="text" name="DeductNum[]" placeholder="填写数量">
                            </div><br>';
                        }
                    }
                }
                echo '</div>';
            }
        }


        echo CHtml::hiddenField('type', $type)
        , CHtml::hiddenField('date', $date)
        , CHtml::submitButton('导入数据', array('class' => 'btn btn-primary'))
        , CHtml::endForm();
    }
    ?>
</div>
</br></br>
<div class="alert alert-info" style="margin-left:284px; margin-right:50px;">
    <ol>
        <li>导入前一天的业务收入数据</li>
        <li>导入的日期要小于当前日期</li>
        <li>大部分官方统计数据一般在每天14：00以后生成</li>
    </ol>
</div>

<script type="text/javascript">
    function sel(type, d) {

        var type = type == "" ? $(":radio[checked='checked']").val() : type;
        var d = d == "" ? $("#importdate").val() : d;
        window.location.replace('<?php echo $this->createUrl('',array('type'=>'')) ?>' + type + '&date=' + d);
    }

    $(function () {
        <?php
                //foreach (Ad::getAdList() as $adk => $adv)
                //{
                  //   if ($type == $adk)
                    // {
                      //  echo
//'
//                          $("input[name="'.$adk.'ParamSel"]").click(function () {
//                              if (this.value == "param") {
//                                  $("#'.$adk.'param").show();
//                              } else {
//                                  $("#'.$adk.'param").hide();
//                              }
//                          });
  //                      '
    //                    ;
      //               }
        //        }
        ?>


        //日期控件
        $('.form_date').datetimepicker({
            language:'zh-CN', weekStart:1,todayBtn:1,
            autoclose:1,
            todayHighlight:1,
            startView:2,
            minView:2,
            forceParse:0
        });


    });
</script>
