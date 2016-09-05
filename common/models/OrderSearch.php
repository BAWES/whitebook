<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form about `common\models\Order`.
 */
class OrderSearch extends Order
{
    public $customerName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'customer_id', 'created_by'], 'integer'],
            [['order_total_delivery_charge', 'order_total_without_delivery', 'order_total_with_delivery', 'order_gateway_percentage', 'order_gateway_total'], 'number'],
            [['customerName', 'order_payment_method', 'order_transaction_id', 'order_datetime', 'order_ip_address', 'modified_by', 'created_datetime', 'modified_datetime', 'trash'], 'safe'],
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
        $query = Order::find();

        // add conditions that should always apply here
        $query->where('order_transaction_id != "" AND whitebook_order.trash="default"');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'customerName' => [
                    'asc' => ['customer_name' => SORT_ASC, 'customer_last_name' => SORT_ASC],
                    'desc' => ['customer_name' => SORT_DESC, 'customer_last_name' => SORT_DESC],
                    'label' => 'Customer',
                    'default' => SORT_ASC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['customer']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'order_id' => $this->order_id,
            'customer_id' => $this->customer_id,
            'order_total_delivery_charge' => $this->order_total_delivery_charge,
            'order_total_without_delivery' => $this->order_total_without_delivery,
            'order_total_with_delivery' => $this->order_total_with_delivery,
            'order_gateway_percentage' => $this->order_gateway_percentage,
            'order_gateway_total' => $this->order_gateway_total,
            'order_datetime' => $this->order_datetime,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'order_payment_method', $this->order_payment_method])
            ->andFilterWhere(['like', 'order_transaction_id', $this->order_transaction_id])
            ->andFilterWhere(['like', 'order_ip_address', $this->order_ip_address])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        $query->joinWith(['customer' => function ($q) {
            $q->where('whitebook_customer.customer_name LIKE "%' . $this->customerName . '%" OR whitebook_customer.customer_last_name LIKE "%' . $this->customerName . '%" ');
        }]);

        return $dataProvider;
    }
}
