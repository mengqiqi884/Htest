<?php

namespace admin\controllers;

use admin\models\CLogistics;
use Yii;
use admin\models\CScoreLog;
use admin\models\ScoreLogSearch;
use yii\base\Exception;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScoreLogController implements the CRUD actions for CScoreLog model.
 */
class ScoreLogController extends Controller
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
     * Lists all CScoreLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ScoreLogSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single CScoreLog model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new CScoreLog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = $this->findModel($id);
        $admin=Yii::$app->user->identity;

        if (Yii::$app->request->post()) {

            $adposted=Yii::$app->request->post('CScoreLog');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->attributes=$adposted;
                $model->sl_logistics=CLogistics::GetLogisticsName($adposted['sl_logistics']);
                $model->sl_operater=$admin->a_name;
                $model->sl_state=1; //1：已发货
                if(!$model->save()){
                    //var_dump($model->getErrors());exit;
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>物流状态添加成功');
            }catch(Exception $e) {
                $transaction->rollBack();
                //var_dump($e);exit;
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>物流状态添加失败');
            }
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CScoreLog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $admin=Yii::$app->user->identity;

        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $adposted=Yii::$app->request->post('CScoreLog');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->attributes=$adposted;
                $model->sl_logistics=CLogistics::GetLogisticsName($adposted['sl_logistics']);
                $model->sl_operater=$admin->a_name;

                if(!$model->save()){
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>物流状态编辑成功');
            }catch(Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>物流状态编辑失败');
            }
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CScoreLog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);

        if (Yii::$app->request->isAjax) {
            $model->is_del=1;
            if($model->save()) {
                echo Json::encode([
                    'success' => true,
                    'messages' => [
                        'kv-detail-info' => $model->user->u_nickname . '用 ' . $model->sl_score. ' 积分兑换的 "' .$model->sl_goodsname .'" 成功删除' .
                            Html::a('<i class="glyphicon glyphicon-hand-right"></i>  点击',
                                ['/score-log/index'], ['class' => 'btn btn-sm btn-info']) . ' 刷新页面.'
                    ]
                ]);
            }else{
                echo Json::encode([
                    'error' => true,
                    'messages' => [
                        'kv-detail-info' => '删除失败' .
                            Html::a('<i class="glyphicon glyphicon-hand-right"></i>  点击',
                                ['/score-log/index'], ['class' => 'btn btn-sm btn-info']) . ' 刷新页面.'
                    ]
                ]);
            }

            return;
        }

        return $this->redirect(['index']);
    }


    /*
     */
    public function actionAjaxOperator($id)
    {
        $post = Yii::$app->request->post(); //表单提交的内容

        $model = $this->findModel($id);
        if ($model->load($post) && $model->save()) {
            Yii::$app->getSession()->setFlash('success','<i class="fa fa-check"></i>物流状态编辑成功');
        }else{
            Yii::$app->getSession()->setFlash('warning','<i class="fa fa-times"></i>物流状态编辑失败');
        }

        return $this->redirect(['index']);
    }
    /**
     * Finds the CScoreLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CScoreLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CScoreLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
