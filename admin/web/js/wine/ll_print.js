/**
 * Created by BF on 2017/8/16.
 */
function PrintPage()
{
    $("#printThis").printThis({   //id为要打印区域的容器
        debug: false, // 调试模式下打印文本的渲染状态
        importCSS: true,
        importStyle: true,
        printContainer: true,// 设置为true，向文本中插入内容
//                loadCSS: "/Content/Themes/Default/style.css",
        pageTitle: "预约中",// 为打印文本中添加<titile>标签,标签内容将在打印出的文件顶部显示，这里会和打印原有标题冲突，建议谨慎使用。
        removeInline: false,// 清除body的默认样式，包括内外边距，字体等，目的是为了让渲染文本和打印文本保持一致
        printDelay: 333,// 布局完打印页面之后与真正执行打印功能中间的间隔
        header: null,// 在打印文本的body中添加header标签，这里的内容将在打印出的文件顶部居左显示
        formValues: true  // 如果打印的目标源码中又表单内容就选择true，这里是为新的打印文本中的表单赋值
    });
}