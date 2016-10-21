<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\VendorPackages;
use common\models\Package;

/**
 * VendorPackagesSearch represents the model behind the search form about `common\models\VendorPackages`.
 */
class VendorPackagesSearch extends VendorPackages
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'vendor_id', 'package_id', 'created_by', 'modified_by'], 'integer'],
            [['package_price'], 'number'],
            [['created_datetime', 'modified_datetime'], 'safe'],
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
        $query = VendorPackages::find()
            ->where(['vendor_id' => Yii::$app->user->getID(), 'trash' => 'Default'])    
            ->orderBy(['created_datetime' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'package_id' => $this->package_id,
            'package_price' => $this->package_price,
            'created_datetime' => $this->created_datetime,
            'modified_datetime' => $this->modified_datetime,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
        ]);

        return $dataProvider;
    }
}
