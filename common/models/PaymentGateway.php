<?php

namespace common\models;

use Yii;
use common\models\OrderStatus;

/**
 * This is the model class for table "whitebook_payment_gateway".
 *
 * @property integer $gateway_id
 * @property string $name
 * @property string $name_ar
 * @property string $code
 * @property string $percentage
 * @property integer $order_status_id
 * @property integer $under_testing
 * @property integer $status
 */
class PaymentGateway extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_payment_gateway';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_ar', 'code'], 'required'],
            [['percentage'], 'number'],
            [['order_status_id', 'under_testing', 'status'], 'integer'],
            [['name', 'name_ar', 'code'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gateway_id' => 'Gateway ID',
            'name' => 'Name',
            'name_ar' => 'Name - Arabic',
            'code' => 'Code',
            'percentage' => 'Percentage',
            'order_status_id' => 'Order Status ID',
            'under_testing' => 'Under Testing',
            'status' => 'Status',
        ];
    }

    public function getOrderStatus() {
        return $this->hasOne(OrderStatus::className(), ['order_status_id' => 'order_status_id']);
    }
}
