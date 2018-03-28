<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\COrders;

/**
 * OrdersSearch represents the model behind the search form about `admin\models\COrders`.
 */
class OrdersSearch extends COrders
{
    public $o_user_name;
    public $o_user_nickname;
    public $o_agency_name;

    const PAGE_SIZE=25;
    public function rules()
    {
        return [
            [['o_id', 'o_user_id', 'o_usercar_id', 'o_replace_id', 'o_agency_id', 'o_state', 'is_del'], 'integer'],
            [['o_code', 'o_usercar', 'o_replacecar', 'created_time'], 'safe'],
            [['o_fee'], 'number'],

            [['o_user_name','o_user_nickname','o_agency_name','province','city'],'safe']
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$state)  //0:预约中 1：预约完成 2：预约取消
    {
        $query = COrders::find()
            ->innerJoinWith(['user'])
            ->innerJoinWith(['agent'])
            ->innerJoinWith(['carreplace'])
            ->Where(['c_orders.is_del'=>0,'c_orders.o_state'=>$state]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->pagination=[
            'pageSize' => self::PAGE_SIZE,
        ];
        $dataProvider->sort = [
            'defaultOrder' => ['created_time'=>SORT_DESC]
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if(!empty($this->o_user_name)){
            $query->andfilterWhere(['like','c_user.u_phone',$this->o_user_name]);
        }

        if(!empty($this->o_user_nickname)){
            $query->andfilterWhere(['like','c_user.u_nickname',$this->o_user_nickname]);
        }

        if(!empty($this->o_agency_name)){
            $query->andfilterWhere(['like','c_agent.a_name',$this->o_agency_name]);
        }

        if(!empty($this->o_code)){
            $query->andfilterWhere(['like','c_orders.o_code',$this->o_code]);
        }

        //按申请时间查询
        if (!empty($this->created_time)) {
            $create_date = explode('to', str_replace(' ', '', $this->created_time));
            $query->andFilterWhere(['between', 'created_time', "$create_date[0] 00:00:00", "$create_date[1] 23:59:59"]);
        }



        //按上牌城市查找
//        if($this->province && empty($this->city)){
//            $query->andWhere('c_carreplace.r_city like "'.$this->province.'%"');
//        }
//        if($this->city){
//            $query->andfilterWhere(['c_carreplace.r_city'=>$this->city]);
//        }

        $query->andFilterWhere(['like', 'o_usercar', $this->o_usercar])
            ->andFilterWhere(['like', 'o_replacecar', $this->o_replacecar]);
        return $dataProvider;
    }
}
