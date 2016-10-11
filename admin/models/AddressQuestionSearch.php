<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\AddressQuestion;

/**
 * AddressQuestionSearch represents the model behind the search form about `common\models\AddressQuestion`.
 */
class AddressQuestionSearch extends AddressQuestion
{
    public $typeName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ques_id', 'address_type_id', 'created_by', 'modified_by'], 'integer'],
            [['question', 'typeName', 'question_ar', 'status', 'modified_datetime', 'trash'], 'safe'],
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
		$query = Addressquestion::find()
            ->where(['!=', 'whitebook_address_question.trash', 'Deleted'])
    		->orderBy('address_type_id');

        $query->joinWith(['type' => function ($q) {
            $q->where('whitebook_address_type.type_name LIKE "%' . $this->typeName . '%"');
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['address_type_id'=>SORT_DESC]]
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'addresstypeName' => [
                    'asc' => ['whitebook_address_type.type_name' => SORT_ASC],
                    'desc' => ['whitebook_address_type.type_name' => SORT_DESC],
                    'label' => 'Address type',
                    'default' => SORT_ASC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['type']);
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
            ->andFilterWhere(['like', 'question_ar', $this->question])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
