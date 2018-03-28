<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "c_logistics".
 *
 * @property string $l_id
 * @property string $l_type
 * @property string $l_code
 * @property string $l_name
 */
class CLogistics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_logistics';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['l_type'], 'string', 'max' => 10],
            [['l_code', 'l_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_id' => 'L ID',
            'l_type' => 'L Type',
            'l_code' => 'L Code',
            'l_name' => 'L Name',
        ];
    }

    /*
     * 获取所有的物流公司名称
     */
    public static function GetAllLogistics(){
        $all=array();

        $model=CLogistics::find()->all();
        if($model){
            foreach($model as $key=>$value){
                $all[$value->l_id]=$value->l_name;
            }
        }
        return $all;
    }

    /*根据物流编号获取物流公司的名称*/
    public static function GetLogisticsName($id){
        $model=self::find()->where(['l_id'=>$id])->one();
        return empty($model)?'':$model->l_name;
    }
}
