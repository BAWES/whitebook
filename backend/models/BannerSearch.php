<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Banner;

/**
 * CountrySearch represents the model behind the search form about `backend\models\Country`.
 */
class BannerSearch extends Banner
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['banner_id'], 'integer'],
            [['banner_title',  'banner_status'], 'safe'],
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
        $query = Banner::find()
        ->where(['!=', 'trash', 'Deleted']);
         $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['banner_id'=>SORT_DESC]],
			'pagination' =>[
				'pageSize'=> 40,
				],
        ]);          

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
             //$query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'banner_id' => $this->banner_id,
        ]);

        $query->andFilterWhere(['like', 'banner_title', $this->banner_title])
            ->andFilterWhere(['like', 'banner_status', $this->banner_status]);

        return $dataProvider;
    }
}
