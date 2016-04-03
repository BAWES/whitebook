<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Vendoritem;
use common\models\Vendor;
use yii\db\Expression;

/**
 * VendoritemSearch represents the model behind the search form about `common\models\Vendoritem`.
 */
class VendoritemSearch extends Vendoritem
{
    public $vendor_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'type_id', 'vendor_id', 'category_id', 'item_amount_in_stock', 'item_default_capacity', 'item_how_long_to_make', 'item_minimum_quantity_to_order', 'created_by', 'modified_by'], 'integer'],
            [['item_name','vendor_name','item_description', 'item_status','item_additional_info', 'item_customization_description', 'item_price_description', 'item_for_sale',  'item_approved','priority',], 'safe'],
            [['item_price_per_unit'], 'number'],
           
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
		$paramss = array('No','Yes','Rejected');
		$v = array_reverse(array_keys($paramss));
	//	var_dump($v);die;
		$query = Vendoritem::find()
        ->where(['!=', 'whitebook_vendor_item.trash', 'Deleted'])       
        ->orderBy(['item_id' => SORT_DESC])
        ->orderBy([new Expression('FIELD (item_approved,'. implode(',', array_reverse(array_keys($paramss))) . ')')]);
         $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['item_id'=>SORT_DESC]],
			'pagination' =>[
				'pageSize'=> 40,
				],
        ]);     

     
        $query->joinWith(['vendor']); 

        $this->load($params);   
       

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'item_id' => $this->item_id,
            'type_id' => $this->type_id,
            'whitebook_vendor_item.category_id' => $this->category_id,
            'item_status' => $this->item_status,
            'item_amount_in_stock' => $this->item_amount_in_stock,
            'item_default_capacity' => $this->item_default_capacity,
            'item_price_per_unit' => $this->item_price_per_unit,
            'item_how_long_to_make' => $this->item_how_long_to_make,
            'item_minimum_quantity_to_order' => $this->item_minimum_quantity_to_order,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'item_name', $this->item_name])            
            ->andFilterWhere(['like', 'item_description', $this->item_description])
            ->andFilterWhere(['like', 'item_additional_info', $this->item_additional_info])
            ->andFilterWhere(['like', 'item_customization_description', $this->item_customization_description])
            ->andFilterWhere(['like', 'item_price_description', $this->item_price_description])
            ->andFilterWhere(['like', 'item_for_sale', $this->item_for_sale])          
            ->andFilterWhere(['like', 'item_approved', $this->item_approved])
            ->andFilterWhere(['like', 'priority', $this->priority])
            ->andFilterWhere(['like', 'trash', $this->trash])
            ->andFilterWhere(['like', '{{%vendor}}.vendor_name',$this->vendor_name]);
       return $dataProvider;
    }
    
    // Filter by respective vendor view their own products
    public function searchVendor($params,$vendor_id=false)
    {
        $pagination = 10;
        if(empty($vendor_id))
        {          
            $vendor_id = Vendor::getVendor('vendor_id');
            $pagination = 40;
        }
		$query = Vendoritem::find()
        ->where(['!=', 'trash', 'Deleted'])   
        ->andwhere(['vendor_id'=> $vendor_id])    
        ->orderBy(['item_id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize'=> $pagination,
                ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'item_id' => $this->item_id,
            'type_id' => $this->type_id,
            'vendor_id' => $this->vendor_id,
            'category_id' => $this->category_id,
			'item_status' => $this->item_status,
            'item_amount_in_stock' => $this->item_amount_in_stock,
            'item_default_capacity' => $this->item_default_capacity,
            'item_price_per_unit' => $this->item_price_per_unit,
            'item_how_long_to_make' => $this->item_how_long_to_make,
            'item_minimum_quantity_to_order' => $this->item_minimum_quantity_to_order,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'item_name', $this->item_name])
            ->andFilterWhere(['like', 'item_description', $this->item_description])
            ->andFilterWhere(['like', 'item_additional_info', $this->item_additional_info])
            ->andFilterWhere(['like', 'item_customization_description', $this->item_customization_description])
            ->andFilterWhere(['like', 'item_price_description', $this->item_price_description])
            ->andFilterWhere(['like', 'item_for_sale', $this->item_for_sale])           
            ->andFilterWhere(['like', 'item_approved', $this->item_approved])
            ->andFilterWhere(['like', 'priority', $this->priority])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }



    public function searchviewVendor($params,$vendor_id=false)
    {
        $pagination = 10;
        if(empty($vendor_id))
        {          
            $vendor_id = Vendor::getVendor('vendor_id');
            $pagination = 40;
        }
        $query = Vendoritem::find()
        ->where(['!=', 'trash', 'Deleted'])   
        ->andwhere(['vendor_id'=> $vendor_id])    
        ->orderBy(['item_id' => SORT_DESC])
        ->all();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize'=> $pagination,
                ],
        ]);

        return $dataProvider;
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'item_id' => $this->item_id,
            'type_id' => $this->type_id,
            'vendor_id' => $this->vendor_id,
            'category_id' => $this->category_id,
            'item_status' => $this->item_status,
            'item_amount_in_stock' => $this->item_amount_in_stock,
            'item_default_capacity' => $this->item_default_capacity,
            'item_price_per_unit' => $this->item_price_per_unit,
            'item_how_long_to_make' => $this->item_how_long_to_make,
            'item_minimum_quantity_to_order' => $this->item_minimum_quantity_to_order,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', 'item_name', $this->item_name])
            ->andFilterWhere(['like', 'item_description', $this->item_description])
            ->andFilterWhere(['like', 'item_additional_info', $this->item_additional_info])
            ->andFilterWhere(['like', 'item_customization_description', $this->item_customization_description])
            ->andFilterWhere(['like', 'item_price_description', $this->item_price_description])
            ->andFilterWhere(['like', 'item_for_sale', $this->item_for_sale])           
            ->andFilterWhere(['like', 'item_approved', $this->item_approved])
            ->andFilterWhere(['like', 'priority', $this->priority])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }  
   
}
