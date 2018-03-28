<?php

namespace admin\controllers;

use Yii;
use admin\models\CForumRule;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * ForumRuleController implements the CRUD actions for CForumRule model.
 */
class ForumRuleController extends Controller
{
    public function behaviors()
    {
        return ['verbs' => ['class' => VerbFilter::className(), 'actions' => ['delete' => ['post', 'get'],],],];
    }

    /**
     * 积分规则列表
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider(['query' => CForumRule::find()->where(['is_del' => 0]),]);

        $dataProvider->pagination = ['pageSize' => 20,];
        $dataProvider->sort = ['defaultOrder' => ['fr_id' => SORT_DESC]];

        return $this->render('index', ['dataProvider' => $dataProvider,]);
    }

    /**
     * 验证规则
     */
     public function actionValidForm()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();
        $id = Yii::$app->request->get('id');
        $model = new CForumRule();
        if (!empty($id)) {
            $model->fr_id = $id;
        }
        $model->load($data);

        return ActiveForm::validate($model);
    }

    /**
     * 新建积分规则
     */
    public function actionCreate()
    {
        $model = new CForumRule;

        if (Yii::$app->request->post()) {
            $adposted = Yii::$app->request->post('CForumRule');
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $model->attributes = $adposted;
                $model->created_time = date('Y-m-d H:i:s');
                if (!$model->save()) {
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success', '<i class="glyphicon glyphicon-ok"></i>添加成功');
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', '<i class="glyphicon glyphicon-remove"></i>添加失败');
            }

            return $this->redirect('index');
        } else {
            return $this->render('create', ['model' => $model,]);
        }
    }

    /**
     * 编辑积分规则
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $adposted = Yii::$app->request->post('CForumRule');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->attributes = $adposted;

                if (!$model->save()) {
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success', '<i class="glyphicon glyphicon-ok"></i>编辑成功');
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', '<i class="glyphicon glyphicon-remove"></i>编辑失败');
            }

            return $this->redirect('index');
        } else {
            return $this->render('update', ['model' => $model,]);
        }
    }

    /**
     * 删除积分规则
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = 'json';
        $model = $this->findModel($id);
        if ($model) {
            $model->is_del = 1;
            if (!$model->save()) {
                return ['status' => '500', 'message' => '删除失败'];
            } else {
                return ['status' => '200', 'message' => '删除成功'];
            }
        }
    }

    /**
     * Finds the CForumRule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CForumRule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CForumRule::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
