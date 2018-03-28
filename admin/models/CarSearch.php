<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\CCar;

/**
 * CarSearch represents the model behind the search form about `admin\models\CCar`.
 */
class CarSearch extends CCar
{
    public function rules()
    {
        return [
            [['c_code', 'c_title', 'c_parent', 'c_logo', 'c_engine', 'c_volume'], 'safe'],
            [['c_level', 'c_type', 'c_price', 'c_sortorder'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CCar::find()->where(['c_level'=>1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->pagination=[
         //   'pageSize' => 20,
        ];
        $dataProvider->sort = [
            'defaultOrder' => ['c_code'=>SORT_ASC]
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'c_level' => $this->c_level,
            'c_type' => $this->c_type,
            'c_price' => $this->c_price,
            'c_sortorder' => $this->c_sortorder,
        ]);

        $query->andFilterWhere(['like', 'c_code', $this->c_code])
            ->andFilterWhere(['like', 'c_title', $this->c_title])
            ->andFilterWhere(['like', 'c_parent', $this->c_parent])
            ->andFilterWhere(['like', 'c_logo', $this->c_logo])
            ->andFilterWhere(['like', 'c_engine', $this->c_engine])
            ->andFilterWhere(['like', 'c_volume', $this->c_volume]);

        return $dataProvider;
    }

    //获取该code下的子树（由 品牌得到车系 / 车系得到车型 ）
    public static function getCarTree($pid){
        $data_son_arr=array();
        $model=CCar::find()->where(['c_parent'=>$pid])->orderBy(['c_sortorder'=>SORT_ASC])->asArray()->all();
        if($model){
            foreach($model as $key=>$value){
                $data_son_arr[]=[
                    "code"=>$value['c_code'],
                    "name"=>$value['c_title'],
                    "level"=>$value['c_level'],
                    "sortorder"=>$value['c_sortorder']
                ];
            }
        }
        return $data_son_arr;
    }

    public static function GetSonTree($pid){
        $all=array();
        $data=\admin\models\CarSearch::getCarTree($pid);
        if($data){
            foreach($data as $k=>$v){
                $all[$v['code']]=$v['name'];
            }
        }
        return $all;
    }

    /*查找特定条件下表中code最大值*/
    public static function getMaxCarCode($level,$parent){
        $model=Yii::$app->db->createCommand('select max(c_code) as max_code from c_car where c_level="'.$level.'" and c_parent="'.$parent.'"')->queryOne();
        $max='';
        if($model['max_code']){
            $max=(string)(intval($model['max_code'])+1);
        }else{
            switch($level){
                case 1:$max='100';break;  //品牌初始值
                case 2:$max=$parent.'100';break; //车系初始值
                case 3:$max=$parent.'100';break; //车型初始值
            }
        }
        return $max;
    }

    /*查找特定条件下表中sortorder最大值*/
    public static function getMaxCarSortOrder($level,$parent){
        $model=Yii::$app->db->createCommand('select max(c_sortorder) as max_sort from c_car where c_level="'.$level.'" and c_parent="'.$parent.'"')->queryOne();
        if($model['max_sort']){
            $max=intval($model['max_sort'])+1;
        }else{
            $max=1;
        }
        return $max;
    }

    /*根据车辆code查找车辆名称*/
    public static function getCarTitle($c_code){
        $model=CCar::find()->where(['c_code'=>$c_code])->one();
        return empty($model)?'':$model->c_title;
    }

    //获取用户车辆的品牌、车系及车型
    public static function getUserCar($car){
        $arr=explode('-',$car);
        $car_name='';
        if($arr){
            foreach($arr as $key){
                if($key){
                    $car_name.=\admin\models\CarSearch::getCarTitle($key).'/';
                }
            }
            if (substr($car_name, -1) == '/') {
                $car_name = substr($car_name, 0, -1);
            }
        }
        return $car_name;
    }
}
