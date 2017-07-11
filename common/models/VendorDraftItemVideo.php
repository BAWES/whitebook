<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "whitebook_vendor_draft_item_video".
 *
 * @property integer $draft_video_id
 * @property string $item_id
 * @property string $video
 * @property string $created_datetime
 * @property string $modified_datetime
 *
 * @property VendorItem $item
 */
class VendorDraftItemVideo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_vendor_draft_item_video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'video_sort_order'], 'integer'],
            [['created_datetime', 'modified_datetime'], 'safe'],
            [['video'], 'string', 'max' => 100],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => VendorItem::className(), 'targetAttribute' => ['item_id' => 'item_id']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => 'modified_datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'draft_video_id' => 'Draft Video ID',
            'item_id' => 'Item ID',
            'video' => 'Video',
            'video_sort_order' => 'Sort Order',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
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
