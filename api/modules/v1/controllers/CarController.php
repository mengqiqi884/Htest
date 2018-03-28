<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/31
 * Time: 15:31
 */

namespace v1\controllers;


use v1\models\CCity;
use v1\models\CCar;
use v1\models\CCarreplace;
use v1\models\CCarreplacePics;
use v1\models\CUser;

use Yii;
use common\helpers\StringHelper;
use yii\web\UploadedFile;


class CarController extends ApiController
{
    const PAGE_SIZE = 10;

    protected function validatePassword($pass1, $pass2)
    {
        return $pass1 == $pass2;
    }

    public function actionIndex()
    {
    }

    //添加车辆信息
    public function actionCar()
    {
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id' => $user_id]);
        if (empty($userModel)) {
            return $this->showResult(302, '获取用户信息失败');
        }
//        $car_id = Yii::$app->request->post('car_id','');//汽车ID 非必传
        $driving_pic1 = Yii::$app->request->post('driving_pic1', '');//行驶证1
        $driving_pic2 = Yii::$app->request->post('driving_pic2', '');//行驶证2
        $brand = Yii::$app->request->post('brand', '');//品牌
        $car_num = Yii::$app->request->post('car_num', '');//车系
        $volume_id = Yii::$app->request->post('volume_id', '');//车型
        $cardtime = Yii::$app->request->post('cardtime', '');//YY-mm-dd
        $city = Yii::$app->request->post('city', '');
        $mileage_pic1 = Yii::$app->request->post('mileage_pic1', '');//里程1
        $mileage_pic2 = Yii::$app->request->post('mileage_pic2', '');//里程2
        $miles = Yii::$app->request->post('miles', '');
        $facade = Yii::$app->request->post('facade', '');//外观数组
        $upholstery = Yii::$app->request->post('upholstery', '');//内饰数组

        $year = date('Y', time()) - substr($cardtime, 0, 4);
        $month = date('m', time()) - substr($cardtime, 5, 2);
        $months = $year * 12 + $month;
        $price = CCar::find()->where(['c_code' => $volume_id])->one();
        if (empty($price->c_price)) {
            return $this->showResult(304, '参数错误');
        } else {
            $prices = $price->c_price * (0.7 - 0.0051 * $months - 0.0015 * $miles/10000);
            $fen = $price->c_price * 0.05;
            $prices2 = $price->c_price * (0.9 - 0.0051 * $months - 0.0015 * $miles/10000);
            $fen2 = $price->c_price * 0.1;
            $r_price = $fen > $prices ? $fen : $prices;
            $r_price2 = $fen2 > $prices2 ? $fen2 : $prices2;
            $r_price_z = round(($r_price + $r_price2) / 2 / 10000 , 2)*10000;
            $data=[
                'price'=>(double)round($r_price_z/10000,2)
            ];
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $carreplace = new CCarreplace();
            $carreplace->r_accept_id = $user_id;
            $carreplace->r_brand = $brand;
            $carreplace->r_car_id = $car_num;
            $carreplace->r_volume_id = $volume_id;
            $carreplace->r_cardtime = $cardtime;
            $carreplace->r_city = $city;
            $carreplace->r_miles = $miles;
            $carreplace->r_driving_pic1 = $driving_pic1;
            $carreplace->r_driving_pic2 = $driving_pic2;
            $carreplace->r_mileage_pic1 = $mileage_pic1;
            $carreplace->r_mileage_pic2 = $mileage_pic2;
            $carreplace->r_price = $r_price_z;
            $carreplace->created_time = date('Y-m-d H:i:s', time());

            if (!$carreplace->save()) {
                throw  new \Exception;
            }
            $upholstery = json_decode($upholstery);
            foreach ($upholstery as $v) {
                $upholsterypic1 = new CCarreplacePics();
                $upholsterypic1->rp_r_id = $carreplace->r_id;
                $upholsterypic1->rp_pics = $v;
                $upholsterypic1->rp_type = 2;
                $upholsterypic1->created_time = date('Y-m-d H:i:s', time());
                if (!$upholsterypic1->save()) {
                    throw  new \Exception;
                }
            }
            $facade = json_decode($facade);
            foreach ($facade as $v) {
                $facadepic1 = new CCarreplacePics();
                $facadepic1->rp_r_id = $carreplace->r_id;
                $facadepic1->rp_pics = $v;
                $facadepic1->created_time = date('Y-m-d H:i:s', time());
                if (!$facadepic1->save()) {
                    throw  new \Exception;
                }
            }
            $userModel->u_cars = ($userModel->u_cars + 1);
            if (!$userModel->save()) {
                throw  new \Exception;
            }
            $transaction->commit();

            return $this->showResult(200, '成功',$data);
        } catch (\Exception $e) {

            $transaction->rollBack();
            return $this->showResult(400, '失败');
        }


    }

