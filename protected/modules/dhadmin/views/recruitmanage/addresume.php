<style type="text/css">
    .resume_main{
        width: 500px;
        margin:150px auto;
    }
    .resume-sel{
        width: 200px;
        height: 30px;
        border-radius: 5px;
    }
    .categorty-title{
        margin-right: 15px;
    }
    .form-group p{
        margin-left: 20px;
    }
    .form-7 textarea{
        margin-left: 50px;
    }
    .form-8 textarea,.form-9 textarea{
        display: block;
        margin-left: 20px;
    }
    .resume_main .confirm{
        width: 82px;
        height:34px;
        color: white;
        background: #337ab7;
        text-align: center;
        line-height: 34px;
        border-radius: 5px;
        border: 1px solid #2e6da4;
        margin-left: 200px;
    }
</style>
<script type="text/javascript">
    $(function () {
        // 确认提交数据
        $('.form-1 select').change(function () {
            var p = $('.form-1 select').val();
            $.ajax({
                type:'post',
                dataType:"json",
                url:"/dhadmin/recruitmanage/resumeAjax",
                data:{
                    'classid':p
                },
                success:function (data) {
                    $('.form-2 .resume-sel').children().remove();
                    $(data.val).each(function (index,dom) {
                        var op = " <option value='" + dom.jobname + "'>"+dom.jobname+"</option>"
                        $('.form-2 .resume-sel').append(op);
                    })
                },
                error:function () {
                    console.log('1111');
                }
            })
        })
        
        // 多输入框显示字数
        $('.form-10 textarea').keyup(function () {
            $('.form-10 .textnum').text($(this).val().length);
        })
        $('.form-11 textarea').keyup(function () {
            $('.form-11 .textnum').text($(this).val().length);
        })

        // 对应模板数据
        var reg = window.location.href;
        if (reg.indexOf('linkid')>0 && reg.indexOf('id')>0) {
            var r = reg.split('=');// 拆分字符串r[1]既是传递的参数
            var linkid = r[1].replace('&id', '');
            var id = r[2];
            $.ajax({
                type: 'post',
                dataType: "json",
                url: "/dhadmin/recruitmanage/editResume",
                data: {
                    'linkid': linkid,
                    'id': id
                },
                success: function (data) {
                    $('.form-1 select').val(data.val[0].link_id);
                    $('.form-1 option:first-child').remove();
                    $('.form-2 .resume-sel').children().remove();
                    $(data.val1).each(function (index, dom) {
                        var op = " <option value='" + dom.jobname + "'>" + dom.jobname + "</option>"
                        $('.form-2 .resume-sel').append(op);
                    })
                    $('.form-2 .resume-sel').val(data.val[0].jobname);
                    $('.form-3 input').each(function () {
                        if ($(this).val() == data.val[0].age) {
                            $(this).attr('checked', 'checked');
                        }
                    });
                    $('.form-4 input').each(function () {
                        if ($(this).val() == data.val[0].sex) {
                            $(this).attr('checked', 'checked');
                        }
                    });
                    $('.form-5 input').each(function () {
                        if ($(this).val() == data.val[0].need_num) {
                            $(this).attr('checked', 'checked');
                        }
                    });
                    $('.form-6 input').each(function () {
                        if ($(this).val() == data.val[0].education) {
                            $(this).attr('checked', 'checked');
                        }
                    });
                    $('.form-7 input').each(function () {
                        if ($(this).val() == data.val[0].experience) {
                            $(this).attr('checked', 'checked');
                        }
                    });
                    $('.form-8 input').each(function () {
                        if ($(this).val() == data.val[0].salary) {
                            $(this).attr('checked', 'checked');
                        }
                    });
                    // 职位亮点
                    var paydata = $.parseJSON(data.val[0].pay);
                    if (paydata['content']) {
                        $('.form-9 textarea').val(paydata['content']);
                    }
                    $.each(paydata, function (index, dom) {
                        $('.form-9 input').each(function () {
                            if ($(this).val() == dom) {
                                $(this).attr('checked', 'checked');
                            }
                        });
                    })
                    // 岗位职责
                    var dataduty = $.parseJSON(data.val[0].duty);
                    if (dataduty) {
                        $('.form-10 textarea').val(dataduty);
                        $('.form-10 .textnum').text(dataduty.length)
                    }

                    // 任职要求
                    var datarequire = $.parseJSON(data.val[0].job_require);
                    if (datarequire) {
                        $('.form-11 textarea').val(datarequire);
                        $('.form-11 .textnum').text(datarequire.length)
                    }

                    // 工作地点
                    var dataplace = $.parseJSON(data.val[0].working_place);
                    if (dataplace) {
                        $('.form-12 textarea').val(dataplace);
                    }


                    // 电话
                    $('.form-13 input').val(data.val[0].tel_num);
                    // 邮箱
                    $('.form-14 input').val(data.val[0].mail);

                },
                error: function () {
                    console.log('1111');
                }
            })
        }

        /**
         * 提交按钮
         */
        $('.confirm').click(function () {
            var arr = {};
            var arr1={};
            var num;
            arr['link_id'] = $('.form-1 select').val();
            arr['jobname'] = $('.form-2 select').val();
            arr['age'] = $('.form-3 input:checked').val();
            arr['sex'] = $('.form-4 input:checked').val();
            arr['num'] = $('.form-5 input:checked').val();
            arr['education'] = $('.form-6 input:checked').val();
            arr['experience'] = $('.form-7 input:checked').val();
            arr['salary'] = $('.form-8 input:checked').val();
            ($('.form-9 input:checked').each(function (index,dom) {
                arr1[index+1] = $(dom).val()
            }));
            // $.trim去掉空格
            if($('.form-9 textarea').val().length && $.trim($(".form-9 textarea").val()).length){
                arr1['content']=$('.form-9 textarea').val();
            }else {
            }
            arr['pay'] = arr1;
            if ($(".form-10 textarea").val().length && $.trim($(".form-10 textarea").val()).length){
                arr['job_duty'] = $(".form-10 textarea").val();
            }else {
                num = 1;
            }

            if ($(".form-11 textarea").val().length && $.trim($(".form-11 textarea").val()).length){
                arr['require'] = $(".form-11 textarea").val();
            }else {
                num = 1;
            }

            if($('.form-12 textarea').val().length && $.trim($(".form-12 textarea").val()).length){
                arr['working_place']=$('.form-12 textarea').val();
            }else {
                num = 1;
            }
            arr['tel_num'] = $('.form-13 input').val();
            arr['mail'] = $('.form-14 input').val();
            console.log(arr);
//            console.log(arr.length);
            $.each(arr,function (index,p) {
              if (p){

              }else {
                  num=1;
              }
            })
                if (!num){
                    // 传递提交数据
                     $.ajax({
                        type:'post',
                         dataType:"json",
                         url:"/dhadmin/recruitmanage/resumeSubmit",
                         data:{
                             'arr':arr,
                             'id' :id
                            },
                        success:function (data) {
                            console.log('sucess');
                         },
                        error:function () {
                             console.log('1111');
                        }
                     })
                    window.location.href= 'resume';
                }else {
                    alert('请补全信息');
                };
        });
    })

