<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\CScoreLog;

/**
 * ScoreLogSearch represents the model behind the search form about `admin\models\CScoreLog`.
 */
class ScoreLogSearch extends CScoreLog
{
    public function rules()
    {
        return [
            [['sl_id', 'sl_user_id', 'sl_state', 'sl_good_id', 'sl_score', 'is_del'], 'integer'],
            [['sl_goodsname', 'sl_receivename', 'sl_receivephone', 'sl_receiveaddress', 'sl_operater', 'sl_logistics', 'sl_number', 'sl_remarks', 'created_time'], 'safe'],

            [['user_name'],'safe']
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CScoreLog::find()
            ->innerJoinWith('user')
            ->where(['c_score_log.is_del'=>0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

//        $dataProvider->pagination=[
//            'pageSize' => 20,
//        ];
        $dataProvider->sort = [
            'defaultOrder' => ['sl_id'=>SORT_ASC,'created_time'=>SORT_DESC ]
        ];


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        //按名称查找
        if(!empty($this->user_name)) {
            $query->andWhere('c_user.u_nickname like "%'.$this->user_name.'%"');
        }

        //按上架时间查询
        if(!empty($this->created_time)){
            $create_date = explode('to', str_replace(' ', '', $this->created_time));
            $query->andFilterWhere(['between', 'c_score_log.created_time', "$create_date[0] 00:00:00", "$create_date[1] 23:59:59"]);
        }

        //按兑换记录状态查询
        if($this->sl_state!=""){
            $query->andFilterWhere(['sl_state'=>$this->sl_state]);
        }

        $query->andFilterWhere([
            'sl_id' => $this->sl_id,
            'sl_good_id' => $this->sl_good_id,
            'sl_score' => $this->sl_score,
          //  'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'sl_goodsname', $this->sl_goodsname])
            ->andFilterWhere(['like', 'sl_receivename', $this->sl_receivename])
            ->andFilterWhere(['like', 'sl_receivephone', $this->sl_receivephone])
            ->andFilterWhere(['like', 'sl_receiveaddress', $this->sl_receiveaddress])
            ->andFilterWhere(['like', 'sl_operater', $this->sl_operater])
            ->andFilterWhere(['like', 'sl_logistics', $this->sl_logistics])
            ->andFilterWhere(['like', 'sl_number', $this->sl_number])
            ->andFilterWhere(['like', 'sl_remarks', $this->sl_remarks]);

        return $dataProvider;
    }
}
