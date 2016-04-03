<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Vendoritemquestionguide;

/**
 * VendoritemquestionguideSearch represents the model behind the search form about `backend\models\Vendoritemquestionguide`.
 */
class VendoritemquestionguideSearch extends Vendoritemquestionguide
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['guide_id', 'question_id', 'guide_image_id', 'created_by', 'modified_by'], 'integer'],
            [['guide_caption', 'created_datetime', 'modified_datetime', 'trash'], 'safe'],
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
        $query = Vendoritemquestionguide::find();

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
            'guide_id' => $this->guide_id,
            'question_id' => $this->question_id,
            'guide_image_id' => $this->guide_image_id,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'guide_caption', $this->guide_caption])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
