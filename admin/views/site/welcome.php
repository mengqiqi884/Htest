<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/7/26
 * Time: 15:44
 */
use yii\helpers\Url;

?>


<script src="<?= Url::to('@web/js/content.min.js?v=1.0.0') ?>"></script>
<!--Don't touch this!-->
<script src="<?= Url::to('@web/js/plugins/plot/js/common/jquery.jqplot.js') ?>"></script>
<!--[if IE]><script type="text/javascript" src="<?= Url::to('@web/js/plugins/plot/js/common/excanvas.js')?>"></script><![endif]-->
<!--Don't touch this!-->

<!-- Additional plugins go here -->
<?=\yii\helpers\Html::cssFile('@web/js/plugins/plot/css/jquery.jqplot.css')?>

<script src="<?= Url::to('@web/js/plugins/plot/js/jqplot.categoryAxisRenderer.js')?>"></script> <!-- 横坐标类别显示 -->
<script src="<?= Url::to('@web/js/plugins/plot/js/jqplot.pointLabels.min.js')?>"></script><!--在图表上显示数值点标签-->
<script src="<?= Url::to('@web/js/plugins/plot/js/jqplot.canvasTextRenderer.js')?>"></script>
<script src="<?= Url::to('@web/js/plugins/plot/js/jqplot.canvasAxisLabelRenderer.js')?>"></script>
<!--柱状图 Tab:month-->
<script src="<?= Url::to('@web/js/plugins/plot/js/jqplot.barRenderer.js')?>"></script> <!-- 柱状图插件 -->
<!--折线图 Tab:year-->
<?= \yii\helpers\Html::jsFile('@web/js/plugins/plot/js/jqplot.canvasAxisTickRenderer.js')?><!-- 折线图插件 -->
<?= \yii\helpers\Html::jsFile('@web/js/plugins/plot/js/jqplot.enhancedLegendRenderer.js')?>
<!--饼状图 Tab:day-->
<script src="<?= Url::to('@web/js/plugins/plot/js/jqplot.pieRenderer.js')?>"></script> <!-- 饼状图插件 -->
<!-- End additional plugins -->

<!--<script src="--><?//= Url::to('@web/js/plugins/flot/jquery.flot.pie.js')?><!--"></script>-->
<!--<script src="--><?//= Url::to('@web/js/plugins/easypiechart/jquery.easypiechart.js') ?><!--"></script> <!--折线图 Tab:year-->
<!--<script src="--><?//= Url::to('@web/js/plugins/peity/jquery.peity.min.js') ?><!--"></script> <!--饼图 Tab:day-->


<style>
    #gray-bg{background-color: #f3f3f4;min-height: 804px;}
</style>
<div id="gray-bg">
    <div class="wrapper wrapper-content">
        <div class="row">
<!--            标签-->
            <div class="col-md-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">月</span>
                        <h5>浏览量</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">386,200</h1>
                        <div class="stat-percent font-bold text-success">
                            98%
                            <i class="fa fa-bolt"></i>
                        </div>
                        <small>总计浏览量</small>
                    </div>
                </div>
            </div>
<!--               标签-->
            <div class="col-md-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">年</span>
                        <h5>帖子数</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?=$forums?></h1>
                        <div class="stat-percent font-bold text-info">
                            20%
                            <i class="fa fa-level-up"></i>
                        </div>
                        <small>新订单</small>
                    </div>
                </div>
            </div>
