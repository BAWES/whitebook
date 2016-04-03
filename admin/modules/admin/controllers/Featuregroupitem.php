<?php

namespace backend\models;



/**
 * This is the model class for table "whitebook_feature_group_item".
 *
 * @property string $featured_id
 * @property string $group_id
 * @property string $item_id
 * @property string $featured_start_date
 * @property string $featured_end_date
 * @property int $featured_sort
 * @property string $group_item_status
 * @property int $created_by
 * @property int $modified_by
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property string $trash
 * @property FeatureGroup $group
 * @property VendorItem $item
 */
class Featuregroupitem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'whitebook_feature_group_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vendor_id', 'category_id', 'subcategory_id', 'item_id', 'featured_start_date', 'featured_end_date', 'featured_sort', 'group_item_status', 'featured_sort'], 'required'],
            [['group_id', 'item_id', 'featured_sort', 'created_by', 'modified_by'], 'integer'],
            [['featured_start_date', 'featured_end_date', 'created_datetime', 'modified_datetime'], 'safe'],
            [['group_item_status', 'trash'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'featured_id' => 'Featured ID',
            'group_id' => 'Group ID',
            'item_id' => 'Item ID',
            'featured_start_date' => 'Featured Start Date',
            'featured_end_date' => 'Featured End Date',
            'featured_sort' => 'Featured Sort',
            'group_item_status' => 'Group Item Status',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'trash' => 'Trash',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(FeatureGroup::className(), ['group_id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(VendorItem::className(), ['item_id' => 'item_id']);
    }

    public static function getGroupName($id)
    {
        $model = Featuregroup::find()->where(['group_id' => $id])->one();

        return $model->group_name;
    }
}
