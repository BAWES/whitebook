<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\Customer;

/**
 * SearchCustomer represents the model behind the search form about `common\models\Customer`.
 */
class CustomerSearch extends Customer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'created_by',], 'integer'],
            [['customer_name', 'customer_email', 'customer_password', 'customer_dateofbirth', 
            'customer_gender', 'customer_mobile', 'customer_last_login', 'customer_ip_address', 'modified_by',], 'safe'],
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
        $query = Customer::find()
        ->where(['!=', 'trash', 'Deleted'])
		->orderBy('customer_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['customer_id'=>SORT_DESC]]
        ]);
		 $dataProvider->sort->attributes['message_status'] = [
			   // The tables are the ones our relation are configured to
			   // in my case they are prefixed with "tbl_"
			   'desc' => ['whitebook_customer.message_status' => SORT_DESC],
		   ];
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'customer_id' => $this->customer_id,
            'customer_dateofbirth' => $this->customer_dateofbirth,
            'customer_last_login' => $this->customer_last_login,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'customer_email', $this->customer_email])
            ->andFilterWhere(['like', 'customer_password', $this->customer_password])
            ->andFilterWhere(['like', 'customer_gender', $this->customer_gender])
            ->andFilterWhere(['like', 'customer_mobile', $this->customer_mobile])
            ->andFilterWhere(['like', 'customer_ip_address', $this->customer_ip_address])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
