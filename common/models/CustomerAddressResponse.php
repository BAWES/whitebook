<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "whitebook_customer_address_response".
 *
 * @property string $response_id
 * @property string $address_id
 * @property string $address_type_question_id
 * @property string $response_text
 */
class CustomerAddressResponse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_customer_address_response';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address_id', 'address_type_question_id'], 'required'],
            [['address_id', 'address_type_question_id'], 'integer'],
            [['response_text'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'response_id' => 'Response ID',
            'address_id' => 'Address ID',
            'address_type_question_id' => 'Address Type Question ID',
            'response_text' => 'Response Text',
        ];
    }
}