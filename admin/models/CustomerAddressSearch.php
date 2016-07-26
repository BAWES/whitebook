<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CustomerAddress;
use common\models\City;

/**
 * CustomerAddressSearch represents the model behind the search form about `app\models\CustomerAddress`.
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
            [['customer', 'type', 'city', 'address_data', 'address_archived', 'created_datetime', 'modified_datetime', 'trash'], 'safe'],
        ];
    }

    public function getType()
    {
        return $this->hasOne(Addresstype::className(), ['type_id' => 'address_type_id']);
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }
     
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['city_id' => 'city_id']);
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
        $query = CustomerAddress::find()
        //->select('ct.city_name, c.customer_name, c.customer_last_name, whitebook_customer_address.*')
        //->leftJoin('order', '`order`.`customer_id` = `customer`.`id`')
        //->where(['c.customer_id' => 't.customer_id'])
        //->where(['at.type_id' => 't.address_type_id'])// at.type_name,
        //->where(['ct.city_id' => 't.city_id'])
        //->joinWith(['customer c'], true, 'INNER JOIN')
       // ->joinWith(['address_type at'], true, 'INNER JOIN')
       // ->joinWith(['city ct'], true, 'INNER JOIN');
        ->joinWith(['city']);

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

        $query->andFilterWhere(['like', 'whitebook_city.city_name', $this->city['city_name']]);
        //->andFilterWhere(['like', 'tbl_country.name', $this->country]);

        return $dataProvider;
    }
}
