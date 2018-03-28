<?php

namespace admin\models;

use v1\models\CCarreplace;
use admin\models\CUser;
use Yii;

/**
 * This is the model class for table "c_orders".
 *
 * @property string $o_id
 * @property string $o_code
 * @property integer $o_user_id
 * @property integer $o_usercar_id
 * @property string $o_usercar
 * @property integer $o_replace_id
 * @property string $o_replacecar
 * @property integer $o_agency_id
 * @property string $o_fee
 * @property integer $o_state
 * @property string $created_time
 * @property string $end_time
 * @property integer $is_del
 *
 * @property string $o_remark
 * @property string $o_user_name
 * @property string $o_user_nickname
 * @property integer $l_type
 * @property string $l_input
 * @property string $province
 * @property string $city
 */
class COrders extends \yii\db\ActiveRecord
{
    public $o_user_name;
    public $o_user_nickname;
    public $o_remark;
    public $o_multi; //批量删除的id,字符串形式

    public $l_type;
    public $o_agency_name;
    public $end_time;
    public $province;
    public $city;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'c_orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['o_code', 'o_user_id', 'o_usercar_id', 'o_replace_id', 'o_agency_id'], 'required'],
            [['o_user_id', 'o_usercar_id', 'o_replace_id', 'o_agency_id', 'o_state', 'is_del'], 'integer'],
            [['o_fee'], 'number'],
            [['created_time'], 'safe'],
            [['o_code'], 'string', 'max' => 20],
            [['o_usercar', 'o_replacecar'], 'string', 'max' => 200],

          //  [['o_user_name','o_user_nickname'],'string' ,'max' =>200],
            [['l_type','l_input','end_time','province','city'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'o_id' => 'O ID',
            'o_code' => '预约序号',
            'o_user_id' => 'O User ID',
            'o_user_name' =>'用户名',
            'o_user_nickname' =>'昵称',
            'o_usercar_id' => 'O Usercar ID',
            'o_usercar' => '用户车型',
            'o_replace_id' => 'O Replace ID',
            'o_replacecar' => '预约车型',
            'o_agency_id' => '4s店id',
            'o_agency_name' => '4s店名称',
            'o_fee' => '置换价',
            'o_state' => '预约状态',
            'created_time' => '申请时间',
            'is_del' => 'Is Del',
        ];
    }

    public function getuser()
    {
        return $this->hasOne(CUser::className(), ['u_id'=>'o_user_id'])->onCondition(['c_user.is_del'=>0]);
    }

    public function getagent()
    {
        return $this->hasOne(CAgent::className(), ['a_id'=>'o_agency_id'])->onCondition(['c_agent.is_del'=>0]);
    }

    public function getcarreplace()
    {
        return $this->hasOne(CCarreplace::className(), ['r_id'=>'o_usercar_id'])->onCondition(['c_carreplace.is_del'=>0])->andOnCondition(['c_carreplace.r_role'=>1]);
    }
}
