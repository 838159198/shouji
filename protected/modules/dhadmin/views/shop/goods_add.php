<script>
    KindEditor.ready(function(K) {
        var editor = K.editor({
            allowFileManager : true,
            uploadJson: '<?php echo "/{$this->getModule()->id}/kindedit/upload";?>'
        });
        K('#imageButton').click(function() {
            editor.loadPlugin('image', function() {
                editor.plugin.imageDialog({
                    imageUrl : K('#ShopGoods_coverimage').val(),

                    clickFn : function(url, title, width, height, border, align) {
                        K('#ShopGoods_coverimage').val(url);
                        editor.hideDialog();
                    }
                });
            });
        });
        K('#imageButtonpre').click(function() {
            editor.loadPlugin('image', function() {
                editor.plugin.imageDialog({
                    imageUrl : K('#ShopGoods_previewimage').val(),

                    clickFn : function(url, title, width, height, border, align) {
                        K('#ShopGoods_previewimage').val(url);
                        editor.hideDialog();
                    }
                });
            });
        });
    });


</script>

<?php
$this->breadcrumbs = array(
    '商品列表' => array('index'),
    '添加商品',
);
?>

    <div class="breadcrumbs">
        <a href="/dhadmin/shop/index">首页</a> &raquo; <a href="/dhadmin/shop/index">商品列表</a> &raquo; <span>添加商品</span></div>
    <h4 class="text-center">添加商品</h4>

<?php
/* @var $this AdminController */
/* @var $model Manage */
/* @var $form CActiveForm */

?>
<div class="container-fluid">
    <div class="alert alert-info" >带（*）的内容是必须填写的！</div>
<div class="row">
    <!--左侧-->
    <div class="col-md-10">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'login-form',
            'enableClientValidation' => false,
            'htmlOptions' => array('class' => "form-horizontal"),
        )); ?>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">商品名称 *</label>
            <div class="col-sm-5">
                <?php echo $form->textField($model, 'title', array('class'=>'form-control')); ?>
            </div>
            <div class="col-md-5"><?php echo $form->error($model, 'title',array('class'=>"errorMessageTips"));?></div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">积分 *</label>
            <div class="col-sm-5">
                <?php echo $form->textField($model, 'credits', array('class'=>'form-control')); ?>
            </div>
            <div class="col-md-5"><?php echo $form->error($model, 'credits',array('class'=>"errorMessageTips"));?></div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">封面图片 *</label>
            <div class="col-sm-5">
                <div class="input-group">
                    <?php echo $form->textField($model, 'coverimage', array('class'=>'form-control','placeholder' => '请上传图片')); ?>
                    <span class="input-group-btn">
                                  <input class="btn btn-default" type="button" id="imageButton" value="选择图片(大)" />
                              </span>
                </div><!-- /input-group -->
            </div>
            <div class="col-md-5"><?php echo $form->error($model, 'coverimage',array('class'=>"errorMessageTips"));?></div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">预览图 *</label>
            <div class="col-sm-5">
                <div class="input-group">
                    <?php echo $form->textField($model, 'previewimage', array('class'=>'form-control','placeholder' => '请上传图片')); ?>
                    <span class="input-group-btn">
                                  <input class="btn btn-default" type="button" id="imageButtonpre" value="选择图片(小)" />
                              </span>
                </div><!-- /input-group -->
            </div>
            <div class="col-md-5"><?php echo $form->error($model, 'previewimage',array('class'=>"errorMessageTips"));?></div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">排序 *</label>
            <div class="col-sm-5">
                <?php echo $form->textField($model, 'order', array('class'=>'form-control')); ?>
            </div>
            <div class="col-md-5"><?php echo $form->error($model, 'order',array('class'=>"errorMessageTips"));?></div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">库存 *</label>
            <div class="col-sm-5">
                <?php echo $form->textField($model, 'kucun', array('class'=>'form-control')); ?>
            </div>
            <div class="col-md-5"><?php echo $form->error($model, 'kucun',array('class'=>"errorMessageTips"));?></div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">下架日期 *</label>
            <div class="col-sm-5">
                <?php echo $form->textField($model, 'down_datetime', array('class'=>'form-control date form_date','data-date-format'=>'yyyy-mm-dd')); ?>
            </div>
            <div class="col-md-5"><?php echo $form->error($model, 'down_datetime',array('class'=>"errorMessageTips"));?></div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">状态</label>
            <div class="col-sm-5">
                <?php echo $form->dropDownList($model,'status',array("0"=>"关闭","1"=>"正常"),array("class"=>"form-control"));?>
            </div>
            <div class="col-md-5"><?php echo $form->error($model, 'status',array('class'=>"errorMessageTips"));?></div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">商品分类</label>
            <div class="col-sm-5"><?php echo $form->dropDownList($model, 'cid',ShopGoodsCategory::model()->getDownList(),array("class"=>"form-control")) ?></div>
            <div class="col-md-5"><?php echo $form->error($model, 'cid',array('class'=>"errorMessageTips")); ?></div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">简介</label>
            <div class="col-sm-5">
                <?php echo $form->textArea($model, 'intro', array('class'=>'form-control')); ?>
            </div>
            <div class="col-md-5"><?php echo $form->error($model, 'intro',array('class'=>"errorMessageTips"));?></div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">商品链接</label>
            <div class="col-sm-5">
                <?php echo $form->textArea($model, 'address', array('class'=>'form-control')); ?>
            </div>
            <div class="col-md-5"><?php echo $form->error($model, 'address',array('class'=>"errorMessageTips"));?></div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">内容 *</label>
            <div class="col-sm-10">
                <?php $this->widget('ext.KEditor.KEditor',array(
                    'model'=>$model,  //传入form model
                    'name'=>'content', //设置name
                    'properties'=>array(
                        //设置接收文件上传的action
                        'uploadJson'=>"/{$this->getModule()->id}/kindedit/upload",
                        //设置浏览服务器文件的action，这两个就是上面配置在/admin/default的
                        'fileManagerJson'=>"/{$this->getModule()->id}/kindedit/manageJson",
                        /*'items'=>array('source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                            'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                            'insertunorderedlist', '|',  'image', 'link','unlink'),*/
                        //'newlineTag'=>'br',
                        'allowFileManager'=>true,
                        //传值前加js:来标记这些是js代码
                        'afterCreate'=>"js:function() {
                        K('#ChapterForm_all_len').val(this.count());
                        K('#ChapterForm_word_len').val(this.count('text'));
                    }",
                        'afterChange'=>"js:function() {
                        K('#ChapterForm_all_len').val(this.count());
                        K('#ChapterForm_word_len').val(this.count('text'));
                    }",
                    ),
                    'textareaOptions'=>array(
                        'style'=>'width:100%;height:400px;',
                    )
                )); ?>
                <?php echo $form->error($model, 'content',array('class'=>"errorMessageTips"));?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">确认提交</button>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>

<div class="form">
    <?php
    //判断是否有提示信息
    if(Yii::app()->user->hasFlash('status')){?>
        <script type="text/javascript">alert("添加成功！");</script>
        <div class="alert alert-success">
            <b><?php echo Yii::app()->user->getFlash('status');?></b>

        </div>
    <?php }?>
</div>
    <script type="text/javascript">
        $(function () {
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