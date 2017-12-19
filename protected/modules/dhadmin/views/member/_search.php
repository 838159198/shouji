<?php
/* @var $this MemberController */
/* @var $model Member */
/* @var $form CActiveForm */
/* @var $gets array */
/* @var $memberCategoryList array */
?>

<div class="wide form h-input-list">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));

    $id = Yii::app()->user->manage_id;
    $role = Manage::model()->getRoleByUid($id);
    ?>



    <ul>


<!--        见习主管以下权限-->
        <?php if (($role >4) && ($id!=14) && ($id!=1) && ($id!=40)) {?>
            <li id="form_search">
                <label>
                    <select class="input-sm" name="sel">
                        <option value="Member_username" selected><?php echo CHtml::encode($model->getAttributeLabel('username')) ?></option>
                        <option value="Member_tel"><?php echo CHtml::encode($model->getAttributeLabel('tel')) ?></option>
                        <option value="Member_mail"><?php echo CHtml::encode($model->getAttributeLabel('mail')) ?></option>
                        <option value="Member_qq"><?php echo CHtml::encode($model->getAttributeLabel('qq')) ?></option>
                        <option value="Member_holder"><?php echo CHtml::encode($model->getAttributeLabel('holder')) ?></option>
<!--                        <option value="Member_agent">--><?php //echo CHtml::encode($model->getAttributeLabel('agent')) ?><!--</option>-->
                    </select>
                </label>

                <?php echo $form->textField($model, 'username', array('size' => 20, 'maxlength' => 20) + Bs::cls(Bs::INPUT_SMALL)); ?>
                <?php echo $form->textField($model, 'tel', array('size' => 20, 'maxlength' => 20) + Bs::cls(Bs::INPUT_SMALL)); ?>
                <?php echo $form->textField($model, 'mail', array('size' => 30, 'maxlength' => 30) + Bs::cls(Bs::INPUT_SMALL)); ?>
                <?php echo $form->textField($model, 'qq', array('size' => 11, 'maxlength' => 11) + Bs::cls(Bs::INPUT_SMALL)); ?>
                <?php echo $form->textField($model, 'holder', array('size' => 10, 'maxlength' => 10) + Bs::cls(Bs::INPUT_SMALL)); ?>
<!--                --><?php //echo $form->textField($model, 'agent', Bs::cls(Bs::INPUT_SMALL)); ?>
            </li>



       <?php  } else { ?>
            <li id="form_search">
                <label>搜索方式
                    <select class="input-sm" name="sel">
                        <option value="Member_username" id="Member_username_"><?php echo CHtml::encode($model->getAttributeLabel('username')) ?></option>
                        <option value="Member_id"><?php echo CHtml::encode($model->getAttributeLabel('id')) ?></option>
                        <option value="Member_tel"><?php echo CHtml::encode($model->getAttributeLabel('tel')) ?></option>
                        <option value="Member_mail"><?php echo CHtml::encode($model->getAttributeLabel('mail')) ?></option>
                        <option value="Member_qq"><?php echo CHtml::encode($model->getAttributeLabel('qq')) ?></option>
                        <option value="Member_holder"><?php echo CHtml::encode($model->getAttributeLabel('holder')) ?></option>
                        <!--<option value="Member_agent"><?php /*echo CHtml::encode($model->getAttributeLabel('agent')) */?></option>-->
                    </select>
                </label>

                <?php echo $form->textField($model, 'username', array('size' => 20, 'maxlength' => 20) + Bs::cls(Bs::INPUT_SMALL)); ?>
                <?php echo $form->textField($model, 'id', array('size' => 20, 'maxlength' => 20) + Bs::cls(Bs::INPUT_SMALL)); ?>
                <?php echo $form->textField($model, 'tel', array('size' => 20, 'maxlength' => 20) + Bs::cls(Bs::INPUT_SMALL)); ?>
                <?php echo $form->textField($model, 'mail', array('size' => 30, 'maxlength' => 30) + Bs::cls(Bs::INPUT_SMALL)); ?>
                <?php echo $form->textField($model, 'qq', array('size' => 11, 'maxlength' => 11) + Bs::cls(Bs::INPUT_SMALL)); ?>
                <?php echo $form->textField($model, 'holder', array('size' => 10, 'maxlength' => 10) + Bs::cls(Bs::INPUT_SMALL)); ?>
                <?php /*echo $form->textField($model, 'agent', Bs::cls(Bs::INPUT_SMALL));*/ ?>
            </li>



        <li>
            <?php //echo $form->label($model, 'type'); ?>
            <?php //echo $form->dropDownList($model, 'type', Member::getTypeList(), Bs::cls(Bs::INPUT_SMALL)); ?>
            <?php echo $form->label($model, 'category'); ?>
            <?php echo $form->dropDownList($model, 'category', $memberCategoryList, Bs::cls(Bs::INPUT_SMALL)); ?>
            <label>业务类型
                <?php echo CHtml::dropDownList('workType', $gets['workType'], Ad::getAdList(), Bs::cls(Bs::INPUT_SMALL)) ?>
            </label>
            <?php echo CHtml::textField('workValue', $gets['workValue'], Bs::cls(Bs::INPUT_SMALL)) ?>
        </li>
        <li>
            <?php if (Auth::check('member_searchlogindate')): ?>
                <?php echo $form->label($model, 'jointime') ?>
                <?php echo $form->textField($model, 'jointime', Bs::cls(Bs::INPUT_SMALL)) ?> -
                <?php echo CHtml::textField('lastjointime', $gets['lastjointime'], Bs::cls(Bs::INPUT_SMALL)) ?>
                <?php echo $form->label($model, 'overtime') ?>
                <?php echo $form->textField($model, 'overtime', Bs::cls(Bs::INPUT_SMALL)) ?> -
                <?php echo CHtml::textField('lastovertime', $gets['lastovertime'], Bs::cls(Bs::INPUT_SMALL)) ?>
            <?php endif; ?>
        </li>



        <?php } ?>
        <li>
            <div class="btn-group">
                <?php echo CHtml::submitButton('开始搜索', Bs::cls(Bs::BTN_PRIMARY)); ?>
                <?php if (Auth::check('member_task')): ?>
                <?php echo CHtml::button('发布任务', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'task()'))); ?>
                <?php endif; ?>
            </div>
        </li>

    </ul>
    <?php $this->endWidget(); ?>

</div><!-- search-form -->

<script type="text/javascript">
    $(function () {
        var selValue = '<?php echo $gets['sel'] ?>';
        var s = $("#form_search");
        var sel = s.find("select");
        if(selValue==""){
            //默认用户名查询
            $("#Member_username_").show();
            $("#Member_username").show();
        }else{
            sel.val(selValue);
        }
        sel.change(function () {
            s.find("input").hide().val('');
            $("#" + sel.val()).show();
        });

        s.find("input").hide();
        $("#" + sel.val()).show();

        $("#Member_overtime").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#lastovertime").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#lastovertime").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#Member_overtime").datepicker("option", "maxDate", selectedDate);
            }
        });

        $("#Member_jointime").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#lastjointime").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#lastjointime").datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function (selectedDate) {
                $("#Member_jointime").datepicker("option", "maxDate", selectedDate);
            }
        });
    });
</script>