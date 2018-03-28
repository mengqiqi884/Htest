<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\Admin;

/**
 * AdminSearch represents the model behind the search form about `admin\models\Admin`.
 */
class AdminSearch extends Admin
{
    public function rules()
    {
        return [
            [['a_id', 'a_state', 'is_del'], 'integer'],
            [['a_name', 'a_pwd', 'a_realname', 'a_token', 'a_position', 'a_phone', 'a_email', 'a_role', 'a_type','last_login_time', 'created_time', 'updated_time', 'a_logo'], 'safe'],

        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Admin::find()->where(['is_del'=>0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->pagination=[
            'pageSize' => 25,
        ];
        $dataProvider->sort = [
            'defaultOrder' => ['a_id'=>SORT_ASC]
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if($this->a_name){
            $query->andfilterWhere(['like','a_name',$this->a_name]);
        }

        if($this->a_realname){
            $query->andfilterWhere(['like','a_realname',$this->a_realname]);
        }

        if($this->a_phone){
            $query->andfilterWhere(['like','a_phone',$this->a_phone]);
        }

        if($this->a_email){
            $query->andfilterWhere(['like','a_email',$this->a_email]);
        }

        //按申请时间查询
        if($this->created_time){
            $time_arr = explode(' to ',$this->created_time);
            $query->andfilterWhere(['between','created_time',$time_arr[0].' 00:00:00',$time_arr[1].' 23:59:59']);
        }

        $query->andFilterWhere([
            'a_id' => $this->a_id,
            'a_state' => $this->a_state,
            'last_login_time' => $this->last_login_time,
            'updated_time' => $this->updated_time,
        ]);

        $query
            ->andFilterWhere(['like', 'a_pwd', $this->a_pwd])
            ->andFilterWhere(['like', 'a_token', $this->a_token])
            ->andFilterWhere(['like', 'a_position', $this->a_position])
            ->andFilterWhere(['like', 'a_role', $this->a_role])
            ->andFilterWhere(['like', 'a_logo', $this->a_logo]);

        return $dataProvider;
    }
}
