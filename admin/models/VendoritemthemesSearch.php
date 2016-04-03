<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FeaturegroupitemSearch represents the model behind the search form about `admin\models\Featuregroupitem`.
 */
class vendoritemthemesSearch extends Vendoritemthemes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

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
        $query = Vendoritemthemes::find()
        ->where(['!=', 'trash', 'Deleted']);
        $dataProvider = new ActiveDataProvider([
        'query' => $query,
		'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'item_id' => $this->item_id,
            'theme_id' => $this->theme_id,
            'theme_start_date' => $this->theme_start_date,
            'theme_end_date' => $this->theme_end_date,
        ]);

        $query  ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
