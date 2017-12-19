<div class="page-header app_head">
    <h1 class="text-center text-primary">修改内容 <small></small></h1>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php $this->renderPartial("_sideMenu");?>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => false,
                'htmlOptions' => array('class' => "form-horizontal"),
            )); ?>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">标题</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'title', array('class'=>'form-control','placeholder' => '请输入标题')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'title',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">状态</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($model,'status',array("0"=>"关闭","1"=>"正常"),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'status',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">分类</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($model, 'cid', CHtml::listData($category,"id","name"),array("class"=>"form-control","empty"=>"-==请选择分类==-")); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'cid',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">内容</label>
                <div class="col-sm-8">
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
</div>
