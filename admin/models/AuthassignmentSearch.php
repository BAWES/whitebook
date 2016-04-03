<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AuthassignmentSearch represents the model behind the search form about `backend\models\Authassignment`.
 */
class AuthassignmentSearch extends Authassignment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id', 'created_datetime', 'modified_datetime'], 'safe'],
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
        $query = Authassignment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
        ]);

        $query->andFilterWhere(['like', 'item_name', $this->item_name])
            ->andFilterWhere(['like', 'user_id', $this->user_id]);

        return $dataProvider;
    }
}
