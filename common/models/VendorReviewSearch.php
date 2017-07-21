<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VendorReview;

/**
 * VendorReviewSearch represents the model behind the search form about `common\models\VendorReview`.
 */
class VendorReviewSearch extends VendorReview
{
    public $customerName;
    public $vendorName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['review_id', 'customer_id', 'vendor_id', 'rating', 'approved'], 'integer'],
            [['vendorName', 'customerName', 'review', 'created_at', 'updated_at'], 'safe'],
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
        $query = VendorReview::find()
            ->where('approved = 0 OR approved IS NULL');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        /**
         * Setup your sorting attributes
         */
         $dataProvider->setSort([
            'attributes' => [
                'id',
                'customerName' => [
                    'asc' => ['customer_name' => SORT_ASC],
                    'desc' => ['customer_name' => SORT_DESC],
                    'label' => 'Customer Name',
                    'default' => SORT_ASC
                ],
                'vendorName' => [
                    'asc' => ['vendor_name' => SORT_ASC],
                    'desc' => ['vendor_name' => SORT_DESC],
                    'label' => 'Vendor Name'
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['customer']);
            $query->joinWith(['vendor']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'review_id' => $this->review_id,
            'customer_id' => $this->customer_id,
            'vendor_id' => $this->vendor_id,
            'rating' => $this->rating,
            'approved' => $this->approved,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'review', $this->review]);

        $query->joinWith(['customer' => function ($q) {
            $q->where('customer_name LIKE "%' . $this->customerName . '%"');
        }]);

        $query->joinWith(['vendor' => function ($q) {
            $q->where('vendor_name LIKE "%' . $this->vendorName . '%"');
        }]);

        return $dataProvider;
    }
}
