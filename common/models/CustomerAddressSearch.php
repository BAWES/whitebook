<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CustomerAddress;

/**
 * CustomerAddressSearch represents the model behind the search form about `backend\models\CustomerAddress`.
 */
class CustomerAddressSearch extends CustomerAddress
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address_id', 'customer_id', 'address_type_id', 'country_id', 'city_id', 'area_id', 'created_by', 'modified_by'], 'integer'],
            [['address_data', 'address_archived', 'created_datetime', 'modified_datetime', 'trash'], 'safe'],
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
        $query = CustomerAddress::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'address_id' => $this->address_id,
            'customer_id' => $this->customer_id,
            'address_type_id' => $this->address_type_id,
            'country_id' => $this->country_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'address_data', $this->address_data])
            ->andFilterWhere(['like', 'address_archived', $this->address_archived])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
