/**
 * Created by BF on 2017/9/6.
 */
function activeTab(n){
    var node = $('#header_area ul.navbar-right').find('li');
    node.siblings().removeClass('active');
    node.eq(n).addClass('active');
}

function toRoute(val){
    var url = document.URL;
    var path = url.split('web');
    return path[0]+'web'+'/index.php/'+val;
}
