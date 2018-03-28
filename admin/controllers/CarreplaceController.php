<?php

namespace admin\controllers;

use admin\models\CarSearch;
use admin\models\CCar;
use admin\models\CCarreplacePics;
use admin\models\CMessage;
use Yii;
use admin\models\CCarreplace;
use admin\models\CarreplaceSearch;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CarreplaceController implements the CRUD actions for CCarreplace model.
 */
class CarreplaceController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post','get'],
                ],
            ],
        ];
    }

    /**
     * Lists all CCarreplace models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CarreplaceSearch;

        $search=Yii::$app->request->getQueryParams();

        //按条件查询
        if($search && (isset($search['CarreplaceSearch']))){
            $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        }else{
            //显示所有用户的数据列表
            $query1= CCarreplace::find()
                ->innerJoinWith(['user'])
                ->select("c_carreplace.*,r_accept_id AS accept_id ")
                ->Where(['c_carreplace.is_del'=>0])->andWhere(['c_carreplace.r_role'=>1]);

            $query2=CCarreplace::find()
                ->innerJoinWith(['agent'])
                ->select("c_carreplace.*,GROUP_CONCAT(r_accept_id) AS accept_id")
                ->Where(['c_carreplace.is_del'=>0])->andWhere(['c_carreplace.r_role'=>2])
                ->groupBy('c_carreplace.r_volume_id');


            $query=$query1->union($query2);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);
            $dataProvider->pagination=[
                'pageSize' => 10,
            ];
            $dataProvider->sort = [
                'defaultOrder' => ['created_time'=>SORT_DESC]
            ];
        }


        /*********************在gridview列表页面上直接修改数据 start*****************************************/
        //获取前面一部传过来的值
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey'); //获取需要编辑的数据id
            $model = $this->findModel($id);
            $out = Json::encode(['output'=>'', 'message'=>'']);
            //获取用户修改的参数（比如：角色）
            $posted = current($_POST['CCarreplace']); //输出数组中当前元素的值，默认初始指向插入到数组中的第一个元素。移动数组内部指针，使用next()和prev()

            $post = ['CCarreplace' => $posted];
            $output = '';
            if ($model->load($post)) { //赋值
                $model->is_forbidden=$posted['is_forbidden'];
                $model->save(); //save()方法会先调用validate()再执行insert()或者update()
                isset($posted['is_forbidden']) && $output=CCarreplace::GetCarreplaceForbiddenState($model->is_forbidden); //配送人员当前状态
            }
            $out = Json::encode(['output'=>$output, 'message'=>'']);
            echo $out;
            return;
        }
        /*******************在gridview列表页面上直接修改数据 end***********************************************/




        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 查看发布的车辆详情
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        //获取置换车辆外观照片
        $outpics=CCarreplacePics::GetCarPics($id,1);
        //获取置换车辆内饰照片
        $inpics=CCarreplacePics::GetCarPics($id,2);


        return $this->render('view', ['model' => $model,'outpics'=>$outpics,'inpics'=>$inpics]);

    }

    /**
     * 发布4S店新车型
     */
    public function actionCreate($id,$brand)
    {
        $model = new CCarreplace;

        if (Yii::$app->request->post()) {
            $adposted=Yii::$app->request->post('CCarreplace');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //根据车型编号获取其指导价
                $carmodel=CCar::find()->where(['c_level'=>3,'c_code'=>$adposted['r_volume_id']])->one();
                $price=empty($carmodel)?'-99999':$carmodel->c_price;

                $model->attributes=[
                    'r_accept_id' => $id,
                    'r_role' => 2, //2: 4s店
                    'r_brand' => $brand,
                    'r_car_id' => $adposted['r_car_id'],
                    'r_volume_id' => $adposted['r_volume_id'],
                    'r_city' =>'',
                    'r_miles' => '',
                    'r_price' => $price,
                    'r_state' =>1, //1: 置换中
                    'r_views' =>0,
                    'r_persons' =>0,
                    'created_time' =>date('Y-m-d H:i:s')
                ];
                if(!$model->save()){
                    throw new Exception;
                }

                //根据车型的编号获取其外观、内饰图片
                $url=Yii::$app->params['base_url'].Yii::$app->params['base_file'].'/photo/cars/';
                //根据车系编号获取其外观、内饰图片
                $cxmodel=CCar::find()->where(['c_level'=>2,'c_code'=>$adposted['r_car_id']])->one();
                $outimgs=$cxmodel->c_imgoutside;

                if(isset($outimgs)) {
                    //外观图片
                    if (substr($outimgs, -1) == ',') {
                        $outimgs = substr($outimgs, 0, -1);
                    }
                    $outpics=explode(',',str_replace(']','',str_replace('[','',$outimgs)));

                    //批量插入
                    $data=array();
                    foreach($outpics as $out){
                        $data[] = [
                            'rp_r_id' =>$model->r_id,
                            'rp_type' =>1,
                            'rp_pics' =>$url.$out,
                            'created_time' =>date('Y-m-d H:i:s'),
                        ];
                    }
                    Yii::$app->db->createCommand()->batchInsert('c_carreplace_pics', [
                            'rp_r_id', 'rp_type', 'rp_pics', 'created_time']
                        , $data)->execute();

                }

                $inimgs=$cxmodel->c_imginside;
                if(isset($inimgs)) {
                    //内饰图片
                    if (substr($inimgs, -1) == ',') {
                        $inimgs = substr($inimgs, 0, -1);
                    }
                    $inpics=explode(',',str_replace(']','',str_replace('[','',$inimgs)));
                    //批量插入
                    $data=array();
                    foreach($inpics as $in){
                        $data[] = [
                            'rp_r_id' =>$model->r_id,
                            'rp_type' =>2,
                            'rp_pics' =>$url.$in,
                            'created_time' =>date('Y-m-d H:i:s'),
                        ];
                    }
                    Yii::$app->db->createCommand()->batchInsert('c_carreplace_pics', [
                            'rp_r_id', 'rp_type', 'rp_pics', 'created_time']
                        , $data)->execute();
                }


                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok text-success"></i>发布成功');
            }catch(Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove text-danger"></i>发布失败');
            }
            return $this->redirect(['agent/index']);
        } else {
            $model->r_brand=$brand;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     *审核状态
     */
    public function actionUpdate($id,$state)
    {
        $model = $this->findModel($id);

        if($model->r_state==0 && $model->r_role==1) { //待审核
            switch($state){
                case 'ok':
                    $model->r_state=1;  //置换中
                    break;
                case 'refuse':
                    $model->r_state=4;  //拒绝审核
                    break;
            }
            $model->save();

            $user = $model->user;
            if($user){
                $admin = Yii::$app->user->identity;
                $brand=CarSearch::getCarTitle($model->r_brand);
                $car=CarSearch::getCarTitle($model->r_car_id);
                $volume=CarSearch::getCarTitle($model->r_volume_id);

                $carname =  (empty($brand)?'':$brand).(empty($car)?'':'/'.$car).(empty($volume)?'':'/'.$volume);
                //新建消息
                $messagemodel = new CMessage();

                date_default_timezone_set('PRC');
                $messagemodel->attributes=[
                    'm_type' =>1,
                    'm_user_id' =>$user->u_id,
                    'm_author' =>$admin->a_name,
                    'm_content' =>$model->r_state==1?'您的 "'.$carname.'" 车辆审核通过了':($model->r_state==4?'您的 "'.$carname.'" 车辆审核未通过了':''),
                    'created_time' =>date('Y-m-d H:i:s'),
                ];
                $messagemodel->save();

                $user_registerid = empty($model->user)?'':$model->user->u_register_id;
                //极光推送
                PushMessageController::PushNotes(array($user_registerid),'您发布的车辆审核通过了','system_message');
            }

        }
        return $this->redirect('index');
    }

    /**
     * 禁用该车辆
     */
    public function actionUpdateForbidden($id)
    {
        $model=$this->findModel($id);
        if (Yii::$app->request->post()) {

            $transaction = Yii::$app->db->beginTransaction();
            try {

                if ($model->load(Yii::$app->request->post())) {

                    if($model->is_forbidden==0){
                        $model->r_forbidden_reason ='';
                    }

                    if( !$model->save()) {  //更新保存
                        throw new Exception();
                    }

                    if($model->is_forbidden==1){  //发送站内信
                        $user = $model->user;
                        if($user){
                            $admin = Yii::$app->user->identity;

                            $brand=CarSearch::getCarTitle($model->r_brand);
                            $car=CarSearch::getCarTitle($model->r_car_id);
                            $volume=CarSearch::getCarTitle($model->r_volume_id);

                            $carname =  (empty($brand)?'':$brand).(empty($car)?'':'/'.$car).(empty($volume)?'':'/'.$volume);
                            //新建消息
                            $messagemodel = new CMessage();
                            
                            date_default_timezone_set('PRC');
                            $messagemodel->attributes=[
                                'm_type' =>1,
                                'm_user_id' =>$user->u_id,
                                'm_author' =>$admin->a_name,
                                'm_content' =>'您的 "'.$carname.'" 车辆被管理员禁用了',
                                'created_time' =>date('Y-m-d H:i:s'),
                            ];
                            $messagemodel->save();
                        }
                    }
                }

                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>操作成功');
            }catch(Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>操作失败');
            }
//        $user_registerid = '';
//        if($model->r_role==1){  //用户
//            $user_registerid = empty($model->user)?'':$model->user->u_register_id;
//        }
//        //极光推送
//        PushMessageController::PushNotes(array($user_registerid),'您的车辆已被禁用','system_message');

            return $this->redirect('index');
        }else{
            return $this->render('_updatestate', [
                'model' => $model,
            ]);
        }

    }
    /**
     * Deletes an existing CCarreplace model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        if($model->r_state==1) { //置换中
            $model->r_state=2;
        }elseif($model->r_state==2) { //已下架
            $model->r_state=1;
        }
        $model->save();

        return $this->redirect(['agent/index']);
    }

    /**
     * Finds the CCarreplace model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CCarreplace the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CCarreplace::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /*
     * 查找符合条件的车型
     */
    public function actionChildCar(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = CCar::find()->where(['c_level'=>3])->andWhere(['c_parent'=>$id])->orderBy(['c_sortorder'=>SORT_ASC])->asArray()->all(); //市
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $car) {
                    $out[] = ['id' => $car['c_code'], 'name' => $car['c_title']];
                    if ($i == 0) {
                        $selected = $car['c_code'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }
}
