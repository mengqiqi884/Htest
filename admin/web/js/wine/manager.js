/**
 * Created by 沈小鱼 on 2016/7/31.
 */
$(function(){
    $("tr").find('#manager_del').click(function(){
        wa_id = $(this).parents('tr').find('#wa_id');
        csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            statusCode: {
                302: function() {
                    layer.alert('登录信息已过期，请重新登录',{icon: 1},function(index){
                        layer.close(index);
                        window.top.location.href=toRoute('site/login');
                        return false;
                    });
                }
            }
        });
        layer.confirm('确认删除该管理员信息？',{icon: 0, title:'提示'},function(index){
            layer.close(index);
            ShowLoad();
            $.post(toRoute('manager/del'),{
                'wa_id':wa_id.text(),
                '_wine-admin':csrfToken
            },function(data){
                ShowMessage(data.status,data.message);
                if(data.status == '302'){
                    layer.alert('登录信息已过期，请重新登录',{icon: 0},function(){
                        window.top.location.href=toRoute('site/login');
                    });
                    return false;
                }else if(data.status == '200'){
                    window.location.reload();
                }else{
                    return false;
                }
            },'json');
        });
    });
    $("tr").find('#manager_recover').click(function(){
        wa_id = $(this).parents('tr').find('#wa_id');
        csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            statusCode: {
                302: function() {
                    layer.alert('登录信息已过期，请重新登录',{icon: 1},function(index){
                        layer.close(index);
                        window.top.location.href=toRoute('site/login');
                        return false;
                    });
                }
            }
        });
        layer.confirm('确认恢复该管理员信息？',{icon: 0, title:'提示'},function(index){
            layer.close(index);
            ShowLoad();
            $.post(toRoute('manager/recover'),{
                'wa_id':wa_id.text(),
                '_wine-admin':csrfToken
            },function(data){
                ShowMessage(data.status,data.message);
                if(data.status == '302'){
                    layer.alert('登录信息已过期，请重新登录',{icon: 0},function(){
                        window.top.location.href=toRoute('site/login');;
                    });
                    return false;
                }else if(data.status == '200'){
                    window.location.reload();
                }else{
                    return false;
                }
            },'json');
        });
    });
    $('#manager_update').click(function(){
        var wa_id = $('#admin-admin_id').val();

        var admin_name = $('#admin-admin_name').val();
        var wa_password = $('#admin-admin_pwd').val();
        var confirm_password = $('#admin-confirm_password').val();
       // var item_name = $('#authassignment-item_name').val();
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        ShowLoad();
        $.ajax({
            statusCode: {
                302: function() {
                    layer.alert('登录信息已过期，请重新登录',{icon: 0},function(){
                        window.top.location.href=toRoute('site/login');
                    });
                    return false;
                }
            }
        });
        $.post('update', {
            'a_id':wa_id,
            'a_name':admin_name,
            'a_pwd':wa_password,
            'confirm_password':confirm_password,
            //'wa_phone':wa_phone,
            //'wa_name':wa_name,
          //  'item_name':item_name,
            '_wine-admin':csrfToken,
        }, function(data) {
            ShowMessage(data.status,data.message);
            if(data.status == '302'){
                layer.alert('登录信息已过期，请重新登录',{icon: 0},function(){
                    window.top.location.href=toRoute('site/login');
                });
                return false;
            }else if(data.status == '304'){
                layer.alert(data.message);
                window.top.location.href=toRoute('site/login');
                return false;
            }else if(data.status == '200'){
               // location.href = toRoute('manager/list');
                location.href=toRoute('site/index');
                return true;
            }
        }, 'json');
    });

    $('#searchManager').click(function(){
        searchManager(1);
    });

    $('#manager_create').click(function(){
        var admin_name = $('#admin-admin_name').val();
        var wa_password = $('#admin-admin_pwd').val();
        var confirm_password = $('#admin-confirm_password').val();
        if(admin_name==''||wa_password==''||confirm_password==''){
            ShowMessage(301,'用户名和密码不能为空');
            return false;
        }
        if(wa_password!=confirm_password){
            ShowMessage(301,'两次输入的密码不一致');
            return false;
        }
        var item_name = $('#admin-item_name').val();
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            statusCode: {
                302: function() {
                    layer.alert('登录信息已过期，请重新登录',{icon: 0},function(){
                        window.top.location.href=toRoute('site/login');
                    });
                    return false;
                }
            }
        });
        ShowLoad();
        $.post(toRoute('manager/create'), {
            'admin_name':admin_name,
            'wa_password':wa_password,
            'confirm_password':confirm_password,
            'item_name':item_name,
            '_wine-admin':csrfToken,
        }, function(data) {
            ShowMessage(data.status,data.message);
            if(data.status == '302'){
                layer.alert('登录信息已过期，请重新登录',{icon: 0},function(){
                    window.top.location.href=toRoute('site/login');
                });
                return false;
            }else if(data.status == '200'){
                location.href = toRoute('manager/list');
            }
            return false;
        }, 'json');
    });

    $('[name="level"]').change(function(){
        searchManager(1);
    });

    $('.onoffswitch-label').click(function(){
        wa_id = $(this).parents('tr').find('#wa_id');
        wa_check = $(this).parents('tr').find('input[type="checkbox"]');
        csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            statusCode: {
                302: function() {
                    layer.alert('登录信息已过期，请重新登录',{icon: 0},function(){
                        window.top.location.href=toRoute('site/login');
                    });
                    return false;
                }
            }
        });
        if(wa_check.is(':disabled')){
            return false;
        }else {
            ShowLoad();
            $.post(toRoute('manager/lock'),{
                'wa_id':wa_id.text(),
                '_wine-admin':csrfToken,
            },function(data){
                ShowMessage(data.status,data.message);
                if(data.status == '302'){
                    layer.alert('登录信息已过期，请重新登录',{icon: 0},function(){
                        window.top.location.href=toRoute('site/login');
                    });
                    return false;
                }else if(data.status == '200'){
                    return false;
                }else{
                    window.location.reload();
                    return false;
                }
            },'json');
        }
    });
});

