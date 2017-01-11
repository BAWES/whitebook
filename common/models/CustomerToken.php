<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%customer_token}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $token_value
 * @property string $token_status
 */
class CustomerToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_token}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id'], 'integer'],
            [['token_value', 'token_status'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'token_value' => 'Token Value',
            'token_status' => 'Token Status',
        ];
    }
}
