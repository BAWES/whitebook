<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ItemTypeSearch represents the model behind the search form about `common\models\ItemType`.
 */
class EventsSearch extends Events
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id','created_by', 'modified_by'], 'integer'],
            [['event_name', 'event_type', 'slug','created_datetime','modified_datetime','event_date','customer_id'], 'safe'],
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
			'sort'=> ['defaultOrder' => ['event_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'event_id' => $this->event_id,
            'event_date' => $this->event_date,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'event_name', $this->event_name])
            ->andFilterWhere(['like', 'event_type', $this->event_type])
            ->andFilterWhere(['like', 'slug', $this->slug]);

         $query->joinWith(['customer' => function ($q) {
                 $q->andWhere('{{%customer}}.customer_name LIKE "%' . $this->customer_id . '%" ' .
                     'OR {{%customer}}.customer_last_name LIKE "%' . $this->customer_id . '%" '.
                     'OR CONCAT({{%customer}}.customer_name, " ", {{%customer}}.customer_last_name) LIKE "%' . $this->customer_id . '%"');
         }]);


        return $dataProvider;
    }
}
