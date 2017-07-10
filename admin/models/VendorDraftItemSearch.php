<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VendorDraftItem;
use common\models\Vendor;
use yii\db\Expression;

/**
 * VendorItemSearch represents the model behind the search form about `common\models\VendorItem`.
 */
class VendorDraftItemSearch extends VendorDraftItem
{
    public $vendor_name, $theme_id, $group_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['minimum_increment', 'item_id', 'type_id', 'vendor_id', 'item_default_capacity', 'item_how_long_to_make', 'item_minimum_quantity_to_order', 'created_by', 'modified_by', 'is_ready','included_quantity'], 'integer'],
            [['theme_id', 'group_id', 'item_name','vendor_name','item_description', 'item_status','item_additional_info', 'item_customization_description', 'item_approved','priority','is_ready'], 'safe'],
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

		$query = VendorDraftItem::find()
            ->where(['!=', 'whitebook_vendor_draft_item.trash', 'Deleted'])       
            ->orderBy(['item_id' => SORT_DESC])
            ->orderBy([new Expression('FIELD (item_approved,'. implode(',', array_reverse(array_keys($paramss))) . ')')]);

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
            'type_id' => $this->type_id,
            'is_ready' => $this->is_ready,
            'item_status' => $this->item_status,
            'item_default_capacity' => $this->item_default_capacity,
            'included_quantity' => $this->included_quantity,
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

        $query->andFilterWhere(['like', 'item_name', $this->item_name])            
            ->andFilterWhere(['like', 'item_description', $this->item_description])
            ->andFilterWhere(['like', 'item_additional_info', $this->item_additional_info])
            ->andFilterWhere(['like', 'item_customization_description', $this->item_customization_description])
            ->andFilterWhere(['like', 'item_approved', $this->item_approved])
            ->andFilterWhere(['like', 'priority', $this->priority])
            ->andFilterWhere(['like', 'trash', $this->trash])
            ->andFilterWhere(['like', '{{%vendor}}.vendor_name',$this->vendor_name]);

       return $dataProvider;
    }
}
