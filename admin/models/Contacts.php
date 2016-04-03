<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "{{%contacts}}".
 *
 * @property integer $id
 * @property string $contact_name
 * @property string $contact_email
 * @property string $contact_phone
 * @property string $subject
 * @property string $message
 */
class Contacts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contacts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_name', 'contact_email', 'contact_phone', 'subject', 'message'], 'required'],
            [['created_datetime', 'modified_datetime','created_by','modified_by','trash'], 'safe'],
            [['message'], 'string'],
            [['contact_name', 'contact_email'], 'string', 'max' => 50],
            [['contact_phone'], 'string', 'max' => 25],
            [['subject'], 'string', 'max' => 250],
             /* Validation Rules */
            [['contact_email'],'email'],
            ['contact_phone','match', 'pattern' => '/^[0-9+ -]+$/','message' => 'Phone number accept only numbers and +,-']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contact_name' => 'Contact Name',
            'contact_email' => 'Contact Email',
            'contact_phone' => 'Contact Phone',
            'subject' => 'Subject',
            'message' => 'Message',
        ];
    }
}
