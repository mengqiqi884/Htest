<?php

namespace v1\controllers;
use Yii;
use yii\base\Object;
use yii\web\Controller;
use api\ext\auth\QueryParamAuth;
class ApiController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'except'=>['goodslist','smser','city','province','index','carcontent','carlist','login','register','updatelogo','retrievepwd','recommendlist','banner', 'get-home-forum-list', 'get-forum-list', 'get-forum-item', 'get-reply-list', 'get-agreement-info'],
        ];
        $behaviors['verbs'] = [
            'class'=> \yii\filters\VerbFilter::className(),
            'actions'=>[
                'sbootpic' => ['get'],
                'ubootpic' => ['get'],
                '*'=>['post','get']
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
    	return preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9][0-9]{8}|17[0-9]{9}$/",$mobilephone) && strlen($mobilephone)==11;
    }

//    public function showResult($code=200,$message='',$data=[]){
//        $result = [
//            'status'=>(string)$code,
//            'message'=>$message,
//        ];
//        if(!empty($data)){
//           array_walk_recursive($data,[static::className(),'treatNull']);
//            $result['data'] = $data;
//        }
//
//
//        return $result;
//    }
    public function showResult($code=200,$message='',$data=[],$is_do_walk=true){
        if(!empty($data)){
            if($is_do_walk){
                array_walk_recursive($data,[static::className(),'treatNull']);
            }
        }
        $result = [
            'status'=>(string)$code,
            'message'=>$message,
            'data'=>empty($data) ?new Object():$data,
        ];
        return $result;
    }



    public function showResArr($code=200,$message='',$data=[],$is_do_walk=true){
        if(!empty($data)){
            if($is_do_walk){
                array_walk_recursive($data,[static::className(),'treatNull']);
            }
        }
        $result = [
            'status'=>(string)$code,
            'message'=>$message,

            'data'=>empty($data) ?[]:$data,
        ];
        return $result;
    }
    /**
     * @param int $code
     * @param string $message
     * @param $totalval
     * @param array $data
     * @return array
     */
    public function showList($code=200, $message='', $totalval, $data=[]){
    	$result = [
    			'status'=>(string)$code,
    			'message'=>$message,
    			'totalval' => (string)$totalval,
    	];
    	if(!empty($data)){
    		$result['data'] = $data;
    	}
    	return $result;
    }

    /**
     * @param int $code
     * @param string $message
     * @param $totalval
     * @param array $data
     * @return array
     */
    public function showListArr($code=200, $message='', $totalval, $data=[],$is_do_walk=true){
        if(!empty($data)){
            if($is_do_walk){
                array_walk_recursive($data,[static::className(),'treatNull']);
            }
        }
        $result = [
            'status'=>(string)$code,
            'message'=>$message,
            'totalval' => (string)$totalval,
            'data'=>$data
        ];
        //    	if(!empty($data)){
        //    		$result['data'] = $data;
        //    	}
        return $result;
    }

    /**
     * @param $v
     * @param $k
     */
    public static function treatNull(&$v, $k){
        if($v === null){
            $v = '';
        }
        if(gettype($v) == 'integer'){
            $v = (string)$v;
        }
    }


}