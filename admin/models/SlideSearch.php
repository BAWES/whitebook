<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\Slide;

/**
 * SlideSearch represents the model behind the search form about `common\models\Slide`.
 */
class SlideSearch extends Slide
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slide_id'], 'integer'],
            [['slide_title', 'created_datetime', 'modified_datetime', 'trash'], 'safe'],
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
        $query = Slide::find()
        ->where(['!=', 'trash', 'Deleted'])
		->orderBy('slide_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['slide_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'slide_id' => $this->slide_id,
            'sort' => $this->sort,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'slide_title', $this->slide_title])
            ->andFilterWhere(['like', 'slide_image', $this->slide_image])
            ->andFilterWhere(['like', 'slide_video_url', $this->slide_video_url])
            ->andFilterWhere(['like', 'slide_url', $this->slide_url])
            ->andFilterWhere(['like', 'slide_status', $this->slide_status])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
