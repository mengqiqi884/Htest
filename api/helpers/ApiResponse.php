<?php

namespace api\helpers;

use yii\base\NotSupportedException;

class ApiResponse
{
	protected static $SERVER_CODE = [
		//系统错误
			'100'=>'系统错误',
			'101'=>'参数错误',
			'102'=>'接口不存在',
			'103'=>'请求方式不正确',
			'104'=>'用户无权访问',
			'105'=>'权限不够',
		//成功
			'200'=>'成功'
	];


	public static function className(){
		return get_called_class();
	}

	public static function showResult($code=200,$data=[],$message='',$is_walk=true){
		$server_code = static::$SERVER_CODE;
		if(empty($message)){
			if(isset($server_code[$code])){
				$message = $server_code[$code];
			}else{
				throw new NotSupportedException('message 不能为空');
			}
		}
		$result = [
				'status'=>(string)$code,
				'message'=>$message,
		];
		if(!empty($data)){
			if($is_walk){
				array_walk_recursive($data,[static::className(),'treatNull']);
			}
			$result['data'] = $data;
		}

		return $result;
	}

	public static function treatNull(&$v, $k){
		if($v === null){
			$v = '';
		}
		if(gettype($v) == 'integer'){
			$v = (string)$v;
		}
	}

}