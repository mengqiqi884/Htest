<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\CBanner;

/**
 * BannerSearch represents the model behind the search form about `admin\models\CBanner`.
 */
class BannerSearch extends CBanner
{
    public function rules()
    {
        return [
            [['b_id', 'b_location', 'b_sortorder'], 'integer'],
            [['b_img', 'b_url', 'b_title', 'content', 'created_time'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CBanner::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'b_id' => $this->b_id,
            'b_location' => $this->b_location,
            'b_sortorder' => $this->b_sortorder,
            'created_time' => $this->created_time,
        ]);

        $query->andFilterWhere(['like', 'b_img', $this->b_img])
            ->andFilterWhere(['like', 'b_url', $this->b_url])
            ->andFilterWhere(['like', 'b_title', $this->b_title])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }

    /*查找特定条件下表中sortorder最大值*/
    public static function getMaxCarSortOrder($location){
        $model=Yii::$app->db->createCommand('select max(b_sortorder) as max_sort from c_banner where b_location='.$location)->queryOne();
        if($model['max_sort']){
            $max=$model['max_sort']+1;
        }else{
            $max=1;
        }
        return $max;
    }

    /*查找图片的总数*/
    public static function getBannerCount($location){
        return CBanner::find()->where(['b_location'=>$location])->count();
    }

    /*根据id查找位置*/
    public static function getLocationName($location){
        switch($location){
            case '1':$name="首页";break;
            case '2':$name="用车报告";break;
            case '3':$name="维修保养";break;
            default:$name="广告位置出错";break;
        }
        return $name;
    }
}
