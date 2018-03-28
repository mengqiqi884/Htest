<?php

namespace admin\controllers;

use v1\models\CUser;
use Yii;
use admin\models\CMessage;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MessageController implements the CRUD actions for CMessage model.
 */
class MessageController extends Controller
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
     * Lists all CMessage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CMessage::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CMessage model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->m_id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new CMessage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $admin = Yii::$app->user->identity;
        $model = new CMessage;
        $user_registerid=array();

        if (Yii::$app->request->post()) {
            $adposted=Yii::$app->request->post('CMessage');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if($adposted['is_all_user']==-1){ //全部用户 ，批量插入
                    $users=CUser::find()->where(['is_del'=>0,'u_type'=>1,'u_state'=>1])->asArray()->all(); //查找所有启用中的用户
                    $data=array();
                    if($users){ //批量插入
                        foreach ($users as $k=>$v) {
                            //获取用户的registerid
                            $user_registerid[] = $v['u_register_id'];
                            //发送数据
                            $data[] = [
                                'm_type' =>1,
                                'm_user_id' =>$v['u_id'],
                                'm_author' =>$admin->a_name,
                                'm_content' =>$adposted['m_content'],
                                'created_time' =>date('Y-m-d H:i:s'),
                            ];
                        }
                        //批量插入
                        Yii::$app->db->createCommand()->batchInsert('c_message', [
                                'm_type', 'm_user_id', 'm_author', 'm_content', 'created_time']
                            , $data)->execute();
                    }
                }else{    //指定用户
                    $user=CUser::find()->where(['u_phone'=>$adposted['m_user_id']])->one();
                    if($user){
                        $user_id=$user->u_id;
                        //获取用户的registerid
                        $user_registerid[] = $user->u_register_id;

                        $model->attributes=[
                            'm_type' =>1,
                            'm_user_id' =>$user_id,
                            'm_author' =>$admin->a_name,
                            'm_content' =>$adposted['m_content'],
                            'created_time' =>date('Y-m-d H:i:s'),
                        ];
                        if(!$model->save()){
                            throw new Exception;
                        }
                    }
                }

                //极光推送
                PushMessageController::PushNotes($user_registerid,$adposted['m_content'],'system_message');

                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>发送成功');
            }catch(Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>发送失败');
            }
            return $this->redirect(['index']);
        } else {
            $model->is_all_user=-1;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CMessage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->m_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CMessage model.
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
     * 批量删除
     */
    public function actionAjaxDeleteAll(){
        Yii::$app->getResponse()->format='json';

        $users=Yii::$app->request->post('mids');

        $transaction = Yii::$app->db->beginTransaction();
        try{
            $f=CMessage::deleteAll(['in','m_id',$users]);
            if(!$f){
                throw new Exception;
            }
            $transaction->commit();//提交
            return ['state'=>200,'message'=>'删除成功'];
        }catch(Exception $e){
            $transaction->rollBack();
            return ['state'=>500,'message'=>'删除失败'];
        }
    }
    /**
     * Finds the CMessage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CMessage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CMessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    //测试
    public function actionTest(){
        require_once ('../../vendor/jpush/jpush/src/JPush/JPush.php');
        $app_key = 'd90e6d66bfa36a43e2a930ef';
        $master_secret = '7632804dc3b671d32637816f';

        $client = new \JPush($app_key, $master_secret);
        $result = $client->push()
            ->setPlatform(array('ios', 'android'))
            ->addRegistrationId(["101d85590975f3d4b36"])
            ->setNotificationAlert('Hi, JPush')
            ->addAndroidNotification("您有新消息测试", "新消息测试", 1, array("key1"=>"value1", "key2"=>"value2"))
            ->addIosNotification("您有新消息", 'iOS sound', \JPush::DISABLE_BADGE, true, 'iOS category', array("key1"=>"value1", "key2"=>"value2"))
            ->setMessage("msg content", 'msg title', 'type', array("key1"=>"value1", "key2"=>"value2"))
            ->setOptions(100000, 3600, null, false)
            ->send();
        var_dump($result);
        exit;
    }
}
