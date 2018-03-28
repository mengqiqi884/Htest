<?php

namespace api\helpers\controllers;
use api\helpers\ApiResponse;
use Yii;
use yii\web\Controller;
class ApiController extends Controller
{


    public function showResult($code=200,$message='',$data=[]){
        $result = [
            'status'=>(string)$code,
            'message'=>$message,
        ];
        if(!empty($data)){
           array_walk_recursive($data,[static::className(),'treatNull']);
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


    public function runAction($id, $params = [])
    {
        $action = $this->createAction($id);
        if ($action === null) {
            //throw new InvalidRouteException('Unable to resolve the request: ' . $this->getUniqueId() . '/' . $id);
            echo json_encode(ApiResponse::showResult(102));
            exit;
        }

        Yii::trace("Route to run: " . $action->getUniqueId(), __METHOD__);

        if (Yii::$app->requestedAction === null) {
            Yii::$app->requestedAction = $action;
        }

        $oldAction = $this->action;
        $this->action = $action;

        $modules = [];
        $runAction = true;

        // call beforeAction on modules
        foreach ($this->getModules() as $module) {
            if ($module->beforeAction($action)) {
                array_unshift($modules, $module);
            } else {
                $runAction = false;
                break;
            }
        }

        $result = null;

        if ($runAction && $this->beforeAction($action)) {
            // run the action
            $result = $action->runWithParams($params);

            $result = $this->afterAction($action, $result);

            // call afterAction on modules
            foreach ($modules as $module) {
                /* @var $module Module */
                $result = $module->afterAction($action, $result);
            }
        }

        $this->action = $oldAction;

        return $result;
    }

}