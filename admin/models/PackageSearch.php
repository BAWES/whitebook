<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PackageSearch represents the model behind the search form about `backend\models\Package`.
 */
class PackageSearch extends Package
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['package_id', 'package_max_number_of_listings', ], 'integer'],
            [['package_name',], 'safe'],
            [['package_name',  'modified_datetime', 'trash'], 'safe'],
            [['package_pricing'], 'number'],
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
		$query = Package::find()
        ->where(['!=', 'trash', 'Deleted'])
		->orderBy('package_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['package_id'=>SORT_DESC]]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'package_id' => $this->package_id,
            'package_max_number_of_listings' => $this->package_max_number_of_listings,
            'package_pricing' => $this->package_pricing,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'package_name', $this->package_name])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
