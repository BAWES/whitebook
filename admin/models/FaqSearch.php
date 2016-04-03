<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Faq;

/**
 * FaqSearch represents the model behind the search form about `backend\models\Faq`.
 */
class FaqSearch extends Faq
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['faq_id', 'created_by', 'modified_by'], 'integer'],
            [['question', 'answer', 'faq_status', 'created_datetime', 'modified_datetime', 'trash'], 'safe'],
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
		$query = Faq::find()
        ->where(['!=', 'trash', 'Deleted'])
		->orderBy('faq_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['faq_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
        ]);

        $query->andFilterWhere(['like', 'question', $this->question])
            ->andFilterWhere(['like', 'answer', $this->answer]);
        return $dataProvider;
    }
}
