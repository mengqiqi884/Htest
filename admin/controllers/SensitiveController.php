<?php

namespace admin\controllers;

use Yii;
use admin\models\CSensitive;
use admin\models\SensitiveSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * SensitiveController implements the CRUD actions for CSensitive model.
 */
class SensitiveController extends BaseController
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
     * Lists all CSensitive models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SensitiveSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single CSensitive model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->s_id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * 验证规则
     */
    public function actionValidForm()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $id = Yii::$app->request->get('id');
        $model = new CSensitive();
        if (!empty($id)) {
            $model->S_id = $id;
        }
        $model->load($data);

        return ActiveForm::validate($model);
    }


    /**
     * Creates a new CSensitive model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CSensitive;

        if (Yii::$app->request->post()) {
            $adposted=Yii::$app->request->post('CSensitive');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->attributes=$adposted;
                $model->created_time=date('Y-m-d H:i:s');
                if(!$model->save()){
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>添加成功');
            }catch(Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>添加失败');
            }
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CSensitive model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $adposted=Yii::$app->request->post('CSensitive');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->attributes=$adposted;
                if(!$model->save()){
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>修改成功');
            }catch(Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>修改失败');
            }
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CSensitive model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model=$this->findModel($id);
        $model->is_del=1;
        if(!$model->save()){
            return ['status'=>'500','message' => '删除失败'];
        }else{
            return ['status'=>'200','message' => '删除成功'];
        }

    }

    /**
     * Finds the CSensitive model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CSensitive the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CSensitive::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
     * Excel数据导入
     */
    public function actionToExcel(){
        $model = new CSensitive();
        return  $this->render('toexcel',['model'=>$model]);
    }

    /*
     * 上传文件、excel导入数据
     */
    public function actionExcelImport(){
        Yii::$app->getResponse()->format='json';

        $model = new CSensitive();
        $path = '';
        /*上传excel文件是否成功*/
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstance($model, 'Cfile');  //上传文件
            if ($file) {
                //想要保存的目录的物理路径 D:/www/qczh/upload/
                $filepath = $_SERVER['DOCUMENT_ROOT'] . '/' . Yii::$app->params['base_file'] . '/upload/file/';
                if (!is_dir($filepath) || !is_writable($filepath)) {
                    @mkdir($filepath, 0777, true);
                }
                $name = 'SensitiveModels_'.date('YmdHis');
                $path = $filepath . $name . '.' . $file->extension;
                $file->saveAs($path);

                $excelData = $this->excelToArray($file);
                $data_arr=json_decode($excelData);

                if($data_arr) {
                    $data = array();

                    //批量插入
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        foreach ($data_arr as $v) {
                            $data[] = [
                                's_name' =>$v[0],
                                'created_time'=>date('Y-m-d H:i:s'),
                                'is_del' =>0
                            ];
                        }
                        //批量插入
                        Yii::$app->db->createCommand()->batchInsert('c_sensitive', [
                                's_name', 'created_time', 'is_del']
                            , $data)->execute();

                        $transaction->commit();
                        return ['state' => 200, 'message' => '敏感词汇批量导入成功'];
                    } catch (Exception $e) {
                        $transaction->rollBack();
                        var_dump($e);
                        // exit;
                        return ['state' => 500, 'message' => '导入失败'];
                    }
                }else{
                    return ['state' => 500, 'message' => '内容为空'];
                }
            }else{
                return ['state'=>500,'message'=>'文件上传失败'];
            }
        }
    }
}
