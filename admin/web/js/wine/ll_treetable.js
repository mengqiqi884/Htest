var grid;
var addFun = function (code, level) {
    var temp = level == 1 ? "品牌" : (level == 2 ? "车系" : "车型");
    var dialog = layer.open({
        type: 2,
        title: '新增' + temp + '信息',
        shadeClose: true,
        shade: 0.8,
        maxmin: true, //开启最大化最小化按钮-->
        area: ['863px', '620px'],
        content: toRoute('car/create?parent=' + code + '&level=' + level)
    });
};
var editFun = function (code, level, p) {
    var temp = level == 1 ? "品牌" : (level == 2 ? "车系" : "车型");
    var dialog = layer.open({
        type: 2,
        title: '编辑' + temp + '信息',
        shadeClose: true,
        shade: 0.8,
        maxmin: true, //开启最大化最小化按钮-->
        area: ['800px', '620px'],
        content: toRoute('car/update?code=' + code + '&level=' + level)
    });
};
var delFun = function (code, p) {

    swal({
        title:"您确定要删除这条信息吗",
        text:"删除后将无法恢复，请谨慎操作！",
        type:"warning",
        showCancelButton:true,
        confirmButtonColor:"#DD6B55",
        confirmButtonText:"删除",
        closeOnConfirm:false
    },function(){

        $.ajax({
            type: "GET",
            url: toRoute("car/delete"),
            data: {"code": code},
            dataType: "json",
            success: function (data) {
                if (data.state == '200') {
                    swal("删除成功！","您已经永久删除了这条信息。","success");
                    if (data.message == 0) {
                        grid.treegrid('reload');
                    } else {
                        grid.treegrid('reload', data.message); //重新加载树
                    }
                } else {
                    if (data.state == '500') {
                        swal("删除失败！","您此次操作失败！","error")
                    }
                    grid.treegrid('reload');
                }
            },
            error: function (data) {
                swal("失败！","您没有操作权限！","error");
                grid.treegrid('reload');
            }
        });
    });
};

$(function () {

    grid = $('#tt').treegrid({
        url: '../../car_tree.json',   //首次查询路径
        method: 'get',
        rownumbers: true,
        idField: 'code',  //定义标识树节点的键名字段。必需
        treeField: 'name',  //定义树节点的字段。必需。
        fitColumns: true,
        pagination: false,
        frozenColumns: [[{
            width: $(this).width() * 0.2,
            title: '名称',
            align: 'left',
            halign: 'center',
            field: 'name',
        }]],
        columns: [[{
            width: $(this).width() * 0.1,
            title: '编号',
            align: 'center',
            field: 'code',
            formatter: function (value, row, index) {
                return value;
            }
        }, {
            width: $(this).width() * 0.1,
            title: 'LOGO',
            align: 'center',
            field: 'logo',
            formatter: function (value, row, index) {
                if (row.level == 1 || row.level == 2) {
                    return "<a class='fancybox' href='" + value + "' title='" + row.name + "'>" +
                        "<img src='" + value + "' width='18px' height='18px' >" +
                        "</a>";

                }
            }
        }, {
            width: $(this).width() * 0.1,
            title: '排序号(正序)',
            align: 'center',
            field: 'sortorder',
            formatter: function (value, row, index) {
                return value;
            }
        }, {
            width: $(this).width() * 0.2,
            title: '操作',
            align: 'center',
            field: 'operation',
            formatter: function (value, row, index) {
                var html = "";
                if (row.level == 0) {
                    html += "<a href='javascript:;' onclick=\"addFun('0',1)\" style='color: #1ab394'>新增品牌</a>";
                } else if (row.level == 1) {
                    html += "<a href='javascript:;' onclick=\"editFun('" + row.code + "',1)\" style='color: #baaa5a'>编辑</a>";
                    html += " | <a href='javascript:;' onclick=\"delFun('" + row.code + "')\" style='color: #ff0000'>删除</a>";
                    html += " | <a href='javascript:;' onclick=\"addFun('" + row.code + "',2)\" style='color: #1ab394'>新增车系</a>";
                } else if (row.level == 2) {
                    html += "<a href='javascript:;' onclick=\"editFun('" + row.code + "',2)\" style='color: #baaa5a'>编辑</a>";
                    html += " | <a href='javascript:;' onclick=\"delFun('" + row.code + "')\" style='color: #ff0000'>删除</a>";
                    html += " | <a href='javascript:;' onclick=\"addFun('" + row.code + "',3)\" style='color: #1ab394'>新增车型</a>";
                } else if (row.level == 3) {
                    html += "<a href='javascript:;' onclick=\"editFun('" + row.code + "',3)\" style='color: #baaa5a'>编辑</a>";
                    html += " | <a href='javascript:;' onclick=\"delFun('" + row.code + "')\" style='color: #ff0000'>删除</a>";
                }
                return html;
            }
        }
        ]],
        onBeforeExpand : function(row) { // 此处就是异步加载地所在
            if (row) {
                $(this).treegrid('options').url = toRoute('car/asyn-data?id='+ row.id);  //第二次数据查询异步加载路径
            }
            return true;
        },
    })
});

function import_data()
{

    layer.open({
        type: 2,
        title: 'Excel导入',
        shadeClose: true,
        shade: 0.8,
        area: ['500px','500px'],
        content: toRoute('car/to-excel') //iframe的url
    });
}