<?php

namespace pc\controllers;

use admin\models\CPage;
use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use dosamigos\qrcode\QrCode;
use dosamigos\qrcode\lib\Enum;
/**
 * page controller
 */
class PageController extends Controller
{

    //用户协议
    public function actionIndex()
    {
        return $this->render('index');
    }


}
