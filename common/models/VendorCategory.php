<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_vendor_category".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $vendor_id
 */
class VendorCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'vendor_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'vendor_id' => 'Vendor ID',
        ];
    }
}
