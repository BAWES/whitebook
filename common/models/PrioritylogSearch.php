<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Prioritylog;

/**
 * PrioritylogSearch represents the model behind the search form about `common\models\Prioritylog`.
 */
class PrioritylogSearch extends Prioritylog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_id', 'vendor_id', 'item_id', 'created_by', 'modified_by'], 'integer'],
            [['priority_level','created_datetime', 'modified_datetime', 'trash'], 'safe'],
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
        $query = Prioritylog::find()
        ->orderBy(['log_id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'log_id' => $this->log_id,
            'vendor_id' => $this->vendor_id,
            'item_id' => $this->item_id,
            'priority_start_date' => $this->priority_start_date,
            'priority_end_date' => $this->priority_end_date,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'priority_level', $this->priority_level])
            ->andFilterWhere(['like', 'trash', $this->trash]);
        return $dataProvider;
        
    }
    
        public function vendorsearch($params,$vendor_id=false)
    {
		        if(empty($vendor_id))
        {          
            $vendor_id = Vendor::getVendor('vendor_id'); 
            $pagination = 40;
        }
        
        $query = Prioritylog::find()
        ->andwhere(['vendor_id'=> $vendor_id])
        ->orderBy(['log_id' => SORT_DESC]);
        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'log_id' => $this->log_id,
            'vendor_id' => $this->vendor_id,
            'item_id' => $this->item_id,
            'priority_start_date' => $this->priority_start_date,
            'priority_end_date' => $this->priority_end_date,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);
        $query->andFilterWhere(['like', 'priority_level', $this->priority_level])
            ->andFilterWhere(['like', 'trash', $this->trash]);
        return $dataProvider;
    }
    
            public function vendorviewsearch($params,$vendor_id)
    {
        $query = Prioritylog::find()
        ->andwhere(['vendor_id'=> $vendor_id])
        ->orderBy(['log_id' => SORT_DESC])
        ->all();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

      $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }
    
    
    
            public function vendoritemsearch($item_id)
    {
        $query = Prioritylog::find()
        ->where(['item_id'=> $item_id])
        ->orderBy(['log_id' => SORT_DESC])
        ->all();
        return $query; 
    }
}
