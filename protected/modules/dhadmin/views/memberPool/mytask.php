<?php
$this->breadcrumbs = array('我的用户池' => array('index'), '当前任务' => array('mytask'));

?>
<div class="page-header app_head"><h1 class="text-center text-primary"><?php echo $model [0] ['title'];?>任务的信息</h1></div>

<div style='border: 1px solid #e7e7e7;width: 1000px;height: 430px;margin: 0 auto;'>
    <dl class="dl-horizontal" style='height: 430px;'>
        <div style="margin-left: 50px;width: 200px;float: left;padding-top: 30px;"><label for="m_category">任务申请时间<span class="required"></span>
            </label> <input type='text' value='<?php echo date('Y-m-d H:i', $model [0] ['a_time']); ?>'>
            <label for="m_category">任务上报时间<span class="required"></span></label>
                <input type='text' value='<?php
                if (empty ($model [0] ['porttime'])) {
                    echo '未上报';
                } else {
                    echo date('Y-m-d H:i', $model [0] ['porttime']);
                }
                ?>'>
            <label for="m_category">任务发布时间<span class="required"></span></label>
                <input type='text' value='<?php echo date('Y-m-d H:i', $model [0] ['ttime']);?>'>
            ‭<label for="m_category">任务内容 <span class="required"></span></label>
                <textarea rows="5" cols="20" value=''><?php echo $model [0] ['content']; ?></textarea>
            <label for="m_category">收款人姓名 <span class="required"></span></label>
                <input type='text' name='123' value='<?php echo $model [0] ['holder']; ?>'>
        </div>
        <div style="margin-left: 50px;width: 200px;float: left;padding-top: 30px;"><label for="m_category">用户注册时间<span class="required"></span>
            </label> <input type='text' name='123'
                            value='<?php echo date('Y-m-d H:i', $model [0] ['jointime']);?>'>
            <label for="m_category">用户最后登录<span class="required"></span></label>
                <input type='text'  value='<?php echo date('Y-m-d H:i', $model [0] ['overtime']); ?>'>
            <label for="m_category">终端数量<span class="required"></span></label>
                <input type='text' value='<?php echo $model [0] ['clients']; ?>'>
            <label for="m_category">代理人(代理商，官网)<span class="required"></span></label>
                <input type='text' value='<?php
                if ($model [0] ['agent'] == 0) {echo "官网"; } else {echo $model [0] ['agent'];} ?>'>
            <label for="m_category">用户类型(普通用户，代理商)<span class="required"></span></label>
                <input type='text'value='<?php
                   if ($model [0] ['type'] == 0) {echo "普通用户";
                   } elseif ($model [0] ['type'] == 1) {echo "代理商";
                   }
                   ?>'>
            <label for="m_category">联系电话 <span class="required"></span></label>
                <input type='text' value='<?php echo $model [0] ['tel']; ?>'>
            <label for="m_category">注册电话 <span class="required"></span></label>
                <input type='text' value='<?php echo $model [0] ['regist_tel']; ?>'>
        </div>
    </dl>
</div>
<?php

