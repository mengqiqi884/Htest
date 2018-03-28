<?php
namespace api\controllers;


use api\ext\auth\QueryParamAuth;
use Yii;
    class SiteController extends ApiController{

        public function behaviors()
        {
            $behaviors = parent::behaviors();
            $behaviors['authenticator'] = [
                'class' => QueryParamAuth::className(),
                'except' => ['error']
            ];
            $behaviors['verbs'] = [
                'class'=> \yii\filters\VerbFilter::className(),
                'actions'=>[
                    '*'=>['post','get']
                ]
            ];
            return $behaviors;
        }

        /*
        * 数据库备份
        */
        public function actionError(){
            return $this->redirect('error');
        }
        
    }