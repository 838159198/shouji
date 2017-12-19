<script type="text/javascript">

    /**
     * 实时动态强制更改用户录入
     * arg1 inputObject
     **/
    function amount(th){
        var regStrs = [
            ['^0(\\d+)$', '$1'], //禁止录入整数部分两位以上，但首位为0
            ['[^\\d\\.]+$', ''], //禁止录入任何非数字和点
            ['\\.(\\d?)\\.+', '.$1'], //禁止录入两个以上的点
            ['^(\\d+\\.\\d{2}).+', '$1'] //禁止录入小数点后两位以上
        ];
        for(i=0; i<regStrs.length; i++){
            var reg = new RegExp(regStrs[i][0]);
            th.value = th.value.replace(reg, regStrs[i][1]);
        }
    }

    /**
     * 录入完成后，输入模式失去焦点后对录入进行判断并强制更改，并对小数点进行0补全
     * arg1 inputObject
     * 这个函数写得很傻，是我很早以前写的了，没有进行优化，但功能十分齐全，你尝试着使用
     * 其实有一种可以更快速的JavaScript内置函数可以提取杂乱数据中的数字：
     * parseFloat('10');
     **/
    function overFormat(th){
        var v = th.value;
        if(v === ''){
            v = '0.00';
        }else if(v === '0'){
            v = '0.00';
        }else if(v === '0.'){
            v = '0.00';
        }else if(/^0+\d+\.?\d*.*$/.test(v)){
            v = v.replace(/^0+(\d+\.?\d*).*$/, '$1');
            v = inp.getRightPriceFormat(v).val;
        }else if(/^0\.\d$/.test(v)){
            v = v + '0';
        }else if(!/^\d+\.\d{2}$/.test(v)){
            if(/^\d+\.\d{2}.+/.test(v)){
                v = v.replace(/^(\d+\.\d{2}).*$/, '$1');
            }else if(/^\d+$/.test(v)){
                v = v + '.00';
            }else if(/^\d+\.$/.test(v)){
                v = v + '00';
            }else if(/^\d+\.\d$/.test(v)){
                v = v + '0';
            }else if(/^[^\d]+\d+\.?\d*$/.test(v)){
                v = v.replace(/^[^\d]+(\d+\.?\d*)$/, '$1');
            }else if(/\d+/.test(v)){
                v = v.replace(/^[^\d]*(\d+\.?\d*).*$/, '$1');
                ty = false;
            }else if(/^0+\d+\.?\d*$/.test(v)){
                v = v.replace(/^0+(\d+\.?\d*)$/, '$1');
                ty = false;
            }else{
                v = '0.00';
            }
        }
        th.value = v;
    }
</script>
<div class="breadcrumbs">
    <a href="/manage/default/index">首页</a> &raquo; <a href="/manage/import/aaa">隐藏广告单独设置</a></div>
<h4 class="text-center">隐藏广告单独设置</h4>
<div class="alert alert-danger">
    <strong>注意事项</strong>
    <ol>
        <li>用户名：1327651490</li>
        <li>ID：10025</li>
        <li>资源id：107083</li>
    </ol>
</div>

<form id="admin-form" action="/manage/import/aaa" method="post" class="form-horizontal">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'admin-form',
        'enableAjaxValidation' => false,
        'htmlOptions' =>array('class'=>"form-horizontal"),
    )); ?>
    <div class="control-group">
        <label class="control-label" for="inputEmail">日期</label>
        <div class="controls">
            <?php echo $form->textField($model, 'createtime',array('lang'=>"date")); ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputEmail">金额</label>
        <div class="controls">
            <?php echo $form->textField($model, 'data',array('onKeyUp'=>"amount(this)",'onBlur'=>"overFormat(this)")); ?>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <input class="btn btn-primary btn-large" type="submit" name="yt0" value="提交" />
        </div>
    </div>
    <?php $this->endWidget(); ?>
    <?php
    if(Yii::app()->user->hasFlash("status_ok")){?>
        <div class="alert alert-success"><?php echo Yii::app()->user->getFlash("status_ok"); ?></div>
    <?php }?>
    <?php
    if(Yii::app()->user->hasFlash("status_exists")){?>
        <div class="alert alert-error"><?php echo Yii::app()->user->getFlash("status_exists"); ?></div>
    <?php }?>
    <?php
    if(Yii::app()->user->hasFlash("status_fail")){?>
        <div class="alert alert-error"><?php echo Yii::app()->user->getFlash("status_fail"); ?></div>
    <?php }?>
<table class="table table-bordered table-hover">
    <tr>
       <th>日期</th>
        <th>金额</th>
    </tr>
<?php foreach($ycgg_data as $row){?>
    <tr>
        <td><?php echo $row['createtime']?></td>
        <td><?php echo $row['data']?></td>

    </tr>
    <?php }?>
</table>