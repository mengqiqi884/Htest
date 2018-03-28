/**
 * Created by BF on 2016/9/7.
 */
function click_pop(imgpath,tourl) {

    $('table a').click(function () {
        var html = '';
        $.ajax({
            url: tourl,
            data: {'wa_id': $(this).text()},
            type: 'post',
            dataType: 'json',
            success: function (msg) {
                if (msg.state == '200') {
                    html = '<table style="width:90%;margin:0 auto;">' +
                        '<tr align="left"><th>后台登陆名</th><td>' + msg.data["username"] + '</td></tr>' +
                        '<tr align="left"><th>登陆密码</th><td>******</td></tr>' +
                        '<tr align="left"><th>头像</th><td>';
                    if(msg.data["wa_logo"]!=''){
                        html +='<img src="'+imgpath+ msg.data["wa_logo"]+ '" width="50" height="50">';
                    }else{
                        html +='<img src="'+imgpath+'/logo/user_default.jpg" width="50" height="50">';
                    }
                    html+='</td></tr>' +
                        '<tr align="left"><th>用户组</th><td>'+msg.data["wa_type"]+'</td></tr>'+
                    '</table>';

                    $('.pop_showbrand').find('div').html(html);
                    $('.pop_hide').slideDown();
                    $('.pop_showbrand').slideDown();
                } else {
                    alert(msg.data);
                }
            }
        });
    });

    /*关闭弹出框*/
    $('.close').click(function () {
        $('.pop_hide').slideUp();
        $('.pop_showbrand').slideUp();
    });
}
