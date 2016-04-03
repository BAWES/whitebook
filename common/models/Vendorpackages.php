<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vendor_packages}}".
 *
 * @property integer $id
 * @property integer $vendor_id
 * @property integer $package_id
 * @property double $package_price
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property integer $created_by
 * @property integer $modified_by
 */
class Vendorpackages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vendor_packages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_id', 'package_id', 'package_price', 'created_datetime', 'modified_datetime', 'created_by', 'modified_by'], 'required'],
            [['vendor_id', 'package_id', 'created_by', 'modified_by'], 'integer'],
            [['package_price'], 'number'],
            [['created_datetime', 'modified_datetime'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vendor_id' => 'Vendor ID',
            'package_id' => 'Package Name',
            'package_price' => 'Price',
            'package_end_date' => 'Start date',
            'package_start_date' => 'End date',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackage()
    {
        return $this->hasOne(Package::className(), ['package_id' => 'package_id']);
    }
}
