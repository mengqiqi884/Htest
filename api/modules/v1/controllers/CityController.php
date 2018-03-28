<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/31
 * Time: 15:31
 */

namespace v1\controllers;


use v1\models\CCarreplace;
use v1\models\CCity;
use v1\models\CUser;

use Yii;
use common\helpers\StringHelper;
use yii\web\UploadedFile;


class CityController extends ApiController
{
    const PAGE_SIZE = 10;

    protected function validatePassword($pass1, $pass2)
    {
        return $pass1 == $pass2;
    }

    public function actionIndex()
    {

    }

    //获取省
    public function actionProvince()
    {
        $city = CCity::find()->where(['level' => 1, 'status' => 1])->orderBy(['is_hot'=>SORT_DESC,'code'=>SORT_ASC])->all();
        if (empty($city)) {
            return $this->showResult(400, '获取失败');
        }
        $data = '';
        foreach ($city as $v) {
            $data[] = [
                'province' => $v->name,
                'code' => $v->code
            ];
        }
        if (!empty($data)) {
            return $this->showResult(200, '获取成功', $data);
        } else {
            return $this->showResult(400, '获取失败');
        }
    }


//获取城市
    public function actionCity()
    {
        $code = Yii::$app->request->post('code', '');
        if (empty($code)) {
            return $this->showResult(302, '传参错误');
        }
        $city = CCity::find()->where(['level' => 2, 'status' => 1, 'parent' => $code])->orderBy('code')->all();
        if (empty($city)) {
            return $this->showResult(400, '获取失败');
        }
        $data = '';
        foreach ($city as $v) {
            $data[] = [
                'city' => $v->name,
                'code' => $v->code
            ];
        }
        if (!empty($data)) {
            return $this->showResult(200, '获取成功', $data);
        } else {
            return $this->showResult(400, '获取失败');
        }
    }
    
}

