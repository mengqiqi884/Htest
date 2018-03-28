<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\helpers;
use yii\helpers\BaseArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * ArrayHelper provides additional array functionality that you can use in your
 * application.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ArrayHelper extends BaseArrayHelper
{

	public static function getOneError($errors){
		foreach($errors as $val){
			return $val[0];
			exit;
		}
	}

	public static function myGetFirstError($model){
		$errors = $model->getFirstErrors();
		return current($errors);
	}

	public static function myJsonError($status,$message){
		return [
			'status'=>$status,
			'message'=>$message
		];
	}
	static public function cut_utf8str($source,$length){
			$return_str = '';
			$i = 0;
			$n = 0;
			$strlength = strlen($source);
			while(($n<$length) &&($i<$strlength)){
				$tem_str = substr($source,$i,1);
				$ascnum = ord($tem_str);
				if($ascnum>=224){
					$return_str .= substr($source,$i,3);
					$i +=3;
					$n++;
				}elseif($ascnum>=192){
					$return_str .= substr($source,$i,2);
					$i +=2;
					$n++;
				}elseif($ascnum>=65 && $ascnum<=90){
					$return_str .=substr($source,$i,1);
					$i +=1;
					$n++;
				}else{
					$return_str .=substr($source,$i,1);
					$i +=1;
					$n +=0.5;
				}
			}
			if($strlength>$length){
				$return_str .='...';
			}
			return $return_str;
		}

	/**
	 * 	curl get 请求
	 *
	 * 	$return result
	 */
	public static  function getRequire($url)
	{

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		$result = curl_exec($ch);

		curl_close($ch);

		return $result;
	}

	/**
	 * 极光推送发通知
	 * 默认消息保存时间1小时
	 */
	public static function pushNotifyByReg($register_arr,$title,$content,$sound='iOS sound',$extra=[],$config=[]){
		require_once ('../../vendor/jpush/jpush/src/JPush/JPush.php');

		$app_key = isset($config['app_key']) ? $config['app_key'] : \Yii::$app->params['app_key'];
		$master_secret = isset($config['master_secret']) ? $config['master_secret'] : \Yii::$app->params['master_secret'];

		$apns_production = isset(\Yii::$app->params['env']) ? \Yii::$app->params['env'] : false;
		if(empty($app_key) || empty($master_secret)){
			throw new NotFoundHttpException('请设置app_key,master_secret');
		}

		$client = new \JPush($app_key, $master_secret);
		$result = $client->push()
			->setPlatform(array('android','ios'))
			->addRegistrationId($register_arr)
			->setNotificationAlert('新推送')
			->addAndroidNotification($content, $title, 1, $extra)
			->addIosNotification($content,$sound, \JPush::DISABLE_BADGE, true, 'iOS category', $extra)
			->setOptions(100000, 3600, null, $apns_production)
			->send();

		return $result;

	}


}