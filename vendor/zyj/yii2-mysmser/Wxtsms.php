<?php
	
	namespace zyj\smser;

	use Yii;
	use yii\base\InvalidConfigException;
	/**
	 * 	网信通短信平台
	 * 	
	 * 	@author zyj
	 */
	class Wxtsms extends Smser
	{
		/**
		 * 	请求地址
		 * 
		 * 	@var string
		 */
		public $url = 'http://www.js10088.com:18001';
		// http://www.js10088.com:18001/?Action=SendSms&UserName=用户名&Password=密码(32位MD5加密)&Mobile=手机号1;手机号2&Message=短信内容

		/**
		 * 发送短信
		 * 
		 * 	@var string $mobile  例如"13861248964;150519888";
		 *	@var string $content 
		 *	@return boolean
		 */
		public 	function sendSms($mobile,$content)
		{
			$data = [
				'Action'=>'SendSms',
				'Username'=>$this->username,
				'Password'=>$this->password,
				'Mobile'=>$mobile,
				'Message'=>urlencode($content)
			];
			$result= $this->postRequire($data);
			$res = explode(':', $result);
			$res_sta = $res[0];
			$this->state = false;
			switch($res_sta){
				case '0':
					$this->message = '短信发送成功';
					$this->state = true;
					break;
				case '1':
					$this->message = '号码太多,最多100个';
					break;
				case '2':
					$this->message = '超过今天的最大发送量';
					break;
				case '3':
					$this->message = '所剩于的发送总量低于您现在的发送量';
					break;
				case '7':
					$this->message = 'action 参数错误';
					break;
				case '8':
					$this->message = '系统错误';
					break;
				case '9':
					$this->message = '用户名或者密码错误';
					break;
				case '99':
					$this->message = '超出许可连接数';
					break;
				default :
					$this->message = '发送失败';
					break;

			}

			return $this->state;
		}

	}
?>