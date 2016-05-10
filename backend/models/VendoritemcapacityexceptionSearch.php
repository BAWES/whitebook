<?php

namespace backend\models;
use yii\helpers\Setdateformat;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Vendoritemcapacityexception;

/**
 * VendoritemcapacityexceptionSearch represents the model behind the search form about `common\models\Vendoritemcapacityexception`.
 */
class VendoritemcapacityexceptionSearch extends common\models\Vendoritemcapacityexception
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
}
