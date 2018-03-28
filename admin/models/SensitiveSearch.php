<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\CSensitive;

/**
 * SensitiveSearch represents the model behind the search form about `admin\models\CSensitive`.
 */
class SensitiveSearch extends CSensitive
{
    public function rules()
    {
        return [
            [['s_id', 'is_del'], 'integer'],
            [['s_name', 'created_time'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CSensitive::find()->where(['is_del'=>0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->pagination=[
            'pageSize' => 20,
        ];
        $dataProvider->sort = [
            'defaultOrder' => ['s_id'=>SORT_ASC,'created_time'=>SORT_DESC ]
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            's_id' => $this->s_id,
            'created_time' => $this->created_time,
//            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 's_name', $this->s_name]);

        return $dataProvider;
    }
}
