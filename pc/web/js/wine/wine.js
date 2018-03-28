$(function(){
    /*查看*/
    $('.view').click(function () {
        showPage($(this));
    });
    /*新建*/
    $('.create').click(function(){
        showPage($(this));
    });

    /*编辑*/
    $('.update').click(function(){
        showPage($(this));
    });
});

function toRoute(val){
    var url = document.URL;
    var path = url.split('web');
    return path[0]+'web'+'/index.php/'+val;
}

/*加载提示框*/
function ShowLoad(){
    layer.closeAll();
    layer.load(2,{
        time: 10*1000,
        shade: [0.1,'#fff']
    });
}

/*modal弹框渲染页面*/
function showPage(obj){
    $(".modal-body").empty();
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    $.get(obj.attr("data-url"),{},
        function(data){
            $(".modal-body").html(data);
        }
    )
}

/*确认删除对话框*/
function confirm_del(obj){
    swal({
        title:"您确定要删除这条信息吗",
        text:"删除后将无法恢复，请谨慎操作！",
        type:"warning",
        showCancelButton:true,
        confirmButtonColor:"#DD6B55",
        confirmButtonText:"删除",
        closeOnConfirm:false
    },function(){
        ShowLoad();
        $.ajax({
            type: "GET",
            url: $('.delete').data('url'),
            dataType: "json",
            success: function (data) {
                layer.closeAll('loading');
                if (data.status == '200') {
                    swal("删除成功！","您已经永久删除了这条信息。","success");
                    location.reload();
                } else {
                    if (data.status == '500') {
                        swal("删除失败！","您此次操作失败！","error")
                    }
                }
            },
            error: function (data) {
                swal("失败！","您没有操作权限！","error");
            }
        });
    });
}

/*批量删除*/
function deleteAll(obj)
{
    var keys = $("#grid").yiiGridView("getSelectedRows");   //#grid为GridView组件的id
    if(keys.length==0){
        swal({title:"",text:"请选择需要删除的用户",type:"error"});
    }else{
        $.ajax({
            type:"POST",
            url:obj.title,
            data:{"mids":keys},
            dataType:"json",
            success:function(data){
                if(data.state==200){
                    swal({title:"",text:data.message,type:"success"});
                    window.location.reload();
                }else{
                    swal({title:"",text:data.message,type:"error"});
                }
            },
            error:function(data){
                swal({title:"",text:'您没有此操作的权限',type:"error"});
            }
        });
    }
}


function ShowMessage(status,message){
    layer.closeAll('loading');
    if(status == '200'){
        layer.msg(message,{
            icon: 6,
            time: 1500, //2秒关闭（如果不配置，默认是3秒）
        });
    }else if(status == '400'){
        layer.msg(message,{
            icon: 5,
            time: 2000, //2秒关闭（如果不配置，默认是3秒）
        });
    }else {
        layer.msg(message,{
            icon: 0,
            time: 2000, //2秒关闭（如果不配置，默认是3秒）
        });
    }
}

function checkEmail(mail){
    if( mail=="" || ( mail!="" && !/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/.test(mail) ) ){
       return  false;
    }else {
        return true;
    }

}

function checkPhone(phone){
    if( phone=="" || ( phone!="" && !/^1[34578]\d{9}$/.test(phone) ) ){
        return  false;
    }else {
        return true;
    }

}

function checkValue(obj){
    if(obj=='' || obj==null || obj.length==0) {
        return false;
    }else {
        return true;
    }
}

function checkPassword(pwd) {
    if(pwd=='' || (pwd!='' && !/^[\w]{6,15}$/.test(pwd))) {
        return false;
    }else {
        return true;
    }
}