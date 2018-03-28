<?php

namespace admin\controllers;

use admin\models\UploadForm;
use Yii;
use admin\models\CCar;
use admin\models\CarSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Url;
/**
 * CarController implements the CRUD actions for CCar model.
 */
class CarController extends BaseController
{
    public $enableCsrfValidation = false;

    /**
     * 车型管理 列表页
     */
    public function actionIndex()
    {
        //所有父级节点
        $parent_id = 0;
        $sql = $this->GetChildrenSql($parent_id);
        $data = Yii::$app->db->createCommand($sql)->queryAll();

        $data_arr[]=[
            "code"=>1,
            "name"=>"所有品牌",
            "level"=>"0",
            "sortorder"=>'',
            "children"=>$data
        ];

        $data_str = json_encode($data_arr,true);

//        if(strlen(file_get_contents('car_tree.json')) != strlen($data_str)){
            //写文件,每次覆盖之前的内容
            file_put_contents('car_tree.json',$data_str);
//        }


        return $this->render('index');

    }

    /**
     * 查看
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->c_code]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /*
    * 跳至 “导入” 页面
    */
    public function actionToExcel(){
        $model = new CCar;
        return  $this->render('toexcel',['model'=>$model]);
    }

    /*
     * 上传文件、excel导入数据
     */
    public function actionExcelImport(){
        Yii::$app->getResponse()->format='json';

        $model = new CCar();
        $path = '';
        /*上传excel文件是否成功*/
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstance($model, 'Cfile');  //上传文件
            if ($file) {
                $filepath = $_SERVER['DOCUMENT_ROOT'] . '/' . Yii::$app->params['base_file'] . '/upload/file/';

                if (!is_dir($filepath) || !is_writable($filepath)) {
                    @mkdir($filepath, 0777, true);
                }
                $name = 'CarModels_'.date('YmdHis');
                $path = $filepath . $name . '.' . $file->extension;
                $file->saveAs($path);

                $excelData = $this->excelToArray($path);
                $data_arr=json_decode($excelData);

                if ($data_arr) {
                    $data = array();
                    //批量插入
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        foreach ($data_arr as $v) {
                            $data[] = [
                                'c_code' =>$v[0],
                                'c_title' =>$v[1],
                                'c_parent' =>$v[2],
                                'c_logo' =>$v[3],
                                'c_level' =>$v[4],
                                'c_type' =>$v[5],
                                'c_engine' =>$v[6],
                                'c_volume' =>$v[7],
                                'c_price' =>$v[8],
                                'c_imgoutside' =>$v[9],
                                'c_imginside' =>$v[10],
                                'c_sortorder' =>$v[11]
                            ];
                        }
                        //批量插入
                        Yii::$app->db->createCommand()->batchInsert('c_car', [
                                'c_code', 'c_title', 'c_parent', 'c_logo', 'c_level', 'c_type',
                                'c_engine', 'c_volume', 'c_price', 'c_imgoutside', 'c_imginside', 'c_sortorder']
                            , $data)->execute();

                        $transaction->commit();
                        return ['state' => 200, 'message' => '订单批量导入成功'];
                    } catch (Exception $e) {
                        $transaction->rollBack();
                        return ['state' => 500, 'message' => '导入失败'];
                    }
                }else{
                    return ['state' => 500, 'message' => '内容为空'];
                }
            }else{
                return ['state'=>500,'message'=>'文件上传失败'];
            }

        }
//            if($_FILES['Cfile']['name']) {
//                //想要保存的目录的物理路径 D:/www/qczh/upload/
//                $filepath = $_SERVER['DOCUMENT_ROOT'] . '/' . Yii::$app->params['base_file'] . '/upload/file/';
//
//                $name=iconv("UTF-8","gb2312", $_FILES['Cfile']["name"]);
//                if (!is_dir($filepath) || !is_writable($filepath)) {
//                    @mkdir($filepath, 0777, true);
//                } else {
//
//                    move_uploaded_file($_FILES['Cfile']['tmp_name'], $filepath . date('Y-n-j') . '_' . $name);
//                }
//                //保存后的文件路径 http://loacalhost/qczh/upload/2017-1-17_xxxx
//                $file = $filepath . date('Y-n-j') . '_' . $name;

