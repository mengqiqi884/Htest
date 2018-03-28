<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\CProducts;

/**
 * ProductsSearch represents the model behind the search form about `admin\models\CProducts`.
 */
class ProductsSearch extends CProducts
{
    public function rules()
    {
        return [
            [['p_id', 'p_sortorder', 'is_all', 'is_del'], 'integer'],
            [['p_name', 'p_content', 'created_time'], 'safe'],
            [['p_month12', 'p_month24', 'p_month36'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CProducts::find()->where(['is_del'=>0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->pagination=[
            'pageSize' => 20,
        ];
        $dataProvider->sort = [
            'defaultOrder' => ['p_id'=>SORT_ASC]
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'p_id' => $this->p_id,
            'p_month12' => $this->p_month12,
            'p_month24' => $this->p_month24,
            'p_month36' => $this->p_month36,
            'p_sortorder' => $this->p_sortorder,
            'is_all' => $this->is_all,
            'created_time' => $this->created_time,
        ]);

        $query->andFilterWhere(['like', 'p_name', $this->p_name])
            ->andFilterWhere(['like', 'p_content', $this->p_content]);

        return $dataProvider;
    }
}
