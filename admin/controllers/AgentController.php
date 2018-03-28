<?php

namespace admin\controllers;

use admin\models\CarSearch;
use admin\models\CCarreplace;
use admin\models\CCity;
use Yii;
use admin\models\CAgent;
use admin\models\AgentSearch;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AgentController implements the CRUD actions for CAgent model.
 */
class AgentController extends Controller
{
    public function behaviors()
    {
        return ['verbs' => ['class' => VerbFilter::className(), 'actions' => ['delete' => ['post', 'get'],],],];
    }

    /**
     * 4s店列表
     */
    public function actionIndex()
    {
        $searchModel = new AgentSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        /*********************在gridview列表页面上直接修改数据 start*****************************************/
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey'); //获取需要编辑的数据id
            $model = CAgent::findOne(['a_id' => $id]);
            $out = Json::encode(['output'=>'', 'message'=>'']);
            //获取用户修改的参数（比如：角色）
            $posted = current($_POST['CAgent']);//输出数组中当前元素的值，默认初始指向插入到数组中的第一个元素。移动数组内部指针，使用next()和prev()
            $post = ['CAgent' => $posted];
            if ($model->load($post)) { //赋值

                $model->save(); //save()方法会先调用validate()再执行insert()或者update()
                isset($posted['a_state']) && $output = $model->a_state;
            }
            $out = Json::encode(['output'=>$output, 'message'=>'']);
            echo $out;
            return;
        }
        /*********************在gridview列表页面上直接修改数据 end*****************************************/


        return $this->render('index', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel,]);
    }

    /**
     *查看已发布的车型列表
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->a_id]);
        } else {
            //查找该4s店已发布的车型信息
            $carsmodel = CCarreplace::find()->where(['r_role' => 2, 'r_accept_id' => $id]);

            $dataProvider = new ActiveDataProvider([
                'query' => $carsmodel,
            ]);

            $dataProvider->pagination=[
                'pageSize' => 25,
            ];
            $dataProvider->sort = [
                'defaultOrder' => ['r_id'=>SORT_ASC]
            ];
            return $this->render('view', ['model' => $model, 'dataProvider' => $dataProvider]);
        }
    }

    /*
     * 重置密码
     */
    public function actionReset($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $adposted = Yii::$app->request->post('CAgent');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->a_pwd = strtolower(md5($adposted['a_newpwd']));
                $model->updated_time = date('Y-m-d H:i:s');

                if (!$model->save()) {
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success', '<i class="glyphicon glyphicon-ok"></i>密码重置成功');
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', '<i class="glyphicon glyphicon-remove"></i>密码重置失败');
            }

            return $this->redirect(['index']);
        } else {
            $model->a_newpwd = '';

            return $this->render('update', ['model' => $model, 'flag' => 'reset-pwd']);
        }
    }

    /*
     * 新建4S店
     */
    public function actionCreate()
    {
        $model = new CAgent;

        if (Yii::$app->request->post()) {
            $adposted = Yii::$app->request->post('CAgent');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->attributes = ['a_account' => $adposted['a_account'], 'a_pwd' => strtolower(md5($adposted['a_newpwd'])), 'a_name' => $adposted['a_name'], 'a_areacode' => $adposted['city'], 'a_brand' => $adposted['a_brand'], 'a_address' => $adposted['a_address'], 'a_concat' => $adposted['a_concat'], 'a_phone' => $adposted['a_phone'], 'a_email' => $adposted['a_email'], 'a_position' => $adposted['a_position'], 'a_state' => 1, 'created_time' => date('Y-m-d H:i:s'), 'updated_time' => date('Y-m-d H:i:s')];

                if (!$model->save()) {
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success', '<i class="glyphicon glyphicon-ok"></i>新增成功');
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', '<i class="glyphicon glyphicon-remove"></i>新增失败');
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('create', ['model' => $model, 'flag' => 'create-agent']);
        }
    }

    /**
     *编辑
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $adposted = Yii::$app->request->post('CAgent');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->attributes = $adposted;
                $model->a_areacode = $adposted['city'];
                $model->updated_time = date('Y-m-d H:i:s');

                if (!$model->save()) {
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success', '<i class="glyphicon glyphicon-ok"></i>编辑成功');
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error', '<i class="glyphicon glyphicon-remove"></i>编辑失败');
            }

            return $this->redirect(['index']);
        } else {
            //获取城市
            $model->city = $model->a_areacode;
            //获取省份
            $city = CCity::GetCityName($model->a_areacode);
            $model->province = explode('^', $city)[1];

            return $this->render('update', ['model' => $model, 'flag' => 'modify-agent']);
        }
    }

    /**
     *修改4s店状态
     * 1：启用 2：禁用
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->a_state == 1) { //启用
            $model->a_state = 2;
            //修改其发布的所有车辆的状态为已下架
            Yii::$app->db->createCommand("update c_carreplace set r_state=2 where r_role=2 and r_accept_id=" . $id)->execute();
        } else {  //禁用
            $model->a_state = 1;
            Yii::$app->db->createCommand("update c_carreplace set r_state=1 where r_role=2 and r_accept_id=" . $id)->execute();
        }
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CAgent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CAgent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CAgent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
