<?php

namespace admin\models;

use common\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "c_city".
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property integer $level
 * @property integer $status
 * @property string $parent
 * @property integer $is_hot
 * @property string $create_time
 */
class CCity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level', 'status', 'is_hot'], 'integer'],
            [['create_time'], 'safe'],
            [['id'], 'string', 'max' => 150],
            [['name'], 'string', 'max' => 90],
            [['code', 'parent'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            'level' => 'Level',
            'status' => 'Status',
            'parent' => 'Parent',
            'is_hot' => 'Is Hot',
            'create_time' => 'Create Time',
        ];
    }

    /**
     * 根据code查找城市名称
     */
    public static function GetCityName($code){
        $str='';
        $model=CCity::find()->where(['code'=>$code])->one(); //市级code
        if($model){
            $str=$model->name.'^'.$model->parent;
        }
        return $str;
    }

    /**
     * 指定位置插入字符串
     * @param $str  原字符串
     * @param $i    插入位置
     * @param $substr 插入字符串
     * @return string 处理后的字符串
     */
    public static function insertToStr($str, $i, $substr){
        //指定插入位置前的字符串
        $startstr="";
        for($j=0; $j<$i; $j++){
            $startstr .= $str[$j];
        }

        //指定插入位置后的字符串
        $laststr="";
        for ($j=$i; $j<strlen($str); $j++){
            $laststr .= $str[$j];
        }

        //将插入位置前，要插入的，插入位置后三个字符串拼接起来
        $str = $startstr . $substr . $laststr;

        //返回结果
        return $str;
    }

    /*获取所有的省份*/
    public static function GetAllProvince(){
        $all=array();

        $model=CCity::find()->where(['level'=>1,'status'=>1])->all();
        if($model){
            foreach($model as $key=>$value){
                $all[$value->code]=$value->name;
            }
        }
        return $all;
    }

    public static function GetSelectProvince(){
        $model=CCity::find()->where(['level'=>1,'status'=>1])->all();
        $all = ArrayHelper::getColumn($model,function($element){
            return [
                'id'=>$element->code,
                'text'=>$element->name,
            ];
        });
        return $all;
    }

    public static function getOwners($code){

        $results=[];
        $model=CCity::find()->where(['code'=>$code,'status'=>1])->one();
        if(!empty($model)){
            $results[$model->code] =$model->name;
           // $results = ArrayHelper::map($model,'code','name');
        }
        return $results;
    }

    //获取选中城市的名称
    public static function getSelectedCityName($code){
        $model=CCity::find()->where(['code'=>$code,'status'=>1])->one();
        return empty($model) ?'':$model->name;
    }

}
