<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/31
 * Time: 15:31
 */

namespace v1\controllers;


use common\helpers\ArrayHelper;
use v1\models\CAgent;
use v1\models\CCar;
use v1\models\CCarreplace;
use v1\models\CCarreplacePics;
use v1\models\CCity;
use v1\models\COrders;
use v1\models\CProducts;
use v1\models\CUser;

use Yii;
use common\helpers\StringHelper;
use yii\web\UploadedFile;


class RecommendController extends ApiController
{
    const PAGE_SIZE = 10;

    protected function validatePassword($pass1, $pass2)
    {
        return $pass1 == $pass2;
    }

    public function actionIndex()
    {

    }

    //推荐列表
    public function actionRecommendlist()
    {
        $token = Yii::$app->request->post('token', '');
        if (empty($token)) {
            $mycar = '';
        } else {

            $userModel = CUser::findOne(['u_token' => $token]);
            if (empty($userModel)) {
                return $this->showResult(401, '获取用户信息失败');
            } else {
                $user_id = $userModel->u_id;
                /*查找用户发布的审核成功的且未被删除或禁用的车辆*/
                $mycar = CCarreplace::find()->where(['r_accept_id' => $user_id, 'r_role' => $userModel->u_type, 'is_del' => 0,'r_state'=>1,'is_forbidden'=>0])->orderBy('r_id DESC')->one();
            }
        }
        $page = Yii::$app->request->post('page', 1);
        $bank_code = Yii::$app->request->post('bank_code', '');
        $price = Yii::$app->request->post('price', '');
        $text = [];
        $logo = [];
        $data = [];
        if (empty($mycar)) {
            $car_price = 0;
            $car = CCarreplace::find()->where(['and', 'is_del = 0','is_forbidden = 0','r_state = 1', empty($bank_code) ? '' : 'r_brand =' . $bank_code, empty($price) ? '' : 'r_price <=' . $price * 10000])->orderBy(empty($price) ? 'r_views DESC' : 'r_price DESC')->all();
            if (empty($car)) {
                return $this->showResult(400, '暂未添加车辆');
            }
            foreach ($car as $v) {
                $brand = CCar::find()->where(['c_code' => $v->r_brand])->one();
                if (empty($brand)) {
                    $brand_title = '未知';
                    $brand_pic = '';
                } else {
                    $brand_title = $brand->c_title;
                    $brand_pic = Yii::$app->params['img_path'] . '/brand/' . $brand->c_logo;
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
                $text[] = [
                    'id' => $v->r_id,
                    'is_new' => $v->r_role,
                    'pic' => $pics,
                    'brand' => $brand_title,
                    'volume' => $volume_title,
                    'car_id' => $car_title,
                    'time' => substr(($v->created_time), 0, 10),
                    'price' => '+' . (($v->r_price) - $car_price) / 10000 . '万'
                ];
                $arr = ArrayHelper::getColumn($logo, 'brand_code');
                if (!in_array($brand->c_code, $arr)) {
                    $logo[] = [
                        'brand_code' => $brand->c_code,
                        'brand_name' => $brand_title,
                        'logo' => $brand_pic,
                    ];
                }
            }
            $text_count = count($text);
            $text_arr=array_slice($text, ($page - 1) * static::PAGE_SIZE, static::PAGE_SIZE);
            $data['text'] = $text_arr;
            $data['logo'] = $logo;
            if (empty($data)) {
                return $this->showResArr(400, '暂无车辆');
            } else {
                return $this->showListArr(200, '获取成功', $text_count, $data);
            }

        } else {
            $car_price = $mycar->r_price;
            //符合要求的推荐车辆
            if (empty($price)) {
                $cars = CCarreplace::find()->where(['or', ['and', empty($bank_code) ? '' : 'r_brand =' . $bank_code, 'r_price >' . $car_price, 'r_price <=' . ($car_price * 3), 'r_role = 1', 'is_del = 0','r_state = 1','is_forbidden=0'], ['and', empty($bank_code) ? '' : 'r_brand =' . $bank_code, 'r_price >' . ($car_price * 1.5), 'r_price <=' . ($car_price * 3), 'r_role = 2', 'is_del = 0','is_forbidden=0','r_state = 1']])->orderBy('r_price DESC')->all();
            } else {
                $total_price = $price*10000 + $car_price;
                $cars = CCarreplace::find()->where(['and', empty($bank_code) ? '' : 'r_brand =' . $bank_code, 'r_price >' . $car_price, 'r_price <=' . $total_price,'is_del = 0','is_forbidden = 0','r_state = 1'])->orderBy('r_price DESC')->all();

            }

            $data = '';
            if (empty($cars)) {
                return $this->showResult(400, '暂未无符合条件车辆');
            } else {
                foreach ($cars as $v) {
                    $brand = CCar::find()->where(['c_code' => $v->r_brand])->one();
                    if (empty($brand)) {
                        $brand_title = '未知';
                        $brand_pic = '';
                    } else {
                        $brand_title = $brand->c_title;
                        $brand_pic = Yii::$app->params['img_path'] . '/brand/' . $brand->c_logo;
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
                    $text[] = [
                        'id' => $v->r_id,
                        'is_new' => $v->r_role,
                        'pic' => $pics,
                        'brand' => $brand_title,
                        'volume' => $volume_title,
                        'car_id' => $car_title,
                        'price' => '+' . (($v->r_price) - $car_price) / 10000 . '万'
                    ];
                    $arr = ArrayHelper::getColumn($logo, 'brand_code');
                    if (!in_array($brand->c_code, $arr)) {
                        $logo[] = [
                            'brand_code' => $brand->c_code,
                            'brand_name' => $brand_title,
                            'logo' => $brand_pic,
                        ];
                    }
                }
                $text_count = count($text);
                $text_arr = array_slice($text, ($page - 1) * static::PAGE_SIZE, static::PAGE_SIZE);
                $data['text'] = $text_arr;
                $data['logo'] = $logo;
                if (empty($data)) {
                    return $this->showResArr(400, '暂无车辆');
                } else {
                    return $this->showListArr(200, '获取成功', $text_count, $data);
                }
            }
        }
    }

    //推荐车辆详情
    public function actionRecommenddetails(){
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id' => $user_id]);
        if (empty($userModel)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $data =[];
        $permute_car = Yii::$app->request->post('permute_car_id', '');//对方车辆ID
        $car_type = Yii::$app->request->post('car_type', '');//新车二手车
        $price_difference= Yii::$app->request->post('price_difference', '');//差价
        if( empty($permute_car)){
            return $this->showResult(306, '缺少参数');
        }
        if(empty($car_type)){
            return $this->showResult(307, '缺少参数');
        }
        if(empty($price_difference)){
            return $this->showResult(308, '缺少参数');
        }

        $stores_address=[];
        $new_car = CCarreplace::find()->where(['r_id'=>$permute_car,'is_del'=>0,'r_state'=>1,'is_forbidden'=>0])->one();
        if(empty($new_car)){
            return $this->showResult(303, '查无此车');
        }
        $phone='';
        if($car_type == 1){
            $user = CUser::find()->where(['u_id'=>$new_car->r_accept_id])->one();
            if(empty($user)){
                return $this->showResult(305, '无此用户');
            }else{
                $phone = $user->u_phone;
            }

        }
        if($car_type == 2){
            $all_car = CCarreplace::find()->where(['r_volume_id'=>$new_car->r_volume_id,'r_role'=>2,'is_forbidden'=>0,'is_del'=>0,'r_state'=>1,])->all();
            foreach ($all_car as $v){
                $stores = CAgent::find()->where(['a_id'=>$v->r_accept_id])->one();
                if(empty($stores)) {
                    continue;
                }
                $stores_address[]=[
                    'id_4s'=>$stores->a_id,
                    'name'=>$stores->a_name,
                    'address'=>$stores->a_address,
                ];
            }
        }
        $data['stores_address']=$stores_address;
        $car = CCar::find()->where(['c_code'=>$new_car->r_volume_id])->one();
        $products = CProducts::find()->where(['p_sortorder'=>1,'is_del'=>0])->one();
        if(empty($products)){
            return $this->showResult(304, '利率有误');
        }
    //计算利息
        $month_12 = ceil($price_difference * 10000 * ($products->p_month12 / 100) + $price_difference * 10000/ 12);
        $day_12 = ceil($month_12 / 30);
        $month_24 = ceil($price_difference * 10000 * ($products->p_month24 / 100) + $price_difference * 10000 / 24);
        $day_24 = ceil($month_24 / 30);
        $month_36 = ceil($price_difference * 10000 * ($products->p_month36 /100) + $price_difference* 10000/ 36);
        $day_36 = ceil($month_36 / 30);

        $res_inside=[];
        $res_facade=[];
        $car_facade_pic = CCarreplacePics::find()->where(['rp_r_id' => $permute_car, 'rp_type' => 1,'is_del'=>0])->all();
        foreach ($car_facade_pic as $v) {
            $res_facade[] = [
                'pics' => $v->rp_pics,
            ];
        }
        $data['facade'] = $res_facade;
        $car_inside_pic = CCarreplacePics::find()->where(['rp_r_id' => $permute_car, 'rp_type' => 2,'is_del'=>0])->all();
        foreach ($car_inside_pic as $v) {
            $res_inside[] = [
                'pics' => $v->rp_pics,
            ];
        }
        $data['inside'] = $res_inside;

        $data['details']=[
            'price'=>$price_difference,//差价
            'guide_price'=>(($car->c_price) /10000).'万',//指导价
            'engine'=>$car->c_engine,//发动机
            'volume'=>$car->c_volume,//长宽高
            'day_12'=>$day_12,
            'month_12'=>$month_12,
            'day_24'=>$day_24,
            'month_24'=>$month_24,
            'day_36'=>$day_36,
            'month_36'=>$month_36,
            'persons'=>$new_car->r_persons,//置换人数
//            'permute_car_pic'=>$permute_car_pic,//对方车图片
            'phone'=>$phone//对方手机号
        ];

        if (empty($data)) {
            return $this->showResult(400, '暂无信息');
        } else {
            return $this->showResult(200, '获取成功', $data);
        }
    }

    //融云 根据电话获取 头像 昵称

    public function actionPhone(){
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id' => $user_id]);
        if (empty($userModel)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $phone= Yii::$app->request->post('phone', '');//手机号
        if( empty($phone)){
            return $this->showResult(306, '缺少参数');
        }

        $user = CUser::find()->where(['u_phone'=>$phone,'is_del'=>0,'u_state'=>1])->one();
        if(empty($user)){
            return $this->showResult(303, '无相关用户');
        }

        $data=[
            'head_pic'=>$user->u_headImg,
            'nickname'=>$user->u_nickname
        ];

        if (empty($data)) {
            return $this->showResult(400, '暂无信息');
        } else {
            return $this->showResult(200, '获取成功', $data);
        }
    }

    //预约置换
    public function actionExchange(){
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id' => $user_id]);
        if (empty($userModel)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $replace_id= Yii::$app->request->post('replace_id', '');//预约车辆ID
        $agency_id= Yii::$app->request->post('agency_id', '');//4S店ID
        $fee = Yii::$app->request->post('fee', '');//置换价

        $mycar = CCarreplace::find()->where(['r_accept_id' => $user_id, 'r_role' => $userModel->u_type, 'is_del' => 0,'r_state'=>1,'is_forbidden'=>0])->orderBy('r_id DESC')->one();
        if(empty($mycar)){
            return $this->showResult(303, '查无此车');
        }
        $usercar = $mycar->r_brand.'-'.$mycar->r_car_id.'-'.$mycar->r_volume_id;//我的车辆
        $hiscar = CCarreplace::find()->where(['r_id'=>$replace_id])->one();
        if(empty($hiscar)){
            return $this->showResult(303, '查无此车');
        }
        $ex_car = $hiscar->r_brand.'-'.$hiscar->r_car_id.'-'.$hiscar->r_volume_id;//我的车辆

        $o_code = date('YmdGis',time()).rand(100,999);
        $is_same = COrders::find()->where(['o_code'=>$o_code])->one();
        if(!empty($is_same)){
            return $this->showResult(304, '预约失败，请重新预约');
        }
        $order = new COrders();
        $order->o_code = $o_code;
        $order->o_user_id = $user_id;
        $order->o_usercar_id = $mycar->r_id;
        $order->o_usercar = $usercar;
        $order->o_replace_id = $replace_id;
        $order->o_replacecar = $ex_car;
        $order->o_agency_id = $agency_id;
        $order->o_fee = $fee*10000;
        $order->created_time=date('Y-m-d H:i:s',time());
        if($order->save()){
            return $this->showResult(200, '保存成功');
        } else {
            return $this->showResult(400, '保存失败');
        }
    }
}

