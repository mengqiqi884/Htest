
accessid = '';
accesskey = '';
host = '';
policyBase64 = '';
signature = '';
callbackbody = '';
filename = '';
key = '';
expire = 0;
g_object_name = '';
g_object_name_type = '';
now = timestamp = Date.parse(new Date()) / 1000;
window.toRoute = function(val){   //路由
    var url = document.URL;
    var path = url.split('index.php');
    return path[0]+'index.php'+'/'+val;
};

function send_request()
{
    var xmlhttp = null;
    if (window.XMLHttpRequest)
    {
        xmlhttp=new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
  
    if (xmlhttp!=null)
    {
        phpUrl = toRoute('video/osssign');   //视频上传地址
        xmlhttp.open( "GET", phpUrl, false );
        xmlhttp.send( null );
        return xmlhttp.responseText
    }
    else
    {
        alert("Your browser does not support XMLHTTP.");
    }
};

function check_object_radio() {
    //var tt = document.getElementsByName('myradio');
    //for (var i = 0; i < tt.length ; i++ )
    //{
    //    if(tt[i].checked)
    //    {
    //        g_object_name_type = tt[i].value;
    //        break;
    //    }
    //}
    g_object_name_type = 'random_name';
}

function get_signature()
{
    //可以判断当前expire是否超过了当前时间,如果超过了当前时间,就重新取一下.3s 做为缓冲
    now = timestamp = Date.parse(new Date()) / 1000; 
    if (expire < now + 3)
    {
        body = send_request();
        var obj = eval ("(" + body + ")");
        host = obj['host'];
        policyBase64 = obj['policy'];
        accessid = obj['accessid'];
        signature = obj['signature'];
        expire = parseInt(obj['expire']);
        callbackbody = obj['callback'] ;
        key = obj['dir'];
        return true;
    }
    return false;
};

function random_string(len) {
　　len = len || 32;
　　var chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';   
　　var maxPos = chars.length;
　　var pwd = '';
　　for (i = 0; i < len; i++) {
    　　pwd += chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
}

function get_suffix(filename) {
    pos = filename.lastIndexOf('.');
    suffix = '';
    if (pos != -1) {
        suffix = filename.substring(pos)
    }
    return suffix;
}

function calculate_object_name(filename)
{
    if (g_object_name_type == 'local_name')
    {
        g_object_name += "${filename}"
    }
    else if (g_object_name_type == 'random_name')
    {
        suffix = get_suffix(filename)
        g_object_name = key + random_string(10) + suffix
    }
    return ''
}

function get_uploaded_object_name(filename)
{
    if (g_object_name_type == 'local_name')
    {
        tmp_name = g_object_name
        tmp_name = tmp_name.replace("${filename}", filename);
        return tmp_name
    }
    else if(g_object_name_type == 'random_name')
    {
        return g_object_name
    }
}

function checkFileType(filepath){
    var extStart=filepath.lastIndexOf(".");
    var ext=filepath.substring(extStart,filepath.length).toUpperCase();
    if(ext ==".BMP" ||  ext==".PNG" || ext==".GIF" || ext==".JPG" || ext==".JPEG"){
            return 'img';
    }
    if(ext == '.MP4' || ext == '.AVI' || ext == '.WMV' || ext == '.RMVB' || ext == '.MKV'){
        return 'video';
    }
}

function set_upload_param(up, filename, ret)
{
    if (ret == false)
    {
        ret = get_signature()
    }
    g_object_name = key;
    if (filename != '') {
        suffix = get_suffix(filename)
        calculate_object_name(filename)
    }
    new_multipart_params = {
        'key' : g_object_name,
        'policy': policyBase64,
        'OSSAccessKeyId': accessid, 
        'success_action_status' : '200', //让服务端返回200,不然，默认会返回204
        'callback' : callbackbody,
        'signature': signature,
    };

    up.setOption({
        'url': host,
        'multipart_params': new_multipart_params
    });

    up.start(); ////调用实例对象的start()方法开始上传文件，当然你也可以在其他地方调用该方法
}

//实例化一个plupload上传对象
var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'selectfiles',      //触发文件选择对话框的按钮，为那个元素id选择文件 按钮
    //multi_selection: false,
	container: document.getElementById('container'),
	flash_swf_url : 'ossuoload/lib/plupload-2.1.2/js/Moxie.swf',   //swf文件，当需要使用swf方式进行上传时需要配置该参数
	silverlight_xap_url : 'ossupload/lib/plupload-2.1.2/js/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
    url : 'http://oss.aliyuncs.com',   //服务器端的上传页面地址

    filters : {
        max_file_size : '10mb',   //允许上传的文件最大尺寸
        mime_types: [
            {title : "Image files", extensions : "jpg,gif,png"},
            {title : "Video files", extensions : "MP4,AVI,WMV,RMVB,MKV"}
        ]
    },

	init: {
		PostInit: function() {
			document.getElementById('ossfile').innerHTML = '';
            //最后给"开始上传"按钮注册事件
			document.getElementById('postfiles').onclick = function() {   //上传文件 按钮
            set_upload_param(uploader, '', false);
            return false;
			};
		},
        //绑定各种事件，并在事件监听函数中做你想做的事
		FilesAdded: function(up, files) {
            document.getElementById('console').innerHTML = '';

			plupload.each(files, function(file) {
				document.getElementById('ossfile').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ')<b></b>'
				+'<div class="progress"><div class="progress-bar" style="width: 0%"></div></div>'
				+'</div>';
			});
		},

		BeforeUpload: function(up, file) {
            check_object_radio();
            set_upload_param(up, file.name, true);
        },

		UploadProgress: function(up, file) {
			var d = document.getElementById(file.id);
			d.getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
            var prog = d.getElementsByTagName('div')[0];
			var progBar = prog.getElementsByTagName('div')[0]
			progBar.style.width= 2*file.percent+'px';
			progBar.setAttribute('aria-valuenow', file.percent);
		},

		FileUploaded: function(up, file, info) {
            if (info.status == 200)
            {
                var file_name = get_uploaded_object_name(file.name);
                //var file_url = 'http://tbea.oss-cn-hangzhou.aliyuncs.com/'+file_name;
                var file_url = host+file_name;
                var file_type = checkFileType(file_name);
                if( file_type == 'img'){
                    $('#cgoods-url').val(file_url);   //设置视频链接
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '发布视频封面成功';
                }else if(file_type == 'video'){
                    $('#cgoods-url').val(file_url);   //设置视频链接
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '发布视频成功';
                }
            }
            else
            {
                document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = info.response;
            } 
		},

		Error: function(up, err) {
			document.getElementById('console').innerHTML = "\nError xml:文件大小或格式不正确！";
		}
	}
});
//在实例对象上调用init()方法进行初始化
uploader.init();
