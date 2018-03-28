<?php

namespace mdm\admin\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use mdm\admin\models\Menu as MenuModel;

/**
 * Menu represents the model behind the search form about [[\mdm\admin\models\Menu]].
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Menu extends MenuModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mam_id', 'mam_parentid', 'mam_order'], 'integer'],
            [['mam_name', 'mam_route', 'parent_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Searching menu
     * @param  array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params)
    {
        $query = MenuModel::find()
            ->from(MenuModel::tableName() . ' t')
            ->joinWith(['menuParent' => function ($q) {
            $q->from(MenuModel::tableName() . ' parent');
        }]);
//        var_dump($query->asArray()->all());
//        exit;
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $sort = $dataProvider->getSort();
        $sort->attributes['menuParent.mam_name'] = [
            'asc' => ['parent.mam_order' => SORT_ASC],
            'desc' => ['parent.mam_order' => SORT_DESC],
            'label' => 'mam_parentid',
        ];
        $sort->attributes['mam_order'] = [
            'asc' => ['parent.mam_order' => SORT_ASC, 't.mam_order' => SORT_ASC],
            'desc' => ['parent.mam_order' => SORT_DESC, 't.mam_order' => SORT_DESC],
            'label' => 'mam_order',
        ];
        $sort->defaultOrder = ['menuParent.mam_name' => SORT_ASC];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            't.mam_id' => $this->mam_id,
            't.mam_parentid' => $this->mam_parentid,
        ]);

        $query->andFilterWhere(['like', 'lower(t.mam_name)', strtolower($this->mam_name)])
            ->andFilterWhere(['like', 't.mam_route', $this->mam_route])
            ->andFilterWhere(['like', 'lower(parent.mam_name)', strtolower($this->parent_name)]);

        return $dataProvider;
    }
}
