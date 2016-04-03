<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Country;

/**
 * CountrySearch represents the model behind the search form about `backend\models\Country`.
 */
class CountrySearch extends Country
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'default'], 'integer'],
            [['country_name', 'iso_country_code', 'currency_code', 'currency_symbol', 'country_status'], 'safe'],
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
        $query = Country::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['country_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
             $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'country_id' => $this->country_id,
            'default' => $this->default,
        ]);

        $query->andFilterWhere(['like', 'country_name', $this->country_name])
            ->andFilterWhere(['like', 'iso_country_code', $this->iso_country_code])
            ->andFilterWhere(['like', 'currency_code', $this->currency_code])
            ->andFilterWhere(['like', 'currency_symbol', $this->currency_symbol])
            ->andFilterWhere(['like', 'country_status', $this->country_status]);

        return $dataProvider;
    }
}