function searchManager(page){
    page!=null? $("[name='manager_page']").val(page):null;
    admin_name = $('#searchUsername').val();
    admin_type = $('select[name="level"]').val();

    csrfToken = $('meta[name="csrf-token"]').attr("content");
    var searchArr = {};
    searchArr['admin_name']=admin_name;
    searchArr['admin_type']=admin_type;
    $.ajax({
        statusCode: {
            302: function() {
                layer.alert('登录信息已过期，请重新登录',{icon: 0},function(){
                    window.top.location.href=toRoute('site/login');;
                });
                return false;
            }
        },
        url: $(".fixed-sidebar").data("id"),
        data:{"page":page,"search":searchArr,"_wine-admin":csrfToken},
        beforeSend: function () {
            ShowLoad();
        },
        complete: function () {
            layer.closeAll('loading');
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            layer.alert('发生错误', {icon: 5});
            return false;
        },
        success: function (data) {
            layer.closeAll('loading');
            $('.fixed-sidebar').html(data);
            return false;
        }
    });
}

var userNameSuggest=$("#searchUsername").bsSuggest({
    url: toRoute('manager/search?key=admin_name&val='),                      //请求数据的 URL 地址
    getDataMethod: "url",           //获取数据的方式，url：一直从url请求；data：从 options.data 获取；firstByUrl：第一次从Url获取全部数据，之后从options.data获取
    ignorecase: false,              //不忽略大小写
    showBtn: false,                  //是否显示下拉按钮
    allowNoKeyword: false,           //是否允许无关键字时请求数据
    multiWord: false,               //以分隔符号分割的多关键字支持
    processData: function(json){
        var i,len,data={value:[]};
        if(!json||!json.data||json.data.length==0){
            return false
        }
        console.log(json);
        len=json.data.length;
        for(i=0;i<len;i++){
            data.value.push(
                {
                    "user_name":json.data[i]
                }
            )}
        console.log(data);
        return data
    },       //格式化数据的方法，返回数据格式参考 data 参数
    autoMinWidth: true,            //是否自动最小宽度，设为 false 则最小宽度不小于输入框宽度
    listAlign: "left",              //提示列表对齐位置，left/right/auto
    inputWarnColor: "rgba(255,0,0,.1)", //输入框内容不是下拉列表选择时的警告色
    listStyle: {
        "padding-top":0, "max-height": "375px", "max-width": "200px","margin-left":"68px","min-width": "179px",
        "transition": "0.5s", "-webkit-transition": "0.5s", "-moz-transition": "0.5s", "-o-transition": "0.5s"
    },                              //列表的样式控制
    listHoverStyle: 'background: #07d; color:#fff', //提示框列表鼠标悬浮的样式
    listHoverCSS: "jhover"         //提示框列表鼠标悬浮的样式名称
}).on("onDataRequestSuccess",function(e,result){
    console.log("onDataRequestSuccess: ",result)
}).on("onSetSelectValue",function(e,result){
    console.log('onSetSelectValue: ', result);
}).on("keydown",function(e){
    if (e.keyCode == "13") {
        searchManager(1);
    }
}).on("blur",function(){
        searchManager(1);
});

