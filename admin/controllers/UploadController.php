<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/1/19
 * Time: 13:29
 */

namespace admin\controllers;

use admin\models\CCar;
use admin\models\CGoods;
use admin\models\CPartner;
use admin\models\CPcBanner;
use admin\models\CProducts;
use Yii;
use admin\models\CBanner;
use yii\base\Exception;
use yii\web\UploadedFile;

class UploadController extends BaseController
{
    /*
     * 多图上传
     */
    public function actionUpload(){

        $flag = Yii::$app->request->get('type'); //type=banner

        $file_name = $flag.'_' . time();

        if (Yii::$app->request->isPost) {
            $basePath= $_SERVER['DOCUMENT_ROOT'].'/'.Yii::$app->params['base_file'];
            $dir='';$type='';
            switch($flag){
                case 'product': //金融产品
                    $Info = new CProducts();
                    $dir='/photo/products/'.date('Ymd').'/';
                    $type='p_content';
                    break;
                case 'banner': //广告图
                    $Info = new CBanner();
                    $dir='/photo/ad/'.date('Ymd').'/';
                    $type='pic';
                    break;
                case 'logo':  //品牌logo、货车系logo
                    $Info = new CCar();
                    $dir='/photo/brand/'.date('Y-n-j').'/';
                    $type='c_logo';
                    break;
                case 'out': //车系外观图
                    $Info = new CCar();
                    $dir='/photo/cars/'.date('Y-n-j').'/out/';
                    $type='c_imgoutside';
                    break;
                case 'in': //车系内饰图
                    $Info = new CCar();
                    $dir='/photo/cars/'.date('Y-n-j').'/in/';
                    $type='c_imginside';
                    break;
                case 'goods': //商品
                    $Info=new CGoods();
                    $dir='/photo/goods/'.date('Y-n-j').'/';
                    $type='g_pic';
                    break;
                case 'partner_colorlogo': //合作伙伴(彩色)
                    $Info=new CPartner();
                    $dir='/photo/partner/'.date('Y-n-j').'/';
                    $type='color_logo';
                    break;
                case 'partner_darklogo'://合作伙伴(灰白)
                    $Info=new CPartner();
                    $dir='/photo/partner/'.date('Y-n-j').'/';
                    $type='dark_logo';
                    break;
                case 'pc_banner':  //PC端banner图
                    $Info=new CPcBanner();
                    $dir='/photo/pc/'.date('Y-n-j').'/';
                    $type='p_img';
                    break;
            }
            $path =$basePath.$dir; //图片路径

            $res=array();
            $img=UploadedFile::getInstances($Info,$type);
            if($img){
                foreach($img as $v){
                    if($v->size>2048*1024){
                        echo json_encode( ['error' => '图片最大不可超过2M']);
                        exit;
                    }
                    if (!in_array(strtolower($v->extension), array('gif', 'jpg', 'jpeg', 'png'))) {
                        echo json_encode( ['error' => '请上传标准图片文件, 支持gif,jpg,png和jpeg.']);
                        exit;
                    }

                    if($flag!='banner' && $flag!='product' && $flag!='goods' && $flag!='partner_colorlogo' && $flag!='partner_darklogo' && $flag!='pc_banner' ){
                        $path.=(strtolower($v->extension)=='jpg' || strtolower($v->extension)=='jpeg' ? 'image_jpg':'image_png').'/';
                    }
                    if (!is_dir($path) || !is_writable($path)) {
                        @mkdir($path, 0777, true);
                    }

                    $filePath = $path . $file_name . '.' . $v->extension;

                    if ($v->saveAs($filePath)) {
                        if($type=='c_imgoutside'){
                            $url = date('Y-n-j').'/out/'.(strtolower($v->extension)=='jpg' || strtolower($v->extension)=='jpeg' ? 'image_jpg':'image_png').'/'; //图片路径
                        }elseif($type=='c_imginside'){
                            $url = date('Y-n-j').'/in/'.(strtolower($v->extension)=='jpg' || strtolower($v->extension)=='jpeg' ? 'image_jpg':'image_png').'/'; //图片路径
                        }elseif($type=='c_logo'){
                            $url = date('Y-n-j').'/'.(strtolower($v->extension)=='jpg' || strtolower($v->extension)=='jpeg' ? 'image_jpg':'image_png').'/'; //图片路径,月和日前面不带0
                        }else{
                            $url=$dir;
                        }

                        $img_url=$url . $file_name . '.' . $v->extension;
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
            }else{
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

    /**
     *删除指定图片
     */
    public function actionDeletePic(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($key = Yii::$app->request->post('key')) {
            $type=Yii::$app->request->get('type');
            $id=Yii::$app->request->get('code');

            $m_id='['.$key.'],';

            $transaction = Yii::$app->db->beginTransaction();
            try{
                switch($type){
                    case 'product': //金融产品c_products
                        $model = CProducts::findOne($id);
                        $model->p_content=str_replace($m_id,'',$model->p_content);  //修改图片内容
                        if(!$model->save()){
                            throw new Exception;
                        }
                        @unlink($_SERVER['DOCUMENT_ROOT'].'/'.Yii::$app->params['base_file'].$key);
                        break;
                    case 'logo':  //车型管理c_car
                        $model = CCar::findOne($id);
                        $model->c_logo=str_replace($key,'',$model->c_logo);  //修改logo
                        if(!$model->save()){
                            throw new Exception;
                        }
                        @unlink($_SERVER['DOCUMENT_ROOT'].'/'.Yii::$app->params['base_file'].'/photo/brand/'.$key);
                        break;
                    case 'out': //车型管理c_car
                        $model = CCar::findOne($id);
                        $model->c_imgoutside=str_replace($m_id,'',$model->c_imgoutside);  //修改外观图片
                        if(!$model->save()){
                            throw new Exception;
                        }
                        @unlink($_SERVER['DOCUMENT_ROOT'].'/'.Yii::$app->params['base_file'].'/photo/cars/'.$key);
                        break;
                    case 'in': //车型管理c_car
                        $model = CCar::findOne($id);
                        $model->c_imginside=str_replace($m_id,'',$model->c_imginside);  //修改内饰图片
                        if(!$model->save()){
                            throw new Exception;
                        }
                        @unlink($_SERVER['DOCUMENT_ROOT'].'/'.Yii::$app->params['base_file'].'/photo/cars/'.$key);
                        break;
                    case 'ad': //广告图管理c_banner
                        $model= CBanner::findOne($id);
                        $model->b_img=str_replace($key,'',$model->b_img);
                        if(!$model->save()){
                            throw new Exception;
                        }
                        @unlink($_SERVER['DOCUMENT_ROOT'].'/'.Yii::$app->params['base_file'].'/photo/banner/'.$key);
                        break;
                    case 'goods': //商品c_goods
                        $model= CGoods::findOne($id);
                        $model->g_pic=str_replace($key,'',$model->g_pic);
                        if(!$model->save()){
                            throw new Exception;
                        }
                        @unlink($_SERVER['DOCUMENT_ROOT'].'/'.Yii::$app->params['base_file'].'/photo/goods/'.$key);
                        break;
                }


                $transaction->commit();//提交
                return ['state' =>'200'];

            }catch(Exception $e) {
                $transaction->rollBack();
                return ['state'=>500];
            }
        }else{
            return ['state' =>'200'];
        }
    }


    public function actionVideo()
    {
        if (Yii::$app->request->isPost) {
            $filename = $_POST['filename'];

            $vname = $_FILES[$filename]['name'];
            $vsize = $_FILES[$filename]['size'];
            $tmpname = $_FILES[$filename]['tmp_name'];

            $p1 = [];
            $p2 = [];

            if (empty($vname)) {
                echo '{}';
                return;
            }else{

                if ($vsize > 20 * 1024000) {
                    echo '视频大小不能超过20M';
                    exit;
                }else{
                    $type = strstr($vname, '.');
                    if ($type != ".mp4" && $type != ".ogg" && $type != ".og" && $type != ".rmvb") {
                        echo '视频格式不对！';
                        exit;
                    } else {
                        $Dir = "/video/film/";
                        //上传路径
                        $pic_path = $_SERVER['DOCUMENT_ROOT'] . '/' . Yii::$app->params['base_file'] . $Dir;

                        if (!is_dir($pic_path)) {
                            $res = @mkdir($pic_path, 0777, true);
                            if (!$res) {
                                echo "对不起！头像目录创建失败！";
                                exit;
                            }
                        }

                        //保存图片
                        move_uploaded_file($tmpname, $pic_path . $vname);

                        // $p1[] = "<img src='http://path.to.uploaded.file/Animal-1.jpg'>"; //封面图
                        $p1[]="<video src='http://localhost/".Yii::$app->params['base_file']. $Dir.$vname. "' controls='controls'>";   //视频播放地址
                        $p2 = [
                            'caption' => $vname,   //视频名称
                            'size' => $vsize,  //视频大小
                            'width' => '120px',
                            'key' => $vname
                        ];

                        echo json_encode([
                            'initialPreview' => $p1,
                            'initialPreviewConfig' => $p2,
                        ]);
                        exit;
                    }
                }

            }
        } else {
            echo json_encode(['error' => '获取上传控件失败']);
            return;
        }
    }
}