<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Vendor;

/**
 * VendorSearch represents the model behind the search form about `common\models\Vendor`.
 */
class VendorSearch extends Vendor
{
    /**
     * @inheritdoc
     */
     public $package_name;
    public function rules()
    {
        return [
            [['vendor_id', 'package_id', 'image_id', 'created_by', 'modified_by'], 'integer'],
            [['vendor_name', 'vendor_brief', 'vendor_return_policy', 'vendor_public_email', 'vendor_public_phone', 'vendor_working_hours', 'vendor_contact_name', 'vendor_contact_email', 'vendor_contact_number', 'vendor_emergency_contact_name', 'vendor_emergency_contact_email', 'vendor_emergency_contact_number', 'vendor_website', 'package_end_date', 'package_start_date', 'vendor_password', 'vendor_status','package_name',], 'safe'],
            [['vendor_delivery_charge'], 'number'],
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
            'package_id' => $this->package_id,
            'image_id' => $this->image_id,
            'package_end_date' => $this->package_end_date,
            'package_start_date' => $this->package_start_date,
            'vendor_delivery_charge' => $this->vendor_delivery_charge,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'vendor_name', $this->vendor_name])
            ->andFilterWhere(['like', 'vendor_brief', $this->vendor_brief])
            ->andFilterWhere(['like', 'vendor_return_policy', $this->vendor_return_policy])
            ->andFilterWhere(['like', 'vendor_public_email', $this->vendor_public_email])
            ->andFilterWhere(['like', 'vendor_public_phone', $this->vendor_public_phone])
            ->andFilterWhere(['like', 'vendor_working_hours', $this->vendor_working_hours])
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
