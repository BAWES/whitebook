<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "email_template".
 *
 * @property integer $email_template_id
 * @property string $email_title
 * @property string $email_subject
 * @property string $email_content
 */
class Emailtemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%email_template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email_title', 'email_subject', 'email_content',], 'required'],
            [['email_content'], 'string'],
            [['created_datetime'],'safe'],
            [['email_title', 'email_subject'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email_template_id' => 'Email Template ID',
            'email_title' => 'Email Title',
            'email_subject' => 'Email Subject',
            'email_content' => 'Email Content',
        ];
    }
}
