<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_draft_image".
 *
 * @property integer $di_id
 * @property string $item_id
 * @property integer $image_user_id
 * @property string $image_path
 * @property integer $vendorimage_sort_order
 *
 * @property VendorItem $item
 */
class VendorDraftImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_draft_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'image_user_id', 'vendorimage_sort_order'], 'integer'],
            [['image_path'], 'string', 'max' => 128],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorItem::className(), 'targetAttribute' => ['item_id' => 'item_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'di_id' => Yii::t('frontend', 'Di ID'),
            'item_id' => Yii::t('frontend', 'Item ID'),
            'image_user_id' => Yii::t('frontend', 'Image User ID'),
            'image_path' => Yii::t('frontend', 'Image Path'),
            'vendorimage_sort_order' => Yii::t('frontend', 'Vendorimage Sort Order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(VendorItem::className(), ['item_id' => 'item_id']);
    }
}
