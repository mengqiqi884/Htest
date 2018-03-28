/**
 * Created by BF on 2017/7/31.
 */
$(function(){
    $(".summernote").summernote({lang:"zh-CN"})
});
var edit=function(){
    $(".click2edit").summernote({focus:true})
};
var save=function() {
    var aHTML = $(".click2edit").code();
    $(".click2edit").destroy();
};

function checktext(){
    var con = $('#cpage-p_content').val();
    if(con.length==0){
        alert('用户协议不能为空');
        return false;
    }
    return true;
}