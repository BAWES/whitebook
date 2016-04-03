<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "{{%events}}".
 *
 * @property integer $event_id
 * @property integer $customer_id
 * @property string $event_name
 * @property string $event_date
 * @property string $event_type
 * @property string $created_date
 */
class Events extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%events}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'event_name', 'event_date', 'event_type'], 'required'],
            [['customer_id'], 'integer'],
            [['event_date', 'created_date'], 'safe'],
            [['event_name', 'event_type'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'event_id' => 'Event ID',
            'customer_id' => 'Customer ID',
            'event_name' => 'Event Name',
            'event_date' => 'Event Date',
            'event_type' => 'Event Type',
            'created_date' => 'Created Date',
        ];
    }
}
