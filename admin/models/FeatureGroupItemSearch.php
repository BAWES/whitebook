<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FeatureGroupItem;

class FeatureGroupItemSearch extends FeatureGroupItem
{
	public $vendorName;
	public $itemName;

	/**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['group_id', 'item_id'], 'required'],
            [['vendorName', 'itemName'], 'safe']
        ];
    }

	/**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $group_id)
    {
        $query = FeatureGroupItem::find()
        	->where(['!=', '{{%feature_group_item}}.trash', 'Deleted'])
            ->andWhere(['group_id' => $group_id]);
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $dataProvider->setSort([
	        'attributes' => [
	            'vendorName' => [
	                'asc' => ['{{%vendor}}.vendor_name' => SORT_ASC],
	                'desc' => ['{{%vendor}}.vendor_name' => SORT_DESC],
	                'label' => 'Vendor Name'
	            ],
	            'itemName' => [
	                'asc' => ['{{%vendor_item}}.item_name' => SORT_ASC],
	                'desc' => ['{{%vendor_item}}.item_name' => SORT_DESC],
	                'label' => 'Item Name'
	            ]
	        ]
	    ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['vendor', 'item']);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'group_id' => $this->group_id,
            'vendor_id' => $this->vendor_id,
            'item_id' => $this->item_id,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->joinWith(['item' => function ($q) {
	        $q->where('{{%vendor_item}}.item_name LIKE "%' . $this->itemName . '%"');
	    }]);

	    $query->joinWith(['vendor' => function ($q) {
	        $q->where('{{%vendor}}.vendor_name LIKE "%' . $this->vendorName . '%"');
	    }]);

        return $dataProvider;
    }
}