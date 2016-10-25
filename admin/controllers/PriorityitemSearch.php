<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PriorityitemSearch represents the model behind the search form about `common\models\PriorityItem`.
 */
class PriorityitemSearch extends PriorityItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
        //    [['item_id',], 'string'],
            [['priority_level', 'created_datetime', 'modified_datetime', 'trash'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PriorityItem::find()
        ->where(['!=', 'trash', 'Deleted'])
        ->orderBy('priority_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['priority_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'priority_id' => $this->priority_id,
            'item_id' => $this->item_id,
            'priority_start_date' => $this->priority_start_date,
            'priority_end_date' => $this->priority_end_date,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'priority_level', $this->priority_level])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
