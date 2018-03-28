<?php

namespace admin\controllers;

use admin\models\CCarreplace;
use admin\models\CCity;
use admin\models\COrderremarks;
use common\helpers\ArrayHelper;
use Yii;
use admin\models\COrders;
use admin\models\OrdersSearch;
use admin\models\CCarreplacePics;
use yii\base\Exception;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrdersController implements the CRUD actions for COrders model.
 */
class OrdersController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     *“预约中” 列表
     */
    public function actionIndexOrdering()
    {
        $searchModel = new OrdersSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), 0);

        return $this->render('index-ordering', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     *“预约完成” 列表
     */
    public function actionIndexOrdered()
    {
        $searchModel = new OrdersSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), 1);

        return $this->render('index-ordered', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     *“预约取消” 列表
     */
    public function actionIndexOrderDismiss()
    {
        $searchModel = new OrdersSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), 2);

        return $this->render('index-order-dismiss', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single COrders model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $inpics = array();
        $outpics = array();

        if ($model->carreplace) {
            //获取置换车辆外观照片
            $outpics = CCarreplacePics::GetCarPics($model->carreplace->r_id, 1);
            //获取置换车辆内饰照片
            $inpics = CCarreplacePics::GetCarPics($model->carreplace->r_id, 2);
        }

        return $this->render('view', ['model' => $model, 'outpics' => $outpics, 'inpics' => $inpics]);

    }

    /**
     * Creates a new COrders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new COrders;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->o_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 只有“预约中”，才会有此处理
     */
    public function actionUpdate($id)
    {
        $admin = Yii::$app->user->identity;
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $adposted = Yii::$app->request->post('COrders');
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $model->attributes = [
                    'o_state' => $adposted['o_state'],
                ];
                if ($adposted['o_remark']) {
                    //添加备注
                    $remark = new COrderremarks();
                    $remark->attributes = [
                        'or_order_id' => $id,
                        'or_author' => $admin->a_name,
                        'or_content' => $adposted['o_remark'],
                        'created_time' => date('Y-m-d H:i:s'),
                    ];
                    if (!$remark->save()) {
                        throw new Exception;
                    }
                }
                //修改此置换车辆的状态
                $carmodel = CCarreplace::find()->where(['r_id' => $model->o_usercar_id])->one();
                $carmodel->r_state = 3; //3:已置换


                if (!$model->save() || !$carmodel->save()) {
                    throw new Exception;
                }

                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success', '<i class="glyphicon glyphicon-ok"></i>操作成功');
            } catch (Exception $e) {
                $transaction->rollBack();

                Yii::$app->getSession()->setFlash('error', '<i class="glyphicon glyphicon-remove"></i>操作失败');
            }
            return $this->redirect(['index-ordering']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'multiids' => 0
            ]);
        }
    }

    /*
     * 批量处理
     */
    public function actionAjaxUpdateAll($multi)
    {

        // $multiids=Yii::$app->request->get('multi'); //批量处理的订单id,获取到的是数组形式
        $model = new COrders;

        $admin = Yii::$app->user->identity;
        if (Yii::$app->request->post()) {
            $adposted = Yii::$app->request->post('COrders');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //批量修改预约单
                $count = Yii::$app->db->createCommand('update c_orders set o_state=' . $adposted['o_state'] . ' where o_id in (' . $adposted['o_multi'] . ')')->execute();
                if ($count < 0) {
                    var_dump('fail');
                    exit;
                }
                if ($adposted['o_remark']) {
                    $data = array();

                    if ($multi) {
                        $data_arr = explode(',', $multi);
                        foreach ($data_arr as $v) {
                            $data[] = [
                                'or_order_id' => $v,
                                'or_author' => $admin->a_name,
                                'or_content' => $adposted['o_remark'],
                                'created_time' => date('Y-m-d H:i:s'),
                            ];
                        }
                    }

                    if ($data) {
                        //批量插入备注
                        Yii::$app->db->createCommand()->batchInsert('c_orderremarks', [
                                'or_order_id', 'or_author', 'or_content', 'created_time']
                            , $data)->execute();
                    }
                }

                //更新用户的车辆状态
                $sql = "UPDATE c_carreplace SET r_state=3 WHERE r_id IN (SELECT o_usercar_id FROM c_orders WHERE o_id IN ($multi))";
                Yii::$app->db->createCommand($sql)->execute();

                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success', '<i class="glyphicon glyphicon-ok"></i>批量操作成功');
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', '<i class="glyphicon glyphicon-remove"></i>批量操作失败');
            }
            return $this->redirect(['index-ordering']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'multiids' => $multi
            ]);
        }
    }

    /**
     * Deletes an existing COrders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the COrders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return COrders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = COrders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
     * 查找符合条件的市
     */
    public function actionChildCity()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = CCity::find()->where(['level' => 2, 'status' => 1])->andWhere(['parent' => $id])->asArray()->all(); //市

            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $city) {
                    $out[] = ['id' => $city['code'], 'name' => $city['name']];
                    if ($i == 0) {
                        $selected = $city['code'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);

    }

    public function actionGetSelectedCity() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = CCity::find()->where(['level' => 2, 'status' => 1])->andWhere(['parent' => $id])->asArray()->all(); //市
            return json_encode(ArrayHelper::getColumn($list, function ($element) {
                return [
                    'id' => $element['code'],
                    'text' => $element['name'],
                ];
            }));
        }
    }
}
