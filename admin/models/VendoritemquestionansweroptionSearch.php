<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Vendoritemquestionansweroption;

/**
 * VendoritemquestionansweroptionSearch represents the model behind the search form about `backend\models\Vendoritemquestionansweroption`.
 */
class VendoritemquestionansweroptionSearch extends Vendoritemquestionansweroption
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['answer_id', 'question_id', 'answer_background_image_id', 'created_by', 'modified_by'], 'integer'],
            [['answer_text', 'answer_background_color', 'answer_archived', 'created_datetime', 'modified_datetime', 'trash'], 'safe'],
            [['answer_price_added'], 'number'],
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
        $query = Vendoritemquestionansweroption::find();

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
            'answer_id' => $this->answer_id,
            'question_id' => $this->question_id,
            'answer_background_image_id' => $this->answer_background_image_id,
            'answer_price_added' => $this->answer_price_added,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'answer_text', $this->answer_text])
            ->andFilterWhere(['like', 'answer_background_color', $this->answer_background_color])
            ->andFilterWhere(['like', 'answer_archived', $this->answer_archived])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
