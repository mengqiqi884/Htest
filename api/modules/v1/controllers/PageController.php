<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/31
 * Time: 15:31
 */
namespace v1\controllers;

use Yii;
use yii\base\Exception;
use yii\base\ErrorException;
use v1\models\CPage;

class PageController extends ApiController
{
    const PAGE_SIZE = 10;

    /**
     * 获取用户协议信息
     * @return array
     */
    public function actionGetAgreementInfo()
    {
       // $data = CPage::find()->where(['p_remark' => '用户协议'])->asArray()->one();

//        if (!empty($data)) {
//            return $this->showResult(200, '获取成功', $data);
//        } else {
//            return $this->showResult(400, '获取失败');
//        }
        $url=Yii::$app->params['pic_url'].'/admin/web/index.php/site/user-page';
        $data=[
            'p_id' => 1,
            'p_content' =>$url,
            'p_remark' => '用户协议'
        ];
        return $this->showResult(200, '获取成功', $data);
    }
}

