<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PaymentGateway;

/**
 * PaymentGatewaySearch represents the model behind the search form about `common\models\PaymentGateway`.
 */
class PaymentGatewaySearch extends PaymentGateway
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gateway_id', 'order_status_id', 'under_testing', 'status'], 'integer'],
            [['name', 'name_ar', 'code'], 'safe'],
            [['percentage', 'fees'], 'number'],
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
        $query = PaymentGateway::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'gateway_id' => $this->gateway_id,
            'percentage' => $this->percentage,
            'fees' => $this->fees,
            'order_status_id' => $this->order_status_id,
            'under_testing' => $this->under_testing,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_ar', $this->name_ar])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
