<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AdvertcategorySearch represents the model behind the search form about `backend\models\Advertcategory`.
 */
class AdvertcategorySearch extends Advertcategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['advert_id', 'category_id'], 'integer'],
            [['advert_position', 'advert_code'], 'safe'],
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
        $query = Advertcategory::find()
        ->where(['!=', 'advert_position', 'bottom']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['advert_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'advert_id' => $this->advert_id,
            'category_id' => $this->category_id,
        ]);
        $query->andFilterWhere(['like', 'advert_position', $this->advert_position])
            ->andFilterWhere(['like', 'advert_code', $this->advert_code]);

        return $dataProvider;
    }

        public function bottomsearch($params)
    {
        $query = Advertcategory::find()
        ->where(['!=', 'advert_position', 'top']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['advert_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'advert_id' => $this->advert_id,
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'advert_position', $this->advert_position])
            ->andFilterWhere(['like', 'advert_code', $this->advert_code]);

        return $dataProvider;
    }
}
