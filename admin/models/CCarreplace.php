<?php

namespace admin\models;

use admin\models\CUser;
use Yii;

/**
 * This is the model class for table "c_carreplace".
 *
 * @property string $r_id
 * @property string $user_name
 * @property integer $r_accept_id
 * @property integer $r_role
 * @property string $r_brand
 * @property string $r_car_id
 * @property string $r_volume_id
 * @property string $r_cardtime
 * @property string $r_city
 * @property string $r_driving_pic1
 * @property string $r_driving_pic2
 * @property string $r_mileage_pic1
 * @property string $r_mileage_pic2
 * @property string $r_miles
 * @property integer $r_price
 * @property integer $r_state
 * @property integer $r_views
 * @property integer $r_persons
 * @property string $created_time
 * @property string $end_time
 * @property integer $is_del
 * @property integer $is_forbidden
 * @property string $r_forbidden_reason
 * @property integer $accept_id
 */
class CCarreplace extends \yii\db\ActiveRecord
{
    public $user_name;
    public $end_time;
    public $accept_id; //便利值
    public $r_accept_name; //置换人名称
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_carreplace';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['r_accept_id', 'r_brand', 'r_car_id', 'r_volume_id'], 'required'],
            [['r_accept_id', 'r_role', 'r_state', 'r_views', 'r_persons', 'is_del','is_forbidden'], 'integer'],
            [['r_cardtime', 'created_time'], 'safe'],
            [['r_brand', 'r_miles'], 'string', 'max' => 100],
            [['r_car_id', 'r_volume_id', 'r_city', 'r_driving_pic1', 'r_driving_pic2', 'r_mileage_pic1', 'r_mileage_pic2'], 'string', 'max' => 200],
            [['r_price'],'number'],
            [['user_name'],'string','max'=>100],
            [['r_forbidden_reason'],'string','max'=>500],
            [['end_time'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'r_id' => 'R ID',
            'r_accept_id' => 'R Accept ID',
            'r_role' => 'R Role',
            'r_brand' => 'R Brand',
            'r_car_id' => '车系',
            'r_volume_id' => '车型',
            'r_cardtime' => '上牌时间',
            'r_city' => '上牌城市',
            'r_driving_pic1' => 'R Driving Pic1',
            'r_driving_pic2' => 'R Driving Pic2',
            'r_mileage_pic1' => 'R Mileage Pic1',
            'r_mileage_pic2' => 'R Mileage Pic2',
            'r_miles' => 'R Miles',
            'r_price' => '置换价',
            'r_state' => '状态',
            'r_views' => '浏览量',
            'r_persons' => '已置换人数',
            'created_time' => '上架时间',
            'is_del' => 'Is Del',
            'is_forbidden' =>'车辆状态'
        ];
    }

    public function getuser()
    {
        return $this->hasOne(CUser::className(), ['u_id'=>'r_accept_id'])->onCondition(['c_user.is_del'=>0,'c_user.u_state'=>1]);
    }

    public function getagent()
    {
        return $this->hasOne(CAgent::className(), ['a_id'=>'r_accept_id'])->onCondition(['c_agent.is_del'=>0,'c_agent.a_state'=>1]);
    }

    public static function GetCarreplaceForbiddenState($state)
    {
        return $state == 1 ? '<i class="glyphicon glyphicon-remove-circle text-danger">禁 用</i>' : '<i class="glyphicon glyphicon-ok-circle text-info">非禁用</i>' ;
    }
}
