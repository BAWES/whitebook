<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VendorItem;
use common\models\Vendor;
use yii\db\Expression;

/**
 * VendorItemSearch represents the model behind the search form about `common\models\VendorItem`.
 */
class VendorItemSearch extends VendorItem
{
    public $vendor_name, $theme_id, $group_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'type_id', 'vendor_id', 'item_default_capacity', 'item_how_long_to_make', 'item_minimum_quantity_to_order', 'created_by', 'modified_by'], 'integer'],
            [['theme_id', 'group_id', 'item_name','vendor_name','item_description', 'item_status','item_additional_info', 'item_customization_description', 'item_price_description', 'item_approved','priority',], 'safe'],
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
    public function search($params, $item_approved = '')
    {
		$paramss = array('No','Yes','Rejected');

		$v = array_reverse(array_keys($paramss));

		$query = VendorItem::find()
            ->where(['!=', 'whitebook_vendor_item.trash', 'Deleted'])   
            ->andWhere(['hide_from_admin' => 0])
            ->orderBy(['item_id' => SORT_DESC]);
           // ->orderBy([new Expression('FIELD (item_approved,'. implode(',', array_reverse(array_keys($paramss))) . ')')]);

        if($item_approved) {
           $query->andFilterWhere(['item_approved' => $item_approved]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['item_id'=>SORT_DESC]],
			'pagination' =>[
				'pageSize'=> 40,
			],
        ]);     
     
        if(!empty($params['VendorItemSearch']['group_id'])) {
            $query->joinWith(['featureGroupItems']);     
        }

        if(!empty($params['VendorItemSearch']['theme_id'])) {
            $query->joinWith(['vendorItemThemes']);     
        }

        $query->joinWith(['vendor']); 

        $this->load($params);   
       
        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'item_id' => $this->item_id,
            'item_default_capacity' => $this->item_default_capacity,
            'item_price_per_unit' => $this->item_price_per_unit,
            'item_how_long_to_make' => $this->item_how_long_to_make,
            'item_minimum_quantity_to_order' => $this->item_minimum_quantity_to_order,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        if(!empty($params['VendorItemSearch']['theme_id'])) {
            $query->andFilterWhere([
                '{{%vendor_item_theme}}.trash' => 'Default',
                '{{%vendor_item_theme}}.theme_id' =>  $params['VendorItemSearch']['theme_id']
            ]);
        }

        if(!empty($params['VendorItemSearch']['group_id'])) {
            $query->andFilterWhere([
                '{{%feature_group_item}}.trash' => 'Default',
                '{{%feature_group_item}}.group_id' =>  $params['VendorItemSearch']['group_id']
            ]);
        }

        $query->andFilterWhere(['like', '{{%vendor_item}}.item_description', $this->item_description])
            ->andFilterWhere(['like', '{{%vendor_item}}.item_additional_info', $this->item_additional_info])
            ->andFilterWhere(['like', '{{%vendor_item}}.item_customization_description', $this->item_customization_description])
            ->andFilterWhere(['like', '{{%vendor_item}}.item_price_description', $this->item_price_description])
            ->andFilterWhere(['like', '{{%vendor_item}}.item_approved', $this->item_approved])
            ->andFilterWhere(['like', '{{%vendor_item}}.priority', $this->priority])
            ->andFilterWhere(['like', '{{%vendor_item}}.trash', $this->trash])
            ->andFilterWhere(['like', '{{%vendor}}.vendor_name',$this->vendor_name]);

        if($this->item_name || $this->type_id || $this->item_status) {
            $query->leftJoin('{{%vendor_draft_item}}', '{{%vendor_draft_item}}.item_id = {{%vendor_item}}.item_id');    
        }
        
        if($this->item_name) 
        {
            $query              
                ->andWhere('IF {{%vendor_draft_item}}.item_id IS NOT NULL  THEN {{%vendor_draft_item}}.item_name LIKE "%'.$this->item_name.'%" ELSE {{%vendor_item}}.item_name LIKE "%'.$this->item_name.'%"');
        }

        if($this->type_id) 
        {
            $query              
                ->andWhere('IF {{%vendor_draft_item}}.item_id IS NOT NULL THEN {{%vendor_draft_item}}.type_id = "'.$this->type_id.'" ELSE {{%vendor_item}}.type_id = "'.$this->type_id.'"');
        }

        if($this->item_status) 
        {
            $query              
                ->andWhere('IF {{%vendor_draft_item}}.item_id IS NOT NULL THEN {{%vendor_draft_item}}.item_status = "'.$this->item_status.'" ELSE {{%vendor_item}}.item_status = "'.$this->item_status.'"');
        }

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

		$query = VendorItem::find()
            ->where(['!=', '{{%vendor_item}}.trash', 'Deleted'])   
            ->andwhere(['{{%vendor_item}}.vendor_id'=> $vendor_id])    
            ->orderBy(['item_id' => SORT_DESC]);

        $query->leftJoin(
                '{{%vendor_draft_item}}',
                '{{%vendor_draft_item}}.item_id = {{%vendor_item}}.item_id'
            );

        $this->load($params);

        /* *
         *  We preserving status and sort order of original item when admin approve item 
         *  So, We not need to add condition for that parameters
         */
        if($this->item_name) {
            
            $query->andwhere('
                (
                    {{%vendor_draft_item}}.item_name IS NULL AND {{%vendor_item}}.item_name like "%'.$this->item_name.'%"
                ) 
                OR 
                (
                    {{%vendor_draft_item}}.item_name IS NOT NULL AND {{%vendor_draft_item}}.item_name like "%'.$this->item_name.'%"
                ) 
            ');
        }
        
        if($this->type_id) {

            $query->andwhere('
                (
                    {{%vendor_draft_item}}.type_id IS NULL AND {{%vendor_item}}.type_id = "'.$this->type_id.'"
                ) 
                OR 
                (
                    {{%vendor_draft_item}}.type_id IS NOT NULL AND {{%vendor_draft_item}}.type_id = "'.$this->type_id.'"
                ) 
            ');
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize'=> $pagination,
            ],
        ]);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            '{{%vendor_item}}.item_id' => $this->item_id,
            '{{%vendor_item}}.vendor_id' => $this->vendor_id,
          	'{{%vendor_item}}.item_status' => $this->item_status,
            '{{%vendor_item}}.item_default_capacity' => $this->item_default_capacity,
            '{{%vendor_item}}.item_price_per_unit' => $this->item_price_per_unit,
            '{{%vendor_item}}.item_how_long_to_make' => $this->item_how_long_to_make,
            '{{%vendor_item}}.item_minimum_quantity_to_order' => $this->item_minimum_quantity_to_order,
            '{{%vendor_item}}.created_by' => $this->created_by,
            '{{%vendor_item}}.modified_by' => $this->modified_by,
            '{{%vendor_item}}.created_datetime' => $this->created_datetime,
            '{{%vendor_item}}.modified_datetime' => $this->modified_datetime,
        ]);

        $query->andFilterWhere(['like', '{{%vendor_item}}.item_description', $this->item_description])
            ->andFilterWhere(['like', '{{%vendor_item}}.item_additional_info', $this->item_additional_info])
            ->andFilterWhere(['like', '{{%vendor_item}}.item_customization_description', $this->item_customization_description])
            ->andFilterWhere(['like', '{{%vendor_item}}.item_price_description', $this->item_price_description])
            ->andFilterWhere(['like', '{{%vendor_item}}.item_approved', $this->item_approved])
            ->andFilterWhere(['like', '{{%vendor_item}}.priority', $this->priority])
            ->andFilterWhere(['like', '{{%vendor_item}}.trash', $this->trash]);

        return $dataProvider;
    }



    public function searchviewVendor($params, $vendor_id=false)
    {
        $pagination = 10;
        
        if(empty($vendor_id))
        {          
            $vendor_id = Vendor::getVendor('vendor_id');
            $pagination = 40;
        }
        
        $query = VendorItem::find()
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
            'item_status' => $this->item_status,
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
            ->andFilterWhere(['like', 'item_approved', $this->item_approved])
            ->andFilterWhere(['like', 'priority', $this->priority])
            ->andFilterWhere(['like', 'trash', $this->trash]);

        return $dataProvider;
    }  
   
}
