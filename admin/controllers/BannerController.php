<?php

namespace admin\controllers;


use Yii;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use admin\models\BannerSearch;
use admin\models\CBanner;
use yii\web\UploadedFile;
use yii\helpers\Url;

/**
 * BannerController implements the CRUD actions for CBanner model.
 */
class BannerController extends BaseController
{
    /**
     * Lists all CBanner models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BannerSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            //  'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single CBanner model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->b_id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new CBanner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CBanner;

        if (Yii::$app->request->post()) {
            $adposted = Yii::$app->request->post('CBanner');
            $count = BannerSearch::getBannerCount($adposted['b_location']);
            $data = [];
            if ($count < 5) {
                $img_arr = explode(',', str_replace(']', '', str_replace('[', '', rtrim($adposted['img'], ",")))); //先去除最后一个','
                if (($count + count($img_arr)) <= 5) {
                    foreach ($img_arr as $img) {
                        $data[] = [
                            'b_location' => $adposted['b_location'],
                            'b_img' => $img,
                            'b_url' => $adposted['b_url'],
                            'b_title' => $adposted['b_title'],
                            'b_sortorder' => $adposted['b_sortorder'],
                            'created_time' => date('Y-m-d H:i:s')
                        ];
                    }

                    if ($data) {
                        $num = Yii::$app->db->createCommand()->batchInsert('c_banner', ['b_location', 'b_img', 'b_url', 'b_title', 'b_sortorder', 'created_time'], $data)->execute();
                        if ($num) {
                            Yii::$app->getSession()->setFlash('success', '<i class="glyphicon glyphicon-ok"></i>添加成功');
                        } else {
                            Yii::$app->getSession()->setFlash('error', '<i class="glyphicon glyphicon-remove"></i>添加失败');
                        }
                    }

                } else {
                    Yii::$app->getSession()->setFlash('error', '<i class="glyphicon glyphicon-warning-sign"></i>图片最多只能上传5张');
                }
            } else {
                Yii::$app->getSession()->setFlash('error', '<i class="glyphicon glyphicon-warning-sign"></i>此位置处图片已达上线');
            }
            return $this->redirect('index');  //重定向，回到 index控制器
        } else {
            $model->b_sortorder = BannerSearch::getMaxCarSortOrder(1); //1.首页
            return $this->render('create', [
                'model' => $model,
                'initialPreview' => '',
                'initialPreviewConfig' => [],
            ]);
        }
    }

    /**
     * Updates an existing CBanner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $initialPreview = '';
        $initialPreviewConfig = array();
        if ($model) {
            $initialPreview = '<img src="' . Yii::$app->params['base_url'] . Yii::$app->params['base_file'] . $model->b_img . '" width="100" >';
            $config = [
                'width' => '18px',
                'url' => Url::toRoute(['upload/delete-pic?type=ad&code=' . $id], true), // server delete action
                'key' => $model->b_img,
            ];
            array_push($initialPreviewConfig, $config);
        }
        if (Yii::$app->request->post()) {
            $adposted = Yii::$app->request->post('CBanner');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->attributes = [
                    // 'b_location' => $adposted['b_location'],
                    'b_img' => $adposted['img'],
                    'b_url' => $adposted['b_url'],
//                  'content' => $adposted['content'],
                    'b_title' => $adposted['b_title'],
                    'b_sortorder' => $adposted['b_sortorder'],
                ];
                if (!$model->save()) {
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success', '<i class="glyphicon glyphicon-ok"></i>修改成功');
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', '<i class="glyphicon glyphicon-remove"></i>修改失败');
            }

            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
                'initialPreview' => $initialPreview,
                'initialPreviewConfig' => $initialPreviewConfig,
            ]);
        }
    }

    /**
     * Deletes an existing CBanner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = 'json';

        $this->findModel($id)->delete();

        return ['status'=>'200','message'=>'删除成功'];
    }

    /**
     * Finds the CBanner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CBanner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CBanner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
     * 异步获取当前位置下最大的排序
     */
    public function actionAjaxGetSort()
    {
        Yii::$app->getResponse()->format = 'json';
        $location = trim(Yii::$app->request->post('location'));
        //获取最大的排序数
        $max_sort = BannerSearch::getMaxCarSortOrder($location);
        //获取当前位置下图片的个数
        $img_count = BannerSearch::getBannerCount($location);
        return ['state' => 200, 'message' => $max_sort, 'count' => $img_count];
    }

    /*
     * 多图上传
     */
    public function actionUpload()
    {

        $flag = Yii::$app->request->get('type'); //type=banner

        $file_name = $flag . '_' . time();

        if (Yii::$app->request->isPost) {
            $basePath = $_SERVER['DOCUMENT_ROOT'] . '/' . Yii::$app->params['base_file'];
            $dir = '';
            switch ($flag) {
                case 'banner':
                    $Info = new CBanner();
                    $dir = '/photo/ad/' . date('Ymd') . '/';
                    $type = 'b_img';
                    $img_count = 5;
                    break;
            }
            $path = $basePath . $dir; //图片路径
            if (!is_dir($path) || !is_writable($path)) {
                @mkdir($path, 0777, true);
            }
            $res = array();
            $img = UploadedFile::getInstances($Info, $type);
            if ($img) {
                foreach ($img as $v) {
                    if ($v->size > 2048 * 1024) {
                        echo json_encode(['error' => '图片最大不可超过2M']);
                        exit;
                    }
                    if (!in_array(strtolower($v->extension), array('gif', 'jpg', 'jpeg', 'png'))) {
                        echo json_encode(['error' => '请上传标准图片文件, 支持gif,jpg,png和jpeg.']);
                        exit;
                    }

                    $filePath = $path . '/' . $file_name . '.' . $v->extension;

                    if ($v->saveAs($filePath)) {
                        $img_url = $dir . $file_name . '.' . $v->extension;
                        $res = [
                            "imgfile" => $img_url
                        ];
                    }
                }
                echo json_encode([
                    'imageUrl' => $res,
                    'error' => '',
                ]);
                exit;
            } else {
                echo json_encode([
                    'imageUrl' => '',
                    'error' => '保存图片失败，请重试',
                ]);
                exit;
            }
        } else {
            echo json_encode([
                'imageUrl' => '',
                'error' => '未获取到图片信息',
            ]);
            exit;
        }
    }


}
