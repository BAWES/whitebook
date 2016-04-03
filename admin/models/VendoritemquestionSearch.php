<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Vendoritemquestion;

/**
 * VendoritemquestionSearch represents the model behind the search form about `backend\models\Vendoritemquestion`.
 */
class VendoritemquestionSearch extends Vendoritemquestion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'item_id', 'answer_id', 'question_max_characters', 'question_sort_order', 'created_by', 'modified_by'], 'integer'],
            [['question_text', 'question_answer_type', 'question_archived', 'created_datetime', 'modified_datetime', 'trash'], 'safe'],
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
        $query = Vendoritemquestion::find();

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
            'question_id' => $this->question_id,
            'item_id' => $this->item_id,
            'answer_id' => $this->answer_id,
            'question_max_characters' => $this->question_max_characters,
            'question_sort_order' => $this->question_sort_order,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'question_text', $this->question_text])
            ->andFilterWhere(['like', 'question_answer_type', $this->question_answer_type])
            ->andFilterWhere(['like', 'question_archived', $this->question_archived])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
