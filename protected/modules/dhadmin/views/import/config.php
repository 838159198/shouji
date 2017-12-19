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
            ['^(\\d+\\.\\d{1}).+', '$1'] //禁止录入小数点后两位以上
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
            v = '0.0';
        }else if(v === '0'){
            v = '0.0';
        }else if(v === '0.'){
            v = '0.0';
        }else if(/^0+\d+\.?\d*.*$/.test(v)){
            v = v.replace(/^0+(\d+\.?\d*).*$/, '$1');
            v = inp.getRightPriceFormat(v).val;
        }else if(/^0\.\d$/.test(v)){
            v = v;
        }else if(!/^\d+\.\d{1}$/.test(v)){
            if(/^\d+\.\d{1}.+/.test(v)){
                v = v.replace(/^(\d+\.\d{1}).*$/, '$1');
            }else if(/^\d+$/.test(v)){
                v = v + '.0';
            }else if(/^\d+\.$/.test(v)){
                v = v + '0';
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
                v = '0.0';
            }
        }
        th.value = v;
    }
</script>
    <div class="breadcrumbs">
        <a href="/manage/default/index">首页</a> &raquo; <a href="/manage/import/config">业务扣量设置</a></div>
    <h4 class="text-center">业务扣量设置</h4>
    <div class="alert alert-danger">
        <strong>注意事项</strong>
        <ol>
            <li>修改完成后，请刷新本页面查看数值是否正确</li>
        </ol>
    </div>

    <form id="admin-form" action="/manage/import/config" method="post" class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="inputEmail">云增值</label>
            <div class="controls">
                <input type="text" name="eda" id="eda"  onKeyUp="amount(this)" onBlur="overFormat(this)" value="<?php echo Yii::app()->params['importConfig']['eda'];?>">
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input class="btn btn-primary btn-large" type="submit" name="yt0" value="提交" />
            </div>
        </div>
        <?php
        if(Yii::app()->user->hasFlash("config-ok")){?>
            <div class="alert alert-success"><?php echo Yii::app()->user->getFlash("config-ok"); ?></div>
        <?php }?>
        <?php
        if(Yii::app()->user->hasFlash("config-fail")){?>
            <div class="alert alert-error"><?php echo Yii::app()->user->getFlash("config-fail"); ?></div>
        <?php }?>

    </form>