<?php

namespace admin\controllers;

use Yii;
use admin\models\COrderremarks;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderReamrkController implements the CRUD actions for COrderremarks model.
 */
class OrderRemarkController extends Controller
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
     * Lists all COrderremarks models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->request->get('oid')){
            $oid=Yii::$app->request->get('oid');

            $remarkmodel=COrderremarks::find()
                ->where(['is_del'=>0,'or_order_id'=>$oid])
                ->orderBy(['created_time'=>SORT_ASC]);

            $count=$remarkmodel->count();
            $remarks=array();
            if($count>0){
                $remark=$remarkmodel->asArray()->all();
                foreach($remark as $key=>$value) {
                    $remarks[]=[
                        'or_id'=>$value['or_id'],
                        'or_author' =>$value['or_author'],
                        'or_content' =>$value['or_content'],
                        'created_time' =>$value['created_time']
                    ];
                }
            }

            return $this->render('index', [
                'oid' =>$oid,
                'count'=>$count,
                'allremarks'=>$remarks
            ]);
        }
    }

    /**
     * Displays a single COrderremarks model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->or_id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new COrderremarks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new COrderremarks;
        $oid=Yii::$app->request->get('oid');
        return $this->render('create', [
            'model' => $model,
            'oid' =>$oid
        ]);
    }

    /*
     * 新增备注
     */
    public function actionAjaxCreate() {
        $model = new COrderremarks;
        $admin = Yii::$app->user->identity;

        Yii::$app->getResponse()->format='json';
        $oid=Yii::$app->request->post('oid');
        $content=Yii::$app->request->post('content');

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->attributes=[
                'or_order_id'=>$oid,
                'or_author'=>$admin->a_name,
                'or_content'=>$content,
                'created_time'=>date('Y-m-d H:i:s'),
            ];
            if(!$model->save()){
                throw new Exception;
            }
            $transaction->commit();//提交
            return ['state'=>200,'message'=>'添加成功'];
        }catch(Exception $e) {
            $transaction->rollBack();
        }
    }
    /**
     * Updates an existing COrderremarks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->or_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing COrderremarks model.
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
     * Finds the COrderremarks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return COrderremarks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = COrderremarks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
