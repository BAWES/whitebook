<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Package;

/**
 * PackageSearch represents the model behind the search form about `common\models\Package`.
 */
class PackageSearch extends Package
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['package_id'], 'integer'],
            [['package_name', 'package_name_ar', 'package_background_image', 'package_description', 'package_description_ar', 'package_avg_price', 'package_number_of_guests', 'status', 'package_slug'], 'safe'],
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
        $query = Package::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'package_id' => $this->package_id,
        ]);

        $query->andFilterWhere(['like', 'package_name', $this->package_name])
            ->andFilterWhere(['like', 'package_name_ar', $this->package_name_ar])
            ->andFilterWhere(['like', 'package_background_image', $this->package_background_image])
            ->andFilterWhere(['like', 'package_description', $this->package_description])
            ->andFilterWhere(['like', 'package_description_ar', $this->package_description_ar])
            ->andFilterWhere(['like', 'package_avg_price', $this->package_avg_price])
            ->andFilterWhere(['like', 'status', $this->status])
            //->andFilterWhere(['like', 'package_slug', $this->package_slug])
            ->andFilterWhere(['like', 'package_number_of_guests', $this->package_number_of_guests]);

        return $dataProvider;
    }
}
