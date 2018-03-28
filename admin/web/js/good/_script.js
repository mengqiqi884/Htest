function ShowImg(obj){
    path  = obj.src;
    var img = new Image();
    // 开始加载图片
    img.src = path;
// 为Image对象添加图片加载成功的处理方法
    img.onload = function(){
        layer.open({
            area: ['360px', '360px'],
            type:1,
            content:'<div style="text-align: center"><img src="'+path+'" height="360px" ></div>',
            title:false,
            scrollbar:false,
            shadeClose:true,
            move :false,
            shift:5,
        });
    }
}