<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AddressQuestion;

/**
 * AddressQuestionSearch represents the model behind the search form about `backend\models\AddressQuestion`.
 */
class AddressQuestionSearch extends AddressQuestion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ques_id', 'address_type_id', 'created_by', 'modified_by'], 'integer'],            
            [['question', 'status', 'modified_datetime', 'trash'], 'safe'],
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
        $query = AddressQuestion::find();
        
		$query = Addressquestion::find()
        ->where(['!=', 'trash', 'Deleted'])
		->orderBy('address_type_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['address_type_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ques_id' => $this->ques_id,
            'address_type_id' => $this->address_type_id,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'question', $this->question])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
