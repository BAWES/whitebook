<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use admin\models\Themes;

/**
 * themesSearch represents the model behind the search form about `common\models\Themes`.
 */
class themesSearch extends Themes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['theme_id', 'created_by', 'modified_by'], 'integer'],
            [['theme_name', ], 'safe'],
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
		$query = Themes::find()
        ->where(['!=', 'trash', 'Deleted'])
		->orderBy('theme_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['theme_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'theme_id' => $this->theme_id,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'theme_name', $this->theme_name])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
