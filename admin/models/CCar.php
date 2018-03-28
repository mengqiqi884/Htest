<?php

namespace admin\models;

use common\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "c_car".
 *
 * @property string $c_code
 * @property string $c_title
 * @property string $c_parent
 * @property string $c_logo
 * @property integer $c_level
 * @property integer $c_type
 * @property string $c_engine
 * @property string $c_volume
 * @property string c_imgoutside
 * @property string c_imginside
 * @property integer $c_price
 * @property integer $c_sortorder
 */
class CCar extends \yii\db\ActiveRecord
{
   // public $c_imgoutside;
   public $Cfile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_car';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'c_title'], 'required'],
            [['c_level', 'c_type', 'c_price', 'c_sortorder'], 'integer'],
            [['c_code', 'c_parent'], 'string', 'max' => 60],
            [['c_title'], 'string', 'max' => 765],
            [['c_logo'], 'string', 'max' => 150],
            [['c_engine'], 'string', 'max' => 100],
            [['c_volume'], 'string', 'max' => 200],
            [['c_imgoutside','c_imginside'], 'string', 'max' => 1000],

            [['Cfile'],'file','extensions' => 'xls,xlsx']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'c_code' => 'C Code',
            'c_title' => '名称',
            'c_parent' => 'C Parent',
            'c_logo' => '图片',
            'c_level' => 'C Level',
            'c_type' => 'C Type',
            'c_engine' => 'C Engine',
            'c_volume' => 'C Volume',
            'c_price' => 'C Price',
            'c_sortorder' => 'C Sortorder',
        ];
    }

    /*获取所有的品牌*/
    public static function GetAllBrand(){
        $all=array();

        $model=CCar::find()->where(['c_level'=>1])->orderBy(['c_sortorder'=>SORT_ASC])->asArray()->all();
        if($model){
            $all = ArrayHelper::map($model,'c_code','c_title');
//            foreach($model as $key=>$value){
//                $all[$value['c_code']]=$value['c_title'];
//            }
        }
//        var_dump($all);exit;
        return $all;
    }
}
