<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * EventsSearch represents the model behind the search form about `backend\models\Events`.
 */
class EventsSearch extends Events
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'customer_id'], 'integer'],
            [['event_name', 'event_date', 'event_type', 'created_date'], 'safe'],
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
        $query = Events::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'event_id' => $this->event_id,
            'customer_id' => $this->customer_id,
            'event_date' => $this->event_date,
            'created_date' => $this->created_date,
        ]);

        $query->andFilterWhere(['like', 'event_name', $this->event_name])
            ->andFilterWhere(['like', 'event_type', $this->event_type]);

        return $dataProvider;
    }
}
