<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VendorWorkingTiming;

/**
 * VendorWorkingTimingSearch represents the model behind the search form about `common\models\VendorWorkingTiming`.
 */
class VendorWorkingTimingSearch extends VendorWorkingTiming
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['working_id', 'vendor_id'], 'integer'],
            [['working_day', 'working_start_time', 'working_end_time', 'trash'], 'safe'],
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
        $query = VendorWorkingTiming::find();

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
            'working_id' => $this->working_id,
            'vendor_id' => $this->vendor_id,
            'working_start_time' => $this->working_start_time,
            'working_end_time' => $this->working_end_time,
        ]);

        $query->andFilterWhere(['like', 'working_day', $this->working_day])
            ->andFilterWhere(['like', 'trash', 'Default']);

        return $dataProvider;
    }
}