/**
 * Created by BF on 2016/9/6.
 */
function selectProvince(){
    var p=$('#ppoint-p_province').val();
    var html = '<option value="0">--市--</option>';
    var city=$('#ppoint-p_city');
    var district=$('#ppoint-p_district');
    city.attr('disabled', false);
    //修改市
    $.ajax({
            url: '../site/selectcity',
            data: {'p_id': p},
            type: 'post',
            dataType: 'json',
            success: function (msg) {
                for (var i = 0; i < msg.length; i++) {
                    html += '<option value="' + msg[i].id + '">' + msg[i].name + '</option>';
                }
                city.empty().append(html);
                changeCity(city,district);
            }
        }
    );
}



function selectAddress(obj1,obj2,obj3){
    var province=obj1;
    var city=obj2;
    var district=obj3;

    //改变省的操作
    province.change(function() {
        alert(123456);
        var p=province.val();
        var html = '<option value="0">--市--</option>';
        city.attr('disabled', false);
        //修改市
        $.ajax({
                url: '../site/selectcity',
                data: {'p_id': p},
                type: 'post',
                dataType: 'json',
                success: function (msg) {
                    for (var i = 0; i < msg.length; i++) {
                        html += '<option value="' + msg[i].id + '">' + msg[i].name + '</option>';
                    }
                    city.empty().append(html);
                    changeCity(city,district);
                }
            }
        );
    });
}

//选中城市后，修改区域
function changeCity(city,district){
    city.change(function(){
        //获取选中的省份
        var c=city.val();
        var html='<option value="0">--区--</option>';
        district.attr('disabled',false);
        //修改市
        $.ajax({
                url:'../site/selectdistrict',
                data:{'c_id':c},
                type:'post',
                dataType:'json',
                success:function(msg){
                    for(var i=0;i<msg.length;i++){
                        html+='<option value="'+msg[i].id+'">'+msg[i].name+'</option>';
                    }
                    district.empty().append(html);
                }
            }
        );
    });


}

//
////选中省份后，修改城市
//function changeProvince(obj){
//    //获取选中的省份
//    var p=obj.val();
//    alert(123);
//    var html='<option value="0">--市--</option>';
//    $('#merchantinfo-city').attr('disabled',false);
//    //修改市
//    $.ajax({
//            url:'../site/selectcity',
//            data:{'p_id':p},
//            type:'post',
//            dataType:'json',
//            success:function(msg){
//                for(var i=0;i<msg.length;i++){
//                    html+='<option value="'+msg[i].id+'">'+msg[i].name+'</option>';
//                }
//                $('#merchantinfo-city').empty().append(html);
//                changeCity(c);
//            }
//        }
//    );
//}
////选中城市后，修改区域
//function changeCity(c){
//    //获取选中的省份
//   // var c=$('#merchantinfo-city').val();
//    var html='<option value="0">--区--</option>';
//    $('#merchantinfo-district').attr('disabled',false);
//    //修改市
//    $.ajax({
//            url:'selectdistrict',
//            data:{'c_id':c},
//            type:'post',
//            dataType:'json',
//            success:function(msg){
//                for(var i=0;i<msg.length;i++){
//                    html+='<option value="'+msg[i].id+'">'+msg[i].name+'</option>';
//                }
//                $('#merchantinfo-district').empty().append(html);
//            }
//        }
//    );
//
//}
