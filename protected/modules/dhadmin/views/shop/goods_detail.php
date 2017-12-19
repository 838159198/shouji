<?php
$this->breadcrumbs = array(
    '商品列表' => array('index'),
    '商品详情',
);
?>
<div class="breadcrumbs">
    <a href="/dhadmin/shop/index">首页</a> &raquo; <a href="/dhadmin/shop/index">商品列表</a> &raquo; <span>商品详情</span></div>
<h4 class="text-center">商品详情</h4>

<form class="form-horizontal" role="form">
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">商品名称</label>
        <div class="col-sm-10">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo $data['title'];?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">商品链接</label>
        <div class="col-sm-10">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo empty($data['address'])?"":$data['address'];?>" disabled>
        </div>
    </div>
    <fieldset disabled>
        <div class="form-group">
            <label for="disabledSelect"  class="col-sm-2 control-label">状态</label>
            <div class="col-sm-10">
                <select id="disabledSelect" class="form-control">
                    <option><?php echo $data['xstatus'];?></option>
                </select>
            </div>
        </div>
    </fieldset>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">购买积分</label>
        <div class="col-sm-10">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo $data['credits'];?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">已兑换人数</label>
        <div class="col-sm-10">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo $data['num'];?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">排序</label>
        <div class="col-sm-10">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo $data['order'];?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">简介</label>
        <div class="col-sm-10">
            <textarea class="form-control" rows="3" disabled><?php echo $data['intro'];?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">详细内容</label>
        <div class="col-sm-10">
            <?php echo $data['content'];?>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">发布人</label>
        <div class="col-sm-10">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo $data->user->name;?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">创建时间</label>
        <div class="col-sm-10">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo date("Y-m-d H:i:s",$data['create_datetime']);?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">更新时间</label>
        <div class="col-sm-10">
            <input class="form-control" id="disabledInput" type="text" value="<?php echo date("Y-m-d H:i:s",$data['update_datetime']);?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">封面图</label>
        <div class="col-sm-10">
            <img src="<?php echo $data['coverimage'];?>" border="0">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">预览图</label>
        <div class="col-sm-10">
            <img src="<?php echo $data['previewimage'];?>" border="0">
        </div>
    </div>
</form>
