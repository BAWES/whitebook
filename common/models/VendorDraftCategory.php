<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_vendor_draft_category".
 *
 * @property integer $draft_id
 * @property string $category_id
 * @property integer $vendor_draft_id
 *
 * @property Category $category
 * @property VendorDraft $vendorDraft
 */
class VendorDraftCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_draft_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'vendor_draft_id'], 'integer'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'category_id']],
            [['vendor_draft_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorDraft::className(), 'targetAttribute' => ['vendor_draft_id' => 'vendor_draft_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'draft_id' => 'Draft ID',
            'category_id' => 'Category ID',
            'vendor_draft_id' => 'Vendor Draft ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendorDraft()
    {
        return $this->hasOne(VendorDraft::className(), ['vendor_draft_id' => 'vendor_draft_id']);
    }
}
