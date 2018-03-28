<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use v1\models\CUser;

/**
 * UserSearch represents the model behind the search form about `v1\models\CUser`.
 */
class UserSearch extends CUser
{
    public function rules()
    {
        return [
            [['u_id', 'u_type', 'u_age', 'u_score', 'u_cars', 'u_forums', 'u_state', 'is_del'], 'integer'],
            [['u_phone', 'u_pwd', 'u_headImg', 'u_nickname', 'u_sex', 'u_token', 'u_register_id', 'created_time', 'updated_time'], 'safe'],

            [['l_type','l_input', 'end_time'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CUser::find()->where(['is_del'=>0,'u_type'=>1]); //u_type:1 用户

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

//        $dataProvider->pagination=[
//            'pageSize' => 15,
//        ];
//        $dataProvider->sort = [
//            'defaultOrder' => ['created_time'=>SORT_DESC]
//        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        switch($this->l_type){
            case 1: //按手机号查找
                $query->andfilterWhere(['like','u_phone',$this->l_input]);
                break;
            case 2: //按昵称查找
                $query->andfilterWhere(['like','u_nickname',$this->l_input]);
                break;

            default:break;
        }

        //按申请时间查询
        if($this->created_time){
            $query->andfilterWhere(['>=','created_time',$this->created_time.' 00:00:00']);
        }

        if($this->end_time){
            $query->andfilterWhere(['<=','created_time',$this->end_time.' 23:59:59']);
        }

        //按性别查找
        if($this->u_sex){
            $query->andfilterWhere([ 'u_sex' => $this->u_sex]);
        }

        $query->andFilterWhere([
            'u_id' => $this->u_id,
            'u_type' => $this->u_type,
            'u_age' => $this->u_age,
            'u_score' => $this->u_score,
            'u_cars' => $this->u_cars,
            'u_forums' => $this->u_forums,
            'u_state' => $this->u_state,
          //  'is_del' => $this->is_del,
          //  'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
        ]);

        $query->andFilterWhere(['like', 'u_phone', $this->u_phone])
            ->andFilterWhere(['like', 'u_pwd', $this->u_pwd])
            ->andFilterWhere(['like', 'u_headImg', $this->u_headImg])
            ->andFilterWhere(['like', 'u_nickname', $this->u_nickname])
           // ->andFilterWhere(['like', 'u_sex', $this->u_sex])
            ->andFilterWhere(['like', 'u_token', $this->u_token])
            ->andFilterWhere(['like', 'u_register_id', $this->u_register_id]);

        return $dataProvider;
    }
}
