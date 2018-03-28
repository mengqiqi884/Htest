<?php
namespace admin\controllers;


use admin\models\AdminForm;
use admin\models\CForums;
use admin\models\CPage;
use admin\models\Menu;
use admin\models\Zone;
use admin\models\CUser;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


/**
 * Site controller
 */
class SiteController extends BaseController
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->enableCsrfValidation = false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','user-page'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','selectdistrict','upload','welcome'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $user_id=Yii::$app->user->identity->getId();
        if(empty($user_id)){
            return $this->goHome();
        }
        $user_info = Yii::$app->authManager->getRolesByUser($user_id);
        $user = Yii::$app->user->identity;
        $menu = new Menu();
        $menu = $menu->getLeftMenuList();

        return $this->render('index',[
            'menu' => $menu,
            'user'=>$user,
            'user_info' => key($user_info),
        ]);
    }


    public function actionWelcome()
    {
        $sql = 'to_days(created_time) = to_days(now())';
        $user_num = CUser::find()->where(['is_del'=>0])->andWhere($sql)->count();
        $sql = 'created_time > DATE_SUB(now(), INTERVAL 1 YEAR)';
        $forum_num = CForums::find()->where(['is_del'=>0])->andWhere($sql)->count();
        return $this->render('welcome',[
            'users' => $user_num,
            'forums' => $forum_num
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new AdminForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if($model->UpdateModel()){
                return $this->goBack();
            }else{
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /*
       * 用户协议
       */
    public function actionUserPage(){
        $model=CPage::find()->where(['p_remark'=>'用户协议'])->one();
        return $this->render('user-page',['model' =>$model]);
    }
//    //查询城市
//    public function actionSelectcity(){
//        $p_id=Yii::$app->request->post('p_id');
//        $data=Zone::getCity($p_id);
//        return json_encode($data);
//    }
//    //查询地区
//    public function actionSelectdistrict(){
//        $c_id=Yii::$app->request->post('c_id');
//        $data=Zone::getDistrict($c_id);
//        return json_encode($data);
//    }
//    //上传单张图片
//    public static function actionUpload($user_id,$img,$pic_path,$img_temp){
//        if(!empty($img)){
//            $ext = $img->getExtension();
//
//            if(!is_dir($pic_path)){
//                @mkdir($pic_path,0777,true);
//            }
//            $logo_name = 'admin_'.time().$user_id.rand(100,999).'.'.$ext;
//            $res=$img->saveAs($pic_path.$logo_name);//设置图片的存储位置
//            return $img_temp.$logo_name;
//        }
//        return '';
//    }

    public function actionError()
    {
        if($error=Yii::$app->errorHandler->errorAction)
        {print_r($error);
            if(Yii::$app->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }
}
