<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Location;

/**
 * LocationSearch represents the model behind the search form about `common\models\Location`.
 */
class LocationSearch extends Location
{
    public $cityName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'country_id', 'city_id'], 'integer'],
            [['location', 'location_ar', 'cityName'], 'safe'],
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
        $query = Location::find();

        $query->joinWith(['city' => function ($q) {
            $q->where('whitebook_city.city_name LIKE "%' . $this->cityName . '%"');
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'cityName' => [
                    'asc' => ['whitebook_city.city_name' => SORT_ASC],
                    'desc' => ['whitebook_city.city_name' => SORT_DESC],
                    'label' => 'Governorate',
                    'default' => SORT_ASC
                ]
            ]
        ]);
     
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['city']);
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'id' => $this->id,
            'country_id' => $this->country_id,
            'city_id' => $this->city_id,
        ]);

        $query->andFilterWhere(['like', 'location', $this->location]);
        $query->andFilterWhere(['like', 'location_ar', $this->location_ar]);

        return $dataProvider;
    }
}
