<script>
    KindEditor.ready(function(K) {
        var editor = K.editor({
            allowFileManager : true,
            uploadJson: '<?php echo "/{$this->getModule()->id}/kindedit/upload";?>',
        });
        K('#imageButton').click(function() {
            editor.loadPlugin('image', function() {
                editor.plugin.imageDialog({
                    imageUrl : K('#Product_pic').val(),

                    clickFn : function(url, title, width, height, border, align) {
                        K('#Product_pic').val(url);
                        editor.hideDialog();
                    }
                });
            });
        });
    });
</script>
<div class="page-header app_head">
    <h1 class="text-center text-primary">业务产品中心 <small></small></h1>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <div class="list-group">
                <li class="list-group-item active">业务产品</li>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/product");?>" class="list-group-item">返回列表</a>
                <a href="<?php echo Yii::app()->createUrl($this->getModule()->id."/product/create");?>" class="list-group-item">重新添加</a>
            </div>
        </div>
        <!--左侧-->
        <div class="col-md-10">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => false,
                'htmlOptions' => array('class' => "form-horizontal"),
            )); ?>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">产品名称</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'name', array('class'=>'form-control','placeholder' => '请输入产品名称')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'name',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">产品标识</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'pathname', array('class'=>'form-control','placeholder' => '请输入产品标识')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'pathname',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">图片</label>
                <div class="col-sm-5">
                    <div class="input-group">
                        <?php echo $form->textField($model, 'pic', array('class'=>'form-control','placeholder' => '请上传图片')); ?>
                        <span class="input-group-btn">
                                  <input class="btn btn-default" type="button" id="imageButton" value="选择图片,大小：72x72" />
                              </span>
                    </div><!-- /input-group -->
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'pic',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">官方单价</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'officialprice', array('class'=>'form-control','placeholder' => '官方单价')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'officialprice',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">用户默认报价</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'quote', array('class'=>'form-control','placeholder' => '用户单价')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'quote',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">用户实际单价</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'price', array('class'=>'form-control','placeholder' => '用户单价')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'price',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">安装说明</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'install_instructions', array('class'=>'form-control','placeholder' => '安装说明')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'install_instructions',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">激活说明</label>
                <div class="col-sm-5">
                    <?php echo $form->textArea($model, 'activate_instructions', array('class'=>'form-control','placeholder' => '激活说明','style'=>'height:100px')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'activate_instructions',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">下架须知</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'under_instructions', array('class'=>'form-control','placeholder' => '下架须知')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'under_instructions',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">排序序号</label>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'order', array('class'=>'form-control','placeholder' => '激活说明')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'order',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">开通权限</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($model,'auth',Product::model()->getXauth(),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'auth',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">状态</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($model,'status',array("0"=>"业务关闭","1"=>"正常"),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'status',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">产品类型</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($model,'ptype',array("0"=>"普通","1"=>"VIP区"),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'ptype',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">产品分类</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($model,'category',$arr,array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'category',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">产品标示</label>
                <div class="col-sm-5">
                    <?php echo $form->checkBoxList($model,'sign',array(1=>'推荐',2=>'热门',3=>'新品'),array('separator'=>'&nbsp;','labelOptions'=>array('class'=>'labelForRadio')));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'sign',array('class'=>"errorMessageTips"));?></div>
            </div>


            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">	激活规则</label>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($model,'actrule',array("1"=>"首次激活","2"=>"二次激活"),array("class"=>"form-control"));?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'actrule',array('class'=>"errorMessageTips"));?></div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">产品介绍</label>
                <div class="col-sm-8" style="display: none">
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
                                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
//                                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',                                'insertunorderedlist', '|',  'image', 'link','unlink'),*/
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
                <div class="col-sm-5">
                    <?php echo $form->textArea($model, 'content', array('class'=>'form-control','placeholder' => '产品介绍','style'=>'height:200px')); ?>
                </div>
                <div class="col-md-5"><?php echo $form->error($model, 'content',array('class'=>"errorMessageTips"));?></div>
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
