<?php

namespace common\models;

use Yii;
use common\models\Order;
use Suborder;
use SuborderItemPurchase;

/**
 * This is the model class for table "whitebook_order".
 *
 * @property string $order_id
 * @property string $customer_id
 * @property string $order_total_delivery_charge
 * @property string $order_total_without_delivery
 * @property string $order_total_with_delivery
 * @property string $order_payment_method
 * @property string $order_transaction_id
 * @property string $order_gateway_percentage
 * @property string $order_gateway_total
 * @property string $order_datetime
 * @property string $order_ip_address
 * @property integer $created_by
 * @property string $modified_by
 * @property integer $created_date
 * @property string $modified_date
 * @property string $trash
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'whitebook_order';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'modified_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => 'modified_datetime',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'order_total_delivery_charge', 'order_total_without_delivery', 'order_total_with_delivery', 'order_gateway_percentage', 'order_gateway_total', 'order_datetime', 'created_by', 'modified_by', 'created_date', 'modified_date', 'trash'], 'required'],
            [['customer_id', 'created_by', 'created_date'], 'integer'],
            [['order_total_delivery_charge', 'order_total_without_delivery', 'order_total_with_delivery', 'order_gateway_percentage', 'order_gateway_total'], 'number'],
            [['order_datetime', 'modified_by', 'modified_date'], 'safe'],
            [['trash'], 'string'],
            [['order_payment_method', 'order_transaction_id', 'order_ip_address'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'customer_id' => 'Customer ID',
            'order_total_delivery_charge' => 'Order Total Delivery Charge',
            'order_total_without_delivery' => 'Order Total Without Delivery',
            'order_total_with_delivery' => 'Order Total With Delivery',
            'order_payment_method' => 'Order Payment Method',
            'order_transaction_id' => 'Order Transaction ID',
            'order_gateway_percentage' => 'Order Gateway Percentage',
            'order_gateway_total' => 'Order Gateway Total',
            'order_datetime' => 'Order Datetime',
            'order_ip_address' => 'Order Ip Address',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
            'trash' => 'Trash',
        ];
    }

    public function place_order($gateway_name, $gateway_percentage, $order_status_id = 0, $transaction_id = ''){

        //make chunks of item by vendor id 
        $chanks = [];

        $items = CustomerCart::items();

        $total = $sub_total = $delivery_charge = 0;

        foreach ($items as $item) {
            
            $delivery_area = CustomerCart::geLocation($item['area_id'], $item['vendor_id']);

            $delivery_charge += $delivery_area->delivery_price;

            $sub_total += $item['item_price_per_unit'] * $item['cart_quantity'];

            $chanks[$item['vendor_id']][] = $item;
        }
        
        $total = $sub_total + $delivery_charge;

        //total gateway commission 
        $gateway_total = $gateway_percentage * ($total / 100);

        //insert main order
        $order = new Order;
        $order->customer_id = Yii::$app->user->getID();
        $order->order_total_delivery_charge = $delivery_charge;
        $order->order_total_without_delivery = $total - $delivery_charge;
        $order->order_total_with_delivery = $total;
        $order->order_payment_method = $gateway_name;
        $order->order_transaction_id = $transaction_id;
        $order->order_gateway_percentage = $gateway_percentage;
        $order->order_gateway_total = $gateway_total;
        $order->order_ip_address = $_SERVER['HTTP_CLIENT_IP'];
        $order->trash = 'Default';
        $order->save();

        //insert suborder 
        foreach($chanks as $vendor_id => $chank) {

            //calculate order total data 
            $total = $sub_total = $delivery_charge = 0;

            foreach ($chank as $item) {
                
                $delivery_area = CustomerCart::geLocation($item['area_id'], $item['vendor_id']);

                $delivery_charge += $delivery_area->delivery_price;

                $sub_total += $item['item_price_per_unit'] * $item['cart_quantity'];
            }

            //insert suborder 
            $sub_order = new Suborder;
            $sub_order->order_id = $order->order_id;
            $sub_order->vendor_id = $vendor_id;
            $sub_order->status_id = $order_status_id;
            $sub_order->suborder_delivery_charge = $delivery_charge;
            $sub_order->suborder_total_without_delivery = $total - $delivery_charge;
            $sub_order->suborder_total_with_delivery = $total;
            $sub_order->suborder_commission_percentage = '';
            $sub_order->suborder_commission_total =  '';
            $sub_order->suborder_vendor_total =  '';
            $sub_order->trash = 'Default';
            $sub_order->save();

            //insert items 
            foreach ($chank as $item) {

                $item_purchase = new SuborderItemPurchase;
                $item_purchase->suborder_id = $sub_order->suborder_id;
                $item_purchase->timeslot_id = '';
                $item_purchase->item_id  = '';
                $item_purchase->area_id = '';
                $item_purchase->address_id = '';
                $item_purchase->purchase_delivery_address = '';
                $item_purchase->purchase_delivery_date = '';
                $item_purchase->purchase_price_per_unit = '';
                $item_purchase->purchase_customization_price_per_unit = '';
                $item_purchase->purchase_quantity = '';
                $item_purchase->purchase_total_price = '';
                $item_purchase->trash = 'Default';
                $item_purchase->save();
            }

        }//foreach chink 

        return $order->order_id;

    }//END place_order
}
