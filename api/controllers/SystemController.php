<?php
namespace api\controllers;
/**
 * Created by PhpStorm.
 * User: 沈小鱼
 * Date: 2016/8/30
 * Time: 18:38
 */
    use admin\models\Dics;
    use api\ext\auth\QueryParamAuth;
    use Yii;
    class SystemController extends ApiController{

        public function behaviors()
        {
            $behaviors = parent::behaviors();
            $behaviors['authenticator'] = [
                'class' => QueryParamAuth::className(),
                'except' => ['database-backups']
            ];
            $behaviors['verbs'] = [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    '*' => ['post']
                ]
            ];
            return $behaviors;
        }

        /*
        * 数据库备份
        */
        public function actionDatabaseBackups(){
            $to_file_name='databaselog/'.date('YmdH').'.sql';
            //数据库中有哪些表
            $juge = Yii::$app->db->createCommand("show tables ")->queryAll();
            //将这些表记录到一个数组
            $tabList = array();
            foreach($juge as $key=>$value){
                $tabList[] = $value['Tables_in_ValetParking'];
            }
            //运行中，请耐心等待
            $info = "/*-- ----------------------------\r\n";
            $info .= "-- 日期：".date("Y-m-d",time())."\r\n";
            $info .= "-- ----------------------------*/\r\n\r\n";
            //写入数据的文件
            file_put_contents($to_file_name,$info,FILE_APPEND);

            //将每个表的表结构导出到文件
            foreach($tabList as $val){
                //将每个表的表结构导出到文件
                $res = Yii::$app->db->createCommand("show create table ".$val)->queryAll();
                $info = "-- ----------------------------\r\n";
                $info .= "-- Table structure for `".$val."`\r\n";
                $info .= "-- ----------------------------\r\n";
                $info .= "DROP TABLE IF EXISTS `".$val."`;\r\n";
                $sqlStr = $info.$res[0]['Create Table'].";\r\n\r\n";
                //追加到文件
                file_put_contents($to_file_name,$sqlStr,FILE_APPEND);

                //将每个表的数据导出到文件
                $res = Yii::$app->db->createCommand("select * from ".$val)->queryAll();
                //如果表中没有数据，则继续下一张表
                if(empty($res)){
                    continue;
                }
                //表中有数据时，写入到文件
                $info = "-- ----------------------------\r\n";
                $info .= "-- Records for `".$val."`\r\n";
                $info .= "-- ----------------------------\r\n";
                //追加到文件
                file_put_contents($to_file_name,$info,FILE_APPEND);
                $arr=array();
                //读取数据
                foreach($res as $k=>$v){
                    $sqlStr = "INSERT INTO `".$val."` VALUES (";
                    foreach($v as $zd){
                        $sqlStr .= "'".$zd."', ";
                    }

                    //去掉最后一个逗号和空格
                    $sqlStr = substr($sqlStr,0,strlen($sqlStr)-2);
                    $sqlStr .= ");\r\n";
                    file_put_contents($to_file_name,$sqlStr,FILE_APPEND);
                    $arr[]=$sqlStr;
                }
               file_put_contents($to_file_name,"\r\n",FILE_APPEND);
            }

              if(file_get_contents($to_file_name)){ //有内容
                  $model=Dics::find()->where(['type'=>'数据库备份'])->one();
                  if(!empty($model)){
                     Yii::$app->db->createCommand("DELETE FROM disc WHERE type='数据库备份'")->execute();
                  }
                  $model=new Dics();
                  $model->type='数据库备份';
                  $model->name='/'.$to_file_name;
                  $model->id=date('Y-m-d H:i:s');
                  if($model->save()){
                      $arr=array('success');
                  }else{
                      $arr=array('fail');
                  }
              }else{
                  $arr=array('数据库内容丢失');
              }
           // return $this->showResult(200,$arr);
        }
        
    }