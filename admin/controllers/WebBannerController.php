<?php

namespace admin\controllers;

use Yii;
use admin\models\CPcBanner;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WebBannerController implements the CRUD actions for CPcBanner model.
 */
class WebBannerController extends Controller
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
     * Lists all CPcBanner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CPcBanner::find()->where(['is_del'=>0]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CPcBanner model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new CPcBanner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CPcBanner();

        if (Yii::$app->request->post()) {
            $adposted=Yii::$app->request->post('CPcBanner');
            $count=CPcBanner::find()->where(['type'=>$adposted['type'],'is_del'=>0])->count();
            if($count<5){
                $img_arr=explode(',',str_replace(']','',str_replace('[','',$adposted['pic'])));
                if(($count+count($img_arr))-1<=5){
                    $data=[];
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        foreach($img_arr as $key){
                            if($key){
                                $data[]=[
                                    'type' =>$adposted['type'],
                                    'pic' => $key,
                                    'url' =>$adposted['url']
                                ];
                            }
                        }
                        Yii::$app->db->createCommand()->batchInsert('c_pc_banner',['type','pic','url'],$data)->execute();
                        $transaction->commit();//提交
                        Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>添加成功');
                    }catch(Exception $e) {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>添加失败');
                    }
                }else{
                    Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-warning-sign"></i>图片最多只能上传5张');
                }
            }else{
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-warning-sign"></i>图片最多只能上传5张');
            }
            return $this->redirect('index');  //重定向，回到 index控制器
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CPcBanner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $initialPreview='';$initialPreviewConfig=array();
        if($model){
            $initialPreview='<img src="'.Yii::$app->params['base_url'].Yii::$app->params['base_file'].$model->pic.'" width="90%" >';
//            $config=[
//                'width'=>'18px',
//                'url' => Url::toRoute(['upload/delete-pic?type=ad&code='.$id],true), // server delete action
//                'key' =>$model->b_img,
//            ];
//            array_push($initialPreviewConfig, $config);
        }
        if (Yii::$app->request->post()) {
            $adposted=Yii::$app->request->post('CPcBanner');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->attributes = [
                    'pic' => $adposted['pic'],
                    'url' => $adposted['url'],
                ];
                if (!$model->save()) {
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>修改成功');
            }catch(Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', '<i class="glyphicon glyphicon-remove"></i>修改失败');
            }

            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
                'initialPreview'=>$initialPreview,
                //'initialPreviewConfig' =>$initialPreviewConfig,
            ]);
        }
    }

    /**
     * Deletes an existing CPcBanner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /*
    * 异步获取当前位置下最大的排序
    */
    public function actionAjaxGetSort(){
        Yii::$app->getResponse()->format='json';
        $location=trim(Yii::$app->request->post('location'));

        //获取当前位置下图片的个数
        $img_count=CPcBanner::find()->where(['is_del'=>0,'type' =>$location])->count();
        return ['state' =>200,'message' =>(5-$img_count) ,'count'=>$img_count];
    }


    /**
     * Finds the CPcBanner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CPcBanner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CPcBanner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
