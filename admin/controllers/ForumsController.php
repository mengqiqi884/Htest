<?php

namespace admin\controllers;

use admin\models\CForumForum;
use admin\models\CForumReplies;
use admin\models\CForumRule;
use admin\models\CScoreList;
use v1\models\CUser;
use Yii;
use admin\models\CForums;
use admin\models\ForumsSearch;
use yii\base\Exception;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use admin\models\CarSearch;
use admin\models\CMessage;

/**
 * ForumsController implements the CRUD actions for CForums model.
 */
class ForumsController extends Controller
{
    public function behaviors()
    {
        return ['verbs' => ['class' => VerbFilter::className(), 'actions' => ['delete' => ['post', 'get'],],],];
    }

    /**
     * 帖子列表
     */
    public function actionIndex()
    {
        $searchModel = new ForumsSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        /*********************在gridview列表页面上直接修改数据 start*****************************************/
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey'); //获取需要编辑的数据id
            $model = CForums::findOne(['f_id' => $id]);
            $out = Json::encode(['output'=>'', 'message'=>'']);
            //获取用户修改的参数（比如：角色）
            $posted = current($_POST['CForums']);//输出数组中当前元素的值，默认初始指向插入到数组中的第一个元素。移动数组内部指针，使用next()和prev()
            $post = ['CForums' => $posted];
            if ($model->load($post)) { //赋值

                $model->save(); //save()方法会先调用validate()再执行insert()或者update()
                isset($posted['is_week_new']) && $output = $model->is_week_new;
                isset($posted['f_is_top']) && $output = $model->f_is_top;
                isset($posted['f_state']) && $output = $model->f_state;
            }
            $out = Json::encode(['output'=>$output, 'message'=>'']);
            echo $out;
            return;
        }
        /*********************在gridview列表页面上直接修改数据 end*****************************************/

        return $this->render('index', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel,]);
    }

    /**
     * 查看帖子详情
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $page = Yii::$app->request->get('page');

        $page = $page ? $page : 1;
        $limit = 7;  //每页显示7条回复
        $offset = ($page - 1) * $limit;
        //查询该帖子主题的回帖数
        $postmodel = CForumReplies::find()->where(['fr_forum_id' => $id])->orderBy(['fr_position' => SORT_DESC])->offset($offset)->limit($limit);
        $count = $postmodel->count();
        $replies = array();
        if ($count > 0) {
            $replies = $postmodel->all();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->f_id]);
        } else {
            return $this->render('view', ['model' => $model, 'count' => $count, 'allreplies' => $replies]);
        }
    }

    /**
     * 每周头条的设置
     */
    public function actionUpdateWeeknew($id)
    {
        $model = $this->findModel($id);
        if ($model->is_week_new == 1) { //每周头条
            $model->is_week_new = 0;
        } else { //非每周头条
            $model->is_week_new = 1;
        }
        $model->save();

        return $this->redirect('index');
    }

    /**
     *更新帖子 状态
     */
    public function actionUpdateState($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->f_state == -1) {
                    $model->f_forbidden_reason = '';
                }

                $model->save();   //跟新保存
                if ($model->f_state == 1) {  //禁用，发送站内信
                    $user = $model->user;
                    if ($user) {
                        $admin = Yii::$app->user->identity;
                        $forumname = '"' . \admin\models\CForumForum::GetFupName($model->f_fup) . '"板块的"' . rawurldecode($model->f_title) . '"';
                        //新建消息
                        $messagemodel = new CMessage();
                        date_default_timezone_set('PRC');
                        $messagemodel->attributes = ['m_type' => 1, 'm_user_id' => $user->u_id, 'm_author' => $admin->a_name, 'm_content' => '您的 ' . $forumname . ' 主题帖子被管理员禁用了', 'created_time' => date('Y-m-d H:i:s'),];
                        $messagemodel->save();
                    }
                }
            }

            return $this->redirect('index');
        } else {
            return $this->render('_updatestate', ['model' => $model,]);
        }

    }

    /**
     *帖子置顶
     * 1.修改用户的积分
     */
    public function actionUpdateTop($id)
    {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->f_is_top == 1) { //置顶
                $model->f_is_top = 0;
            } else { //非置顶
                $model->f_is_top = 1;
                if ($model->f_is_first_top == 0) {
                    $model->f_is_first_top = 1;
                    //查询第一次置顶奖励的积分数
                    $score = CForumRule::find()->where(['is_del' => 0])->andWhere(['fr_item' => '置顶'])->andWhere(['fr_fup' => $model->f_fup])->one();
                    if ($score) { //存在记录
                        $user = CUser::find()->where(['u_id' => $model->f_user_id])->one();
                        if ($user) {
                            $user->u_score = $user->u_score + $score->fr_score;  //修改积分
                            if (!$user->save()) {
                                throw new Exception;

                            }
                            //添加积分记录
                            $score_list = new CScoreList();
                            $score_list->attributes = ['sl_user_id' => $model->f_user_id, 'sl_rule' => '【置顶】' . CForumForum::GetFupName($model->f_fup), 'sl_score' => $score->fr_score, 'sl_act' => 'add', 'created_time' => date('Y-m-d H:i:s')];
                            if (!$score_list->save()) {
                                throw new Exception;

                            }
                        }
                    }
                }
            }
            if (!$model->save()) {
                throw new Exception;
            }
            $transaction->commit();//提交

        } catch (Exception $e) {
            $transaction->rollBack();
        }

        return $this->redirect('index');
    }

    /**
     * Deletes an existing CForums model.
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
     * Finds the CForums model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CForums the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CForums::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
