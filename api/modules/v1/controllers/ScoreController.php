<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/31
 * Time: 15:31
 */

namespace v1\controllers;


use v1\models\CGoods;
use v1\models\CScoreList;
use v1\models\CScoreLog;
use v1\models\CUser;

use Yii;
use common\helpers\StringHelper;
use yii\web\UploadedFile;


class ScoreController extends ApiController
    {
    const PAGE_SIZE=10;

    protected function validatePassword($pass1,$pass2){
        return $pass1 == $pass2;
    }

    public function actionIndex(){
    }
    
//积分兑换商品列表
   public function actionGoodslist(){
//       $user_id = Yii::$app->user->identity->getId();
//       if (empty($user_id)) {
//           return $this->showResult(302, '获取用户信息失败');
//       }
//       $userModel = CUser::findOne(['u_id'=>$user_id]);
//       if(empty($userModel)){
//           return $this->showResult(302, '获取用户信息失败');
//       }
       $page = Yii::$app->request->post('page', 1);
       $goods = CGoods::find()->where(['and','g_state = 1','is_del = 0','g_amount > 0'])->all();
       if(empty($goods)){
           return $this->showResArr(400, '暂无信息');
       }
//       $url = 'http://101.201.78.217:8181/qczh';
    $data=[];
       foreach($goods as $v){
           $data[]=[
               'id'=>$v->g_id,
               'pic'=>Yii::$app->params['pic_url'].$v->g_pic,
               'name'=>$v->g_name,
               'score'=>$v->g_score,
               'instroduce'=>$v->g_instroduce
           ];
       }
       if (empty($data)) {
           return $this->showResArr(400, '暂无信息');
       } else {
           return $this->showListArr(200,'获取成功',count($data),array_slice($data,($page-1)*static::PAGE_SIZE,static::PAGE_SIZE));
       }
   }
    
    //兑换列表
    public function actionExchangelist()
    {
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id'=>$user_id]);
        if(empty($userModel)){
            return $this->showResult(302, '获取用户信息失败');
        }
        $page = Yii::$app->request->post('page', 1);
        $list=CScoreLog::find()->where(['and','sl_user_id ='.$user_id,'is_del = 0'])->orderBy('created_time DESC')->all();
        if(empty($list)){
            return $this->showResArr(400, '暂无兑换记录');
        }

        $data=[];
        foreach($list as $v){
            $goods=CGoods::find()->where(['g_id'=>$v->sl_good_id])->one();
            $data[]=[
                'id'=>$v->sl_id,
                'pic'=>Yii::$app->params['pic_url'].$goods->g_pic,
                'receive_name'=>$v->sl_receivename,
                'receive_phone'=>$v->sl_receivephone,
                'receive_address'=>$v->sl_receiveaddress,
                'sl_state'=>$v->sl_state == 0?"待发货":"已发货"
            ];
        }

        if (empty($data)) {
            return $this->showResArr(400, '暂无兑换记录');
        } else {
            return $this->showListArr(200,'获取成功',count($data),array_slice($data,($page-1)*static::PAGE_SIZE,static::PAGE_SIZE));
        }
    }

    //积分页面
    public function actionScorelist(){
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id'=>$user_id]);
        if(empty($userModel)){
            return $this->showResult(302, '获取用户信息失败');
        }

        $scorelist =CScoreList::find()->where(['sl_user_id'=>$user_id])->orderBy('created_time DESC')->all();
        $page = Yii::$app->request->post('page', 1);
        $data=[];
        $data['score'] = $userModel->u_score;
        if(empty($scorelist)){
            $res = [];
        }else{
        foreach ($scorelist as $v){
            $res[]=[
                'time'=>$v->created_time,
                'sl_score'=>($v->sl_act == 'add'?'+':'-').$v->sl_score,
                'rule'=>$v->sl_rule,
            ];
        }
        }
        $data['res'] = array_slice($res,($page-1)*static::PAGE_SIZE,static::PAGE_SIZE);
        if (empty($data)) {
            return $this->showResult(400, '暂无信息');
        } else {
//            return $this->showResult(200,'获取成功',$data);
            return $this->showListArr(200,'获取成功',count($data['res']),$data);
        }
    }

    //兑换商品
    public function actionExchange(){
        $user_id = Yii::$app->user->identity->getId();
        if (empty($user_id)) {
            return $this->showResult(302, '获取用户信息失败');
        }
        $userModel = CUser::findOne(['u_id'=>$user_id]);
        if(empty($userModel)){
            return $this->showResult(302, '获取用户信息失败');
        }
        $goods_id = Yii::$app->request->post('goods_id', '');
        $address=Yii::$app->request->post('address', '');
        $name=Yii::$app->request->post('name', '');
        $tel=Yii::$app->request->post('tel', '');

        if(empty($goods_id)){
            return $this->showResult(301, '缺少参数');
        }

        if(empty($address) || empty($name) || empty($tel) ){
            return $this->showResult(303, '请把信息填写完整');
        }

        $goods = CGoods::find()->where(['g_id'=>$goods_id])->one();
        if(empty($goods)){
            return $this->showResult(304, '无此商品');
        }
        if($userModel->u_score < $goods->g_score){
            return $this->showResult(305, '积分不足');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $goods->g_sellout = $goods->g_sellout + 1;
            $goods->g_amount =$goods->g_amount -1;
            if (!$goods->save()) {
                throw  new \Exception;
            }
            $score_log = new CScoreLog();
            $score_log->sl_user_id = $user_id;
            $score_log->sl_good_id = $goods_id;
            $score_log->sl_goodsname = $goods->g_name;
            $score_log->sl_score = $goods->g_score;
            $score_log->sl_receivename = $name;
            $score_log->sl_receivephone = $tel;
            $score_log->sl_receiveaddress = $address;
            $score_log->created_time = date('Y-m-d H:i:s',time());
            if (!$score_log->save()) {
                throw  new \Exception;
            }
            //【发帖】用车报告
            $score_list = new CScoreList();
            $score_list->sl_log_id = $score_log->sl_id;
            $score_list->sl_user_id = $user_id;
            $score_list->sl_rule = '【兑换】'.$goods->g_name;
            $score_list->sl_score = $goods->g_score;
            $score_list->sl_act = 'sub';
            $score_list->created_time = date('Y-m-d H:i:s',time());
            if (!$score_list->save()) {
                throw  new \Exception;
            }
          $userModel->u_score = $userModel->u_score - $goods->g_score;
                if (!$userModel->save()) {
                    throw  new \Exception;
                }
            $data=[
                'score'=>$userModel->u_score,
            ];
            $transaction->commit();
            return $this->showResult(200, '获取成功',$data);
        } catch (\Exception $e) {
            $transaction->rollBack();

            return $this->showResult(400, '失败');
        }
    }
}

