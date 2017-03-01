<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VendorAccountPayable;

/**
 * VendorAccountPayableSearch represents the model behind the search form about `common\models\VendorAccountPayable`.
 */
class VendorAccountPayableSearch extends VendorAccountPayable
{
    public $vendorName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payable_id', 'vendor_id'], 'integer'],
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
        $query = VendorAccountPayable::find()
            ->orderBy('payable_id DESC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'vendorName' => [
                    'asc' => ['vendor_name' => SORT_ASC],
                    'desc' => ['vendor_name' => SORT_DESC],
                    'label' => 'Vendor',
                    'default' => SORT_ASC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['vendor']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'payable_id' => $this->payable_id,
            'vendor_id' => $this->vendor_id,
            'amount' => $this->amount,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        $query->joinWith(['vendor' => function ($q) {
            $q->where('whitebook_vendor.vendor_name LIKE "%' . $this->vendorName . '%"');
        }]);

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
        $query = VendorAccountPayable::find()
            ->orderBy('payable_id DESC');

        // add conditions that should always apply here

        $this->load($params);

        if (!$this->validate()) {
            $query->joinWith(['vendor']);
            return $q->sum('amount');
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'payable_id' => $this->payable_id,
            'vendor_id' => $this->vendor_id,
            'amount' => $this->amount,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        $query->joinWith(['vendor' => function ($q) {
            $q->where('whitebook_vendor.vendor_name LIKE "%' . $this->vendorName . '%"');
        }]);

        return $query->sum('amount');
    }
}
