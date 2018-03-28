<?php

namespace api\controllers;
use Yii;
use yii\web\Controller;
use api\ext\auth\QueryParamAuth;
class ApiController extends Controller
{
	/**
	asdfsadfsdf阿斯顿法师打发
	*/
    public function behaviors(){
    	$behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'except'=>['register','is-exist','send-message','login','reset-pwd','search-list','home','boot-pic','ad-list','rush-list',
					'vip-list','hot-list','good-list','good-detail','comment-list','shop-spread','wx-pay-order','ali-pay-order',
				'wx-pay-account','ali-pay-account','setting','activity']
        ];
    	$behaviors['verbs'] = [
    			'class'=> \yii\filters\VerbFilter::className(),
    			'actions'=>[
	    			'*'=>['post'],
    			]
    		];
    	return $behaviors;
    }
    
    /**
     * 验证手机号格式方法
     * @param unknown $mobilephone
     * @return boolean
     */
    public function validateMobilePhone($mobilephone){
    	return preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9][0-9]{8}|17[0-9]{9}$|14[0-9]{9}$/",$mobilephone) && strlen($mobilephone)==11;
    }

	/**
	 * 按json方式输出通信数据格式
	 * @param integer $code 状态码
	 * @param string $message 提示信息
	 * @param array $data 数据
	 * @param integer $totalval 数据总数
	 * @param bool $tradeNull
	 * @return string
	 */
	public function showJson($code, $message = '', $data = [], $totalval = 0, $tradeNull=true){
		if(!is_numeric($code)){  //$code不是数字
			return '';
		}
		$result = [
			'code' => (string)$code,
			'message' => $message,
			'totalval' => (string)$totalval,
		];

		if(!empty($data)){
			if(gettype($data)=='array'){
				if($tradeNull){
					array_walk_recursive($data,[static::className(),'HandleData']);
				}
				$result['data'] = $data;
			}else{
				$result['data'] = (string)$data;
			}
		}

		return json_encode($result);
	}

	/**
	 * 按xml方式输出通信数据格式
	 * @param int $code 状态码
	 * @param string $message 提示信息
	 * @param array  $data 数据
	 * @return string
	 */
	public function showXml($code=200, $message='', $data=[])
	{
		if (!is_numeric($code)) {  //$code不是数字
			return '';
		}
		$result = [
			'code' => (string)$code,
			'message' => $message,
			'data' => $data
		];

		header("Content-Type:text/xml");   //指定页面显示类型
		$xml = "";
		$xml .= "<?xml version='1.0' encoding='UTF-8'?>\n";
		$xml .= "<root>\n";
		$xml .= static::XmltoData($result);
		$xml .= "</root>";

		echo $xml;
		exit;
	}

	/**
	 * 将数组封装成xml需要的格式
	 * @param $data  数据
	 * @return string
	 */
	public static function XmltoData($data){
		$xml = "";
		$attr = "";
		foreach($data as $key=>$value){
			if(is_numeric($key)){ //如果$key是数字的话
				$attr = " id='{$key}'";
				$key = "item";
			}
			$xml .= "<{$key}{$attr}>";
			$xml .= is_array($value) ? self::XmltoData($value): $value;  //当$value是数组时，使用递归，再进行遍历
			$xml .= "</{$key}>\n";
		}

		return $xml;
	}

    public function showResult($code=200,$message='',$data=[],$tradeNull=true){
        $result = [
            'status'=>(string)$code,
            'message'=>$message,
        ];
		if(!empty($data)){
			if(gettype($data)=='array'){
				if($tradeNull){
					array_walk_recursive($data,[static::className(),'HandleData']);
				}
				$result['data'] = $data;
			}else{
				$result['data'] = (string)$data;
			}
		}
        return $result;
    }

	/**
	 * @param $v
	 * @param $k
	 */
	public static function HandleData(&$val, $key)
	{
		if ($val === null) {
			$val = '';
		}
		if (gettype($val) != 'array') {
			$val = (string)$val;
		}
	}

}