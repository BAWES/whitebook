<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "smtp_settings".
 *
 * @property integer $id
 * @property string $smtp_host
 * @property string $smtp_username
 * @property string $smtp_password
 * @property string $smtp_port
 * @property string $transport_layer_security
 * @property integer $smtp
 */
class Smtp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%smtp_settings}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['smtp_host', 'smtp_username', 'smtp_password', 'smtp_port', 'transport_layer_security'], 'required'],
            [['smtp_host', 'smtp_username', 'smtp_password'], 'string', 'max' => 100],
            [['smtp_port', 'transport_layer_security'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'smtp_host' => 'SMTP Host',
            'smtp_username' => 'SMTP Username',
            'smtp_password' => 'SMTP Password',
            'smtp_port' => 'SMTP Port',
            'transport_layer_security' => 'Transport Layer Security',
        ];
    }
}
