<?php

namespace admin\controllers;





use Yii;
use yii\base\Exception;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use admin\models\ProductsSearch;
use admin\models\CProducts;
use yii\web\UploadedFile;

/**
 * ProductsController implements the CRUD actions for CProducts model.
 */
class ProductsController extends BaseController
{


    /**
     * Lists all CProducts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $dataProvider->pagination=[
            'pageSize' => 20,
        ];

        $dataProvider->sort = [
            'defaultOrder' => ['p_id'=>SORT_ASC]
        ];

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CProducts model.
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
     * Creates a new CProducts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CProducts;
        //查找当前最大序号
        $model->p_sortorder=CProducts::findmaxsort();

        if (Yii::$app->request->post()) {
            $adposted=Yii::$app->request->post('CProducts');
            $transaction = Yii::$app->db->beginTransaction();
            try{

                $model->attributes=[
                    'p_name'=>$adposted['p_name'],
                    'p_content' => $adposted['pic'],
                    'p_month12' => $adposted['p_month12'],
                    'p_month24'=>$adposted['p_month24'],
                    'p_month36'=>$adposted['p_month36'],
                    'p_sortorder'=>$adposted['p_sortorder'],
                    'created_time' =>date('Y-m-d H:i:s')
                ];
                if(!$model->save()){
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
                'initialPreview'=>[],
                'initialPreviewConfig'=>[]
            ]);
        }
    }

    /**
     * Updates an existing CProducts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $initialPreview=array();$initialPreviewConfig=array();

        $model = $this->findModel($id);
        if($model){

            $arr=explode(',',str_replace('[','',str_replace(']','',$model->p_content)));
            foreach($arr as $key){
                if($key){
                    array_push($initialPreview, '<img src="'.Yii::$app->params['base_url'].Yii::$app->params['base_file'].$key.'" width="100px" height="100px">');
                    $config = [
                        'width' => '120px',
                        'url' => Url::toRoute(['upload/delete-pic?type=product&code='.$id],true), // server delete action
                        'key' =>$key,
                    ];
                    array_push($initialPreviewConfig, $config);
                }
            }
        }

        if (Yii::$app->request->post()) {
            $adposted=Yii::$app->request->post('CProducts');
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $model->attributes = [
                    'p_name' => $adposted['p_name'],
                    'p_content' => $adposted['pic'],
                    'p_month12' => $adposted['p_month12'],
                    'p_month24' => $adposted['p_month24'],
                    'p_month36' => $adposted['p_month36'],
                    'p_sortorder' => $adposted['p_sortorder'],
                ];
                if (!$model->save()) {
                    // var_dump($model->getErrors());exit;
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>编辑成功');
            }catch(Exception $e) {
                $transaction->rollBack();
                //var_dump($e);
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>编辑失败');
            }

            return $this->redirect('index');

        } else {
            return $this->render('update', [
                'model' => $model,
                'initialPreview' =>$initialPreview,
                'initialPreviewConfig' =>$initialPreviewConfig
            ]);
        }
    }

    /**
     * Deletes an existing CProducts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = 'json';
        $model=$this->findModel($id);
        $model->is_del=1;
        if(!$model->save()){
            return ['status' => '500'];
        }else{
            return ['status' => '200'];
        }

    }

    /**
     * Finds the CProducts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CProducts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CProducts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /*多张图片上传*/
//    public function actionUpload()
//    {
//        $productsInfo = new CProducts();
//
//        $type = Yii::$app->request->post('type'); //type=p_content
//
//        $file_name = $type.'_' . time();
//
//        if (Yii::$app->request->isPost) {
//            $path = $_SERVER['DOCUMENT_ROOT'].'/'.Yii::$app->params['base_file'].'/photo/products/'.date('Ymd').'/'; //图片路径
//            if (!is_dir($path) || !is_writable($path)) {
//                @mkdir($path, 0777, true);
//            }
//            $res=array();
//
//            $img=UploadedFile::getInstances($productsInfo,$type);
//
//            if($img){
//                foreach($img as $v){
//                    if($v->size>2048*1024){
//                        echo json_encode( ['error' => '图片最大不可超过2M']);
//                        exit;
//                    }
//                    if (!in_array(strtolower($v->extension), array('gif', 'jpg', 'jpeg', 'png'))) {
//                        echo json_encode( ['error' => '请上传标准图片文件, 支持gif,jpg,png和jpeg.']);
//                        exit;
//                    }
//                    $filePath = $path . '/' . $file_name . '.' . $v->extension;
//
//                    if ($v->saveAs($filePath)) {
//                        $img_url='/photo/products/' . date('Ymd') . '/' . $file_name . '.' . $v->extension;
//                        $res = [
//                            "imgfile" => $img_url
//                        ];
//                    }
//                }
//                echo json_encode([
//                    'imageUrl' => $res,
//                    'error' => '',
//                ]);
//                exit;
//            }else{
//                echo json_encode([
//                    'imageUrl' => '',
//                    'error' => '保存图片失败，请重试',
//                ]);
//                exit;
//            }
//        } else {
//            echo json_encode([
//                'imageUrl' => '',
//                'error' => '未获取到图片信息',
//            ]);
//            exit;
//        }
//    }
//
//    /*删除指定图片*/
//    public function actionDeletePic(){
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//
//        if ($key = Yii::$app->request->post('key')) {
//            $id=Yii::$app->request->get('id');
//
//            $m_id='['.$key.'],';
//            $model = $this->findModel($id);
//            $model->p_content=str_replace($m_id,'',$model->p_content);  //修改图片内容
//            if($model->save()){
//               // @unlink($_SERVER['DOCUMENT_ROOT'].'/'.Yii::$app->params['base_file'].$key);
//            }
//            return ['state' =>'200'];
//        }
//    }

    /*删除全部图片*/
    public function actionDeleteAllPics(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($pics = Yii::$app->request->post('pics')){
            $arr=explode(',',str_replace(']','',str_replace('[','',$pics)));
            foreach($arr as $key){
                @unlink($_SERVER['DOCUMENT_ROOT'].'/'.Yii::$app->params['base_file'].$key);
            }
            return ['state'=>'200'];
        }
    }
}
