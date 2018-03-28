<?php
	
	namespace zyj\smser;

	use Yii;
	use yii\base\InvalidConfigException;
	/**
	 * 	网信通短信平台
	 * 	
	 * 	@author zyj
	 */
	class Qczhsms extends Smser
	{
		/**
		 * 	请求地址
		 * 
		 * 	@var string
		 */
		public $url = 'http://139.129.207.83/send_msg_hz/DEHsendMsg.php?';
		// http://www.js10088.com:18001/?Action=SendSms&UserName=用户名&Password=密码(32位MD5加密)&Mobile=手机号1;手机号2&Message=短信内容

		/**
		 * 发送短信
		 * 
		 * 	@var string $mobile  例如"13861248964;150519888";
		 *	@var string $content 
		 *	@return boolean
		 */
		public 	function sendSms($mobile,$content,$session)
		{
			$data = [
				'phones'=>$mobile,
				'content'=>$content,
				'session'=>$session
			];
			$this->url.=http_build_query($data);
			$result= $this->getRequire();

			$res = explode(',', $result);
			$res_sta = $res[0];
			$message =$res[2];
			$this->state = false;
			if($res_sta==0){
				$this->state=true;
				$this->message=$message;
			}else{
				$this->message=$message;
			}

			return $this->state;
		}

	}
?>