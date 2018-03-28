<?php

namespace admin\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "c_products".
 *
 * @property string $p_id
 * @property string $p_name
 * @property string $p_month12
 * @property string $p_month24
 * @property string $p_month36
 * @property string $p_content
 * @property integer $p_sortorder
 * @property integer $is_all
 * @property string $created_time
 * @property integer $is_del
 */
class CProducts extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['p_name'], 'required'],
            [['p_month12', 'p_month24', 'p_month36'], 'number'],
            [['p_content'], 'string'],
            [['p_sortorder', 'is_all', 'is_del'], 'integer'],
            [['created_time'], 'safe'],
            [['p_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'p_id' => 'P ID',
            'p_name' => '产品名称',
            'p_month12' => 'P Month12',
            'p_month24' => 'P Month24',
            'p_month36' => 'P Month36',
            'p_content' => '申请流程',
            'p_sortorder' => '产品序号',
            'is_all' => 'Is All',
            'created_time' => 'Created Time',
            'is_del' => 'Is Del',
        ];
    }

    //查找当前最大序号
    public static function findmaxsort(){
        $product=Yii::$app->db->createCommand('select max(p_sortorder) as max_sort from c_products where is_del=0')->queryOne();

        if(empty($product['max_sort'])){
            return 1;
        }else{
            return $product['max_sort']+1;
        }
    }

}
