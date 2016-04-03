<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "{{%vendor_location}}".
 *
 * @property integer $id
 * @property integer $vendor_id
 * @property string $city_id
 * @property string $area_id
 * @property string $created_datetime
 * @property string $modified_datetime
 */
class vendorlocation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vendor_location}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[[''], 'required'],
            [['vendor_id'], 'integer'],
            [['created_datetime', 'modified_datetime'], 'safe'],
            [['city_id', 'area_id'], 'string', 'max' => 150]
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
            'city_id' => 'City ID',
            'area_id' => 'Area ID',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
        ];
    }
}
