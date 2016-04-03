<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SocialinfoSearch represents the model behind the search form about `admin\models\Socialinfo`.
 */
class SocialinfoSearch extends Socialinfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_social_id', 'store_id'], 'integer'],
            [['store_facebook_share', 'store_twitter_share', 'store_google_share', 'store_linkedin_share', 'google_analytics', 'live_script'], 'safe'],
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
        $query = Socialinfo::find();

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
            'store_social_id' => $this->store_social_id,
            'store_id' => $this->store_id,
        ]);

        $query->andFilterWhere(['like', 'store_facebook_share', $this->store_facebook_share])
            ->andFilterWhere(['like', 'store_twitter_share', $this->store_twitter_share])
            ->andFilterWhere(['like', 'store_google_share', $this->store_google_share])
            ->andFilterWhere(['like', 'store_linkedin_share', $this->store_linkedin_share])
            ->andFilterWhere(['like', 'google_analytics', $this->google_analytics])
            ->andFilterWhere(['like', 'live_script', $this->live_script]);

        return $dataProvider;
    }
}
