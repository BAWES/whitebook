<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VendorPayment;

/**
 * VendorPaymentSearch represents the model behind the search form about `common\models\VendorPayment`.
 */
class VendorPaymentSearch extends VendorPayment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_id', 'vendor_id'], 'integer'],
            [['amount'], 'number'],
            [['vendorName', 'description', 'created_datetime', 'modified_datetime'], 'safe'],
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
        $query = VendorPayment::find()
            ->orderBy('payment_id DESC');

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
            'payment_id' => $this->payment_id,
            'vendor_id' => $this->vendor_id,
            'amount' => $this->amount,
           // 'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'created_datetime', $this->created_datetime]);
        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
     * Total payable amount 
     *
     * @param array $params
     *
     * @return number $total 
     */
    public function total($params)
    {
        $query = VendorPayment::find()
            ->orderBy('payment_id DESC');

        // add conditions that should always apply here

        $this->load($params);

        if (!$this->validate()) {
            return $query->sum('amount');
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'payment_id' => $this->payment_id,
            'vendor_id' => $this->vendor_id,
            'amount' => $this->amount,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $query->sum('amount');
    }
}
