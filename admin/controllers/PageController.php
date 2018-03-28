<?php

namespace admin\controllers;

use admin\models\CPage;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use dosamigos\qrcode\QrCode;
use dosamigos\qrcode\lib\Enum;
/**
 * page controller
 */
class PageController extends BaseController
{

    public function actions()
    {
        return [
            'Kupload' => [
                'class' => 'pjkui\kindeditor\KindEditorAction',
            ]
        ];
    }

    //用户协议
    public function actionIndex()
    {

        $model =CPage::find()->where(['p_remark'=>'用户协议'])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['site/welcome']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'flag' => 'user-agreement'
            ]);
        }
    }

    //App下载
    public function actionAppIndex()
    {

        $model =CPage::find()->where(['p_remark'=>'APP下载'])->one();

        return $this->render('update', [
            'model' => $model,
            'flag' => 'app-download'
        ]);
    }

    //调用二维码生成方法
    public function actionQrcode()
    {
        Yii::$app->getResponse()->format='json';
        $img_url = Yii::$app->request->post('code');
        $basePath= $_SERVER['DOCUMENT_ROOT'].'/'.Yii::$app->params['base_file'];
        $Dir = '/photo/qcode/';
        $outfile = $basePath.$Dir;
        if (!is_dir($outfile) || !is_writable($outfile)) {
            @mkdir($outfile, 0777, true);
        }
        $name = 'Appdown_qczh.png';
        $outfile = $outfile .$name;
        $saveAndPrint = true;
        //调用生成二维码
        QrCode::png($img_url,$outfile,0,3,4, $saveAndPrint);
        //保存二维码图片地址
        $model =CPage::find()->where(['p_remark'=>'APP下载'])->one();
        $transaction = Yii::$app->db->beginTransaction();
        try {

            $model ->attributes=[
                'p_url' =>$img_url,
                'p_content' =>$Dir.$name
            ];
            $model->save();

            $transaction->commit();
            return [
                'status' =>200,
                'message' => Yii::$app->params['base_url'].Yii::$app->params['base_file'].$Dir.$name
            ];

        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'status' =>500,
                'message' =>''
            ];
        }

    }

    /**
     * 客服电话
     */
    public function actionPhoneIndex()
    {
        $model =CPage::find()->where(['p_remark'=>'客服咨询'])->one();

        if ($model->load(Yii::$app->request->post())) {
             $model->save();
        }
        return $this->render('update', [
            'model' => $model,
            'flag' => 'phone'
        ]);

    }
//
//    /**
//     * Creates a new CPage model.
//     * If creation is successful, the browser will be redirected to the 'view' page.
//     * @return mixed
//     */
//    public function actionCreate()
//    {
//        $model = new CPage;
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->p_id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * 编辑用户协议
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->p_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CPage model.
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
     * Finds the CPage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CPage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CPage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
