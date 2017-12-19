$(function(){
    $("#add_member_tree").dialog({autoOpen: false, width: 550, height: 300, modal: true});
    $("#ShowMsg").dialog({autoOpen: false, width: 450, height: 400, modal: true});

})

function shownext(id,fid,ob){
    var top = 'tree_';
    var id = id;
    var fid = fid;
    var url = MC_SHOWNEXT;
    var li = '';
    var thisrank = $(ob).parent('ul').attr('rank');
    var name = $(ob).attr('name');

    var nextrank = parseInt(thisrank)+1;
    var nextid = $("#tree_"+nextrank);

    nextid.children(":first").html(name+"<b class='caret'></b>");


    $.post(url,{id:id,fid:fid},function(data){

        var obj = (new Function("return " + data))();
        if(obj == 0){
            nextid.hide('fast');
            return false;
        }else{
            for(var i=0;i<obj.length;i++){
                var id=obj[i].id;
                var name=obj[i].name;
                var content=obj[i].content;
                var fid=obj[i].fid;

                li += "<li name = "+name+" style = 'width:200px;' onclick="+"shownext("+id+","+fid+",this)>" +
                        "<a  href='#' style = 'float: left;'>" +name +"</a>" +
                            "<div style = 'float: right;'>"+
                                "<input type = 'radio' name = 'setThis' value = '"+id+"'>"+
                                "<a href = '#' style = 'margin-right: 5px;' onclick="+"ShowCatalogue("+id+","+fid+","+nextrank+")>" +
                                "<i class='icon-eye-open'></i>" +
                                "</a>"+
                                "<a href = '#' style = 'margin-right: 5px;' onclick="+"DelCatalogue("+id+","+fid+","+nextrank+")>" +
                                    "<i class='icon-remove'></i>" +
                                "</a>"+
                                "<a href = '#' style = 'margin-right: 5px;' onclick="+"AddCatalogue("+id+","+fid+","+nextrank+")>" +
                                    "<i class='icon-plus-sign'></i>" +
                                "</a>"+
                            "</div>"+
                      "</li>";
            }

            nextid.children('ul').html(li);
            nextid.show('fast');
            nextid.attr('isshow',1);
        }
    });

}

function AddCatalogue(id,fid,thisrank){

    var modal = $("#add_member_tree");
    $("#nowrank").val(thisrank);
    var url = MC_ADDTREE;
    modal.dialog("open");
    $("#ADDTOPTREE").click(function(){
        var title = $("#catalogue_title").val();
        var content = $("#catalogue_content").val();
        var rank = $("#nowrank").val();


        if(title.length==0){
            alert('empty');
            return false;
        }else{
            $.post(url,{id:id,fid:fid,rank:rank,title:title,content:content},function(data){
                var obj = (new Function("return " + data))();
                if (obj.msg == data_back.DATA_ERROR_NOPOWER) {
                    asyncbox.alert(data_back_msg.DATA_ERROR_NOPOWER, title.TITLE_ERROR);
                } else if (obj.msg == data_back.DATA_SUCCESS) {
                    asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                    location.reload();
                }else if (obj.msg == data_back.DATA_ERROR_DOWN) {
                    asyncbox.alert(data_back_msg.DATA_ERROR_DOWN, title.TITLE_ERROR);
                }
            });
        }
    })
}

function ShowCatalogue(id,fid,rank){
    var modal = $("#ShowMsg");
    var url = MC_SHOWMSG;
    $.post(url,{id:id},function(data){
        var obj = (new Function("return " + data))();
        $("#thiscatid").val(id);
        $("#title").val(obj.msg['title']);
        $("#msg_content").val(obj.msg['content']);
    });

    modal.dialog("open");
}
function DelCatalogue(id,fid,rank){
    var url = MC_DELMSG;
    asyncbox.confirm('确认删除此状态？删除后其子状态也一并删除', title.QUESTION);

    $("#asyncbox_confirm_ok").click(function () {
        $.post(url, {id: id}, function (data) {
            var obj = (new Function("return " + data))();
            if (obj.msg == data_back.DATA_ERROR) {
                asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
            } else if (obj.msg == data_back.DATA_SUCCESS) {
                asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                location.reload();
            }

        })
    })
}

function addTopTree(){
    var url = MC_ADDTOPTREE;
    var modal = $("#add_member_tree");
    modal.dialog("open");
    $("#ADDTOPTREE").click(function(){
        var title = $("#catalogue_title").val();
        var content = $("#catalogue_content").val();

        if(title.length==0){
            asyncbox.alert('标题不可为空', title.TITLE_ERROR);
            return false;
        }else{
            $.post(url,{title:title,content:content},function(data){
                var obj = (new Function("return " + data))();
                if (obj.msg == data_back.DATA_ERROR) {
                    asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
                } else if (obj.msg == data_back.DATA_SUCCESS) {
                    asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
                    location.reload();
                }
            })
        }
    })
}
function updateCatalogue(){
    var url = MC_UPMSG;
    var catid = $("#thiscatid").val();
    var title = $("#title").val();
    var content = $("#msg_content").val();
    $.post(url,{title:title,content:content,catid:catid},function(data){
        var obj = (new Function("return " + data))();
        if (obj.msg == data_back.DATA_ERROR) {
            asyncbox.alert(data_back_msg.DATA_ERROR, title.TITLE_ERROR);
        } else if (obj.msg == data_back.DATA_SUCCESS) {
            asyncbox.alert(data_back_msg.DATA_SUCCESS, title.TITLE_SUCCESS);
            location.reload();
        }
    });
}


