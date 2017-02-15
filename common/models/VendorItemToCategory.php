<?php

namespace common\models;

use Yii;
use common\models\Category;


/**
 * This is the model class for table "whitebook_vendor_item_to_category".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $category_id
 */
class VendorItemToCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_item_to_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'category_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'item_id' => Yii::t('frontend', 'Item ID'),
            'category_id' => Yii::t('frontend', 'Category ID'),
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    public function get_item_count($category_id)
    {
        return VendorItemToCategory::find()
                   ->where(['category_id' => $category_id])
                   ->count();
    }
}