<!--               标签-->
            <div class="col-md-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-primary pull-right">今天</span>
                        <h5>总用户</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"><?=$users?></h1>
                        <div class="stat-percent font-bold text-navy">
                            44%
                            <i class="fa fa-level-down"></i>
                        </div>
                        <small>增长较慢</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title select_table_tab">
                        <div class="pull-left">
                            <div class="btn-group">
                                <button type="button" class="btn btn-xs btn-white active" value="day">天</button>
                                <button type="button" class="btn btn-xs btn-white" value="month">月</button>
                                <button type="button" class="btn btn-xs btn-white" value="year">年</button>
                            </div>
                        </div>
                        <div class="ibox-tools">
                            <!--展开 / 收缩-->
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <!--关闭-->
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" placeholder="搜索" class="input-sm form-control">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-primary">搜索</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>项目</th>
                                        <th>任务</th>
                                        <th>日期</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>米莫说｜MiMO Show</td>

                                        <td>20%</td>
                                        <td>2014.11.11</td>
                                        <td><a href="#"><i class="fa fa-check text-navy"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>商家与购物用户的交互试衣应用</td>

                                        <td>40%</td>
                                        <td>2014.11.11</td>
                                        <td><a href="#"><i class="fa fa-check text-navy"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>天狼---智能硬件项目</td>

                                        <td>75%</td>
                                        <td>2014.11.11</td>
                                        <td><a href="#"><i class="fa fa-check text-navy"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>线下超市+线上商城+物流配送互联系统</td>

                                        <td>18%</td>
                                        <td>2014.11.11</td>
                                        <td><a href="#"><i class="fa fa-check text-navy"></i></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
     $(function(){
         createPie();
     });

     function createPie(){
         html = getTabDayHTML();
         $('.select_table_tab').next('.ibox-content').html(html);

         var p1 = [['Sony',20], ['Samsumg',13.3], ['LG',14.7], ['Vizio',48], ['Insignia', 4]];

         plot1 = $.jqplot('flot-pie-chart', [p1], {
             title:{
                 text:'各大品牌的销售比（%）',
                 show:true,
                 fontFamily:'微软雅黑',
                 fontSize:'14px',
                 fontWeight:'bold',
                 textColor:'#515151',

             }, //标题
             legend: {    // 图例属性
                 show: true,//设置是否出现分类名称框（即所有分类的名称出现在图的某个位置）
                 placement: 'insideGrid',  // 设置图例位于图表外部
                 rendererOptions: {
                     numberRows: 1
                 },
                 yoffset: 20,//垂直偏移量
                 location: 's'    // 分类名称框出现位置, (e:东,w:西,s:南,n:北,nw:西北,ne:东北,sw:西南,se:东南)
             },
             seriesDefaults:{ //饼状图通过seriesDefaults和series配置对象进行配置
                 renderer:$.jqplot.PieRenderer,
                 rendererOptions: {
//                     sliceMargin: 5, // 饼的每个部分之间的距离
//                     fill:true, // 设置饼的每部分被填充的状态
//                     shadow:true, //为饼的每个部分的边框设置阴影，以突出其立体效果
//                     shadowOffset: 2, //设置阴影区域偏移出饼的每部分边框的距离
//                     shadowDepth: 5, // 设置阴影区域的深度
//                     shadowAlpha: 0.07, // 设置阴影区域的透明度
                     showDataLabels: true,
                     dataLabelFormatString: '%.1f%%'  //一位小数
                 }
             }

         });
     }

     function  createBar(){
         var s1 = [['1',2],['2',6],['3',7],['4',10],['5',25],['6',5],['7',1],['8',6],['9',1],['10',8],['11',6],['12',10]];
         var s2 = [['1',5],['2',3],['3',2],['4',14],['5',25],['6',3],['7',14],['8',4],['9',5],['10',2],['11',5],['12',10]];

         plot2 = $.jqplot('flot-bar-chart', [s1,s2], {
             title:{
                 text:'当月的销售额',
                 show:true,
                 fontFamily:'微软雅黑',
                 fontSize:'16px',
                 fontWeight:'bold',
                 textColor:'#515151',

             }, //标题
             legend: {    // 图例属性
                 renderer: $.jqplot.EnhancedLegendRenderer,//渲染插件
                 show: true,//设置是否出现分类名称框（即所有分类的名称出现在图的某个位置）
                 placement: 'outsideGrid',  // 设置图例位于图表外部
                 location: 's',     // 分类名称框出现位置, (e:东,w:西,s:南,n:北,nw:西北,ne:东北,sw:西南,se:东南)
                 rendererOptions: {
                     numberRows: 1 //显示多少行
                 }
             },
             series: [{color:'#FF6666', label: '种类1'}, { color:'#0066CC', label: '种类2'}], //提示工具栏
             seriesDefaults: {
                 renderer:$.jqplot.BarRenderer,  //用柱状图表示
                 rendererOptions: {
                     barPadding: 8,      //设置同一分类两个柱状条之间的距离（px）
                     barMargin: 10,      //设置不同分类的两个柱状条之间的距离（px）（同一个横坐标表点上）
                     barDirection: 'vertical', //设置柱状图显示的方向：垂直显示和水平显示
                     //，默认垂直显示 vertical or horizontal.
                     barWidth: null,     // 设置柱状图中每个柱状条的宽度
                     shadowOffset: 2,    // 同grid相同属性设置
                     shadowDepth: 5,     // 同grid相同属性设置
                     shadowAlpha: 0.3,   // 同grid相同属性设置
                 },
                 pointLabels: {  //显示数据点
                     show: true //是否在图表上显示数值点标签
                 } //数值点标签属性设置，该属性来自于jqplot.pointLabels.min.js
             },
             axes: { // 具体坐标轴属性
                 xaxis: {
                     label:"日期（天）", //x轴显示标题
                     renderer: $.jqplot.CategoryAxisRenderer, //把横(纵)坐标具有相同值的数据进行分类的渲染器
                     tickOptions: {
                         fontSize: '14px'
                     }
                 },
                 yaxis: {
                     label: "现金（元）", // y轴显示标题
                     tickOptions: { formatString: '%d', fontSize: '14px' }
                 }
             }
         });

     }

     function createLine(){
         var l1 = [[1,2],[2,6],[3,7],[4,10],[5,25],[6,0]];
         var l2 = [[1,5],[2,3],[3,2],[4,14],[5,25],[6,0]];

         plot3 = $.jqplot('flot-line-chart', [l1,l2], {
             title:{
                 text:'当年的销售额',
                 show:true,
                 fontFamily:'微软雅黑',
                 fontSize:'16px',
                 fontWeight:'bold',
                 textColor:'#515151',

             }, //标题
             legend: {    // 图例属性
                 renderer: $.jqplot.EnhancedLegendRenderer,//渲染插件
                 show: true,//设置是否出现分类名称框（即所有分类的名称出现在图的某个位置）
                 placement: 'outsideGrid',  // 设置图例位于图表外部
                 location: 's',     // 分类名称框出现位置, (e:东,w:西,s:南,n:北,nw:西北,ne:东北,sw:西南,se:东南)
                 yoffset: 60,//垂直偏移量
                 rendererOptions:{
                     numberRows: 1//显示多少行
                 }
             },
             series:
                 [
                     {color:'#FF8C00', label: 'line1'},
                     { color:'#BDB76B', label: 'line2'},
                     {
                         // 设置线条1的宽度和点的样式
                         lineWidth:2,
                         markerOptions: { style:'dimaond' }//点的样式为砖石
                     },
                 ], //提示工具栏
             seriesDefaults: {
                 rendererOptions: {
                     smooth: true
                 },
                 pointLabels: {  //显示数据点
                     show: true //是否在图表上显示数值点标签
                 } //数值点标签属性设置，该属性来自于jqplot.pointLabels.min.js
             },

             axes: { // 具体坐标轴属性
                 xaxis: {
                     label:"日期（天）", //x轴显示标题
                     renderer: $.jqplot.CategoryAxisRenderer, //把横(纵)坐标具有相同值的数据进行分类的渲染器
                     tickOptions: {
                         fontSize: '14px',
                     }
                 },
                 yaxis: {
                     label: "现金（元）", // y轴显示标题
                     min:0, //最小值
                     tickOptions: { formatString: '%d', fontSize: '14px' }
                 }
             }
         });
     }

    $('.select_table_tab button.btn-white').each(function(e){
        $(this).click(function(){
            $(this).siblings().removeClass('active'); //移除前一个选中按钮的Class:"active"
            $(this).addClass('active'); //给选中的button添加"active" Class

            var key = $(this).val();
            var parent = $('.select_table_tab').next('.ibox-content');
            var html= '';
            switch(key){
                case 'day':  //日 ->饼状图
                   createPie();
                    break;

                case 'month':   //月 生成柱状图表 -> 对应 Tab“月”
                    html = getTabMonthHTML();
                    parent.html(html);

                   createBar();
                    break;

                case 'year':   //年  生成折线图表 -> 对应 Tab“年”
                    html = getTabYearHTML();
                    parent.html(html);

                    createLine();

                    break;
            }
        });

    });

    /*获取“天”下的数据*/
    function getTabDayHTML(){
        var html = '';
        html += '<div class="row">'
                + '<div class="col-sm-3">'
                    + '<div class="input-group">'
                        + '<input type="text" placeholder="搜索" class="input-sm form-control"> '
                        + '<span class="input-group-btn">'
                            + '<button type="button" class="btn btn-sm btn-primary">搜索</button> '
                        + '</span>'
                    + '</div>'
                + '</div>'
            + '</div>'
            + '<div class="table-responsive">'
                + '<table class="table table-striped">'
                    + '<thead>'
                        + '<tr>'
                            + '<th>项目</th>'
                            + '<th>进度</th>'
                            + '<th>任务</th>'
                            + '<th>日期</th>'
                            + '<th>操作</th>'
                        + '</tr>'
                    + '</thead>'
                    + '<tbody>'
                        + '<tr>'
                            + '<td>米莫说｜MiMO Show</td>'
                            + '<td><span class="pie">0.52/1.561</span></td>'
                            + '<td>20%</td>'
                            + '<td>2014.11.11</td>'
                            + '<td><a href="#"><i class="fa fa-check text-navy"></i></a></td>'
                        + '</tr>'
                        + '<tr>'
                            + '<td>商家与购物用户的交互试衣应用</td>'
                            + '<td><span class="pie">6,9</span></td>'
                            + '<td>40%</td>'
                            + '<td>2014.11.11</td>'
                            + '<td><a href="#"><i class="fa fa-check text-navy"></i></a></td>'
                        + '</tr>'
                        + '<tr>'
                            + '<td>天狼---智能硬件项目</td>'
                            + '<td><span class="pie">3,1</span></td>'
                            + '<td>75%</td>'
                            + '<td>2014.11.11</td>'
                            + '<td><a href="#"><i class="fa fa-check text-navy"></i></a></td>'
                        + '</tr>'
                        + '<tr>'
                            + '<td>线下超市+线上商城+物流配送互联系统</td>'
                            + '<td><span class="pie">4,9</span></td>'
                            + '<td>18%</td>'
                            + '<td>2014.11.11</td>'
                            + '<td><a href="#"><i class="fa fa-check text-navy"></i></a></td>'
                        + '</tr>'
                    + '</tbody>'
                + '</table>'
            + '</div>';

        html += '<div class="flot-chart">';
        html += '<div class="flot-chart-content" id="flot-pie-chart"></div>';  //饼状图
        html += '</div>';
        return html;
    }

    /*获取“月”下的数据*/
    function getTabMonthHTML() {
        var html ='';
        html += '<div class="flot-chart">';
        html += '<div id="info2"></div>';
        html += '<div class="flot-chart-content" id="flot-bar-chart" style="height: 280px"></div>';  //折线图
        html += '</div>';


//
//        html += '<div class="row">'
//            + '<div class="col-sm-9">'
//            + '<div class="flot-chart">'
//            + '<div class="flot-chart-content" id="flot-dashboard-chart"></div>'
//            + '</div>'
//            + '</div>'
//            + '<div class="col-sm-3">'
//            + '<ul class="stat-list">'
//            + '<li>'
//            + '<h2 class="no-margins">2,346</h2>'
//            + '<small>订单总数</small>'
//            + '<div class="stat-percent">48% <i class="fa fa-level-up text-navy"></i></div>'
//            + '<div class="progress progress-mini">'
//            + '<div style="width: 48%;" class="progress-bar"></div>'
//            + '</div>'
//            + '</li>'
//            + '<li>'
//            + '<h2 class="no-margins ">4,422</h2>'
//            + '<small>最近一个月订单</small>'
//            + '<div class="stat-percent">60% <i class="fa fa-level-down text-navy"></i></div>'
//            + '<div class="progress progress-mini">'
//            + '<div style="width: 60%;" class="progress-bar"></div>'
//            + '</div>'
//            + '</li>'
//            + '</ul>'
//            + '</div>'
//            + '</div>';
        return html;
    }

    /*获取“年”下的数据*/
    function getTabYearHTML(){
        var html = '';
        html += '<div class="flot-chart">';
        html += '<div class="flot-chart-content" id="flot-line-chart"></div>';
        html += '</div>';
        return html;
    }
</script>