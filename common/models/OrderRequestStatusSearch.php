<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrderRequestStatus;

/**
 * OrderRequestStatusSearch represents the model behind the search form about `common\models\OrderRequestStatus`.
 */
class OrderRequestStatusSearch extends OrderRequestStatus
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_id', 'order_id','vendor_id'], 'integer'],
            [['request_status', 'request_note', 'created_datetime', 'modified_datetime'], 'safe'],
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
        $query = OrderRequestStatus::find();

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
            'request_id' => $this->request_id,
            'order_id' => $this->order_id,
            'vendor_id' => $this->vendor_id,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);
        $query->andFilterWhere(['like', 'request_status', $this->request_status])
            ->andFilterWhere(['like', 'request_note', $this->request_note]);

        return $dataProvider;
    }
}