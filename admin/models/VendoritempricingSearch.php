<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * VendoritempricingSearch represents the model behind the search form about `admin\models\Vendoritempricing`.
 */
class VendoritempricingSearch extends Vendoritempricing
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pricing_id', 'item_id', 'range_from', 'range_to', 'pricing_quantity_ordered', 'pricing_price_per_unit', 'created_by', 'modified_by'], 'integer'],
            [['created_datetime', 'modified_datetime', 'trash'], 'safe'],
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
        $query = Vendoritempricing::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'pricing_id' => $this->pricing_id,
            'item_id' => $this->item_id,
            'range_from' => $this->range_from,
            'range_to' => $this->range_to,
            'pricing_quantity_ordered' => $this->pricing_quantity_ordered,
            'pricing_price_per_unit' => $this->pricing_price_per_unit,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
