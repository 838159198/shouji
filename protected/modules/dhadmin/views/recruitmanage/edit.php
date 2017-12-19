<style type="text/css">
    .edit-main{
        margin: 200px auto;
    }
    .category-submit{
        /*float: right;*/
     margin: 50px auto;
    }
    .categorty-main{
        width: 300px;

        margin: 70px auto;
    }
    .categorty-add,.categorty-del{
        height: 20px;
        font-size: 25px;
        color: #0e0e0e;
        margin-left: 10px;
        cursor: pointer;
    }



</style>
<script type="text/javascript">
    var arr = {};
    var catearr={};
    $(function () {
        var reg = window.location.href;
        var r = reg.split('=');// 拆分字符串r[1]既是传递的参数
        var id = r[1];
        if (id) {
            $.ajax({
                type: 'post',
                dataType: "json",
                url: "/dhadmin/recruitmanage/editajax",
                data: {
                    'classid': id
                },
                success: function (data) {
                    $('.name').attr('value', data.val[0]['classname']);
                    $('.name').attr('id', data.val[0]['id']);
                    $.each(data.valjob, function (index, datajob) {
                        if (index == 0) {
                            $('.job').attr('value', datajob.jobname);
                            $('.job').attr('id', datajob.id);
                        } else {
                            var cloneinput = "<input class='job' id='" + datajob.id + "' type='text' style='display: block;margin-left: 61px;margin-top: 20px' placeholder='请输入职位' value='" + datajob.jobname + "'/>";
                            $('.form-job').append($(cloneinput));
                        }
                    })
                },
                error: function () {
                    console.log('111');
                }
            })
        }
        // 添加职位输入框
        $('.categorty-add').click(function () {
           var add = "<input class='job' type='text' style='display: block;margin-left: 61px;margin-top: 20px' placeholder='请输入职位'/>";
            $('.form-job').append($(add));
        })
        // 删除输入框
        $('.categorty-del').click(function () {
            $('.form-job input:last-child').remove();// 移除最后一个input标签
        })

        // 确认提交
        $('.btn-primary').click(function () {
            if ($('.categorty-main .form-title .name').attr('id')){
                catearr['name'] = $('.categorty-main .form-title .name').val();
                catearr['id'] = $('.categorty-main .form-title .name').attr('id')
            }else {
                catearr['name'] = $('.categorty-main .form-title .name').val();
                catearr['id'] = -1;
            }
          $('.categorty-main .form-job .job').each(function (index,dom) {
              var arr1 ={};
              if ($(dom).attr('id')){
                  arr1['name'] = $(dom).val();
                  arr1['id'] = $(dom).attr('id');
              }else {
                  arr1['id'] = -1;
                  arr1['name'] = $(dom).val();

              }
              arr[index] =arr1;
          });
           
           if (catearr['name'] && arr[0]['name']) {
               $.ajax({
                   type: 'post',
                   dataType: "json",
                   url: "/dhadmin/recruitmanage/submit",
                   data: {
                       'catearr': catearr,
                       'jobarr': arr,
                       'submittime':id
                   },
                   success: function (data) {
                       console.log(data.val);
                      if(data.val == 'error'){
                          alert("已存在相同的类别名");
                      }
                   },
                   error: function () {
                       history.back(-1);
                   }
               })

           }else {
               alert('表格不能为空');
           }
        })

    })
</script>
<div class="edit-main">

    <div class="categorty-main">
            <div class="form-group form-title">
                <label  class="categorty-title">类别名称</label>
                <input class="name" type="text" placeholder="请输入类别名" value=""/>
            </div>
            <div class="form-group form-job">
                <label  class="categorty-title">招聘职位</label>
                <input class="job" type="text" placeholder="请输入职位" value=""/>
                <span class="categorty-add">+</span>
                <span class="categorty-del">-</span>
            </div>
            <div class="category-submit">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">确认提交</button>
                </div>
            </div>
    </div>

</div>