    //我的车辆
    public function actionMycars()
    {
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id' => $user_id]);
        if (empty($userModel)) {
            return $this->showResult(302, '获取用户信息失败');
        }

        $page = Yii::$app->request->post('page', 1);

        $car = CCarreplace::find()
            ->where(['r_accept_id' => $user_id, 'r_role' => 1, 'is_del' => 0,'is_forbidden'=>0])
            ->orderBy('r_id DESC')
            ->all();
        if (empty($car)) {
            return $this->showResArr(400, '暂未添加车辆');
        }
        $data = [];
        foreach ($car as $v) {
            $brand = CCar::find()->where(['c_code' => $v->r_brand])->one();
            if (empty($brand)) {
                $brand_title = '未知';
            } else {
                $brand_title = $brand->c_title;
            }
            $car_id = CCar::find()->where(['c_code' => $v->r_car_id])->one();
            if (empty($car_id)) {
                $car_title = '未知';
            } else {
                $car_title = $car_id->c_title;
            }
            $volume_id = CCar::find()->where(['c_code' => $v->r_volume_id])->one();
            if (empty($volume_id)) {
                $volume_title = '未知';
            } else {
                $volume_title = $volume_id->c_title;
            }
            $pic = CCarreplacePics::find()->where(['rp_type' => 1, 'rp_r_id' => $v->r_id, 'is_del' => 0])->one();
            if (empty($pic)) {
                $pics = '';
            } else {
                $pics = $pic->rp_pics;
            }


            $data[] = [
                'id' => $v->r_id,
                'pic' => $pics,
                'brand' => $brand_title,
                'volume' => $volume_title,
                'car_id' => $car_title,
                'time' => substr(($v->created_time), 0, 10),
                'price' => ($v->r_price / 10000) . '万',
                'state' => $v->r_state
            ];

        }
        if (empty($data)) {
            return $this->showResArr(400, '暂未添加车辆');
        } else {
            return $this->showListArr(200, '获取成功', count($data), array_slice($data, ($page - 1) * static::PAGE_SIZE, static::PAGE_SIZE));
        }
    }


    //删除我的车辆
    public function actionDelcar()
    {
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id' => $user_id]);
        if (empty($userModel)) {
            return $this->showResult(302, '获取用户信息失败');
        }

        $car_id = Yii::$app->request->post('id', '');
        if (empty($car_id)) {
            return $this->showResult(301, '传参错误');
        }
        $car = CCarreplace::find()->where(['r_id' => $car_id, 'is_del' => 0])->one();
//        var_dump($car->is_del);
//        exit;
        if (empty($car)) {
            return $this->showResult(303, '查无此车');
        }
        $car->is_del = 1;
        if($car->save()){
            $cars = CCarreplace::find()
                ->where(['r_accept_id'=>$user_id,'is_del'=>0,'r_role'=>1,'is_forbidden'=>0])
                ->andWhere(['!=','r_state',2])
                ->count();
            $userModel->u_cars = $cars;
            if ($userModel->save()) {
                $data = [
                    'count' => $userModel->u_cars,
                ];
                return $this->showResult(200, '删除成功', $data);
        }else{
                return $this->showResult(400, '删除失败');
            }

        } else {
            return $this->showResult(400, '删除失败');
        }
    }

    //查汽车品牌 车型 车系列表

    public function actionCarlist()
    {
        $level = Yii::$app->request->post('level', '');
        if (empty($level)) {
            return $this->showResult(302, '传参错误');
        }
        $parent = Yii::$app->request->post('code', '');
        if ($parent === '') {
            return $this->showResult(303, '传参错误');
        }

        $car = CCar::find()->where(['c_parent' => $parent, 'c_level' => $level])->all();
        if (empty($car)) {
            return $this->showResult(400, '暂无更多信息');
        };
        $url = 'http://101.201.78.217:8181/qczh/photo/brand/';
        $data = '';
        foreach ($car as $v) {
            $data[] = [
                'code' => $v->c_code,
                'title' => $v->c_title,
                'logo' => $v->c_logo == '' ? '' : $url . $v->c_logo
            ];
        }
        if (!empty($data)) {
            return $this->showResult(200, '获取成功', $data);
        } else {
            return $this->showResult(400, '暂无更多信息');
        }
    }

