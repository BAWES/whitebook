<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%event_invitees}}".
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
class Eventinvitees extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%event_invitees}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'phone_number'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invitees_id' => 'Invitees ID',
            'event_id' => 'Event ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone_number' => 'Phone Number',
            'created_datetime' => 'Created Datetime',
            'modified_datetime' => 'Modified Datetime',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
        ];
    }
}
