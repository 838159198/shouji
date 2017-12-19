<style type="text/css">
    form{width:1100px;margin: 0 auto;}
	.row{float:left;width:300px; padding-left:50px; padding-bottom: 20px;}
    .note{margin-bottom: 30px;}
	.buttons{width:1100px;float:left; text-align:center;padding-top:30px;}
	.buttons input[type="submit"]{width:120px; height:30px; font-weight:bold; letter-spacing:4px;}
	.respan{ color:red; font-size:15px; font-weight:bold;padding-left:30px;}
	.errorSummary ul{color:red; padding-bottom:20px;padding-left:6px;list-style: none;list-style-type: none;padding-left: 30px;}
	.errorSummary p{ padding-left:30px;}

</style>
<?php
/* @var $this SerachInfoSaleController */
/* @var $model SerachInfo */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'serach-info-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="respan">*用户名做为用户注册比对条件，请按照数据规则填写；所填信息要求真实有效，一经录入不能更改(除备注字段)</span></p>

	<?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'username'); ?>
        <?php echo $form->textField($model,'username',array('size'=>20,'maxlength'=>20)); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tel'); ?>
		<?php echo $form->textField($model,'tel',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'qq'); ?>
		<?php echo $form->textField($model,'qq',array('size'=>20,'maxlength'=>20)); ?>
	</div>
    <?php echo $form->hiddenField($model,'reg_status', array('value'=>'0'));?>
	<?php echo $form->hiddenField($model,'status', array('value'=>'0'));?>
	<?php echo $form->hiddenField($model,'manage_id', array('value'=>$uid));?>
	<?php echo $form->hiddenField($model,'search_id', array('value'=>$uid));?>
    <?php echo $form->hiddenField($model,'type', array('value'=>1));?>
	<?php echo $form->hiddenField($model,'createtime', array('value'=>date('Y-m-d H:i:s',time())));?>
	<?php echo $form->hiddenField($model,'motifytime', array('value'=>date('Y-m-d H:i:s',time())));?>
	<?php echo $form->hiddenField($model,'id');?>

	<div class="row">
		<?php echo $form->labelEx($model,'门店名称'); ?>
		<?php echo $form->textField($model,'com',array('size'=>20,'maxlength'=>20)); ?>
	</div>
    <div class="row">
        <?php echo $form->labelEx($model,'所属地区'); ?>
        <?php echo $form->textField($model,'userarea',array('size'=>20,'maxlength'=>20)); ?>
        <?php echo $form->error($model,'userarea'); ?>
    </div>
	<div class="row">
		<?php echo $form->labelEx($model,'门店地址'); ?>
		<?php echo $form->textField($model,'area',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'area'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textField($model,'content',array('size'=>20,'maxlength'=>200)); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'提醒时间'); ?>
		<?php echo $form->textField($model,'tixingtime',array('size'=>20,'maxlength'=>200)); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建信息' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


<div class="mask"></div>
<div id="wrap">

    <div class="city">
        <div class="top01-city"  >
            <em class="ok">确定</em><b >请选择地区</b>
        </div>
        <div class="mid01-city">
            <div class="cityshow" id="show">
                <i>已选地点:</i>
                <ul></ul>
            </div>
            <div class="cityshow1" id="show1">
                <table>
                    <tr><td colspan="2" class="trone"><i>请选择省份:</i></td></tr>
                    <tr>
                        <td>
                            <ul class="cityul">
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="cityshow2" id="show2">
                <table id="show2t">
               </table>
            </div>


        </div>
    </div>


</div>
<input type="hidden" id="cur" name="cur"/>