if (($model [0] ['type'] != Task::TYPE_VISIT) || ($ro == Role::PRACTICE_STAFF)) {
    ?>
    <div style='width:1000px;margin:0 auto;display:<?php
    if (($now == 1)) {
        echo 'display';
    } else {
        echo 'none';
    }
    ?>'>
        <div style='float: left;margin-top: 15px;'>
            <div class="control-group">
                <div style = 'margin-top:15px;'>
                    <select id = 'fail_type' onchange="show_fail_reason()">
                        <option value = '0'>快速回复</option>
                        <option value = '1'>网吧不做了</option>
                        <option value = '2'>作弊刷量网赚</option>
                        <option value = '3'>一直联系不上</option>
                    </select>
                </div>

                <div class="controls">
                    <textarea rows="5" cols="50" value='任务回复'id='my_reply'></textarea>
                </div>
            </div>
            <input type='hidden' id='tid' value='<?php echo $tid;?>'>
            <?php if ($task_msg ['tw_status'] ==  TaskWhen::STATUS_SUBMAIT) { ?>
                <div class="controls" style="margin-left: 5px; margin-bottom: 10px;">
                    <span class="label label-info" style='margin-top: 5px;'>任务已经提交</span></br>
                </div>
            <?php } elseif ($task_msg ['pro'] == 1) { ?>
                <div class="controls" style="margin-left: 5px; margin-bottom: 10px;"><span
                        class="label label-info" style='margin-top: 5px;'>任务可以提交</span></br>
                </div>
            <?php } else {?>
                <div class="controls" style="margin-left: 5px; margin-bottom: 10px;">
                    <span class="label label-info" style='margin-top: 5px;'>任务发布/申请至当前时间未满30天</span>
                </div>
            <?php } ?>

            <div class="control-group" style='float: left;'>
                <div class="controls">
                    <?php
                    echo CHtml::submitButton('提交', array_merge(Bs::cls(Bs::BTN_PRIMARY, Bs::BTN_LARGE), array('onclick' => 'reply_task(0)')))?>
                </div>
            </div>
            <div class="control-group" style='float: left; margin-left: 50px;'>
                <div class="controls">
                    <?php
                    echo CHtml::submitButton('失败', array_merge(Bs::cls(Bs::BTN_PRIMARY, Bs::BTN_LARGE), array('onclick' => 'reply_task(1)')))?>
                </div>
            </div>

        </div>
    </div>

    <div style='float:left;border:0px solid #e7e7e7;margin-left:50px;display:<?php
    if ($role < Manage::MANAGE_POWER) {
        echo 'display';
    } else {
        echo 'none';
    }
    ?>'>
        <label for="m_category" style='margin-top: 20px;'>
            （管理员评分）<span class="required"></span>
        </label>
        <fieldset class="rating" style='margin-top: 20px;'>
            <legend><?php
                if (isset ($task_msg ['tw_score']) && ($task_msg ['tw_score'] != TaskWhen::ZERO_STAR)) {
                    echo "已评分/得分：" . $task_msg ['tw_score'];
                } else {
                    echo "评分:";
                }
                ?>
            </legend>
            <input onclick="getScore(this)" type="radio" id="star<?php echo TaskWhen::FIVE_STAR ?>" name="rating"
                   value="<?php echo TaskWhen::FIVE_STAR ?>"
                <?php
                if (isset ($task_msg ['tw_score']) && ($task_msg ['tw_score'] == TaskWhen::FIVE_STAR)) {
                    echo 'checked';
                }
                ?> />
            <label for="star<?php echo TaskWhen::FIVE_STAR ?>" title="Rocks!">
                <?php echo TaskWhen::FIVE_STAR ?> stars
            </label>

            <input onclick="getScore(this)" type="radio" id="star<?php echo TaskWhen::FOUR_STAR ?>" name="rating"
                   value="<?php echo TaskWhen::FOUR_STAR ?>"
                <?php
                if (isset ($task_msg ['tw_score']) && ($task_msg ['tw_score'] == TaskWhen::FOUR_STAR)) {
                    echo 'checked';
                }
                ?> />
            <label for="star<?php echo TaskWhen::FOUR_STAR ?>" title="Pretty good">
                <?php echo TaskWhen::FOUR_STAR ?> stars
            </label>
            <input onclick="getScore(this)" type="radio" id="star<?php echo TaskWhen::THREE_STAR ?>" name="rating"
                   value="<?php echo TaskWhen::THREE_STAR ?>"
                <?php
                if (isset ($task_msg ['tw_score']) && ($task_msg ['tw_score'] == TaskWhen::THREE_STAR)) {
                    echo 'checked';
                }
                ?> />
            <label for="star<?php echo TaskWhen::THREE_STAR ?>" title="Meh">
                <?php echo TaskWhen::THREE_STAR ?> stars
            </label>
            <input onclick="getScore(this)"
                   type="radio" id="star<?php echo TaskWhen::TWO_STAR ?>" name="rating"
                   value="<?php echo TaskWhen::TWO_STAR ?>"
                <?php
                if (isset ($task_msg ['tw_score']) && ($task_msg ['tw_score'] == TaskWhen::TWO_STAR)) {
                    echo 'checked';
                }
                ?> />
            <label for="star<?php echo TaskWhen::TWO_STAR ?>" title="Kinda bad">
                <?php echo TaskWhen::TWO_STAR ?> stars
            </label>
            <input onclick="getScore(this)" type="radio" id="star<?php echo TaskWhen::ONE_STAR ?>" name="rating"
                   value="<?php echo TaskWhen::ONE_STAR ?>"
                <?php
                if (isset ($task_msg ['tw_score']) && ($task_msg ['tw_score'] == TaskWhen::ONE_STAR)) {
                    echo 'checked';
                }
                ?> />
            <label for="star<?php echo TaskWhen::ONE_STAR ?>" title="Sucks big time">
                <?php echo TaskWhen::ONE_STAR ?> star
            </label>
        </fieldset>
    </div>
    <input type='hidden' id='f_id' value='<?php echo $task_msg ['f_id']; ?>'>
    <input type='hidden' id='accept' value='<?php echo $task_msg ['accept_id']; ?>'>
    <input type='hidden' id='publish' value='<?php echo $task_msg ['publish_id']; ?>'>
    <input type='hidden' id='tw_id' value='<?php echo $task_msg ['tw_id']; ?>'>
    <input type='hidden' id='tw_status' value='<?php echo $task_msg ['tw_status']; ?>'>
    <input type='hidden' id='t_id' value='<?php echo $task_msg ['t_id']; ?>'>
    <input type='hidden' id='tw_isset' value='<?php echo $task_msg ['tw_isset']; ?>'>
    <input type='hidden' id='role' value='<?php echo $role; ?>'>
    <input type='hidden' id='t_sendtime' value='<?php echo $task_msg ['t_sendime']; ?>'>
    <input type='hidden' id='type' value='<?php echo $model [0] ['type']; ?>'>
    <input type='hidden' id='mid' value='<?php echo $model [0] ['mid']; ?>'>
    <input type='hidden' id='t_mid' value=''>
    <input type='hidden' id='monday' value='<?php echo $monday; ?>'>
<?php
}
?>

<?php
$this->renderPartial('/layouts/pop');
?>
<script type="text/javascript">

    $(document).ready(function () {

        $('.item .delete').click(function () {

            var elem = $(this).closest('.item');

            $.confirm({
                'title': 'Delete Confirmation',
                'message': 'You are about to delete this item. <br />It cannot be restored at a later time! Continue?',
                'buttons': {
                    'Yes': {
                        'class': 'blue',
                        'action': function () {
                            elem.slideUp();
                        }
                    },
                    'No': {
                        'class': 'gray',
                        'action': function () {
                        }	// Nothing to do in this case. You can as well omit the action property.
                    }
                }
            });

        });

    });
</script>