var phoneSuggest=$("#searchPhone").bsSuggest({
    url: toRoute('manager/search?key=wa_phone&val='),                      //请求数据的 URL 地址
    getDataMethod: "url",           //获取数据的方式，url：一直从url请求；data：从 options.data 获取；firstByUrl：第一次从Url获取全部数据，之后从options.data获取
    ignorecase: false,              //不忽略大小写
    showBtn: false,                  //是否显示下拉按钮
    allowNoKeyword: false,           //是否允许无关键字时请求数据
    multiWord: false,               //以分隔符号分割的多关键字支持
    processData: function(json){
        var i,len,data={value:[]};
        if(!json||!json.data||json.data.length==0){
            return false
        }
        console.log(json);
        len=json.data.length;
        for(i=0;i<len;i++){
            data.value.push(
                {
                    "user_name":json.data[i]
                }
            )}
        console.log(data);
        return data
    },       //格式化数据的方法，返回数据格式参考 data 参数
    autoMinWidth: true,            //是否自动最小宽度，设为 false 则最小宽度不小于输入框宽度
    listAlign: "left",              //提示列表对齐位置，left/right/auto
    inputWarnColor: "rgba(255,0,0,.1)", //输入框内容不是下拉列表选择时的警告色
    listStyle: {
        "padding-top":0, "max-height": "375px", "max-width": "200px","margin-left":"68px","min-width": "179px",
        "transition": "0.5s", "-webkit-transition": "0.5s", "-moz-transition": "0.5s", "-o-transition": "0.5s"
    },                              //列表的样式控制
    listHoverStyle: 'background: #07d; color:#fff', //提示框列表鼠标悬浮的样式
    listHoverCSS: "jhover"         //提示框列表鼠标悬浮的样式名称
}).on("onDataRequestSuccess",function(e,result){
    console.log("onDataRequestSuccess: ",result)
}).on("onSetSelectValue",function(e,result){
    console.log('onSetSelectValue: ', result);
}).on("keydown",function(e){
    if (e.keyCode == "13") {
        searchManager(1);
    }
}).on("blur",function(){
        searchManager(1);
});

var nameSuggest=$("#searchName").bsSuggest({
    url: toRoute('manager/search?key=wa_name&val='),                      //请求数据的 URL 地址
    getDataMethod: "url",           //获取数据的方式，url：一直从url请求；data：从 options.data 获取；firstByUrl：第一次从Url获取全部数据，之后从options.data获取
    ignorecase: false,              //不忽略大小写
    showBtn: false,                  //是否显示下拉按钮
    allowNoKeyword: false,           //是否允许无关键字时请求数据
    multiWord: false,               //以分隔符号分割的多关键字支持
    processData: function(json){
        var i,len,data={value:[]};
        if(!json||!json.data||json.data.length==0){
            return false
        }
        console.log(json);
        len=json.data.length;
        for(i=0;i<len;i++){
            data.value.push(
                {
                    "user_name":json.data[i]
                }
            )}
        console.log(data);
        return data
    },       //格式化数据的方法，返回数据格式参考 data 参数
    autoMinWidth: true,            //是否自动最小宽度，设为 false 则最小宽度不小于输入框宽度
    listAlign: "left",              //提示列表对齐位置，left/right/auto
    inputWarnColor: "rgba(255,0,0,.1)", //输入框内容不是下拉列表选择时的警告色
    listStyle: {
        "padding-top":0, "max-height": "375px", "max-width": "200px","margin-left":"62px","min-width": "185px",
        "transition": "0.5s", "-webkit-transition": "0.5s", "-moz-transition": "0.5s", "-o-transition": "0.5s"
    },                              //列表的样式控制
    listHoverStyle: 'background: #07d; color:#fff', //提示框列表鼠标悬浮的样式
    listHoverCSS: "jhover"         //提示框列表鼠标悬浮的样式名称
}).on("onDataRequestSuccess",function(e,result){
    console.log("onDataRequestSuccess: ",result)
}).on("onSetSelectValue",function(e,result){
    console.log('onSetSelectValue: ', result);
}).on("keydown",function(e){
    if (e.keyCode == "13") {
        searchManager(1);
    }
}).on("blur",function(){
        searchManager(1);
});

function goPage(obj){
    var page=$(obj).data('page')+1;
    searchManager(page);
    return false;
}