//                $excelData = $this->excelToArray($file);
//
//                $data_arr=json_decode($excelData);
//
//                if ($data_arr) {
//                    $data = array();
//
//                    //批量插入
//                    $transaction = Yii::$app->db->beginTransaction();
//                    try {
//                        foreach ($data_arr as $v) {
//                            $data[] = [
//                                'c_code' =>$v[0],
//                                'c_title' =>$v[1],
//                                'c_parent' =>$v[2],
//                                'c_logo' =>$v[3],
//                                'c_level' =>$v[4],
//                                'c_type' =>$v[5],
//                                'c_engine' =>$v[6],
//                                'c_volume' =>$v[7],
//                                'c_price' =>$v[8],
//                                'c_imgoutside' =>$v[9],
//                                'c_imginside' =>$v[10],
//                                'c_sortorder' =>$v[11]
//                            ];
//                        }
//                        //批量插入
//                        Yii::$app->db->createCommand()->batchInsert('c_car', [
//                                'c_code', 'c_title', 'c_parent', 'c_logo', 'c_level', 'c_type',
//                                'c_engine', 'c_volume', 'c_price', 'c_imgoutside', 'c_imginside', 'c_sortorder']
//                            , $data)->execute();
//
//                        $transaction->commit();
//                        return ['state' => 200, 'message' => '订单批量导入成功'];
//                    } catch (Exception $e) {
//                        $transaction->rollBack();
//                        //var_dump($e);
//                       // exit;
//                        return ['state' => 500, 'message' => '导入失败'];
//                    }
//                }
//
//            }else{
//                return ['state'=>500,'message'=>'文件上传失败'];
//            }
    }
    /**
     * 跳至 添加 页面 （品牌、车系、车型）
     */
    public function actionCreate()
    {
        $level=Yii::$app->request->get('level');
        $pid=Yii::$app->request->get('parent');

        $level=empty($level)?1:$level;
        $pid=empty($pid)?0:$pid;

        $model = new CCar;
        $model->c_sortorder=CarSearch::getMaxCarSortOrder($level,$pid);

        return $this->render('create', [
            'model' => $model,
            'level' =>$level,
            'parent' =>$pid,
            'initialPreview'=>'',
            'initialPreviewConfig'=>[],
            'initialPreview1'=>'',
            'initialPreviewConfig1'=>[],
            'initialPreview2'=>'',
            'initialPreviewConfig2'=>[],
        ]);

    }

    /**
     * 跳至 编辑  页面（品牌、车系、车型）
     */
    public function actionUpdate()
    {
        $initialPreview=array();$initialPreviewConfig=array();

        $level = Yii::$app->request->get('level');
        $id = Yii::$app->request->get('code'); //c_code标识

        $level=empty($level)?1:$level;

        $model = $this->findModel($id);
        if($model){
            switch($level){
                case 1: //品牌
                    array_push($initialPreview,'<img src="'.Yii::$app->params['base_url'].Yii::$app->params['base_file'].'/photo/brand/'.$model->c_logo.'" width="80px" height="80px">');
                    $config=[
                        'width'=>'18px',
                        'url' => Url::toRoute(['upload/delete-pic?type=logo&code='.$id],true), // server delete action
                        'key' =>$model->c_logo,
                    ];
                    array_push($initialPreviewConfig, $config);

                    return $this->render('update', [
                        'model' => $model,
                        'level' => $level,
                        'code' => $id,
                        'initialPreview' => $initialPreview,
                        'initialPreviewConfig' => $initialPreviewConfig
                    ]);

                    break;
                case 2: //车系
                    $initialPreview1=array();$initialPreviewConfig1=array();
                    $initialPreview2=array();$initialPreviewConfig2=array();

                    array_push($initialPreview,'<img src="'.Yii::$app->params['base_url'].Yii::$app->params['base_file'].'/photo/brand/'.$model->c_logo.'" width="80px" height="80px">');
                    $config=[
                        'width'=>'18px',
                        'url' => Url::toRoute(['upload/delete-pic?type=logo&code='.$id],true), // server delete action
                        'key' =>$model->c_logo,
                    ];
                    array_push($initialPreviewConfig, $config);
                    //外观图片
                    $arr=explode(',',str_replace('[','',str_replace(']','',$model->c_imgoutside)));
                    foreach($arr as $key){
                        if($key){
                            array_push($initialPreview1, '<img src="'.Yii::$app->params['base_url'].Yii::$app->params['base_file'].'/photo/cars/'.$key.'" width="150px" height="150px">');
                            $config = [
                                'width' => '120px',
                                'url' => Url::toRoute(['upload/delete-pic?type=out&code='.$id],true), // server delete action
                                'key' =>$key,
                            ];
                            array_push($initialPreviewConfig1, $config);
                        }
                    }
                    //内饰图片
                    $arr=explode(',',str_replace('[','',str_replace(']','',$model->c_imginside)));
                    foreach($arr as $key){
                        if($key){
                            array_push($initialPreview2, '<img src="'.Yii::$app->params['base_url'].Yii::$app->params['base_file'].'/photo/cars/'.$key.'" width="150px" height="150px">');
                            $config = [
                                'width' => '120px',
                                'url' => Url::toRoute(['upload/delete-pic?type=in&code='.$id],true), // server delete action
                                'key' =>$key,
                            ];
                            array_push($initialPreviewConfig2, $config);
                        }
                    }

                    return $this->render('update', [
                        'model' => $model,
                        'level' => $level,
                        'code' => $id,
                        'initialPreview' => $initialPreview,
                        'initialPreviewConfig' => $initialPreviewConfig,
                        'initialPreview1' => $initialPreview1,
                        'initialPreviewConfig1' => $initialPreviewConfig1,
                        'initialPreview2' => $initialPreview2,
                        'initialPreviewConfig2' => $initialPreviewConfig2

                    ]);

                    break;
                case 3: //车型
                    return $this->render('update', [
                        'model' => $model,
                        'level' => $level,
                        'code' => $id,
//                        'initialPreview' => $initialPreview,
//                        'initialPreviewConfig' => $initialPreviewConfig
                    ]);
                    break;
            }
        }
    }

    /*添加/编辑 品牌*/
    public function actionAjaxSavebrand(){
        Yii::$app->getResponse()->format='json';

        if (Yii::$app->request->post()) {
            $level=Yii::$app->request->post('level');
            $pid=Yii::$app->request->post('pid');
            $title=Yii::$app->request->post('title');
            $logo=Yii::$app->request->post('logo');
            $is_new=Yii::$app->request->post('is_new');

            $transaction = Yii::$app->db->beginTransaction();
            try{
                if($is_new){ //新添
                    $model = new CCar;
                    $model->attributes=[
                        'c_code' =>CarSearch::getMaxCarCode($level,$pid),
                        'c_title' =>$title,
                        'c_parent' =>$pid,
                        'c_logo' =>$logo,
                        'c_level' =>$level,
                        'c_type' =>1,
                        'c_sortorder' =>1
                    ];
                }else{ //编辑
                    $model=CCar::find()->where(['c_code'=>$pid])->one();
                    $model->attributes=[
                        'c_title' =>$title,
                        'c_logo' =>$logo,
                    ];
                }

                if(!$model->save()){
                    var_dump($model->getErrors());
                    throw new Exception;
                }
                $transaction->commit();//提交
               // Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>添加成功');
                return ['state'=>200];
            }catch(Exception $e) {
                $transaction->rollBack();
               // var_dump($e);
               // Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>添加失败');
                return ['state'=>500];
            }
        }
    }

    /*添加/编辑 车系*/
    public function actionAjaxSavecarxi(){
        Yii::$app->getResponse()->format='json';

        if (Yii::$app->request->post()) {
            $level=Yii::$app->request->post('level');
            $pid=Yii::$app->request->post('pid');
            $title=Yii::$app->request->post('title');
            $logo=Yii::$app->request->post('logo');
            $out_img=Yii::$app->request->post('out_img');
            $in_img=Yii::$app->request->post('in_img');
            $engine=Yii::$app->request->post('engine');
            $volume=Yii::$app->request->post('volume');
            $car_type=Yii::$app->request->post('c_type');
            $sort=Yii::$app->request->post('sort');
            $is_new=Yii::$app->request->post('is_new');

            $transaction = Yii::$app->db->beginTransaction();
            try{
                if($is_new){ //新添
                    $model = new CCar;
                    $model->attributes=[
                        'c_code' =>CarSearch::getMaxCarCode($level,$pid),
                        'c_title' =>$title,
                        'c_parent' =>$pid,
                        'c_logo' =>$logo,
                        'c_level' =>$level,
                        'c_type' =>$car_type,
                        'c_engine' =>$engine,
                        'c_volume' =>$volume,
                        'c_sortorder' =>$sort,
                        'c_imginside' =>$in_img,
                        'c_imgoutside' =>$out_img
                    ];
                }else{ //编辑
                    $model=CCar::find()->where(['c_code'=>$pid])->one();
                    $model->attributes=[
                        'c_title' =>$title,
                        'c_logo' =>$logo,
                        'c_type' =>$car_type,
                        'c_engine' =>$engine,
                        'c_volume' =>$volume,
                        'c_sortorder' =>$sort,
                        'c_imginside' =>$in_img,
                        'c_imgoutside' =>$out_img
                    ];
                }

                if(!$model->save()){
                    var_dump($model->getErrors());
                    throw new Exception;
                }
                $transaction->commit();//提交
                //Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>添加成功');
                return ['state'=>200];
            }catch(Exception $e) {
                $transaction->rollBack();
                // var_dump($e);
                //Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>添加失败');
                return ['state'=>500];
            }
        }
    }

    /*添加/编辑 车型*/
    public function actionAjaxSavecartype(){
        Yii::$app->getResponse()->format='json';

        if (Yii::$app->request->post()) {
            $level=Yii::$app->request->post('level');
            $pid=Yii::$app->request->post('pid');
            $title=Yii::$app->request->post('title');
            $sort=Yii::$app->request->post('sort');
            $price=Yii::$app->request->post('price');
            $is_new=Yii::$app->request->post('is_new');

            $transaction = Yii::$app->db->beginTransaction();
            try{
                if($is_new){ //新添
                    $model = new CCar;
                    $model->attributes=[
                        'c_code' =>CarSearch::getMaxCarCode($level,$pid),
                        'c_title' =>$title,
                        'c_parent' =>$pid,
                        'c_sortorder' =>$sort,
                        'c_level' =>$level,
                        'c_price' =>$price,
                    ];
                }else { //编辑
                    $model=CCar::find()->where(['c_code'=>$pid])->one();
                    $model->attributes=[
                        'c_title' =>$title,
                        'c_sortorder' =>$sort,
                        'c_price' =>$price,
                    ];
                }

                if(!$model->save()){
                   // var_dump($model->getErrors());
                    throw new Exception;
                }
                $transaction->commit();//提交
                Yii::$app->getSession()->setFlash('success','<i class="glyphicon glyphicon-ok"></i>添加成功');
                return ['state'=>200];
            }catch(Exception $e) {
                $transaction->rollBack();
                // var_dump($e);
                Yii::$app->getSession()->setFlash('error','<i class="glyphicon glyphicon-remove"></i>添加失败');
                return ['state'=>500];
            }
        }
    }



    /**
     * 删除 品牌、车系、车型
     */
    public function actionDelete()
    {
        Yii::$app->getResponse()->format='json';
        $code=Yii::$app->request->get('code');
        if($code){
            $model= $this->findModel($code);
            $parent=$model->c_parent;

           if($model->delete()){
               return ['state'=>200,'message'=>$parent];
           }else{
               return ['state'=>500];
           }
        }
    }

    /**
     *根据主键c_code查找数据
     */
    protected function findModel($id)
    {
        if (($model = CCar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('数据不存在');
        }
    }

    /*异步获取数据*/
    public function actionAsynData(){
       Yii::$app->getResponse()->format='json';
        if(Yii::$app->request->get('id')){
            $parent_id=Yii::$app->request->get('id');
        }else{
            $parent_id = 0;
        }

        $sql = $this->GetChildrenSql($parent_id);
        $data = Yii::$app->db->createCommand($sql)->queryAll();

        if($parent_id){
            $data_arr = $data;
        }else{
            $data_arr[]=[
                "code"=>1,
                "name"=>"所有品牌",
                "level"=>"0",
                "sortorder"=>'',
                "children"=>$data
            ];
        }

        return $data_arr;
    }

    public function GetChildrenSql($parent_id){
        $sql = "";
        $sql .= "select c_code as code,c_title as name,c_level as level, ";
        $sql .= "concat('". Yii::$app->params['base_url'].Yii::$app->params['base_file'].'/photo/brand/' ."', c_logo) as logo, ";
        $sql .= "(case when c_sortorder is null then '字母排序' else c_sortorder end) as sortorder, ";
        $sql .= "(select case when count(1)>0 then 'closed' else 'open' end from c_car a where a.c_parent=c_car.c_code) as state ";
        $sql .= "from c_car ";
        $sql .= "where c_parent=" . $parent_id ." order by c_code ";
        return $sql;
    }

    /*递归算法*/
    public function GetChildren($parent_id){
        $this_children = array();
        $sql = $this->GetChildrenSql($parent_id);
        $this_children_list = Yii::$app->db->createCommand($sql)->queryAll();
        foreach($this_children_list as $this_children_item){
            if($this_children_item['level'] < 1){
                $this_children_item['children'] = $this->GetChildren($this_children_item['code']);
            }
            $this_children[] = $this_children_item;
        }
        return $this_children;
    }

    /*多张图片上传*/
//    public function actionUpload()
//    {
//        $productsInfo = new CCar();
//
//        $type = Yii::$app->request->post('type'); //type=p_content
//
//        $file_name = $this->create_uuid(); //生成唯一编码
//
//        if (Yii::$app->request->isPost) {
//            $path=$_SERVER['DOCUMENT_ROOT'].'/'.Yii::$app->params['base_file'];
//            if($type=='c_imgoutside'){
//                $pathfile = '/photo/cars/'.date('Y-n-j').'/out/'; //图片路径
//            }elseif($type=='c_imginside'){
//                $pathfile = '/photo/cars/'.date('Y-n-j').'/in/'; //图片路径
//            }elseif($type=='c_logo'){
//                $pathfile = '/photo/brand/'.date('Y-n-j').'/'; //图片路径,月和日前面不带0
//            }else{
//                $pathfile='/photo/others/';
//            }
//            $path=$path.$pathfile;
//            $res=array();
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
//                    $path.=(strtolower($v->extension)=='jpg' || strtolower($v->extension)=='jpeg' ? 'image_jpg':'image_png').'/';
//                    if (!is_dir($path) || !is_writable($path)) {
//                        @mkdir($path, 0777, true);
//                    }
//                    $filePath = $path . $file_name . '.' . $v->extension;
//
//                    if ($v->saveAs($filePath)) {
//                        if($type=='c_imgoutside'){
//                            $url = date('Y-n-j').'/out/'; //图片路径
//                        }elseif($type=='c_imginside'){
//                            $url = date('Y-n-j').'/in/'; //图片路径
//                        }elseif($type=='c_logo'){
//                            $url = date('Y-n-j').'/'; //图片路径,月和日前面不带0
//                        }else{
//                            $url='/';
//                        }
//                        $img_url=$url.(strtolower($v->extension)=='jpg' || strtolower($v->extension)=='jpeg' ? 'image_jpg':'image_png').'/'. $file_name . '.' . $v->extension;
//
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
//            $type=Yii::$app->request->get('type');
//            $id=Yii::$app->request->get('code');
//
//            $m_id='['.$key.'],';
//            $model = $this->findModel($id);
//            $transaction = Yii::$app->db->beginTransaction();
//            try{
//                switch($type){
//                    case 'logo':
//                        $model->c_logo=str_replace($key,'',$model->c_logo);  //修改logo
//                        break;
//                    case 'out':
//                        $model->c_imgoutside=str_replace($m_id,'',$model->c_imgoutside);  //修改外观图片
//                        break;
//                    case 'in':
//                        $model->c_imginside=str_replace($m_id,'',$model->c_imginside);  //修改内饰图片
//                        break;
//                }
//                if(!$model->save()){
//                    throw new Exception;
//                }
//                // @unlink($_SERVER['DOCUMENT_ROOT'].'/'.Yii::$app->params['base_file'].'/photo/brand/'.$key);
//                $transaction->commit();//提交
//                return ['state' =>'200'];
//
//            }catch(Exception $e) {
//                $transaction->rollBack();
//                // var_dump($e);
//                return ['state'=>500];
//            }
//        }
//    }


}
