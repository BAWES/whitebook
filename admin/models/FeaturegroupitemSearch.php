<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FeaturegroupitemSearch represents the model behind the search form about `backend\models\Featuregroupitem`.
 */
class FeaturegroupitemSearch extends Featuregroupitem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['featured_id', 'group_id', 'item_id', 'featured_sort', 'created_by', 'modified_by'], 'integer'],
            [['featured_start_date', 'featured_end_date', 'group_item_status', ], 'safe'],
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
        $query = Featuregroupitem::find()
        ->where(['!=', 'trash', 'Deleted']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['featured_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'featured_id' => $this->featured_id,
            'group_id' => $this->group_id,
            'item_id' => $this->item_id,
            'featured_start_date' => $this->featured_start_date,
            'featured_end_date' => $this->featured_end_date,
            'featured_sort' => $this->featured_sort,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'group_item_status', $this->group_item_status])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
