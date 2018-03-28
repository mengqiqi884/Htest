<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\CAgent;

/**
 * AgentSearch represents the model behind the search form about `admin\models\CAgent`.
 */
class AgentSearch extends CAgent
{
    const PAGE_SIZE = 1;

    public function rules()
    {
        return [
            [['a_id', 'a_state', 'is_del'], 'integer'],
            [['a_account', 'a_pwd', 'a_name', 'a_areacode', 'a_brand', 'a_address', 'a_concat', 'a_phone', 'a_email', 'created_time', 'updated_time'], 'safe'],

        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CAgent::find()
                ->joinWith(['car'])
                ->where(['c_agent.is_del'=>0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->pagination=[
            'pageSize' => self::PAGE_SIZE,
        ];
        $dataProvider->sort = [
            'defaultOrder' => ['a_id'=>SORT_ASC]
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        switch($this->l_type){
            case 1: //按用户名查找
                $query->andfilterWhere(['like','c_agent.a_account',$this->l_input]);
                break;
            case 2: //按姓名查找
                $query->andfilterWhere(['like','c_agent.a_name',$this->l_input]);
                break;
            case 3: //按手机号查找
                $query->andfilterWhere(['like','c_agent.a_phone',$this->l_input]);
                break;
            case 4: //按邮箱查找
                $query->andFilterWhere(['like', 'c_agent.a_email', $this->l_input]);
                break;
            default:break;
        }
        //按照状态查找
        if($this->a_state){
            $query->andWhere(['a_state'=>$this->a_state]);
        }
        //按申请时间查询
        if($this->created_time){
            $query->andfilterWhere(['>=','c_agent.created_time',$this->created_time.' 00:00:00']);
        }

        if($this->end_time){
            $query->andfilterWhere(['<=','c_agent.created_time',$this->end_time.' 23:59:59']);
        }

        //按品牌查找
        if($this->a_brand){
            $query->andWhere(['a_brand'=>$this->a_brand]);
        }

        $query->andFilterWhere([
            'a_id' => $this->a_id,
           // 'a_state' => $this->a_state,
           // 'created_time' => $this->created_time,
          //  'updated_time' => $this->updated_time,
           // 'is_del' => $this->is_del,
        ]);

        $query
            //->andFilterWhere(['like', 'a_account', $this->a_account])
            ->andFilterWhere(['like', 'a_pwd', $this->a_pwd])
            //->andFilterWhere(['like', 'a_name', $this->a_name])
            ->andFilterWhere(['like', 'a_areacode', $this->a_areacode])
            //->andFilterWhere(['like', 'a_brand', $this->a_brand])
            ->andFilterWhere(['like', 'a_address', $this->a_address]);
           // ->andFilterWhere(['like', 'a_concat', $this->a_concat])
           // ->andFilterWhere(['like', 'a_phone', $this->a_phone])
            //->andFilterWhere(['like', 'a_email', $this->a_email]);

        return $dataProvider;
    }
}
