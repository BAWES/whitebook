<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%wishlist}}".
 *
 * @property integer $invitees_id
 * @property integer $event_id
 * @property string $name
 * @property string $email
 * @property string $phone_number
 * @property string $created_datetime
 * @property string $modified_datetime
 * @property integer $created_by
 * @property integer $modified_by
 */
class Eventitemlink extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%event_item_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'link_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'event_id' => 'Event ID',
            'link_id' => 'Link ID',
            'link_datetime' => 'Link Date time',
        ];
    }
}
