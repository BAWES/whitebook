<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Deliverytimeslot;

/**
 * DeliverytimeslotSearch represents the model behind the search form about `backend\models\Deliverytimeslot`.
 */
class DeliverytimeslotSearch extends Deliverytimeslot
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timeslot_id', 'vendor_id', 'timeslot_maximum_orders', 'created_by', 'modified_by'], 'integer'],
            [['timeslot_day', 'timeslot_start_time', 'timeslot_end_time', 'created_datetime', 'modified_datetime', 'trash'], 'safe'],
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
    public function search($params,$vendor_id=false)
    {
        if(empty($vendor_id))
        {          
            $vendor_id = Vendor::getVendor('vendor_id');
            $pagination = 40;
        }
        $query = Deliverytimeslot::find()
        ->where(['!=', 'trash', 'Deleted'])   
        ->andwhere(['vendor_id'=> $vendor_id]);

        if(empty($vendor_id))
        {          
            $vendor_id = Vendor::getVendor('vendor_id');
            $pagination = 40;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['timeslot_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'timeslot_id' => $this->timeslot_id,
            'vendor_id' => $this->vendor_id,
            'timeslot_start_time' => $this->timeslot_start_time,
            'timeslot_end_time' => $this->timeslot_end_time,
            'timeslot_maximum_orders' => $this->timeslot_maximum_orders,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'timeslot_day', $this->timeslot_day])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
    
    
        public function deliverysearch($vendor_id)
    {
        if(empty($vendor_id))
        {          
            $vendor_id = Vendor::getVendor('vendor_id');
            $pagination = 40;
        }
        $query = Deliverytimeslot::find()
        ->where(['!=', 'trash', 'Deleted'])   
        ->andwhere(['vendor_id'=> $vendor_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['timeslot_id'=>SORT_DESC]]
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }
}
