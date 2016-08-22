<?php

namespace admin\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PackageSearch represents the model behind the search form about `common\models\Package`.
 */
class VendorPackagesSearch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_packages';
    }

    public function vendorviewsearch($params, $vendor_id)
    {
        return VendorPackagesSearch::find()
            ->where(['!=', 'trash', 'Deleted'])
            ->andwhere(['vendor_id' => $vendor_id])
            ->orderBy('id')
            ->all();
    }
}
