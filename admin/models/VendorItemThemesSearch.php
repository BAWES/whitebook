<?php

namespace admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VendorItemThemes;

class VendorItemThemesSearch extends VendorItemThemes
{
	public $vendorName;
	public $itemName;

	/**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['theme_id', 'item_id'], 'required'],
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
    public function search($params, $theme_id)
    {
        $query = VendorItemThemes::find()
        	->where(['!=', '{{%vendor_item_theme}}.trash', 'Deleted'])
            ->andWhere(['theme_id' => $theme_id]);
		
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
            $query->joinWith(['vendor', 'vendoritem']);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'theme_id' => $this->theme_id,
            'vendor_id' => $this->vendor_id,
            'item_id' => $this->item_id,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
        ]);

        $query->joinWith(['vendoritem' => function ($q) {
	        $q->where('{{%vendor_item}}.item_name LIKE "%' . $this->itemName . '%"');
	    }]);

	    $query->joinWith(['vendor' => function ($q) {
	        $q->where('{{%vendor}}.vendor_name LIKE "%' . $this->vendorName . '%"');
	    }]);

        return $dataProvider;
    }
}