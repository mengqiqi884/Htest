<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/9/1
 * Time: 13:25
 */

namespace admin\controllers;

use admin\models\CUser;
use yii\filters\VerbFilter;
use yii\web\Controller;
use \Yii;

class ExportController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 导出excel
     */
    public function actionToExcel()
    {
        //引入excel
//        require dirname(dirname(__FILE__)).'/../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
//        $objPHPExcel = new \PHPExcel(); //实例化PHPExcel类，等同于在桌面上新建一个excel
        $excel_content = '';

        $table = Yii::$app->request->get('table');

        switch($table) {
            case "user":
                //使用html语句生成显示的格式
                $excel_content .= "<meta http-equiv='content-type' content='application/ms-excel;charset=utf-8'/>";
                $excel_content .= "<table border='1' style='font-size: 14px;border-collapse:collapse;'>";
                $excel_content .= "<thead>";
                $excel_content .= "<tr>" .
                                    "<th rowspan='2' width='5%' style='text-align: center'>ID</th>" .
                                    "<th rowspan='2' width='8%' style='text-align: center'>联系方式</th>" .
                                    "<th rowspan='2' width='10%' style='text-align: center'>昵称</th>" .
                                    "<th rowspan='2' width='5%' style='text-align: center'>年龄</th>" .
                                    "<th rowspan='2' width='5%' style='text-align: center'>性别</th>" .
                                    "<th colspan='3' width='24%' style='text-align: center'>数值统计</th>" .
                                    "<th rowspan='2' width='10%' style='text-align: center'>注册日期</th>" .
                                 "</tr>";
                $excel_content .= "<tr>" .
                                    "<th colspan='1' width='8%' style='text-align: center'>积分数</th>" .
                                    "<th colspan='1' width='8%' style='text-align: center'>车辆数</th>" .
                                    "<th colspan='1' width='8%' style='text-align: center'>帖子数</th>" .
                                 "</tr>";
                $excel_content .= "</thead>";

                //数据库查找数据
                $sql = "select u_id,u_phone,u_nickname,u_age,u_sex,u_score,u_cars,u_forums,created_time from c_user where u_state=1 order by created_time desc";
                $searchmodel = Yii::$app->db->createCommand($sql)->queryAll();
                if ($searchmodel) {
                    $excel_content .= "<tbody>";
                    foreach ($searchmodel as $user) {
                        $excel_content .= "<tr>";
                        $excel_content .= "<td style='text-align: center'>" . $user['u_id'] . "</td>" .
                                          "<td style='text-align: center'>" . $user['u_phone'] . "</td>" .
                                          "<td style='text-align: center'>" . $user['u_nickname'] . "</td>" .
                                          "<td style='text-align: center'>" . $user['u_age'] . "岁 </td>" .
                                          "<td style='text-align: center'>" . ($user['u_sex'] == 1 ? '男' : ($user['u_sex'] == 2 ? '女' : '性别不详')) . "</td>" .
                                          "<td style='text-align: center'>" . $user['u_score'] . "</td>" .
                                          "<td style='text-align: center'>" . $user['u_cars'] . "</td>" .
                                          "<td style='text-align: center'>" . $user['u_forums'] . "</td>" .
                                          "<td style='text-align: center'>" . $user['created_time'] . "</td>";
                        $excel_content .= "</tr>";
                    }
                    $excel_content .= "</tbody>";
                }
                $excel_content .= "</table>";
                break;
            default:
                break;
        }
        $this->browser_export('Excel5','browser_excel03.xls'); //输出到浏览器 ,第一个参数是 excel版本 03/07 ，第二个参数是 生成的excel文件名称
        echo $excel_content;
        exit;

//        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5'); //生成excel文件

//        $objWriter->save('php://output');
//        exit;//一定要紧跟exit 要不然会有列数据限制或说文件损坏之类
    }

    private function browser_export($type,$filename)
    {
        if($type == 'Excel5'){
            header("Content-Type:application/vnd.ms-excel"); //告诉浏览器将要输出excel03文件
        }else{
            header("Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"); //告诉浏览器将要输出excel07文件
        }
        header("Content-Disposition:attachment;filename='".$filename."'"); //告诉浏览器将输出文件的名称
        header("Cache-Control:max-age=0"); //禁止缓存
    }

    /**
     * 使用excel表格上的数据实在excel上生成图形
     * 只支持excel2007版本的
     * （折线图）
     */
    public function actionCreateExcelImg()
    {
        //引入excel
        require dirname(dirname(__FILE__)).'/../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';
        $objPHPExcel = new \PHPExcel(); //实例化PHPExcel类，等同于在桌面上新建一个excel
        $objSheet = $objPHPExcel->getActiveSheet(); //获取当前活动sheet

        //准备数据
        $array = [
            ["","一班","二班","三班"],
            ["不及格",20,30,40],
            ["良好",30,50,55],
            ["优秀",10,27,8]
        ];
        //填充单元格
        $objSheet->fromArray($array);
        //开始图表代码编写
        $label = [
            new \PHPExcel_Chart_DataSeriesValues('String','WorkSheet!$B$1',null,1), //一班
            new \PHPExcel_Chart_DataSeriesValues('String','WorkSheet!$C$1',null,1), //二班
            new \PHPExcel_Chart_DataSeriesValues('String','WorkSheet!$D$1',null,1), //三班
        ];//先取得绘制图表的标签
        $xLabel = [
            new \PHPExcel_Chart_DataSeriesValues('String','WorkSheet!$A$2:$A$4',null,3) //取得图标X轴的刻度
        ];//
        $data = [
            new \PHPExcel_Chart_DataSeriesValues('Number','WorkSheet!$B$2:$B$4',null,3), //取一班的数据
            new \PHPExcel_Chart_DataSeriesValues('Number','WorkSheet!$C$2:$C$4',null,3), //取二班的数据
            new \PHPExcel_Chart_DataSeriesValues('Number','WorkSheet!$D$2:$D$4',null,3), //取三班的数据

        ];//取得绘图所需的数据
        $series = [
            new \PHPExcel_Chart_DataSeries(
                \PHPExcel_Chart_DataSeries::TYPE_LINECHART,
                \PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
                range(0,count($label)-1),
                $label,
                $xLabel,
                $data
            )
        ];//根据取得的信息做出一个图表的框架

        $layout = new \PHPExcel_Chart_Layout();
        $layout->setShowVal(true); //给图表上每个点添加值
        $areas = new \PHPExcel_Chart_PlotArea($layout,$series);
        $legend = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_RIGHT,$layout,false);
        $title = new \PHPExcel_Chart_Title('高一学生成绩分布'); //图表的标题
        $ytitle = new \PHPExcel_Chart_Title('value(人数)');
        $chart = new \PHPExcel_Chart(
            'line_chart',
            $title,
            $legend,
            $areas,
            true,
            false,
            null,
            $ytitle
        );//生成一个图表
        $chart->setTopLeftPosition('A7')->setBottomRightPosition('K25');//给定图表所在表格的位置
        $objSheet->addChart($chart);//将chart添加到表格中

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007'); //生成excel文件
        $objWriter->setIncludeCharts(true);  //这句在生成图表时必须使用

        $this->browser_export('Excel2007','browser_excel03.xls'); //输出到浏览器 ,第一个参数是 excel版本 03/07 ，第二个参数是 生成的excel文件名称
        $objWriter->save('php://output');
        exit;//一定要紧跟exit 要不然会有列数据限制或说文件损坏之类
    }


    /**
     * 导入表格
     */
    public function actionImport()
    {
        header('Content-Type:text/html;charset=utf-8');
        //引入读取excel的类文件
        require dirname(dirname(__FILE__)).'/../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
        $filename = 'xxx.xls';

        //////////////////////如果只需要加载指定的sheet时，用以下5句 strat////////////////////////////////////////////////////
        $fileType = \PHPExcel_IOFactory::identify($filename); //自动获取文件的类型提供给phpexcel用
        $objReader = \PHPExcel_IOFactory::createReader($fileType); //获取文件读取操作对象
        $sheetName = ['2年级','3年级'];
        $objReader->setLoadSheetsOnly($sheetName); //只加载指定的sheet
        $objPHPExcel = $objReader->load($filename);//加载文件
        /////////////////////////////////end ///////////////////////////////////////////////

        /////////////////////////如果需要加载全部的sheet时，用以下2句 strat//////////////////////////////////////////////////////////
//        $objPHPExcel =\PHPExcel_IOFactory::load($filename); //加载文件
//        $sheetCount = $objPHPExcel->getSheetCount(); //获取文件里sheet的数量
        ////////////////////////////end/////////////////////////////////////////////////////////////

        foreach($objPHPExcel->getWorksheetIterator() as $sheet){ //循环取sheet
            foreach($sheet->getRowIterator() as $row){ //逐行处理
                if($row->getRowIndex()<2){
                    continue;
                }
                //获取第2行及以后的数据
                foreach($row->getCellIterator() as $cell){//逐列读取
                    $data = $cell->getValue(); //获取数据,得到的是 字符串
                    echo $data . ' ';
                }
                echo '<br/>';
            }
            echo '<br/>';
        }
        exit;
    }
}