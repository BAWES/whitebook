<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\BlockedDate;

/**
 * BlockedDateSearch represents the model behind the search form about `common\models\BlockedDate`.
 */
class BlockedDateSearch extends BlockedDate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['block_id', 'vendor_id', 'created_by', 'modified_by'], 'integer'],
            [[ 'created_datetime', 'modified_datetime', 'trash'], 'safe'],
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
        $query = BlockedDate::find()
        ->where(['!=', 'trash', 'Deleted'])   
        ->andwhere(['created_by'=> $vendor_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['block_id'=>SORT_DESC]]            
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'block_id' => $this->block_id,
            'vendor_id' => $this->vendor_id,
            'block_date' => $this->block_date,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }
}
