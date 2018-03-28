<?php

namespace admin\controllers;

use admin\models\CCarreplace;
use Yii;
use admin\models\CUser;
use admin\models\UserSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use admin\models\COrders;
use yii\data\ActiveDataProvider;

/**
 * UserController implements the CRUD actions for CUser model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all CUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());


        /*********************在gridview列表页面上直接修改数据 start*****************************************/
        //获取前面一部传过来的值
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey'); //获取需要编辑的数据id
            $model = $this->findModel($id);
            $out = Json::encode(['output'=>'', 'message'=>'']);
            //获取用户修改的参数（比如：角色）
            $posted = current($_POST['CUser']); //输出数组中当前元素的值，默认初始指向插入到数组中的第一个元素。移动数组内部指针，使用next()和prev()

            $post = ['CUser' => $posted];
            $output = '';
            if ($model->load($post)) { //赋值
                $model->u_state=$posted['u_state'];
                $model->save(); //save()方法会先调用validate()再执行insert()或者update()
                isset($posted['u_state']) && $output=CUser::getUserState($model->u_state); //配送人员当前状态
            }
            $out = Json::encode(['output'=>$output, 'message'=>'']);
            echo $out;
            return;
        }
        /*******************在gridview列表页面上直接修改数据 end***********************************************/



        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single CUser model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        //获取该用户置换信息
        $orders=COrders::find()
            ->where(['is_del'=>0,'o_user_id'=>$id])
            ->orderBy(['created_time'=>SORT_ASC]);

        $allorders = new ActiveDataProvider([
            'query' => $orders,
        ]);

        //获取该用户的车辆信息
        $cars = CCarreplace::find()
            ->where(['is_del'=>0,'r_role'=>1,'r_accept_id'=>$id])
            ->orderBy(['created_time'=>SORT_ASC]);

        $allcars = new ActiveDataProvider([
            'query' => $cars
        ]);
        return $this->render('view', [
            'model' => $model,
            'allorders'=>$allorders,
            'allcars' => $allcars
        ]);

    }


    /**
     * Creates a new CUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate()
//    {
//        $model = new CUser;
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->u_id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Updates an existing CUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->u_id]);
//        } else {
//            return $this->render('update', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Deletes an existing CUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }

    /**
     * Finds the CUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
