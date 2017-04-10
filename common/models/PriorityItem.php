<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_priority_item".
 *
 * @property string $priority_id
 * @property string $vendor_id
 * @property integer $category_id
 * @property string $subcategory_id
 * @property integer $child_category
 * @property string $item_id
 * @property string $priority_level
 * @property string $priority_start_date
 * @property string $priority_end_date
 * @property string $status
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 */
class PriorityItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_priority_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_id', 'item_id', 'priority_start_date', 'priority_end_date', 'created_by', 'modified_by', 'created_datetime', 'modified_datetime'], 'required'],
            [['vendor_id', 'category_id', 'subcategory_id', 'child_category', 'created_by', 'modified_by'], 'integer'],
            [['priority_level', 'status', 'trash'], 'string'],
            [['priority_start_date', 'priority_end_date', 'created_datetime', 'modified_datetime'], 'safe'],
            [['item_id'], 'string', 'max' => 120]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'priority_id' => 'Priority ID',
            'vendor_id' => 'Vendor ID',
            'item_id' => 'Item ID',
            'priority_level' => 'Priority Level',
            'priority_start_date' => 'Priority Start Date',
            'priority_end_date' => 'Priority End Date',
            'status' => 'Status',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }

    /**
     * @inheritdoc
     * @return query\PriorityItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new query\PriorityItemQuery(get_called_class());
    }
}
