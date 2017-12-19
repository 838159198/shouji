<?php
$this->breadcrumbs = array('任务审核' => array('check'), '任务查看');
?>
<style type="text/css">
    label{margin-top: 10px;}
    .btn-lg{margin-top: 10px;}
</style>

<div id="task_when_msg">

    <div style='float: left; margin: 50px;width: 200px;margin-left: 200px;'>
        <label for="m_category">用户名 <span class="required"></span></label>
        <input type='text' name='123' value='<?php echo $task_msg ['username']; ?>'>
        <label for="m_category">发布人 <span class="required"></span></label>
        <input type='text' name='123'value='<?php echo $task_msg ['publish']; ?>'>
        <label for="m_category">客服 <span class="required"></span></label>
        <input type='text' name='123'value='<?php echo $task_msg ['accept'];?>'>
        <label for="m_category">客服报告任务的时间 <span class="required"></span></label>
        <input type='text' name='123'value='<?php echo $task_msg ['tw_porttime'];?>'>
        <label for="m_category">任务创建创建时间 <span class="required"></span></label>
        <input type='text' name='123'value='<?php echo $task_msg ['a_time']; ?>'>
        ‭<label for="m_category">回复内容 <span class="required"></span></label>
        <textarea rows="5" cols="20" value=''><?php echo $task_msg ['tw_content']; ?></textarea>
    </div>
    <div style='float: left; margin: 50px;width: 200px;'>
        <label for="m_category">任务状态 <span class="required"></span></label>
        <input type='text' name='123'is_back='yes' id='is_back'value='<?php
                                                        if ($task_msg ['tw_status'] == 0) {
                                                            echo '任务正在执行中';
                                                        } elseif ($task_msg ['tw_status'] == 1) {
                                                            echo '任务已上报';
                                                        } elseif ($task_msg ['tw_status'] == 2) {
                                                            echo '任务打回修改';
                                                        };
                                                        ?>'>
        <label for="m_category">任务是否失败 <span class="required"></span></label>
        <input type='text' name='123' value='<?php
                if ($task_msg ['tw_isfail'] == 0) {
                   echo '任务未失败';
               } else if ($task_msg ['tw_isfail'] == 1) {
                   echo '任务失败';
               }
               ?>'>
        <label for="m_category" style='clear: both; margin-top: 20px'><span class="required"></span></label>
        <?php
        $ro = Manage::model()->getRoleByUid($task_msg ['f_id']); //当前登录客服的级别
        if (($task_msg ['type'] != Task::TYPE_VISIT) || $ro == Role::PRACTICE_STAFF) { ?>
            <div>
                <?php echo CHtml::submitButton('驳回任务', array_merge(Bs::cls(Bs::BTN_PRIMARY, Bs::BTN_LARGE), array('onclick' => 'back_task(\'' . $task_msg ['t_id'] . '\',\'' . $task_msg ['tw_id'] . '\')')))?>
            </div>
            <div style='border:0px solid #e7e7e7;display:<?php
            if ($role < Manage::MANAGE_POWER) {
                echo 'display';
            } else {
                echo 'none';
            }
            ?>'>
                <label for="m_category" style='margin-top: 60px'>管理员评分<span class="required"></span> </label>
                <fieldset class="rating">
                    <legend><?php
                        if (isset ($task_msg ['tw_score']) && ($task_msg ['tw_score'] != TaskWhen::ZERO_STAR)) {
                            echo "已评分/得分：" . $task_msg ['tw_score'];
                        } else {
                            echo "评分:";
                        }
                        ?>
                    </legend>
                    <input onclick="getScore(this)" type="radio" id="star<?php echo TaskWhen::FIVE_STAR ?>"
                           name="rating"
                           value="<?php echo TaskWhen::FIVE_STAR ?>"
                        <?php
                        if (isset ($task_msg ['tw_score']) && ($task_msg ['tw_score'] == TaskWhen::FIVE_STAR)) {
                            echo 'checked';
                        }
                        ?> />
                    <label for="star<?php echo TaskWhen::FIVE_STAR ?>" title="Rocks!">
                        <?php echo TaskWhen::FIVE_STAR ?> stars
                    </label>

                    <input onclick="getScore(this)" type="radio" id="star<?php echo TaskWhen::FOUR_STAR ?>"
                           name="rating"
                           value="<?php echo TaskWhen::FOUR_STAR ?>"
                        <?php
                        if (isset ($task_msg ['tw_score']) && ($task_msg ['tw_score'] == TaskWhen::FOUR_STAR)) {
                            echo 'checked';
                        }
                        ?> />
                    <label for="star<?php echo TaskWhen::FOUR_STAR ?>" title="Pretty good">
                        <?php echo TaskWhen::FOUR_STAR ?> stars
                    </label>
                    <input onclick="getScore(this)" type="radio" id="star<?php echo TaskWhen::THREE_STAR ?>"
                           name="rating"
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

            <div style='clear:both;margin-top:50px;border:0px solid #e7e7e7;display:<?php
            if ($role < 4) {
                echo 'display';
            } else {
                echo 'none';
            }
            ?>'>
                <?php
                echo CHtml::button('清除任务', array_merge(Bs::cls(Bs::BTN_DANGER), array('onclick' => 'del_this_task(
                \'' . $task_msg ['m_id'] . '\',\'' . $task_msg ['at_id'] . '\',
                \'' . $task_msg ['t_id'] . '\',\'' . $task_msg ['tw_id'] . '\',
                \'' . $task_msg ['tw_isfail'] . '\')')));
                ?>
            </div>

        <?php
        }
        ?>

    </div>
    <input type='hidden' id='f_id' value='<?php echo $task_msg ['f_id']; ?>'>
    <input type='hidden' id='accept' value='<?php echo $task_msg ['accept_id']; ?>'>
    <input type='hidden' id='publish' value='<?php echo $task_msg ['publish_id']; ?>'>
    <input type='hidden' id='tw_id' value='<?php echo $task_msg ['tw_id']; ?>'>
    <input type='hidden' id='t_id' value='<?php echo $task_msg ['t_id']; ?>'>
    <input type='hidden' id='tw_isset' value='<?php echo $task_msg ['tw_isset']; ?>'>
    <input type='hidden' id='role' value='<?php echo $role; ?>'>

    <div style='clear: both'></div>
</div>

<?php $this->renderPartial('/layouts/pop');?>
