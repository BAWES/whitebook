<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Categoryads;

/**
 * CategoryadsSearch represents the model behind the search form about `common\models\Categoryads`.
 */
class CategoryadsSearch extends Categoryads
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['advert_id', 'sort', 'created_by', 'modified_by'], 'integer'],
            [['category_id', 'top_ad', 'bottom_ad', 'advert_code', 'status', 'created_datetime', 'modified_datetime'], 'safe'],
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
        $query = Categoryads::find();

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
            'advert_id' => $this->advert_id,
            'sort' => $this->sort,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'category_id', $this->category_id])
            ->andFilterWhere(['like', 'top_ad', $this->top_ad])
            ->andFilterWhere(['like', 'bottom_ad', $this->bottom_ad])
            ->andFilterWhere(['like', 'advert_code', $this->advert_code])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
