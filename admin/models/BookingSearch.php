<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Booking;

/**
 * BookingSearch represents the model behind the search form about `common\models\Booking`.
 */
class BookingSearch extends Booking
{
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Booking::find()
            ->where(['booking_status' => Booking::STATUS_PENDING]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['booking_id' =>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
            'booking_token' => $this->booking_token,
            'vendor_id' => $this->vendor_id,
            'customer_id' => $this->customer_id,
            'customer_mobile' => $this->customer_mobile,
            'ip_address' => $this->ip_address
        ]);

        $query->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'customer_lastname', $this->customer_lastname])
            ->andFilterWhere(['like', 'customer_email', $this->customer_email]);

        return $dataProvider;
    }

    public function searchAll($params)
    {
        $query = Booking::find()
            ->where(['booking_status' => [Booking::STATUS_ACCEPTED,Booking::STATUS_EXPIRED,Booking::STATUS_REJECTED]]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['booking_id' =>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
            'booking_token' => $this->booking_token,
            'vendor_id' => $this->vendor_id,
            'customer_id' => $this->customer_id,
            'customer_mobile' => $this->customer_mobile,
            'ip_address' => $this->ip_address
        ]);

        $query->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'customer_lastname', $this->customer_lastname])
            ->andFilterWhere(['like', 'customer_email', $this->customer_email]);

        return $dataProvider;
    }
}
