<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\CForums;

/**
 * ForumsSearch represents the model behind the search form about `admin\models\CForums`.
 */
class ForumsSearch extends CForums
{
    const PAGE_SIZE = 15;

    public function rules()
    {
        return [
            [['f_id', 'f_fup', 'f_user_id', 'f_views', 'f_replies', 'f_is_top', 'f_is_first_top', 'f_state', 'is_del'], 'integer'],
            [['f_user_nickname', 'f_title', 'f_content', 'f_pic', 'f_car_cycle', 'f_car_miles', 'f_car_describle', 'created_time', 'updated_time'], 'safe'],

            [['l_type1', 'l_input', 'end_time'], 'safe']
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CForums::find()
            ->innerJoinWith(['user'])
            ->where(['c_forums.is_del' => 0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->pagination = [
            'pageSize' => self::PAGE_SIZE,
        ];
        $dataProvider->sort = [
            'defaultOrder' => ['created_time' => SORT_DESC]
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        //按标题/浏览量查询
        if ($this->l_input) {
            switch ($this->l_type1) {
                case 1:  //标题
                    $query->andfilterWhere(['like', 'c_forums.f_title', rawurlencode($this->l_input)]);
                    break;
                case 2: //浏览量
                    $query->andfilterWhere(['f_views' => $this->l_input]);
                    break;
            }
        }
        //是否置顶
        if ($this->f_is_top) {
            $query->andfilterWhere(['f_is_top' => $this->f_is_top]);
        } elseif($this->f_is_top==0) {
            $query->andfilterWhere(['f_is_top' => $this->f_is_top]);
        }

        //是否禁用
        if ($this->f_state) {
            $query->andfilterWhere(['f_state' => $this->f_state]);
        }
        //按上架时间查询
        if ($this->created_time) {
            $query->andfilterWhere(['>=', 'c_forums.created_time', $this->created_time . ' 00:00:00']);
        }

        if ($this->end_time) {
            $query->andfilterWhere(['<=', 'c_forums.created_time', $this->end_time . ' 23:59:59']);
        }
        //按板块查询
        if ($this->f_fup) {
            $query->andfilterWhere(['f_fup' => $this->f_fup]);
        }


        $query->andFilterWhere([
            'f_id' => $this->f_id,

            'f_user_id' => $this->f_user_id,
            'f_views' => $this->f_views,
            'f_replies' => $this->f_replies,
            //  'f_is_top' => $this->f_is_top,

            //
            //  'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
            // 'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'f_user_nickname', $this->f_user_nickname])
            ->andFilterWhere(['like', 'f_title', $this->f_title])
            ->andFilterWhere(['like', 'f_content', $this->f_content])
            ->andFilterWhere(['like', 'f_pic', $this->f_pic])
            ->andFilterWhere(['like', 'f_car_cycle', $this->f_car_cycle])
            ->andFilterWhere(['like', 'f_car_miles', $this->f_car_miles])
            ->andFilterWhere(['like', 'f_car_describle', $this->f_car_describle]);

        return $dataProvider;
    }
}
