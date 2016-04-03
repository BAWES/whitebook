<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CitySearch represents the model behind the search form about `backend\models\City`.
 */
class CitySearch extends City
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id',], 'integer'],
            [['city_name', 'status'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = City::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['city_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'city_id' => $this->city_id,
            'country_id' => $this->country_id,
        ]);

        $query->andFilterWhere(['like', 'city_name', $this->city_name])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
