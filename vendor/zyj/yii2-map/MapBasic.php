<?php
	
	namespace zyj\map;

	use Yii;
	/**
	 * 	地图接口
	 * 
	 * 	@author zyj <617242499@qq.com>
	 */
	abstract class MapBasic extends \yii\base\Object
	{	
		/**
		 *  请求地址
		 * 
		 * 	@var string 
		 */

		public $url = '';

		/**
		 *  响应格式
		 *
		 * 	@var string
		 */
		public $output = 'json';
		/**
		 * @var
		 *	用户申请 的key
		 *
		 */
		protected $key = '';
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
		protected  function postRequire($data)
		{

			$ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $this->url);
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
//			curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
			$result = curl_exec($ch);

			curl_close($ch);

			return $result;
		}

		/**
		 * @param $key
		 * 设置
         */
		protected function setKey($key){
			$this->key = $key;
		}

		protected  function getKey(){
			return $this->key;
		}
	}
?>