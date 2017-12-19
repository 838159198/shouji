<style type="text/css">
    dd{height: 35px;line-height: 35px;}
</style>

<div class="page-header app_head">
    <h1 class="text-center text-primary">测试数据管理（请慎操作）！</h1>
</div>

<div class="alert alert-danger">注意：删除均为测试数据，注意区分，删除后不可还原，尽量都选择日期内uid测试数据，日期为必填</div>
<?php
$tables=array('app_client_data'=>'client_data','app_rom_appresource'=>'rom_appresource','app_rom_appupdata'=>'rom_appupdata','app_rom_appupdata_dtmark'=>'rom_appupdata_dtmark','app_rom_appupdatalog'=>'app_rom_appupdatalog');
$uids=array('23'=>'23','304'=>'304','510'=>'510','722'=>'722','1128'=>'1128');
$imeis=array(
    '351878061865246'=>'351878061865246#SM-G900L',
    '860770020460215'=>'860770020460215#K1391',
    '864690027292393'=>'864690027292393#MI 3W',
    '863177025078231'=>'863177025078231#R821T',
    '868256020596432'=>'868256020596432#letv x500',
    '866769021264109'=>'866769021264109#努比亚',
    '867931025955962'=>'867931025955962#华为',
    '868201023263283'=>'868201023263283#魅族mx5',
    '868715024270294'=>'868715024270294#Redmi Note 2',
    '359320050933117'=>'359320050933117#nexus 6',
    '864501020163962'=>'864501020163962#X9180',
    '866769021864106'=>'866769021864106#努比亚',
    '866046029501984'=>'866046029501984#联想Lenovo K50-T5',
    '864690022033537'=>'864690022033537#小米MI3W',
    '869092020283815'=>'869092020283815#联想K52t38',
    '861795032193149'=>'861795032193149#酷派C106',
    '862023030583651'=>'862023030583651#oppoA59m',
    '869092020283823'=>'869092020283823#Lenovo-k52t38',
    '862023030583644'=>'862023030583644#OPPO-A59m',
    '867931026158004'=>'867931026158004#HUAWEI-TAG-AL00',
    '860034032439356'=>'860034032439356#JD-T-20160122T-V1.1',
    '860034032439364'=>'860034032439364#JD-T-20160122T-V1.1',
    '353919025680137'=>'353919025680137#酷派5270',
    '864426032755144'=>'864426032755144#vivo-X9i',
    '864426032755136'=>'864426032755136#vivo-X9i',
    '862304030359171'=>'862304030359171#DOOV-V3',
    '862304030359176'=>'862304030359176#DOOV-V3',
    '868623028454791'=>'868623028454791#酷派5263',
    '990005629935731'=>'990005629935731#酷派5263',
    '866004036641392'=>'866004036641392#Redmi 4X',
    '861084037358720'=>'861084037358720#Redmi Note 3',
    '866412033642141'=>'866412033642141#MI 5X',
    '866048030208674'=>'866048030208674#HUAWEI PRA-AL00',
    '862932030084454'=>'862932030084454#HUAWEI TIT-AL00',
    '864100031988226'=>'864100031988226#HUAWEI SLA-AL00',
    '861010033685899'=>'861010033685899#HUAWEI MLA-AL00',
    '865471032294362'=>'865471032294362#HUAWEI DLI-AL10',
    '863991035726971'=>'863991035726971#HUAWEI VNS-AL00',
    '865628036914630'=>'865628036914630#OPPO R9st',
    '866010032504096'=>'866010032504096#OPPO A37m',
    '869409020650977'=>'869409020650977#OPPO A33',
    '866697033610896'=>'866697033610896#vivo Y66',
    '863208031370617'=>'863208031370617#vivo Y51A',
    '868970020313412'=>'868970020313412#vivo Y37',
    

);
?>
<?php echo CHtml::beginForm() ?>
<dl class="dl-horizontal">
    <dt>选择表</dt>
    <dd><?php echo CHtml::dropDownList('table', '', $tables) ?></dd>
    <dt>用户id</dt>
    <dd><?php echo CHtml::dropDownList('uid', '', $uids,array('empty'=>'')) ?></dd>
    <dt>IMEI码</dt>
    <dd><?php echo CHtml::dropDownList('imeicode', '', $imeis,array('empty'=>'')) ?></dd>
    <dt>删除日期</dt>
    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" style="padding-left: 20px;">
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
        <input lang="date" class = "input-small" id="closedate"  name="closedate" size="10"  data-rule="required" type="text" >&nbsp;<span class="label label-info">空为全部</span>
    </div>
    <dd style="margin-top: 20px;"><?php echo CHtml::submitButton('删除', Bs::cls(Bs::BTN_PRIMARY)) ?></dd>
</dl>
<?php echo CHtml::endForm() ?>

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