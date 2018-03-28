<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "c_agent".
 *
 * @property string $a_id
 * @property string $a_account
 * @property string $a_pwd
 * @property string $a_name
 * @property string $a_areacode
 * @property string $a_brand
 * @property string $a_address
 * @property string $a_concat
 * @property string $a_phone
 * @property string $a_email
 * @property integer $a_state
 * @property string $a_position
 * @property string $created_time
 * @property string $updated_time
 * @property integer $is_del
 *
 * @property string $l_type
 * @property string $l_input
 * @property string $end_time
 * @property string $province
 * @property string $city
 */
class CAgent extends \yii\db\ActiveRecord
{
    public $l_type;
    public $l_input;
    public $end_time;
    public $a_newpwd;
    public $a_confirmpwd;
    public $province;
    public $city;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_agent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['a_account', 'a_pwd', 'a_name', 'a_areacode', 'a_brand'], 'required'],
            [['a_state', 'is_del'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['a_account', 'a_pwd'], 'string', 'max' => 32],
            [['a_name'], 'string', 'max' => 50],
            [['a_areacode', 'a_address','a_position'], 'string', 'max' => 200],
            [['a_brand', 'a_concat', 'a_email'], 'string', 'max' => 100],
            [['a_phone'], 'string', 'max' => 11],

            [['l_type', 'l_input','end_time','province','city'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'a_id' => 'A ID',
            'a_account' => '用户名',
            'a_pwd' => '密码',
            'a_name' => '4S店名称',
            'a_areacode' => '城市',
            'a_brand' => '品牌',
            'a_address' => '地址',
            'a_concat' => '联系人',
            'a_phone' => '手机号',
            'a_email' => '邮箱',
            'a_position' => '职务',
            'a_state' => '状态',
            'created_time' => '创建时间',
            'updated_time' => 'Updated Time',
            'is_del' => 'Is Del',
        ];
    }

    public function getcar()
    {
        return $this->hasOne(CCar::className(), ['c_code'=>'a_brand'])->onCondition(['c_car.c_level'=>1]);
    }


    //获取4S店列表里所有的品牌
    public static function GetAllBrand(){
        $results=[];
        $model=CAgent::find()
            ->select('`c_agent`.`a_brand`,case when `c_car`.`c_title` is null then "" else `c_car`.`c_title` end as `c_title`')
            ->joinWith(['car'])
            ->where(['c_agent.is_del'=>0])->groupBy('c_agent.a_brand')->asArray()->all();
        if(!empty($model)){
            foreach($model as $key=>$value){
                $results[$value['a_brand']] =$value['c_title'];
            }
        }
        return $results;
    }

    //获取所有4s店
    public static function GetAllAgencyName()
    {
        $data = [];
        $agents = self::find()->where(['is_del'=>0])->asArray()->all();
        foreach($agents as $agent){
            $data[] = $agent['a_name'];
        }
        return $data;
    }
}
