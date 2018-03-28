<?php

namespace admin\controllers;

use admin\models\ScoreLogSearch;
use Yii;
use admin\models\CGoods;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
/**
 * GoodsController implements the CRUD actions for CGoods model.
 */
class GoodsController extends Controller
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
     * Lists all CGoods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CGoods::find()->where(['is_del'=>0]),
        ]);
        $dataProvider->pagination=[
            'pageSize' => 15,
        ];
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CGoods model.
     * @param string $id
     * @return mixed
     */
    public function actionView()
    {
        $searchModel = new ScoreLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->g_id]);
//        } else {
//            return $this->render('view', ['model' => $model]);
//        }
    }

    /**
     * Creates a new CGoods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new CGoods;

        if (Yii::$app->request->post()) {
            $adposted=Yii::$app->request->post('CGoods');
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $model->attributes = [
                    'g_name'=>$adposted['g_name'],
                    'g_pic' =>$adposted['pic'],
                    'g_instroduce' =>$adposted['g_instroduce'],
                    'g_sellout' =>empty($adposted['g_sellout'])?0:$adposted['g_sellout'],
                    'g_amount' =>empty($adposted['g_amount'])?0:$adposted['g_amount'],
                    'g_score' =>empty($adposted['g_score'])?0:$adposted['g_score'],
                    'created_time' =>date('Y-m-d H:i:s')
                ];
                if (!$model->save()) {
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>添加成功');
            }catch(Exception $e) {
                $transaction->rollBack();
                //var_dump($e);
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>添加失败');
            }
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
                'initialPreview'=>'',
                'initialPreviewConfig'=>array()
            ]);
        }
    }

    /**
     * Updates an existing CGoods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $initialPreview='';$initialPreviewConfig=array();
        $model = $this->findModel($id);

        if($model){
           $initialPreview='<img src="'.Yii::$app->params['base_url'].Yii::$app->params['base_file'].'/'.$model->g_pic.'" width="100px">';
            $config = [
                'width' => '120px',
                'url' => Url::toRoute(['upload/delete-pic?type=goods&code='.$id],true), // server delete action
                'key' =>$model->g_pic,
            ];
            array_push($initialPreviewConfig, $config);
        }

        if (Yii::$app->request->post()) {
            $adposted=Yii::$app->request->post('CGoods');
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $model->attributes = [
                    'g_name'=>$adposted['g_name'],
                    'g_pic' =>$adposted['pic'],
                    'g_instroduce' =>$adposted['g_instroduce'],
                    'g_sellout' =>empty($adposted['g_sellout'])?0:$adposted['g_sellout'],
                    'g_amount' =>empty($adposted['g_amount'])?0:$adposted['g_amount'],
                    'g_score' =>empty($adposted['g_score'])?0:$adposted['g_score'],
                ];
                if (!$model->save()) {
                    var_dump($model->getErrors());exit;
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>修改成功');
            }catch(Exception $e) {
                $transaction->rollBack();
                var_dump($e);exit;
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>修改失败');
            }
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
                'initialPreview'=>$initialPreview,
                'initialPreviewConfig'=>$initialPreviewConfig
            ]);
        }
    }

    /**
     * Deletes an existing CGoods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        if($model->g_state==1){
            $model->g_state=2;
        }else{
            $model->g_state=1;
        }
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the CGoods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CGoods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
