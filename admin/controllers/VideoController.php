<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/8/9
 * Time: 14:01
 */

namespace admin\controllers;

use admin\models\CGoods;
use common\helpers\StringHelper;
use OSS\Core\OssException;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\yii2oss\Oss;

class VideoController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [''],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['upload-video', 'osssign'],
                        'allow' => true
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function actionOsssign()
    {

        $id = 'LTAIhcmmYv6XYfgi';
        $key = 'OB8H0ekDpOXCRrrshOZVuNF3raKRnw';
        $host = 'http://tbea.oss-cn-hangzhou.aliyuncs.com';

        $now = time();
        $expire = 30; //设置该policy超时时间是30s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = StringHelper::gmt_iso8601($end);

        $dir = 'easy_charge/video/';

        //最大文件大小.用户可以自己设置
        $condition = array(0 => 'content-length-range', 1 => 0, 2 => 1048576000);
        $conditions[] = $condition;

        //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(0 => 'starts-with', 1 => '$key', 2 => $dir);
        $conditions[] = $start;


        $arr = array('expiration' => $expiration, 'conditions' => $conditions);
        //echo json_encode($arr);
        //return;
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        $response = array();
        $response['accessid'] = $id;
        $response['host'] = $host;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        //这个参数是设置用户上传指定的前缀
        $response['dir'] = $dir;
        echo json_encode($response);
        exit;
    }


    /**
     * 上传文件（图片）
     * @return string
     */
    public function actionUploadVideo()
    {
        \Yii::$app->response->format = 'json';
        $res = [];
        $image_extension_arr = ['jpg', 'jpeg', 'png', 'gif',];
        $audio_extension_arr = ['mp3', 'wmv',];
        $video_extension_arr = ['mp4', 'avi', 'rmvb', 'rm', 'mkv',];

        if(\Yii::$app->request->isPost){
            $model = new CGoods();
            $key = \Yii::$app->request->post('key');
            $type = \Yii::$app->request->post('type'); //上传的文件类型 image/video/audio

            $file = UploadedFile::getInstance($model,$key);//获取到上传的文件
            if (empty($file)) {
                return ['status'=>'300', 'message'=>'文件上传失败'];
            } else {
                //如果该文件已经存在，则不再上传，直接返回成功的结果
                $file_exists = BFile::find()->where(['file_md5' => md5_file($file->tempName)])->one();
                if (!empty($file_exists)) {
                    $res['file_id'] = $file_exists->file_id;
                    return ['status'=>'200', 'message'=>'上传成功', 'data' =>$res];
                }
            }
            //检查文件类型，并获取文件的尺寸信息
            $file_extension = strtolower($file->getExtension());//获取文件后缀

            if ( $type == 'image' && in_array($file_extension, $image_extension_arr)) {
                $image_size = getimagesize($file->tempName);//获取图片尺寸信息
                if (empty($image_size)) {
                    return ['status'=>'500', 'message'=>'文件异常'];
                }
            } elseif (  $type == 'audio' && in_array($file_extension, $audio_extension_arr)) {

            } elseif ( $type == 'video' && in_array($file_extension, $video_extension_arr)) {

            } else {
                return ['status'=>'300', 'message'=>'文件格式错误'];
            }

            //设置oss文件路径和文件名
            $oss_directory = 'easy_charge/video/' . date('Y-m-d') . '/';
            $oss_file_name = date('YmdHis') . rand(100, 999) . '.' . $file_extension;
            $oss_path = $oss_directory . $oss_file_name;

            try {
                $OssClient = Oss::getOssClient();
                try{
                    $result = $OssClient->uploadFile(Oss::getBucketName(), $oss_path, $file->tempName); //简单上传
                } catch(OssException $e) {
                    printf(__FUNCTION__ . ": FAILED\n");
                    printf($e->getMessage() . "\n");
                    exit;
                }

                $b_file = new BFile;
                $b_file->file_id = StringHelper::createGuid();
                $b_file->file_belong = $result['oss-requestheaders']['Host'];//归属
                $b_file->file_path = $oss_path;//相对路径
                $b_file->file_minitype = $file->type;//mine类型
                $b_file->file_name = $oss_file_name;//文件名
                $b_file->file_state = 1;
                $b_file->file_size = $file->size;
                $b_file->file_md5 = md5_file($file->tempName);
                $b_file->file_creater = $image_size[0] . '*' . $image_size[1];
                $b_file->file_createtime = date('Y-m-d H:i:s');
                if (!$b_file->save()) {
                    throw new Exception;
                }
                $res['file_id'] = $b_file->file_id;
            } catch (Exception $e) {
                return ['status'=>'500', 'message'=>'上传失败'];
            }

            return ['status'=>'200', 'message'=>'上传成功', 'data'=>$res];
        }else{

            return ['status'=>'500', 'message'=>'传参失败'];
        }

    }
}