</script>
<div class="resume_main">
    <div class="form-group form-1">
        <label  class="categorty-title">类别名称</label>
        <select class="resume-sel">
            <option value="">请选择</option>
            <?php foreach ($data as $vt):;?>
                <?php echo "
            <option value='{$vt['classid']}'>{$vt['classname']}</option>
            "?>
            <?php endforeach;?>
        </select>
    </div>
    <div class="form-group form-2">
        <label  class="categorty-title" >招聘职位</label>
        <select class="resume-sel">
        </select>
    </div>
    <div class="form-group form-3">
        <label  class="categorty-title">年龄要求:</label>
        <input type="radio" name="age"  value="1" >30岁以下
        <input type="radio" name="age"  value="2" >30岁以上
        <input type="radio" name="age"  value="3" >不限
    </div>
    <div class="form-group form-4">
        <label  class="categorty-title">性别要求:</label>
        <input type="radio" name="sex"  value="1" >男
        <input type="radio" name="sex"  value="2" >女
        <input type="radio" name="sex"  value="3" >不限
    </div>
    <div class="form-group form-5">
        <label  class="categorty-title">招聘人数:</label>
        <input type="radio" name="num"  value="1" >1人
        <input type="radio" name="num"  value="2" >2人
        <input type="radio" name="num"  value="3" >若干
    </div>
    <div class="form-group form-6">
        <label  class="categorty-title">学&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;历:</label>
        <p><input type="radio" name="education"  value="1" >不限<p>
        <p><input type="radio" name="education"  value="2" >大专及以上<p>
        <p><input type="radio" name="education"  value="3" >本科及以上<p>
    </div>
    <div class="form-group form-7">
        <label  class="categorty-title">工作经验:</label>
        <p><input type="radio" name="experience"  value="1" >不限，可接受应届毕业生</p>
        <p><input type="radio" name="experience"  value="2" >一年以下</p>
        <p><input type="radio" name="experience"  value="3" >一年到两年</p>
        <p><input type="radio" name="experience"  value="4" >两年到三年</p>
    </div>
    <div class="form-group form-8">
        <label  class="categorty-title">薪&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;资:</label>
        <p><input type="radio" name="salary"  value="1">2K-4K</p>
        <p><input type="radio" name="salary"  value="2" >4K-6K</p>
        <p><input type="radio" name="salary"  value="3" >6K-8K</p>
        <p><input type="radio" name="salary"  value="4" >面议</p>
    </div>
    <div class="form-group form-9">
        <label  class="categorty-title">职位亮点:</label>
        <p><input type="checkbox" name="pay"  value="1">六险一金</p>
        <p><input type="checkbox" name="pay"  value="2" >带薪年假</p>
        <p><input type="checkbox" name="pay"  value="3" >周末双休</p>
        <p><input type="checkbox" name="pay"  value="4" >年底双薪</p>
        <p><input type="checkbox" name="pay"  value="5" >全勤奖</p>
        <p><input type="checkbox" name="pay"  value="6" >员工旅游</p>
        <p>其他:</p>
        <textarea name="MSG" cols=40 rows=3 maxlength="50" style="overflow-x:hidden;resize: none" placeholder="最多可输入50个字符"></textarea>
    </div>
    <div class="form-group form-10">
        <label  class="categorty-title">岗位职责:</label>
        <p style="font-size: 10px;margin-left: 380px;color: grey">已经输入<span class="textnum">0</span>个字</p>
        <textarea name="duty" wrap="physical" style="width: 450px;height: 300px; overflow-x:hidden;resize: none" placeholder="请输入..."></textarea>
    </div>
    <div class="form-group form-11">
        <label  class="categorty-title">任职要求:</label>
        <p style="font-size: 10px;margin-left: 380px;color: grey">已经输入<span class="textnum">0</span>个字</p>
        <textarea name="require" style="width: 450px;height: 300px; overflow-x:hidden;resize: none" placeholder="请输入..."></textarea>

    </div>

    <div class="form-group form-12">
        <label  class="categorty-title">工作地点:</label>
        <textarea name="place" maxlength="50" style="width: 450px;height: 50px; margin-top:10px;overflow-x:hidden;resize: none" placeholder="最多可输入50个字符"></textarea>
    </div>

    <div class="form-group form-13">
        <label  class="categorty-title">联&nbsp;&nbsp;系&nbsp;&nbsp;电&nbsp;&nbsp;&nbsp;话:</label>
        <input type="text"value="">
    </div>
    <div class="form-group form-14">
        <label  class="categorty-title">简历接收邮箱:</label>
        <input type="text"value="">
    </div>
    <button class="confirm">确认提交</button>


</div>