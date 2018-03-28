<?php
	
	namespace zyj\smser;

	use Yii;
	/**
	 * 	短信扩展基类
	 * 
	 * 	@author zyj <617242499@qq.com>
	 */
	abstract class Smser extends \yii\base\Object
	{	
		/**
		 *  请求地址
		 *
		 * 	@var string 
		 */

		public $url;

		/**
		 * 	用户名
		 * 	
		 *  @var string
		 */
		public $username;

		/**
		 *  密码
		 * 
		 * 	@var string
		 */
		protected $password;

		/**
		 *  云片apikey
		 * 
		 * 	@var string
		 */
		protected $apikey;
		/**
		 * 	状态码
		 * 	
		 * 	@var string
		 */
		protected $state;

		/**
		 * 	状态信息
		 * 
		 * 	@var string
		 */
		protected $message;

		/**
		 *  curl post 请求
		 *	
		 *	@param array  $data
		 *	@retrun result
		 */
		protected  function postRequire($data,$url)
		{

			$ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	        
	        $result = curl_exec($ch);
	        curl_close($ch);
	        return $result;
		}

		/**
		 * 	curl get 请求
		 * 
		 * 	$return result
		 */
		protected function getRequire()
		{

			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$this->url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HEADER, 0);

			$result = curl_exec($ch);

			curl_close($ch);

			return $result;
		}

		/**
		 * 	设置密码
		 * 
		 * 	@var string $password
		 */
		protected function setPassword($password)
		{

			$this->password = $password;
		}

		/**
		 * 	获取消息
		 * 
		 * 	@return string
		 */
		protected function getMessage()
		{

			return $this->message;
		}

		/**
		 * 	获取状态
		 * 
		 * 	@return state
		 */
		protected function getState()
		{
			return $this->state;
		}

		/**
		 * 	设置apikey
		 * 
		 * 	@return state
		 */
		protected function setApikey($apikey)
		{
			 $this->apikey  = $apikey;
		}

	}
?>