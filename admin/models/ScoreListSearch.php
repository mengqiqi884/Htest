<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\CScoreList;

/**
 * ScoreListSearch represents the model behind the search form about `admin\models\CScoreList`.
 */
class ScoreListSearch extends CScoreList
{
   // public $user_name;
   // public $end_time;
    public function rules()
    {
        return [
            [['sl_id', 'sl_user_id', 'sl_score'], 'integer'],
            [['sl_rule', 'sl_act', 'created_time'], 'safe'],

            [['user_name','end_time'],'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CScoreList::find()
                ->joinWith('user');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->pagination=[
            'pageSize' => 14,
        ];
        $dataProvider->sort = [
            'defaultOrder' => ['created_time'=>SORT_DESC]
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        //按名称查找
        $query->andWhere('c_user.u_nickname like "%'.$this->user_name.'%"');


        //按上架时间查询
        if (!empty($this->created_time)) {
            $create_date = explode('to', str_replace(' ', '', $this->created_time));
            $query->andFilterWhere(['between', 'c_score_list.created_time', "$create_date[0] 00:00:00", "$create_date[1] 23:59:59"]);
        }

        $query->andFilterWhere([
            'sl_id' => $this->sl_id,
            'sl_user_id' => $this->sl_user_id,
            'sl_score' => $this->sl_score,
          //  'created_time' => $this->created_time,
        ]);

        $query->andFilterWhere(['like', 'sl_rule', $this->sl_rule])
            ->andFilterWhere(['like', 'sl_act', $this->sl_act]);

        return $dataProvider;
    }
}
