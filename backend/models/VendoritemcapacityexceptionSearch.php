<?php

namespace backend\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * VendoritemcapacityexceptionSearch represents the model behind the search form about `common\models\Vendoritemcapacityexception`.
 */
class VendoritemcapacityexceptionSearch extends \common\models\Vendoritemcapacityexception
{
    public $item_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exception_id', 'exception_capacity', 'created_by', 'modified_by'], 'integer'],
          //  [['item_id'], 'string'],
            [['exception_date', 'item_name','created_datetime', 'modified_datetime', 'trash'], 'safe'],
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
        $query = Vendoritemcapacityexception::find()
        ->orderBy(['exception_id' => SORT_DESC]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->leftJoin('{{%vendor_item}}','FIND_IN_SET({{%vendor_item}}.item_id,{{%vendor_item_capacity_exception}}.item_id)'); 
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
          if($this->exception_date!=''){
          $this->exception_date = strtotime($this->exception_date);
          }   
        $query->andFilterWhere([
            'exception_id' => $this->exception_id,
            'exception_date' => $this->exception_date,
            'exception_capacity' => $this->exception_capacity,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', '{{%vendor_item}}.item_name',$this->item_name]);
        return $dataProvider;
    }

}
