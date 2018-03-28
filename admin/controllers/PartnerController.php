<?php

namespace admin\controllers;

use Yii;
use admin\models\CPartner;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PartnerController implements the CRUD actions for CPartner model.
 */
class PartnerController extends Controller
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
     * Lists all CPartner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CPartner::find()->where(['is_del'=>0]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CPartner model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->p_id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new CPartner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CPartner;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CPartner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
                'initialPreview' =>'<img src="'.Yii::$app->params['base_url'].Yii::$app->params['base_file'].$model->p_colorlogo.'" width="150px" height="150px">',
                'initialPreview2' =>'<img src="'.Yii::$app->params['base_url'].Yii::$app->params['base_file'].$model->p_darklogo.'" width="150px" height="150px">',

            ]);
        }
    }

    /**
     * 设置合作伙伴 显示/不显示
     */
    public function actionUpdateShow($id)
    {
        $model =$this->findModel($id);
        if($model->p_is_show==1) {  //显示
            $model->p_is_show=0;
        } else {
            $model->p_is_show=1;  //不显示
        }
        $model->save();
        return $this->redirect(['index']);
    }
    /**
     * Deletes an existing CPartner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model= $this->findModel($id);
        $model->is_del=1;
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the CPartner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CPartner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CPartner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
