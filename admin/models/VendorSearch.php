<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\Vendor;

/**
 * VendorSearch represents the model behind the search form about `common\models\Vendor`.
 */
class VendorSearch extends Vendor
{
    public function rules()
    {
        return [
            [['vendor_id', 'image_id', 'created_by', 'modified_by'], 'integer'],
            [['vendor_name', 'vendor_return_policy', 'vendor_public_email', 'vendor_contact_name', 'vendor_contact_email', 'vendor_contact_number', 'vendor_emergency_contact_name', 'vendor_emergency_contact_email', 'vendor_emergency_contact_number', 'vendor_website', 'vendor_password', 'vendor_status','vendor_payable'], 'safe'],
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
        $query = Vendor::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['vendor_id'=>SORT_DESC]],
            'pagination' =>[
                'pageSize'=> 40,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'vendor_id' => $this->vendor_id,
            'vendor_payable' => $this->vendor_payable,
            'image_id' => $this->image_id,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'vendor_name', $this->vendor_name])
            ->andFilterWhere(['like', 'vendor_return_policy', $this->vendor_return_policy])
            ->andFilterWhere(['like', 'vendor_public_email', $this->vendor_public_email])
            ->andFilterWhere(['like', 'vendor_contact_name', $this->vendor_contact_name])
            ->andFilterWhere(['like', 'vendor_contact_email', $this->vendor_contact_email])
            ->andFilterWhere(['like', 'vendor_contact_number', $this->vendor_contact_number])
            ->andFilterWhere(['like', 'vendor_emergency_contact_name', $this->vendor_emergency_contact_name])
            ->andFilterWhere(['like', 'vendor_emergency_contact_email', $this->vendor_emergency_contact_email])
            ->andFilterWhere(['like', 'vendor_emergency_contact_number', $this->vendor_emergency_contact_number])
            ->andFilterWhere(['like', 'vendor_website', $this->vendor_website])
            ->andFilterWhere(['like', 'vendor_password', $this->vendor_password])
            ->andFilterWhere(['like', 'vendor_status', $this->vendor_status])
            ->andFilterWhere(['like', 'trash', $this->trash]);
        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied and payable > 0
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchPayable($params)
    {
        $query = Vendor::find()
            ->where('vendor_payable > 0');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['vendor_id'=>SORT_DESC]],
			'pagination' =>[
				'pageSize'=> 40,
			],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'vendor_id' => $this->vendor_id,
            'vendor_payable' => $this->vendor_payable,
            'image_id' => $this->image_id,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'vendor_name', $this->vendor_name])
            ->andFilterWhere(['like', 'vendor_return_policy', $this->vendor_return_policy])
            ->andFilterWhere(['like', 'vendor_public_email', $this->vendor_public_email])
            ->andFilterWhere(['like', 'vendor_contact_name', $this->vendor_contact_name])
            ->andFilterWhere(['like', 'vendor_contact_email', $this->vendor_contact_email])
            ->andFilterWhere(['like', 'vendor_contact_number', $this->vendor_contact_number])
            ->andFilterWhere(['like', 'vendor_emergency_contact_name', $this->vendor_emergency_contact_name])
            ->andFilterWhere(['like', 'vendor_emergency_contact_email', $this->vendor_emergency_contact_email])
            ->andFilterWhere(['like', 'vendor_emergency_contact_number', $this->vendor_emergency_contact_number])
            ->andFilterWhere(['like', 'vendor_website', $this->vendor_website])
            ->andFilterWhere(['like', 'vendor_password', $this->vendor_password])
            ->andFilterWhere(['like', 'vendor_status', $this->vendor_status])
            ->andFilterWhere(['like', 'trash', $this->trash]);
        return $dataProvider;
    }
}
