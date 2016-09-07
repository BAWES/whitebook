<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Suborder;

/**
 * SubOrderSearch represents the model behind the search form about `common\models\SubOrder`.
 */
class SubOrderSearch extends Suborder
{
    public $statusName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['suborder_id', 'order_id', 'vendor_id', 'status_id', 'created_by', 'modified_by'], 'integer'],
            [['suborder_delivery_charge', 'suborder_total_without_delivery', 'suborder_total_with_delivery', 'suborder_commission_percentage', 'suborder_commission_total', 'suborder_vendor_total'], 'number'],
            [['statusName', 'created_datetime', 'modified_datetime', 'trash'], 'safe'],
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
        $query = SubOrder::find();

        // add conditions that should always apply here

        $query->where('whitebook_suborder.trash="default"');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'statusName' => [
                    'asc' => ['whitebook_order_status.name' => SORT_ASC, 'whitebook_order_status.name' => SORT_ASC],
                    'desc' => ['whitebook_order_status.name' => SORT_DESC, 'whitebook_order_status.name' => SORT_DESC],
                    'label' => 'Status',
                    'default' => SORT_ASC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['status']);
            $query->joinWith(['order']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'suborder_id' => $this->suborder_id,
            'order_id' => $this->order_id,
            'vendor_id' => $this->vendor_id,
            'status_id' => $this->status_id,
            'suborder_delivery_charge' => $this->suborder_delivery_charge,
            'suborder_total_without_delivery' => $this->suborder_total_without_delivery,
            'suborder_total_with_delivery' => $this->suborder_total_with_delivery,
            'suborder_commission_percentage' => $this->suborder_commission_percentage,
            'suborder_commission_total' => $this->suborder_commission_total,
            'suborder_vendor_total' => $this->suborder_vendor_total,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'trash', $this->trash]);

        $query->joinWith(['status' => function ($q) {
            $q->where('whitebook_order_status.name LIKE "%' . $this->statusName . '%"');
        }]);

        $query->joinWith(['order' => function ($q) {
            $q->where('whitebook_order.order_transaction_id != "" AND whitebook_order.trash="default"');
        }]);

        return $dataProvider;
    }
}
