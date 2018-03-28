<?php

namespace admin\controllers;

use admin\models\AuthAssignment;
use admin\models\AuthItem;
use admin\models\AuthItemChild;
use mdm\admin\models\Route;
use Yii;
use admin\models\Admin;
use admin\models\AdminSearch;
use yii\base\Exception;
use yii\base\Response;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rbac\Item;

/**
 * AdminController implements the CRUD actions for Admin model.
 */
class OperatorController extends Controller
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
     * 运营人员列表
     */
    public function actionOperatorIndex()
    {
        $searchModel = new AdminSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        /*********************在gridview列表页面上直接修改数据 start*****************************************/
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey'); //获取需要编辑的数据id
            $model = Admin::findOne(['a_id' => $id]);
            $out = Json::encode(['output'=>'', 'message'=>'']);
            //获取用户修改的参数（比如：角色）
            $posted = current($_POST['Admin']);//输出数组中当前元素的值，默认初始指向插入到数组中的第一个元素。移动数组内部指针，使用next()和prev()
            $post = ['Admin' => $posted];
            if ($model->load($post)) { //赋值

                $model->save(); //save()方法会先调用validate()再执行insert()或者update()
                isset($posted['a_state']) && $output = $model->a_state;
            }
            $out = Json::encode(['output'=>$output, 'message'=>'']);
            echo $out;
            return;
        }
        /*********************在gridview列表页面上直接修改数据 end*****************************************/


        return $this->render('operator-index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 角色列表
     */
    public function actionRoleIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AuthItem::find()->where(['type'=>1,'level'=>0]),
        ]);
        $dataProvider->pagination=[
            'pageSize' => 25,
        ];
        $dataProvider->sort = [
            'defaultOrder' => ['created_at'=>SORT_DESC]
        ];
        return $this->render('role-index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 运营人员信息详情
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->a_id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * 新增运营人员
     */
    public function actionOperatorCreate()
    {
        $model = new Admin;

        if (Yii::$app->request->post()) {
            $adposted=Yii::$app->request->post('Admin');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //根据角色名获取它的id
                $rolemodel=AuthItem::find()->where(['name'=>$adposted['a_role']])->one();

                $model->attributes=[
                    'a_name' => $adposted['a_name'],
                    'a_realname' => $adposted['a_realname'],
                    'a_pwd' => md5(strtolower($adposted['a_newpwd'])),
                    'a_position' => $adposted['a_position'],
                    'a_phone' => $adposted['a_phone'],
                    'a_email' => $adposted['a_email'],
                    'a_type' => $adposted['a_type'],
                    'a_role' =>empty($rolemodel)?'':$rolemodel->i_id,
                    'created_time' => date('Y-m-d H:i:s'),
                    'updated_time' => date('Y-m-d H:i:s')
                ];

                if(!$model->save()){
                    throw new Exception;
                }

                //给admin_type表添加数据
                //角色单选情况下
                $itemmodel=new AuthAssignment();
                $itemmodel->attributes=[
                    'item_name' => $adposted['a_role'],
                    'user_id' => $model->a_id,
                    'created_at' => time(),
                    'updated_at' =>time(),
                ];

                if(!$itemmodel->save()){
                    throw new Exception;
                }

                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>运营人员新增成功');
            }catch(Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>运营人员新增失败');
            }
            return $this->redirect(['operator-index']);
        } else {
            $avaliable=Admin::GetAllRules();
            $routes=[
                'avaliable' => array_values($avaliable),
                'assigned' =>[] //$assign是数组
            ];
            return $this->render('create', [
                'model' => $model,
                'routes' => $routes,
                'flag' => 'create-operator'
            ]);
        }
    }

    /**
     * 编辑 "运营人员"
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $adposted=Yii::$app->request->post('Admin');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //根据角色名获取它的id
                $rolemodel=AuthItem::find()->where(['name'=>$adposted['a_role']])->one();

                $model->attributes=[
                    'a_name' => $adposted['a_name'],
                    'a_realname' => $adposted['a_realname'],
                    'a_position' => $adposted['a_position'],
                    'a_phone' => $adposted['a_phone'],
                    'a_email' => $adposted['a_email'],
                    'a_role' =>(String)(empty($rolemodel)?'':$rolemodel->i_id),
                    'updated_time' => date('Y-m-d H:i:s')
                ];

                if(!$model->save()){
//                    var_dump($model->getErrors());exit;
                    throw new Exception;
                }

                //给admin_type表添加数据
                //角色单选情况下
                Yii::$app-> db ->createCommand('update auth_assignment set item_name="'.$adposted['a_role'].'",updated_at="'.time().'" WHERE user_id='.$id)->execute();

                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>编辑成功');
            }catch(Exception $e) {
                //var_dump($e);exit;
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>编辑失败');
            }
            return $this->redirect(['operator-index']);
        } else {
            //根据运营人员的角色id获取该运营人员的角色名称
            $role_id = $model->a_role;

            $avaliable=Admin::GetAllRules();
            $assign=Admin::getUserRoleName($role_id); //查找该运营人员当前所属的角色
            $routes=[
                'avaliable' => array_values($avaliable),
                'assigned' =>array($assign) //$assign是数组
            ];
            return $this->render('update', [
                'model' => $model,
                'routes' => $routes,
                'flag' => 'modify-operator'
            ]);
        }
    }

    /**
     * 新增 “角色” 页面
     */
    public function actionRoleCreate(){
        $model=new AuthItem;
        $pid=0;
        $avaliable=$this->GetChildren($pid);

        $routes=[
            'avaliable' =>$avaliable,
            'assigned' =>'' //查找该角色拥有的权限 $assign是字符串
        ];
        return $this->render('create', [
            'model' => $model,
            'routes' => $routes,
            'flag' => 'create-role'
        ]);
    }

    /**
     * 编辑 "角色" 页面
     */
    public function actionModify($id)
    {
        $model=AuthItem::find()->where(['i_id'=>$id])->one();

        return $this->render('update', [
            'model' => $model,
            'flag' => 'modify-role'
        ]);
    }


    /**
     * ajax异步保存"角色" （新增/编辑）
     */
    public function actionAjaxSaverole($id,$isnew){
        Yii::$app->getResponse()->format='json';

        if(empty($id) && $isnew){
            $model=new AuthItem();
        }else{
            $model=AuthItem::find()->where(['i_id'=>$id])->one();
        }

        if(Yii::$app->request->post()){
            $name=Yii::$app->request->post('name');
            $des=Yii::$app->request->post('description');
            $permissons=Yii::$app->request->post('permissons'); //多个权限之间以‘,’隔开

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if(!$isnew){ //编辑
                    //修改关联表（admin_item、admin_item_child、admin_type）的角色名
                    $sql="";
                    $sql.="UPDATE auth_item AS a ";
                    $sql.="INNER JOIN auth_item_child AS b ON b.parent = a.name ";
                    $sql.="INNER JOIN auth_assignment AS c ON c.item_name=a.name ";
                    $sql.="SET a.name='".$name."',";
                    $sql.="a.description='".$des."',";
                    $sql.="a.updated_at=".time()." ";
                    $sql.="WHERE a.i_id =".$id;

                    Yii::$app->db->createCommand($sql)->execute();

                    //获取刚刚表单提交过来选中的权限
                    $newarr=explode(',',rtrim($permissons,','));

                    //0.获取该角色原有的权限
                    $oldarr = [];
                    $assignmodel =AuthItemChild::find()
                        ->select(['b.i_id'])
                        ->leftJoin('auth_item b','b.name=auth_item_child.child')
                        ->where(['auth_item_child.parent'=>$name])
                        ->orderBy(['b.i_id'=>SORT_ASC])
                        ->asArray()->all();
                    foreach($assignmodel as $key=>$value) {
                        array_push($oldarr,$value['i_id']);
                    }

                    //1获取“该角色原有的权限与现在选中权限的交集”，【即获取原有权限与刚选中权限id一样的数组】
                    $samesetion = array_intersect($oldarr,$newarr);

                    //2比较“原有权限”与“权限交集”键值，并返回差集1：【即获取原有权限与交即部分权限不一样的id数组】
                    $diff_old = array_diff($oldarr,$samesetion);

                    //3先批量删除差集1权限
                    if($diff_old){
                        $sql="delete from auth_item_child where parent='".$model->name."' and child in (select name from auth_item where i_id in (".implode(',',$diff_old)."))";
                        Yii::$app->db->createCommand($sql)->execute();
                    }

                    //4.比较“选中权限”与“权限交集”键值，并返回差集2：【即获取刚选中权限与交集部分权限不一样的id数组】
                    $diff_new = array_diff($newarr,$samesetion);

                    //5、再批量添加差集2中的新权限
                    if($diff_new){
                        $data=array();
                        foreach($diff_new as $key){
                            $data[]=[
                                'parent' => $name, //角色名称(admin_item表查询)
                                'child' => AuthItem::getItemNameById($key), //权限名称
                            ];
                        }

                        Yii::$app->db->createCommand()->batchInsert('auth_item_child',['parent','child'],$data)->execute();
                    }
                    //////////////////////////////////////////////////////
                }else{ //新增
                    $model->attributes=[
                        'name' =>$name,
                        'type' =>1,
                        'description' =>$des ,
                        'rule_name' =>'SHANTE',
                        'data' =>'s:0:"";',
                        'level' =>0,
                        'p_level' =>0,
                        'created_at' =>time(),
                        'updated_at' =>time()
                    ];
                    if(!$model->save()){
                        throw new Exception;
                    }
                    //修改权限表auth_item_child
                    //-1、批量添加该角色选中的权限
                    $data=array();
                    $result=false;
                    $permissons_arr=explode(',',$permissons);

                    if($permissons_arr){
                        foreach($permissons_arr as $key){
                            $data[]=[
                                'parent' =>$name, //角色名称(auth_item表查询)
                                'child' => AuthItem::getItemNameById($key), //权限名称
                            ];
                        }

                        Yii::$app->db->createCommand()->batchInsert('auth_item_child',['parent','child'],$data)->execute();
                    }
                }

                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>操作成功');

                return ['state'=>200,'message'=>Url::toRoute('role-index')];
            }catch(Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>操作失败');

                return ['state'=>500,'message'=>'编辑失败'];
            }
        }else{
            return ['state'=>500,'message'=>'缺少参数'];
        }
    }

    /**
     * 运营人员 重置密码
     */
    public function actionReset($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $adposted=Yii::$app->request->post('Admin');
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->a_pwd=strtolower(md5($adposted['a_pwd']));
                $model->updated_time=date('Y-m-d H:i:s');

                if(!$model->save()){
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>密码重置成功');
            }catch(Exception $e) {
                $transaction->rollBack();
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>密码重置失败');
            }
            return $this->redirect(['operator-index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'flag' => 'reset-pwd'
            ]);
        }
    }

    /**
     * 修改运营人员状态
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if($model->a_state==1) {
                $model->a_state=2;
            }elseif($model->a_state==2) {
                $model->a_state=1;
            }
            if(!$model->save()){
                throw new Exception;
            }
            $transaction->commit();//提交
           // Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>密码重置成功');
        }catch(Exception $e) {
            $transaction->rollBack();
           // Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>密码重置失败');
        }

        return $this->redirect(['operator-index']);
    }

    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 刷新
     * @return type
     */
    public function actionRefresh($id)
    {
        $avaliable=Admin::GetAllRules(); //所有的角色
        Yii::$app->getResponse()->format = 'json';
        return  $points=[
            'avaliable' => array_values($avaliable),
            'assigned' =>array(Admin::getUserRoleName($id)) //查找该运营人员当前所属的角色
        ];
    }


    /**
     * 异步获取角色父节点
     * @param $parentid
     * @return string
     */
    public function actionAsynData()
    {
        Yii::$app->getResponse()->format='json';

        $parent_id = Yii::$app->request->get('id')!='#' ? Yii::$app->request->get('id') : 0; //level
        $role_name = Yii::$app->request->get('name') ? Yii::$app->request->get('name') : ''; //role_name

        //查找该角色拥有的权限
        $sql=Admin::getPermissonValue($role_name);
        $assign=Yii::$app->db->createCommand($sql)->queryOne();
        $checked_arr = explode(',',rtrim($assign['id'],',')); //字符串转数组

        //一次性遍历所有的权限
        $data_arr = $this->GetChildrenSql($parent_id, $role_name, $checked_arr);

        return $data_arr;
    }

    //获取分支
    public function GetChildrenSql($parentid, $role_name = '', $checked_arr = []) {

        $sql="";
        $sql.="SELECT i_id as id, name as text, level, p_level, ";
        $sql.="(SELECT CASE WHEN COUNT(1)>0 THEN 'closed' ELSE 'open' END FROM auth_item a WHERE a.p_level=auth_item.level) AS state ";
        $sql.="FROM auth_item ";
        $sql.="WHERE TYPE=2 AND LEVEL>1 and p_level=".$parentid." ORDER BY created_at";

        $data = Yii::$app->db->createCommand($sql)->queryAll();

        foreach($data as& $item){
            //初始化自定义节点图标
            if($item['p_level']==0){
                $item['icon'] = "fa fa-lock";
            }else if($item['state']=='closed'){
                $item['icon'] = "fa fa-asterisk";
            }else{
                $item['icon'] = "fa fa-leaf";
            }
            if($item['state']=='closed'){
                $item['children'] = self::GetChildrenSql($item['level'], $role_name, $checked_arr);
            }

            $item['state'] = [
                'opened' => ($item['p_level']==0) ? true:false,
                'checked' => ($role_name && in_array($item['id'],$checked_arr)) ? true:false //初始化数据有无勾选,“编辑”功能下
            ];
        }
        return $data;
    }

    /*递归算法*/
    public function GetChildren($parent_id){
        $this_children = array();
        $sql = $this->GetChildrenSql($parent_id);
        $this_children_list = Yii::$app->db->createCommand($sql)->queryAll();
        foreach($this_children_list as $this_children_item){
            if($this_children_item['state'] =='closed'){
                $this_children_item['children'] = $this->GetChildren($this_children_item['level']);
            }
            $this_children[] = $this_children_item;
        }
        return $this_children;
    }
}