    //查找  品牌 车型  车系
    public function actionCarcontent()
    {
        $code = Yii::$app->request->post('code', '');
        if (empty($code)) {
            return $this->showResult(302, '传参错误');
        }
        $car = CCar::find()->where(['c_code' => $code])->one();
        if (empty($car)) {
            return $this->showResult(400, '暂无信息');
        }
        $data = [];
        $url = 'http://101.201.78.217:8181/qczh/photo/brand/';

        $data = [
            'code' => $car->c_code,
            'title' => $car->c_title,
            'logo' => $car->c_logo == '' ? '' : $url . $car->c_logo
        ];
        if (empty($data)) {
            return $this->showResult(400, '暂无信息');
        } else {
            return $this->showResult(200, '获取成功', $data);
        }

    }


    //修改车辆信息
    public function actionAltercar()
    {
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id' => $user_id]);
        if (empty($userModel)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $car_id = Yii::$app->request->post('car_id', '');//汽车ID
        if (empty($car_id)) {
            return $this->showResult(303, '缺少参数');
        }
        $carreplace = CCarreplace::find()->where(['r_id' => $car_id])->one();
        $driving_pic1 = Yii::$app->request->post('driving_pic1', $carreplace->r_driving_pic1);//行驶证1
        $driving_pic2 = Yii::$app->request->post('driving_pic2', $carreplace->r_driving_pic2);//行驶证2
        $brand = Yii::$app->request->post('brand', $carreplace->r_brand);//品牌
        $car_num = Yii::$app->request->post('car_num', $carreplace->r_car_id);//车系
        $volume_id = Yii::$app->request->post('volume_id', $carreplace->r_volume_id);//车型
        $cardtime = Yii::$app->request->post('cardtime', $carreplace->r_cardtime);//YY-mm-dd
        $city = Yii::$app->request->post('city', $carreplace->r_city);
        $mileage_pic1 = Yii::$app->request->post('mileage_pic1', $carreplace->r_mileage_pic1);//里程1
        $mileage_pic2 = Yii::$app->request->post('mileage_pic2', $carreplace->r_mileage_pic2);//里程2
        $miles = Yii::$app->request->post('miles', $carreplace->r_miles);
        $facade = Yii::$app->request->post('facade', '');//外观数组
        $upholstery = Yii::$app->request->post('upholstery', '');//内饰数组

        $year = date('Y', time()) - substr($cardtime, 0, 4);
        $month = date('m', time()) - substr($cardtime, 5, 2);
        $months = $year * 12 + $month;
        $price = CCar::find()->where(['c_code' => $volume_id])->one();
        if (empty($price->c_price)) {
            return $this->showResult(304, '参数错误');
        } else {
            $prices = $price->c_price * (0.7 - 0.0051 * $months - 0.0015 * $miles/10000);
            $fen = $price->c_price * 0.05;
            $prices2 = $price->c_price * (0.9 - 0.0051 * $months - 0.0015 * $miles/10000);
            $fen2 = $price->c_price * 0.1;
            $r_price = $fen > $prices ? $fen : $prices;
            $r_price2 = $fen2 > $prices2 ? $fen2 : $prices2;
            $r_price_z = round(($r_price + $r_price2) / 2 / 10000 , 2)*10000;
            $data=[
                'price'=>(double)round($r_price_z/10000,2)
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $carreplace->r_accept_id = $user_id;
            $carreplace->r_brand = $brand;
            $carreplace->r_car_id = $car_num;
            $carreplace->r_volume_id = $volume_id;
            $carreplace->r_cardtime = $cardtime;
            $carreplace->r_city = $city;
            $carreplace->r_miles = $miles;
            $carreplace->r_driving_pic1 = $driving_pic1;
            $carreplace->r_driving_pic2 = $driving_pic2;
            $carreplace->r_mileage_pic1 = $mileage_pic1;
            $carreplace->r_mileage_pic2 = $mileage_pic2;
            $carreplace->r_price =$r_price_z;
            $carreplace->r_state = 0; //更新状态
            $carreplace->created_time = date('Y-m-d H:i:s');

            if (!$carreplace->save()) {
//                var_dump($carreplace->getErrors());
//                exit;
                throw  new \Exception;
            }
            if (!empty($upholstery)) {
                $upcarpic1 = CCarreplacePics::find()->where(['rp_r_id' => $car_id, 'is_del' => 0, 'rp_type' => 2])->all();
                if (!empty($upcarpic1)) {
                    foreach ($upcarpic1 as $j) {
                        $j->is_del = 1;
                        if (!$j->save()) {
                            throw  new \Exception;
                        }
                    }
                }
                $upholstery = json_decode($upholstery);
                foreach ($upholstery as $v) {
                    $upholsterypic1 = new CCarreplacePics();
                    $upholsterypic1->rp_r_id = $carreplace->r_id;
                    $upholsterypic1->rp_pics = $v;
                    $upholsterypic1->rp_type = 2;
                    $upholsterypic1->created_time = date('Y-m-d H:i:s', time());
                    if (!$upholsterypic1->save()) {
                        throw  new \Exception;
                    }
                }
            }
            if (!empty($facade)) {
                $upcarpic2 = CCarreplacePics::find()->where(['rp_r_id' => $car_id, 'is_del' => 0, 'rp_type' => 1])->all();
                if (!empty($upcarpic2)) {
                    foreach ($upcarpic2 as $k) {
                        $k->is_del = 1;
                        if (!$k->save()) {
                            throw  new \Exception;
                        }
                    }
                }
                $facade = json_decode($facade);
                foreach ($facade as $v) {
                    $facadepic1 = new CCarreplacePics();
                    $facadepic1->rp_r_id = $carreplace->r_id;
                    $facadepic1->rp_pics = $v;
                    $facadepic1->created_time = date('Y-m-d H:i:s', time());
                    if (!$facadepic1->save()) {
                        throw  new \Exception;
                    }
                }
            }
            $transaction->commit();
            return $this->showResult(200, '成功',$data);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->showResult(400, '失败');
        }
    }

    //我的车辆详情
    public function actionCardetails()
    {
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id' => $user_id]);
        if (empty($userModel)) {
            return $this->showResult(302, '获取用户信息失败');
        }

        $car_id = Yii::$app->request->post('car_id', '');//汽车ID
        if (empty($car_id)) {
            return $this->showResult(303, '缺少参数');
        }

        $car = CCarreplace::find()->where(['r_id' => $car_id])->one();
        if (empty($car)) {
            return $this->showResult(400, '暂无信息');
        }
        $car_brand = CCar::find()->where(['c_code' => $car->r_brand])->one();

        if (!empty($car_brand)) {
            $brand = $car_brand->c_title;
        } else {
            $brand = '未知';
        }
        $car_car_id = CCar::find()->where(['c_code' => $car->r_car_id])->one();

        if (!empty($car_car_id)) {
            $car_series = $car_car_id->c_title;
        } else {
            $car_series = '未知';
        }
        $car_volume = CCar::find()->where(['c_code' => $car->r_volume_id])->one();
        if (!empty($car_volume)) {
            $car_model = $car_volume->c_title;
        } else {
            $car_model = '未知';
        }
        $city = CCity::find()->where(['code' => $car->r_city])->one();

        if ($city->parent == 0) {
            $city_name = $city->name;
        } else {
            $province = CCity::find()->where(['code' => $city->parent])->one();
            $city_name = $province->name . '-' . $city->name;
        }
        $data['details'] = [
            'id' => $car->r_id,
            'brand_code' => $car->r_brand,
            'series_code' => $car->r_car_id,
            'volume_code' => $car->r_volume_id,
            'brand' => $brand,
            'series' => $car_series,
            'volume' => $car_model,
            'cardtime' => $car->r_cardtime,
            'driving_pic1' => $car->r_driving_pic1,
            'driving_pic2' => $car->r_driving_pic2,
            'mileage_pic1' => $car->r_mileage_pic1,
            'mileage_pic2' => $car->r_mileage_pic2,
            'miles' => $car->r_miles,
            'city_id'=>$car->r_city,
            'city' => $city_name,
        ];
        $res_inside=[];
        $res_facade=[];
        $car_facade_pic = CCarreplacePics::find()->where(['rp_r_id' => $car_id, 'rp_type' => 1,'is_del'=>0])->all();
        foreach ($car_facade_pic as $v) {
            $res_facade[] = [
                'pics' => $v->rp_pics,
            ];
        }
        $data['facade'] = $res_facade;
        $car_inside_pic = CCarreplacePics::find()->where(['rp_r_id' => $car_id, 'rp_type' => 2,'is_del'=>0])->all();
        foreach ($car_inside_pic as $v) {
            $res_inside[] = [
                'pics' => $v->rp_pics,
            ];
        }
        $data['inside'] = $res_inside;

        if (empty($data)) {
            return $this->showResult(400, '暂无信息');
        } else {
            return $this->showResult(200, '获取成功', $data);
        }
    }
}
