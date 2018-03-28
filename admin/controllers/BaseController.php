<?php

namespace admin\controllers;

use Yii;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller {

    public function renderJson($params = array()) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $params;
    }

    public function showResult($code=200,$message='',$data=[]){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = [
            'status'=>(string)$code,
            'message'=>$message,
        ];
        if(!empty($data)){
            $result['data'] = $data;
        }
        return $result;
    }

    public function validateMobilePhone($mobilephone){
        return preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9][0-9]{8}|17[0-9]{9}$|14[0-9]{9}$/",$mobilephone) && strlen($mobilephone)==11;
    }

    /*生成uuid编码*/
    function create_uuid($prefix = ""){    //可以指定前缀
        $str = md5(uniqid(mt_rand(), true));
        $uuid  = substr($str,0,8);
        $uuid .= substr($str,8,4) ;
        $uuid .= substr($str,12,4);
        $uuid .= substr($str,16,4);
        $uuid .= substr($str,20,12);
        return $prefix . $uuid;
    }



    /*
    * 表数据转化为数组 excel 低版本excel，不包括excel2007
    */

    function excelToArray($file){

        /*创建对象,针对Excel2003*/
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        try {
            /*加载对象路径*/
            $objPHPExcel = $objReader->load($file);
        }catch(Exception $e){}

        if(!isset($objPHPExcel)){
            return ['state'=>'500','message'=>'读取文件失败'];
        }

        /*获取工作表*/
        $objWorksheet=$objPHPExcel->getSheet(0);
        //也可以这样获取，读取第一个表,参数0
        $excelData=array();
        /*得到总行数*/
        $allRow = $objWorksheet->getHighestRow();
        /*得到总列数*/
        $highestColumn = $objWorksheet->getHighestColumn();
        $allColumn= \PHPExcel_Cell::columnIndexFromString($highestColumn);
        for ($row=3; $row <= $allRow; ++$row) {
            for ($col = 0; $col <= $allColumn; ++$col) {
                $excelData[$row][] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return json_encode($excelData);
    }
}