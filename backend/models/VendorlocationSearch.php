<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Vendorlocation;

/**
 * VendorlocationSearch represents the model behind the search form about `common\models\Vendorlocation`.
 */
class VendorlocationSearch extends Vendorlocation
{
    public $cityName, $locationName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'vendor_id', 'created_by', 'modified_by'], 'integer'],
            [['locationName', 'cityName', 'city_id', 'area_id', 'created_datetime', 'modified_datetime'], 'safe'],
            [['delivery_price'], 'number'],
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
        $query = Vendorlocation::find();

        // add conditions that should always apply here
        $query->where(['vendor_id' => Yii::$app->user->getId()]);

        $query->joinWith('city');
        $query->joinWith('location');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'cityName' => [
                    'asc' => ['{{%city}}.city_name' => SORT_ASC],
                    'desc' => ['{{%city}}.city_name' => SORT_DESC],
                    'label' => 'City',
                    'default' => SORT_ASC
                ],
                'locationName' => [
                    'asc' => ['{{%location}}.location' => SORT_ASC],
                    'desc' => ['{{%location}}.location' => SORT_DESC],
                    'label' => 'Area'
                ]
            ]
        ]);
     
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'delivery_price' => $this->delivery_price,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by            
        ]);

        $query->andFilterWhere(['like', 'city_id', $this->city_id])
            ->andFilterWhere(['like', '{{%city}}.city_name', $this->cityName])
            ->andFilterWhere(['like', '{{%location}}.location', $this->locationName])
            ->andFilterWhere(['like', 'area_id', $this->area_id]);

        return $dataProvider;
    }
}
