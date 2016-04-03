<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AdverthomeSearch represents the model behind the search form about `backend\models\Adverthome`.
 */
class AdverthomeSearch extends Adverthome
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['advert_id', 'created_by', 'modified_by'], 'integer'],
            [['advert_code', 'created_datetime', 'modified_datetime', 'trash'], 'safe'],
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
        $query = Adverthome::find();

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
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'advert_code', $this->advert_code])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
