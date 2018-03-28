<?php

namespace admin\models;

use v1\models\CUser;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\CCarreplace;

/**
 * CarreplaceSearch represents the model behind the search form about `admin\models\CCarreplace`.
 */
class CarreplaceSearch extends CCarreplace
{
    const PAGE_SIZE = 10;
    public function rules()
    {
        return [[['r_id', 'r_accept_id', 'r_role', 'r_price', 'r_state', 'r_views', 'r_persons', 'is_del'], 'integer'],
            [['r_brand', 'r_car_id', 'r_volume_id', 'r_cardtime', 'r_city', 'r_driving_pic1', 'r_driving_pic2', 'r_mileage_pic1', 'r_mileage_pic2', 'r_miles', 'created_time'], 'safe'],

            [['r_accept_name'], 'safe']];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CCarreplace::find()
            ->leftJoin('c_user','c_user.u_id=c_carreplace.r_accept_id and c_carreplace.r_role=1 and c_user.is_del=0')
            ->leftJoin('c_agent','c_agent.a_id=c_carreplace.r_accept_id and c_carreplace.r_role=2 and c_agent.is_del=0')
            ->select('c_carreplace.*')
            ->Where(['c_carreplace.is_del' => 0]);


        $dataProvider = new ActiveDataProvider(['query' => $query,]);

//        $dataProvider->pagination = ['pageSize' => self::PAGE_SIZE,];
//        $dataProvider->sort = ['defaultOrder' => ['created_time' => SORT_DESC]];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        //按名称(用户手机号/4s店名称)查找
        $query->andWhere('(c_user.u_nickname like "%' . $this->r_accept_name . '%" and c_user.u_state=1)or (c_agent.a_name like "%' . $this->r_accept_name . '%" and c_agent.a_state=1)');

        //按上架时间查询
        if (!empty($this->created_time)) {
            $create_date = explode('to', str_replace(' ', '', $this->created_time));
            $query->andFilterWhere(['between', 'c_carreplace.created_time', "$create_date[0] 00:00:00", "$create_date[1] 23:59:59"]);
        }

        $query->andFilterWhere([
            'r_id' => $this->r_id,
            'r_accept_id' => $this->r_accept_id,
            'r_role' => $this->r_role,
            'r_cardtime' => $this->r_cardtime,
            'r_price' => $this->r_price,
            'r_state' => $this->r_state,
            'r_views' => $this->r_views,
            'r_persons' => $this->r_persons,
            //        'created_time' => $this->created_time,
            //            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'r_brand', $this->r_brand])
            ->andFilterWhere(['like', 'r_car_id', $this->r_car_id])
            ->andFilterWhere(['like', 'r_volume_id', $this->r_volume_id])
            ->andFilterWhere(['like', 'r_city', $this->r_city])
            ->andFilterWhere(['like', 'r_driving_pic1', $this->r_driving_pic1])
            ->andFilterWhere(['like', 'r_driving_pic2', $this->r_driving_pic2])
            ->andFilterWhere(['like', 'r_mileage_pic1', $this->r_mileage_pic1])
            ->andFilterWhere(['like', 'r_mileage_pic2', $this->r_mileage_pic2])
            ->andFilterWhere(['like', 'r_miles', $this->r_miles]);

        return $dataProvider;
    }

    function GetArray($arr)
    {

    }
}
