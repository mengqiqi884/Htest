/**
 * Created by BF on 2017/8/9.
 */
/*上传视频*/
$("#ffilms-video").fileinput({
    uploadUrl: toRoute('upload/video'), // server upload action
    uploadExtraData:{'filename':$('#ffilms-video').attr('name')},
    uploadAsync: true,
    minFileCount: 1,
    maxFileCount: 1,
    showUpload: true,
    browseOnZoneClick: true,
    autoReplace: true,
    initialPreviewAsData: true // identify if you are sending preview data only and not the markup
});