<?php
$this->breadcrumbs = array('积分动态');
$this->menu = array(array('label' => '商品列表', 'url' => array('shop/index')),
    array('label' => '订单列表', 'url' => array('shop/goodsOrder')),
    array('label' => '添加商品', 'url' => array('shop/goodsAdd')),
    array('label' => '积分记录', 'url' => array('memberCredits/index')),
    array('label' => '商品分类', 'url' => array('shop/addCategory')),
);
?>

<h4 class="text-center">积分动态</h4>
<?php
$this->widget('zii.widgets.CMenu', array(
    'items' => $this->menu,
    'htmlOptions' => array('class' => 'breadcrumb')
));

$template = '';
//$template .= Auth::check('manage.admin.view') ? ' {view}' : '';

//$template .= Auth::check('manage.admin.update') ? ' {update}' : '';
//$template .= Auth::check('manage.admin.delete') ? ' {delete}' : '';?>
<?php //$form = $this->beginWidget('CActiveForm', array(
//    'id' => 'admin-form',
//    'enableAjaxValidation' => false,
//    'htmlOptions' => array("class"=>"form-inline"),
//)); ?>
<?php //echo Bs::formErrorSummary($form, $model, Bs::ALERT_ERROR); ?>
<!--    用户名：--><?php //echo $form->textField($model, 'username',array("class"=>"input-small")); ?>
<!--    积分：--><?php //echo $form->textField($model, 'credits',array("class"=>"input-small")); ?>
<!--    备注：--><?php //echo $form->textField($model, 'remarks',array("class"=>"input-large")); ?>
<!--<button type="submit" class="btn">确认提交</button>-->
<?php //$this->endWidget(); ?>
<form class="form-inline" id="admin-form2"  method="post">
    用户名：<input class="input-small"  id="username" type="text" style="width: 300px" />
    积分：
    <input list="browsers" id="credits" class="input-small">
    <datalist id="browsers">
        <option value="10000"> 积分</option>
        <option value="5000"> 积分</option>
        <option value="3000"> 积分</option>
        <option value="1000"> 积分</option>
        <option value="500"> 积分</option>
        <option value="100"> 积分</option>
    </datalist>
    备注：
    <input list="browserss" id="remarks" class="input-large">
    <datalist id="browserss">
        <option value="周周赢手机 天天高收益活动赠送">
    </datalist>
    <button onclick="check()" class="btn" >确认提交</button>
</form>
<?php
//判断是否有提示信息
if(Yii::app()->user->hasFlash('credits_status')){?>
    <div class="alert alert-success">
        <b><?php echo Yii::app()->user->getFlash('credits_status');?></b>
    </div>
<?php }?>
<?php $this->widget('zii.widgets.grid.CGridView', array(

    'id' => 'admin-grid',
    'dataProvider' => $model->search(),
    'pager'=>array(
        'class'=>'CLinkPager',//定义要调用的分页器类，默认是CLinkPager，需要完全自定义，还可以重写一个，参考我的另一篇博文：http://blog.sina.com.cn/s/blog_71d4414d0100yu6k.html
// 'cssFile'=>false,//定义分页器的要调用的css文件，false为不调用，不调用则需要亲自己css文件里写这些样式
        'header'=>'转往分页：',//定义的文字将显示在pager的最前面
// 'footer'=>'',//定义的文字将显示在pager的最后面
        'firstPageLabel'=>'首页',//定义首页按钮的显示文字
        'lastPageLabel'=>'尾页',//定义末页按钮的显示文字
        'nextPageLabel'=>'下一页',//定义下一页按钮的显示文字
        'prevPageLabel'=>'前一页',//定义上一页按钮的显示文字
//关于分页器这个array，具体还有很多属性，可参考CLinkPager的API
    ),
//    'filter' => $model,
    'columns' => array(
        'id',
        //'title',
        array(
            'name'=>'memberId',
            'value'=>'$data->member->username',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'credits',
            'value'=>'($data->credits>=0)?"<font color=#009900><b>+$data->credits</b></font>":"<font color=#ff0000><b>$data->credits</b></font>"',
            'htmlOptions'=>array('style'=>'text-align:center;'),
            'type'=>'html',
        ),
        array(
            'name'=>'account_credits',
            'value'=>'$data->account_credits',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'source',
            'value'=>'$data->creditssource->title',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'remarks',
            'value'=>'$data->remarks',
            //'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        /*array(
            'name'=>'create_datetime',
            'value'=>'date("Y-m-d H:i:s", $data->create_datetime)',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),*/
        array(
            'name'=>'create_datetime',
            'value'=>'date("Y-m-d H:i:s",$data->create_datetime)',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'opid',
            'value'=>'$data->opname',
            'htmlOptions'=>array('style'=>'text-align:center;'),
        ),

        /*array(
            'header'=>'操作',
            'class' => 'CButtonColumn',
            'viewButtonUrl'=>'Yii::app()->createUrl("manage/shop/goodsDetail",array("id"=>$data->id));',
            'updateButtonUrl'=>'Yii::app()->createUrl("manage/shop/goodsUpdate",array("id"=>$data->id));',
            //'deleteButtonUrl'=>'Yii::app()->createUrl("manage/shop/goodsUpdate",array("id"=>$data->id));',
            //'template' => $template,
            'template'=>'{view}{update}',
            'afterDelete'=>'function(link,success,data){alert(data) }',
            'buttons'=>array(

            ),
        ),*/
    ),
));
?>
<script type="text/javascript">
    function check(){
        var  username= $("#username").val();
        var  credits= $("#credits").val();
        var  remarks= $("#remarks").val();
        if(username==''){
            alert("请填写用户名");
            return false;
        }
        if(remarks==''){
            alert("请填写备注");
            return false;
        }
        if(credits==''){
            alert("请填写积分");
            return false;
        }
        $.ajax({
            type:"POST",
            url:"/dhadmin/memberCredits/check",
            data:{credits:credits,remarks:remarks,username:username},
            datatype: "json",
            success:function(data){
                var jsonStr = eval("("+data+")");
                if(jsonStr.status==200){
                    alert(jsonStr.message);
                    location.replace(location.href);
                }else{
                    alert("发生错误"+jsonStr.message);
                    return false;
                }
            }

        });
    }

</script>