<?php

namespace zyj\map\gaode;

use Yii;
use zyj\map\MapBasic;

/**
 * 	高德 逆经纬度 获取地理位置接口
 *
 * 	@author zyj
 */
class Regao extends MapBasic
{
	/**
	 * 	请求地址
	 *
	 * 	@var string
	 */
	public $url = 'http://restapi.amap.com/v3/geocode/regeo?';


	/**
	 * 根据经纬度获取详细的地理位置信息
	 * @param $lng
	 * @param $lat
	 * @return array ！
     */
	public 	function myRequest($lng, $lat)
	{
		$location = $lng.','.$lat;
		$data = [
				'location'=> $location,
				'key'=>$this->key,
				'output'=>$this->output
		];

		$query_string = http_build_query($data);
		$require_url = $this->url.$query_string;
		$this->url = $require_url;
		$result= $this->getRequire();
		return $this->MyJsonData($result);
	}


	/**
	 * 解析逆经纬度获取的数据
	 * @param $result
	 * @return array | city 城市 province 省 district 区 distance 距离 location 经纬度（经度,维度）
     */
	public function MyJsonData($result){
		$obj = json_decode($result);
		$arr = [];
		$status = $obj->status;
		$arr['status'] = $status;
		if($status != 0){
			$addressComponent = $obj->regeocode->addressComponent;
			$arr['city'] = $addressComponent->city;
			$arr['province'] = $addressComponent->province;
			$arr['district'] = $addressComponent->district;
			$arr['distance'] = $addressComponent->streetNumber->distance;
			$arr['location'] = $addressComponent->streetNumber->location;
		}

		return $arr;
	}
}
?>