<style type="text/css">
    ul,li{list-style: none;list-style-type: none;}
    .mask{height:100%; width:100%; position:fixed; _position:absolute; top:0; z-index:1000; opacity:0.6; filter: alpha(opacity=60); background-color:#000;display:none;z-index:10;}
    #wrap{width:900px;margin:0 auto;z-index:11;font-family: "微软雅黑";color: black;font-weight: normal;}
    .city{width:900px;z-index:11;display:none;z-index:11;background:#fff;position:absolute;top:50px;}
    .top01-city{height:30px;line-height:30px;width:100%; float:left;border-bottom:1px solid #ccc; background-color: #157abd;}
    .top01-city b{display:block;float:left;padding-left:8px;color:#fff;}
    .top01-city .ok{float:right;cursor:pointer;font-style:normal;padding-right:10px;color:#fff;}
    .mid01-city{padding:2% 1%;width:98%;float:left;}
    .cityshow{width:100%;float:left;padding-bottom:6px;}
    .cityshow i{font-style:normal;font-weight:bold;color:#000000;float:left;padding-right:5px;font-weight: normal;}
    .cityshow ul{float:left;}
    .cityshow ul li{float:left;padding-right:14px;}
    .cityshow ul li .check{margin-right:5px;}
    .cityul{width:100%;float:left;}
    .cityul li{width:80px;float:left;padding-bottom:7px;height:20px;line-height:20px;}
    .cityul li b{cursor:pointer;font-weight: normal;}
    .cityshow1{float:left;padding-left: 10px;padding-right: 10px;}
    .cityshow1 i{font-style:normal;font-weight:bold;color:#000000;float:left;padding-right:5px;font-weight: normal;}
    .cityshow1 .trone{ line-height: 40px;height: 40px;border-bottom: 1px solid #30a9fa;font-size: 18px;}
    .cityshow2{float:left;padding-left: 10px;padding-right: 10px;width:864px;}
    .cityshow2 table{width:864px;}
    .cityshow2 table tr{}
    .cityshow2 i{font-style:normal;font-weight:bold;color:#000000;float:left;padding-right:5px;font-weight: normal;}
    .cityshow2 .trone{ line-height: 40px;height: 40px;border-bottom: 1px solid #30a9fa;font-size: 18px;}
    .cityshow2 .cityst{width: 15%;color: #fb5b0d;font-size: 16px;line-height: 30px;height:30px;text-decoration:none;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;}
    .cityshow2 .areast{width: 85%;line-height: 30px;height: auto;}
    .cityshow2 .areast a{padding-right: 10px;}

</style>
<script type="text/javascript">
    function gInitData(){
        if($('.licity').html()==undefined)
        {
            var dataroot="/js/areas.json";
            $.getJSON(dataroot, function(data){
                for(var i = 0; i < data.length; i++){
                    $('<li><a><b city="'+i+'" cid="'+i+'">'+data[i].name+'</b></a></li>').appendTo('#show1 ul');
                }
            });
        }
        else
        {
            return;
        }

    }
    function gGetData(protext){
        var dataroot="/js/areas.json";
        $.getJSON(dataroot, function(data){
            for(var i = 0; i < data.length; i++){
                if(data[i].name==protext)
                {

                    $('<tr><td colspan="2" class="trone"><i>'+protext+':</i></td></tr>').appendTo('#show2 table');
                    for(var j = 0; j < data[i].city.length; j++)
                    {
                        var districts="";
                        for(var k = 0; k < data[i].city[j].district.length; k++)
                        {
                            districts=districts+'<a ><b>'+data[i].city[j].district[k]+'</b></a>';
                        }
                        $('<tr><td> <ul class="cityul"> ' +
                        '<li class="cityst"> <a >'+data[i].city[j].name+'</a></li> ' +
                        '<li class="areast"> '+districts+'</li></ul></td></tr>').appendTo('#show2 table');
                    }
                    break;
                }


            }
        });
    }
    $(function () {
        $("#SerachInfo_tixingtime").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#SerachInfo_tixingtime").datepicker("option", "minDate", selectedDate);
            }
        });
    })
</script>
<script type="text/javascript">
    var cityId = 0;
    $(function(){
        $('#SerachInfo_userarea').click(function(){
            var currid=$(this).attr('id');
            $('#cur').val(currid);
            $('.city').show();
            $('.mask').show();
            gInitData();
        });
        $(document).on("click",".cityul b",function(){
            if(his!="")
            {
                if($(this).attr("cid")==undefined)
                {
                    $('#show2t').children('tbody').remove();
                }
                else
                {
                    $('#show ul').children('li').remove();
                    $('#show2t').children('tbody').remove();
                }
            }
            var protext=$(this).html();
            var his=protext;
            gGetData(protext);
            if($(this).attr("city") == undefined){ $(this).attr("city", window.cityId++);}
            var city = $(this).attr("city");
            var ml=$('.cityshow li').length;
            if(ml>2)
            {
                alert('最多只能添加3项，您可清除已选地点再次选择！');
            }
            else
            {
                var test=$(this).html();
                if($(this).attr("cid")==undefined)
                {
                    var citytext=$(this).parents("li").siblings("li").html();
                    var test1='<li class="licity"><input  name="citychk" type="checkbox" checked="true" city="'+ city +'" class="check"><b>'+citytext.replace(/(^\s*)|(\s*$)/g,"")+'</b></li>';
                    $(test1+'<li class="licity"><input  name="citychk" type="checkbox" checked="true" city="'+ city+1 +'" class="check"><b>'+test+'</b></li>').appendTo('.cityshow ul');
                }
                else
                {
                    $('<li class="licity"><input  name="citychk" type="checkbox" checked="true" city="'+ city +'" class="check"><b>'+test+'</b></li>').appendTo('.cityshow ul');
                }
            }

            $('input[name=citychk]').click(function(){
                $(this).parent('.licity').remove();
            });
        });
        $('.ok').click(function(){
            var total="";
            if($('.cityshow ul li').text()=='')
            {
                $('.city').hide();
                $('.mask').hide();
            }
            else
            {
                $('.cityshow ul li').each(function(){
                    total += (total.length > 0 ? "+" : "" ) + $(this).text();
                });
                var thisid=$('#cur').val();
                $("#"+thisid).val();
                $("#"+thisid).val(total);
                $('.city').hide();
                $('.mask').hide();
            }
        })
    })
</script>
